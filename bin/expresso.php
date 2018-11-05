<?php

use Staticka\Expresso\Filesystem;
use Staticka\Expresso\TwigRenderer;
use Staticka\Website;
use Zapheus\Http\Message\RequestInterface;
use Zapheus\Http\Message\ResponseInterface;

$autoload = __DIR__ . '/../vendor/autoload.php';

$global = __DIR__ . '/../../../autoload.php';

file_exists($global) && $autoload = $global;

$current = str_replace('/autoload.php', '', $autoload);

require $autoload;

$paths = array(__DIR__ . '/../tpl');

$paths[] = getcwd() . '/plates';

$filesystem = new Filesystem(getcwd());

$loader = new Twig_Loader_Filesystem($paths);

$environment = new Twig_Environment($loader);

$renderer = new TwigRenderer($environment);

$website = new Website($renderer);

$app = new Zapheus\Coordinator;

$app->set('Staticka\Website', $website);

$app->set('Zapheus\Renderer\RendererInterface', $renderer);

$app->set('Staticka\Expresso\Filesystem', $filesystem);

$pages = 'Staticka\Expresso\PagesController';

$app->get('build', $pages . '@build');

$app->get('/', (string) $pages . '@index');

$app->post('pages', $pages . '@store');

$app->get('pages/create', $pages . '@create');

$app->get('pages/:id', $pages . '@show');

$app->post('pages/:id', $pages . '@update');

echo $app->run();
