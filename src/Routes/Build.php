<?php

namespace Staticka\Expresso\Routes;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Staticka\Expresso\Checks\BuildCheck;
use Staticka\Expresso\Depots\BuildDepot;
use Staticka\Expresso\Helpers\CsrfHelper;
use Staticka\Expresso\Session;

/**
 * @package Staticka
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class Build
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
     * @param \Staticka\Expresso\Helpers\CsrfHelper $csrf
     * @param \Staticka\Expresso\Depots\BuildDepot  $depot
     * @param \Staticka\Expresso\Session            $session
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function index(CsrfHelper $csrf, BuildDepot $depot, Session $session)
    {
        if (! $csrf->verify($this->request))
        {
            return $this->toJson('Invalid CSRF token', 403);
        }

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

        $session->flash('success', 'Pages compiled!');

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
