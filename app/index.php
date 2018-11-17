<?php

use Staticka\Expresso\Composer;
use Staticka\Expresso\Content;
use Staticka\Expresso\Expresso;
use Staticka\Expresso\Renderer;
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

$composer = new Composer($content);

$content = new Content($current, $composer);

$renderer = new Renderer($paths);

$website = new Website($renderer);

$app = new Expresso($website);

echo $app->content($content)->run();
