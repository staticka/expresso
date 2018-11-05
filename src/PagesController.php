<?php

namespace Staticka\Expresso;

use Staticka\Website;
use Zapheus\Http\Message\RequestInterface;
use Zapheus\Http\Message\ResponseInterface;
use Zapheus\Renderer\RendererInterface;

class PagesController
{
    protected $filesystem;

    protected $renderer;

    public function __construct(Filesystem $filesystem, RendererInterface $renderer)
    {
        $this->filesystem = $filesystem;

        $this->renderer = $renderer;
    }

    public function build(ResponseInterface $response, Website $website)
    {
        $pages = $this->filesystem->pages();

        foreach ($pages as $page)
        {
            $website->page($page['file']);
        }

        $path = (string) $this->filesystem->path();

        $website->compile($path . '/build');

        return $this->redirect($response, '/');
    }

    public function create()
    {
        return $this->renderer->render('e.new', $this->data());
    }

    public function index()
    {
        $data = array('pages' => $this->filesystem->pages());

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

    public function store(RequestInterface $request, ResponseInterface $response)
    {
        return $this->save($response, $request->data(), null);
    }

    public function update(RequestInterface $request, ResponseInterface $response, $id)
    {
        $data = (array) $request->data();

        $data['updated_at'] = date('Y-m-d H:i:s');

        return $this->save($response, $data, $id);
    }

    protected function data()
    {
        $data = array('item' => array());

        $data['item']['content'] = null;

        $data['item']['filename'] = null;

        $data['item']['layout'] = null;

        $data['item']['permalink'] = null;

        $data['item']['title'] = null;

        $data['plates'] = $this->filesystem->plates();

        $data['pages'] = $this->filesystem->pages();

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

    protected function redirect(ResponseInterface $response, $link)
    {
        $response = $response->set('code', (integer) 302);

        $data = array((string) $link);

        return $response->push('headers', $data, 'Location');
    }

    protected function save(ResponseInterface $response, array $data, $id = null)
    {
        $title = date('YmdHis ') . strtolower($data['title']);

        $name = str_replace(' ', '_', $title) . '.md';

        $name = $id !== null ? $data['filename'] : $name;

        $text = $this->matter($data) . PHP_EOL . $data['content'];

        file_put_contents(getcwd() . '/pages/' . $name, $text);

        return $this->redirect($response, '/');
    }
}
