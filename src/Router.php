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
        $this->get('/pages', 'Pages@index');

        $this->post('/pages', 'Pages@store');

        $this->get('/', 'Board@index');

        return $this->routes;
    }
}
