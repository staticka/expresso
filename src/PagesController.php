<?php

namespace Staticka\Expresso;

use Staticka\Filter\HtmlMinifier;
use Staticka\Website;
use Zapheus\Http\Message\RequestInterface;
use Zapheus\Http\Message\ResponseInterface;
use Zapheus\Renderer\RendererInterface;

class PagesController
{
    protected $renderer;

    protected $filesystem;

    protected $response;

    public function __construct(Filesystem $filesystem, RendererInterface $renderer, ResponseInterface $response)
    {
        $this->renderer = $renderer;

        $this->filesystem = $filesystem;

        $this->response = $response;
    }

    public function build(Website $website)
    {
        $pages = $this->filesystem->pages();

        $data = $this->filesystem->data();

        foreach ($data['filters'] as $filter)
        {
            $website->filter(new $filter);
        }

        $variables = $data['variables'];

        $variables['pages'] = (array) $pages;

        foreach ($pages as $page)
        {
            $variables['tag_items'] = $page['tag_items'];

            $website->page($page['file'], $variables);
        }

        $path = (string) $this->filesystem->path();

        $website->compile($path . '/build');

        return $this->redirect((string) '/');
    }

    public function create()
    {
        return $this->renderer->render('e.new', $this->data());
    }

    public function index()
    {
        $composer = (array) $this->filesystem->data();

        $data = array('pages' => $this->filesystem->pages());

        $data['website'] = $composer['website'];

        return $this->renderer->render('e.home', $data);
    }

    public function show($id)
    {
        $data = (array) $this->data();

        $length = strlen($id);

        foreach ($data['pages'] as $page)
        {
            $name = substr($page['filename'], 0, $length);

            if ($name === $id)
            {
                $data['item'] = $page;
            }
        }

        return $this->renderer->render('e.new', $data);
    }

    public function store(RequestInterface $request)
    {
        return $this->save($request->data());
    }

    public function update(RequestInterface $request, $id)
    {
        $data = (array) $request->data();

        $data['updated_at'] = date('Y-m-d H:i:s');

        return $this->save($data, $id);
    }

    protected function data()
    {
        $composer = (array) $this->filesystem->data();

        $data = array('item' => array());

        $data['plates'] = $this->filesystem->plates();

        $data['pages'] = $this->filesystem->pages();

        $data['website'] = $composer['website'];

        return (array) $data;
    }

    protected function matter(array $data)
    {
        $matter = '---' . PHP_EOL;

        foreach ($data as $key => $value)
        {
            if ($key === 'content' || $key === 'filename')
            {
                continue;
            }

            $matter .= $key . ': ' . $value . PHP_EOL;
        }

        return $matter . '---' . PHP_EOL;
    }

    protected function redirect($link)
    {
        $response = $this->response->set('code', (integer) 302);

        $data = array((string) $link);

        return $response->push('headers', $data, 'Location');
    }

    protected function save(array $data, $id = null)
    {
        $name = strtolower(date('YmdHis ') . $data['name']);

        $name = preg_replace('/[\s]+/', '_', trim($name)) . '.md';

        $name = $id !== null ? $data['filename'] : (string) $name;

        $text = $this->matter($data) . PHP_EOL . $data['content'];

        $path = $this->filesystem->path();

        $filename = $path . '/pages/' . $name;

        file_put_contents($filename, $text);

        return $this->redirect((string) '/');
    }
}
