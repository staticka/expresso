<?php

namespace Staticka\Expresso\Routes;

use Psr\Http\Message\ResponseInterface;
use Staticka\Expresso\Checks\BuildCheck;
use Staticka\Expresso\Depots\BuildDepot;

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
     * @param \Psr\Http\Message\ResponseInterface $response
     */
    public function __construct(ResponseInterface $response)
    {
        $this->response = $response;
    }

    /**
     * @param \Staticka\Expresso\Depots\BuildDepot $depot
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function index(BuildDepot $depot)
    {
        $root = $depot->getRootPath();

        $check = new BuildCheck($root);

        if (! $check->valid(array()))
        {
            return $this->toJson($check->firstError(), 422);
        }

        // This requires the "staticka/console" ---
        // package to test this logic manually. ---
        // @codeCoverageIgnoreStart
        $depot->build();

        return $this->toJson('Pages compiled!');
        // @codeCoverageIgnoreEnd
        // ----------------------------------------
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

        $html = @json_encode($data, $options);

        $http->getBody()->write($html === false ? '' : $html);

        $value = 'application/json';

        return $http->withHeader('Content-Type', $value);
    }
}
