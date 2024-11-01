<?php

use Rougin\Slytherin\System\Routing;
use Staticka\Expresso\Package as Expresso;
use Staticka\Package as Staticka;

$root = dirname(dirname(__DIR__));

require $root . '/vendor/autoload.php';

$app = new Routing;

$path = $root . '/app';

$app->get('/', function ()
{
    return 'Hello world!';
});

$staticka = new Staticka($path);
$staticka->setPathsFromRoot();
$app->integrate($staticka);

$expresso = new Expresso($path);
$app->integrate($expresso);

$app->run();
