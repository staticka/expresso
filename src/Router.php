<?php

namespace Staticka\Expresso;

use Rougin\Slytherin\Routing\Router as Slytherin;

/**
 * @package Staticka
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class Router extends Slytherin
{
    /**
     * @var string
     */
    protected $namespace = 'Staticka\Expresso\Routes';

    /**
     * @return \Rougin\Slytherin\Routing\RouteInterface[]
     */
    public function routes()
    {
        $this->post('/build', 'Build@index');

        $this->get('/pages/:id', 'Pages@show');

        $this->get('/pages', 'Pages@index');

        $this->post('/pages', 'Pages@store');

        $this->post('/pages/:id', 'Pages@update');

        $this->get('/', 'Board@index');

        return $this->routes;
    }
}
