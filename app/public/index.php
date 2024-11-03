<?php

use Staticka\Expresso\Express;

$root = dirname(dirname(__DIR__));

require $root . '/vendor/autoload.php';

$app = new Express;

$app->setAppUrl('http://localhost:3977');
$app->setSiteUrl('http://localhost:3978');

$app->run();
