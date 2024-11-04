<?php

namespace Staticka\Expresso\Routes;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Staticka\Expresso\Checks\BuildCheck;
use Staticka\Expresso\Checks\PageCheck;
use Staticka\Expresso\Depots\BuildDepot;
use Staticka\Expresso\Depots\PageDepot;
use Staticka\Expresso\Helpers\FieldHelper;
use Staticka\Expresso\Plate;

/**
 * @package Staticka
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class Build
{
    /**
     * @var \Psr\Http\Message\ResponseInterface
     */
    protected $response;

    /**
     * @param \Psr\Http\Message\ResponseInterface      $response
     */
    public function __construct(ResponseInterface $response)
    {
        $this->response = $response;
    }

    /**
     * @param \Staticka\Expresso\Depots\BuildDepot $depot
     *
     * @return string
     */
    public function index(BuildDepot $depot)
    {
        $root = $depot->getRootPath();

        $check = new BuildCheck($root);

        if (! $check->valid())
        {
            return $this->toJson($check->firstError(), 422);
        }

        $depot->build();

        return $this->toJson('Pages compiled!');
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
}