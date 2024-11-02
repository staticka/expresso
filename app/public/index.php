<?php

use Staticka\Expresso\Express;

$root = dirname(dirname(__DIR__));

require $root . '/vendor/autoload.php';

/** @var string */
$appPath = realpath($root . '/app');

$app = new Express;

$fields = array('name');
$fields[] = 'title';
$fields[] = 'link';
$fields[] = 'description';
$fields[] = 'plate';
$fields[] = 'category';
$fields[] = 'tags';

$app->setFields($fields);

$app->setRootPath($appPath);

$app->run();
