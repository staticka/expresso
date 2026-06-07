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
        // Forcing as "integer" since request ---
        // parameters always return as "string".
        $id = (int) $id;
        // --------------------------------------

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
            $errors = $check->errors();

            return $this->toJson($errors, 422);
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
        // Forcing as "integer" since request ---
        // parameters always return as "string".
        $id = (int) $id;
        // --------------------------------------

        $check = new PageCheck($page);

        $check->setId($id);

        /** @var array<string, string> */
        $data = $this->request->getParsedBody();

        if (! $check->valid($data))
        {
            $errors = $check->errors();

            return $this->toJson($errors, 422);
        }

        $page->update($id, $data);

        return $this->toJson(null, 204);
    }

    /**
     * @param integer $id
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    protected function asNotFound($id)
    {
        $text = 'Page (' . $id . ') not found';

        return $this->withError($text, 422);
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
        $http = $this->response->withStatus($code);

        $stream = @json_encode($data, $options);

        $stream = $stream === false ? '' : $stream;

        $http->getBody()->write($stream);

        $key = 'Content-Type';

        $value = 'application/json';

        return $http->withHeader($key, $value);
    }

    /**
     * @param string  $text
     * @param integer $code
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    protected function withError($text, $code)
    {
        $http = $this->response->withStatus($code);

        $http->getBody()->write($text);

        return $http;
    }
}
