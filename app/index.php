<?php

use Staticka\Expresso\Composer;
use Staticka\Expresso\Content;
use Staticka\Expresso\Expresso;
use Staticka\Expresso\Renderer;

$global = __DIR__ . '/../../../autoload.php';

$autoload = __DIR__ . '/../vendor/autoload.php';

file_exists($global) && $autoload = $global;

require realpath((string) $autoload);

$root = (string) substr(realpath($autoload), 0, -19);

$composer = new Composer($root . '/composer.json');

$default = array($root . '/pages', $root . '/plates');

$pages = $composer->get('paths.pages', $default[0]);

$search = (string) '%%CURRENT_DIRECTORY%%';

$pages = str_replace($search, $root, (string) $pages);

$plates = $composer->get('paths.plates', $default[1]);

$plates = str_replace($search, $root, $plates);

$content = new Content($composer, $pages, $plates);

$paths = array(__DIR__ . '/../tpl', (string) $plates);

$renderer = new Renderer($paths);

$website = new Staticka\Website($renderer);

$app = new Expresso($website);

echo $app->content($content)->run();
