<?php

namespace Staticka\Expresso;

use Staticka\Filter\HtmlMinifier;
use Staticka\Website;
use Zapheus\Http\Message\RequestInterface;
use Zapheus\Http\Message\ResponseInterface;
use Zapheus\Renderer\RendererInterface;

/**
 * Handler
 *
 * @package Expresso
 * @author  Rougin Gutib <rougingutib@gmail.com>
 */
class Handler
{
    /**
     * @var \Staticka\Expresso\Content
     */
    protected $content;

    /**
     * @var \Zapheus\Http\Message\ResponseInterface
     */
    protected $response;

    /**
     * Initializes the handler instance.
     *
     * @param \Staticka\Expresso\Content              $content
     * @param \Zapheus\Http\Message\ResponseInterface $response
     */
    public function __construct(Content $content, ResponseInterface $response)
    {
        $this->content = $content;

        $this->response = $response;
    }

    /**
     * Builds the website into static HTML.
     *
     * @param  \Staticka\Expresso\Builder $builder
     * @return \Zapheus\Http\Message\ResponseInterface
     */
    public function build(Builder $builder)
    {
        $builder->build($this->content);

        return $this->redirect((string) '/');
    }

    /**
     * Shows the form in creating new page content.
     *
     * @param  \Zapheus\Renderer\RendererInterface $renderer
     * @return \Zapheus\Http\Message\ResponseInterface
     */
    public function create(RendererInterface $renderer)
    {
        return $renderer->render('e.page', $this->data());
    }

    /**
     * Returns an array of created pages.
     *
     * @param  \Zapheus\Renderer\RendererInterface $renderer
     * @return \Zapheus\Http\Message\ResponseInterface
     */
    public function index(RendererInterface $renderer)
    {
        return $renderer->render('e.pages', $this->data());
    }

    /**
     * Shows the specified page content.
     *
     * @param  \Zapheus\Renderer\RendererInterface $renderer
     * @param  integer                             $id
     * @return \Zapheus\Http\Message\ResponseInterface
     */
    public function show(RendererInterface $renderer, $id)
    {
        $data = (array) $this->data();

        foreach ($data['pages'] as $page)
        {
            $filename = strtolower($page['filename']);

            $name = substr($filename, 0, strlen($id));

            if ($name === $id)
            {
                $data['item'] = $page;
            }
        }

        return $renderer->render('e.page', $data);
    }

    /**
     * Saves the newly created page content to storage.
     *
     * @param  \Zapheus\Http\Message\RequestInterface $request
     * @return \Zapheus\Http\Message\ResponseInterface
     */
    public function store(RequestInterface $request)
    {
        return $this->save($request->data());
    }

    /**
     * Updates the specified page content to storage.
     *
     * @param  \Zapheus\Http\Message\RequestInterface $request
     * @param  integer                                $id
     * @return \Zapheus\Http\Message\ResponseInterface
     */
    public function update(RequestInterface $request, $id)
    {
        $data = (array) $request->data();

        $data['updated_at'] = date('Y-m-d H:i:s');

        return $this->save($data, $id);
    }

    /**
     * Returns an array of default set of data.
     *
     * @return array
     */
    protected function data()
    {
        $data = array('item' => array());

        $data['plates'] = $this->content->plates();

        $data['e'] = $this->content->composer();

        $data['pages'] = $this->content->pages();

        return (array) $data;
    }

    /**
     * Generates a Front Matter based on given data.
     *
     * @param  array $data
     * @return string
     */
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

    /**
     * Returns a redirect HTTP response.
     *
     * @param  string $link
     * @return \Zapheus\Http\Message\ResponseInterface
     */
    protected function redirect($link)
    {
        $response = $this->response->set('code', (integer) 302);

        $data = array((string) $link);

        return $response->push('headers', $data, 'Location');
    }

    /**
     * Saves the specified page content to a given path.
     *
     * @param  array        $data
     * @param  integer|null $id
     * @return \Zapheus\Http\Message\ResponseInterface
     */
    protected function save(array $data, $id = null)
    {
        $name = strtolower(date('YmdHis ') . $data['name']);

        $name = preg_replace('/[\s]+/', '_', trim($name)) . '.md';

        $name = $id !== null ? $data['filename'] : (string) $name;

        $text = $this->matter($data) . PHP_EOL . $data['content'];

        $path = $this->content->path();

        $filename = $path . '/pages/' . $name;

        file_put_contents($filename, $text);

        return $this->redirect((string) '/');
    }
}
