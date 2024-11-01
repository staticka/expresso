<?php

namespace Staticka\Expresso\Routes;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Staticka\Expresso\Checks\PageCheck;
use Staticka\Expresso\Depots\PageDepot;
use Staticka\Expresso\Plate;
use Staticka\Helper\PagesHelper;
use Staticka\System;

/**
 * @package Staticka
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class Pages
{
    /**
     * @var \Staticka\Helper\PagesHelper
     */
    protected $helper;

    /**
     * @var \Psr\Http\Message\ServerRequestInterface
     */
    protected $request;

    /**
     * @var \Psr\Http\Message\ResponseInterface
     */
    protected $response;

    /**
     * @param \Staticka\Helper\PagesHelper             $helper
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param \Psr\Http\Message\ResponseInterface      $response
     */
    public function __construct(PagesHelper $helper, ServerRequestInterface $request, ResponseInterface $response)
    {
        $this->helper = $helper;

        $this->response = $response;

        $this->request = $request;
    }

    /**
     * @return string
     */
    public function index(Plate $plate)
    {
        $data = array('items' => $this->helper->get());

        return $plate->view('pages', $data);
    }

    /**
     * @param \Staticka\Expresso\Depots\PageDepot $page
     * @param \Staticka\System                    $app
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function store(PageDepot $page, System $app)
    {
        $check = new PageCheck;

        /** @var array<string, string> */
        $data = $this->request->getParsedBody();

        if (! $check->valid($data))
        {
            return $this->toJson($check->errors(), 422);
        }

        $timezone = $app->getTimezone();

        $path = $app->getPagesPath();

        $page->create($data, $path, $timezone);

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
