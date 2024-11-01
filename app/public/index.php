<?php

use Staticka\Expresso\Express;

$root = dirname(dirname(__DIR__));

require $root . '/vendor/autoload.php';

/** @var string */
$appPath = realpath($root . '/app');

$app = new Express;

$app->setRootPath($appPath)->run();
