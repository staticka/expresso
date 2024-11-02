<?php

namespace Staticka\Expresso\Routes;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Staticka\Expresso\Checks\PageCheck;
use Staticka\Expresso\Depots\PageDepot;
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
     * @param \Staticka\Expresso\Depots\PageDepot $page
     * @param \Staticka\Expresso\Plate            $plate
     *
     * @return string
     */
    public function index(PageDepot $page, Plate $plate)
    {
        $items = $page->getAsData(PageDepot::SORT_DESC);

        return $plate->view('pages', compact('items'));
    }

    /**
     * @param \Staticka\Expresso\Depots\PageDepot $page
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
     * Returns the specified data to JSON.
     *
     * @param mixed   $data
     * @param integer $code
     * @param integer $options
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function toJson($data, $code = 200, $options = 0)
    {
        $response = $this->response->withStatus($code);

        /** @var string */
        $stream = @json_encode($data, $options);

        $response->getBody()->write($stream);

        return $response->withHeader('Content-Type', 'application/json');
    }
}
