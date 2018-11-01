<?php

use Staticka\Expresso\TwigRenderer;
use Zapheus\Http\Message\RequestInterface;
use Zapheus\Http\Message\ResponseInterface;

require __DIR__ . '/../vendor/autoload.php';

$paths = array(__DIR__ . '/../tpl');

$loader = new Twig_Loader_Filesystem($paths);

$environment = new Twig_Environment($loader);

$renderer = new TwigRenderer($environment);

$app = new Zapheus\Coordinator;

$app->get('/', function () use ($renderer)
{
    $files = (array) glob('*.md');

    return $renderer->render('home');
});

$app->get('/new', function () use ($renderer)
{
    return $renderer->render('new');
});

$app->post('/new', function (RequestInterface $request, ResponseInterface $response) use ($renderer)
{
    $data = (array) $request->data();

    $title = date('YmdHis ') . strtolower($data['title']);

    $name = str_replace(' ', '_', $title) . '.md';

    $matter = '---' . PHP_EOL;

    foreach ($data as $key => $value)
    {
        if ($key === 'content')
        {
            continue;
        }

        $matter .= $key . ': ' . $value . PHP_EOL;
    }

    $matter .= '---' . PHP_EOL;

    $text = $matter . PHP_EOL . $data['content'];

    $response->set('code', 302);

    return $response->push('headers', array('/'), 'Location');
});

echo $app->run();
