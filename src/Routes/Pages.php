<?php

namespace Staticka\Expresso\Routes;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Staticka\Depots\PageDepot;
use Staticka\Expresso\Checks\PageCheck;
use Staticka\Expresso\Helpers\DataHelper;
use Staticka\Expresso\Plate;

/**
 * @package Staticka
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class Pages
{
    /**
     * @var \Psr\Http\Message\ServerRequestInterface
     */
    protected $request;

    /**
     * @var \Psr\Http\Message\ResponseInterface
     */
    protected $response;

    /**
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param \Psr\Http\Message\ResponseInterface      $response
     */
    public function __construct(ServerRequestInterface $request, ResponseInterface $response)
    {
        $this->request = $request;

        $this->response = $response;
    }

    /**
     * @param \Staticka\Depots\PageDepot $page
     * @param \Staticka\Expresso\Plate   $plate
     *
     * @return string
     */
    public function index(PageDepot $page, Plate $plate)
    {
        $items = $page->getAsData(PageDepot::SORT_DESC);

        return $plate->view('pages', compact('items'));
    }

    /**
     * @param integer                    $id
     * @param \Staticka\Depots\PageDepot $page
     * @param \Staticka\Expresso\Plate   $plate
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function show($id, PageDepot $page, Plate $plate)
    {
        $result = $page->find($id);

        if (! $result)
        {
            return $this->asNotFound($id);
        }

        $fields = $page->getFields();

        $data = $result->getData();

        $item = array('fields' => $fields);

        $item['page'] = $data;

        $item['data'] = DataHelper::toJson($fields, $data);

        /** @var \Psr\Http\Message\ResponseInterface */
        return $plate->view('editor', $item);
    }

    /**
     * @param \Staticka\Depots\PageDepot $page
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function store(PageDepot $page)
    {
        $check = new PageCheck($page);

        /** @var array<string, string> */
        $data = $this->request->getParsedBody();

        if (! $check->valid($data))
        {
            return $this->toJson($check->errors(), 422);
        }

        $page->create($data);

        return $this->toJson('Page created!', 201);
    }

    /**
     * @param integer                    $id
     * @param \Staticka\Depots\PageDepot $page
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function update($id, PageDepot $page)
    {
        $check = new PageCheck($page, $id);

        /** @var array<string, string> */
        $data = $this->request->getParsedBody();

        if (! $check->valid($data))
        {
            return $this->toJson($check->errors(), 422);
        }

        $page->update($id, $data);

        return $this->toJson('Page updated!', 204);
    }

    /**
     * @param integer $id
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    protected function asNotFound($id)
    {
        return $this->withError('Page (' . $id . ') not found', 422);
    }

    /**
     * @param mixed   $data
     * @param integer $code
     * @param integer $options
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    protected function toJson($data, $code = 200, $options = 0)
    {
        $response = $this->response->withStatus($code);

        /** @var string */
        $stream = @json_encode($data, $options);

        $response->getBody()->write($stream);

        return $response->withHeader('Content-Type', 'application/json');
    }

    /**
     * @param string  $text
     * @param integer $code
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    protected function withError($text, $code)
    {
        $response = $this->response->withStatus($code);

        $response->getBody()->write($text);

        return $response;
    }
}
