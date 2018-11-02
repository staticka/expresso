<?php

use Staticka\Expresso\TwigRenderer;
use Staticka\Matter;
use Zapheus\Http\Message\RequestInterface;
use Zapheus\Http\Message\ResponseInterface;
use Staticka\Website;

$autoload = __DIR__ . '/../vendor/autoload.php';

$global = __DIR__ . '/../../../autoload.php';

file_exists($global) && $autoload = $global;

$current = str_replace('/autoload.php', '', $autoload);

require $autoload;

$paths = array(__DIR__ . '/../tpl');

$paths[] = getcwd() . '/plates';

$loader = new Twig_Loader_Filesystem($paths);

$environment = new Twig_Environment($loader);

$renderer = new TwigRenderer($environment);

$website = new Website($renderer);

$app = new Zapheus\Coordinator;

$app->get('/', function (RequestInterface $request) use ($renderer)
{
    $query = $request->queries();

    $files = (array) glob(getcwd() . '/pages/*.md');

    $pages = array();

    foreach ($files as $file)
    {
        $content = file_get_contents($file);

        $matter = Matter::parse($content);

        $item = (array) $matter[0];

        $item['content'] = $matter[1];

        $item['filename'] = basename($file);

        array_push($pages, $item);
    }

    $pages = array_reverse($pages);

    return $renderer->render('e.home', compact('pages'));
});

$app->get('pages', function (RequestInterface $request) use ($renderer)
{
    $item = array('title' => null);
    $item['content'] = null;
    $item['filename'] = null;
    $item['layout'] = null;
    $item['permalink'] = null;

    $items = glob(getcwd() . '/plates/*');

    $plates = array();

    $queries = (array) $request->queries();

    foreach ($items as $file)
    {
        $info = pathinfo($file);

        $plates[$info['filename']] = basename($file);
    }

    $files = glob(getcwd() . '/pages/*');

    foreach ($files as $file)
    {
        if (basename($file) === $queries['id'])
        {
            $content = file_get_contents($file);

            $matter = Matter::parse($content);

            $item = (array) $matter[0];

            $item['content'] = $matter[1];

            $item['filename'] = basename($file);
        }
    }

    return $renderer->render('e.new', compact('plates', 'item'));
});

$app->get('build', function (ResponseInterface $response) use ($website)
{
    $files = (array) glob(getcwd() . '/pages/*.md');

    foreach ($files as $file)
    {
        $website->page($file);
    }

    $website->compile(getcwd() . '/build');

    $response->set('code', 302);

    return $response->push('headers', array('/'), 'Location');
});

$app->get('new', function () use ($renderer)
{
    $item = array('title' => null);
    $item['content'] = null;
    $item['filename'] = null;
    $item['layout'] = null;
    $item['permalink'] = null;

    $files = glob(getcwd() . '/plates/*');

    $plates = array();

    foreach ($files as $file)
    {
        $info = pathinfo($file);

        $plates[$info['filename']] = basename($file);
    }

    return $renderer->render('e.new', compact('plates', 'item'));
});

$app->post('new', function (RequestInterface $request, ResponseInterface $response) use ($renderer)
{
    $data = (array) $request->data();

    $title = date('YmdHis ') . strtolower($data['title']);

    $name = str_replace(' ', '_', $title) . '.md';

    if ($data['filename'] !== '')
    {
        $name = $data['filename'];

        $data['updated_at'] = date('Y-m-d H:i:s');
    }

    $matter = '---' . PHP_EOL;

    foreach ($data as $key => $value)
    {
        if ($key === 'content' || $key === 'filename')
        {
            continue;
        }

        $matter .= $key . ': ' . $value . PHP_EOL;
    }

    $matter .= '---' . PHP_EOL;

    $text = $matter . PHP_EOL . $data['content'];

    if (! file_exists(getcwd() . '/pages'))
    {
        mkdir(getcwd() . '/pages');
    }

    file_put_contents(getcwd() . '/pages/' . $name, $text);

    $response->set('code', 302);

    return $response->push('headers', array('/'), 'Location');
});

echo $app->run();
