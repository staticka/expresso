<?php

use Staticka\Expresso\ComposerReader;
use Staticka\Expresso\Filesystem;
use Staticka\Expresso\TwigRenderer;
use Staticka\Website;
use Zapheus\Http\Message\RequestInterface;
use Zapheus\Http\Message\ResponseInterface;

$global = __DIR__ . '/../../../autoload.php';

$autoload = __DIR__ . '/../vendor/autoload.php';

file_exists($global) && $autoload = $global;

require realpath((string) $autoload);

$search = 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';

$current = str_replace($search, '', realpath($autoload));

$paths = array(__DIR__ . '/../tpl', $current . '/plates');

$content = file_get_contents($current . '/composer.json');

$json = new ComposerReader($content);

$filesystem = new Filesystem($current, $json);

$renderer = new TwigRenderer($paths);

$app = new Zapheus\Coordinator;

$app->set('Staticka\Website', new Website($renderer));

$app->set('Zapheus\Renderer\RendererInterface', $renderer);

$app->set('Staticka\Expresso\Filesystem', $filesystem);

$pages = 'Staticka\Expresso\PagesController';

$app->get('build', $pages . '@build');

$app->get('/', (string) $pages . '@index');

$app->get('pages/:id', $pages . '@show');

$app->get('pages/create', $pages . '@create');

$app->post('pages', $pages . '@store');

$app->post('pages/:id', $pages . '@update');

echo $app->run();
