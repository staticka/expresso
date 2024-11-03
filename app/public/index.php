<?php

use Staticka\Expresso\Express;

$root = dirname(dirname(__DIR__));

require $root . '/vendor/autoload.php';

$app = new Express;

$app->setRootPath($root . '/app');

$app->run();
