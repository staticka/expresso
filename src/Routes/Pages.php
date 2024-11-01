<?php

namespace Staticka\Expresso\Routes;

use Staticka\Expresso\Render;

/**
 * @package Staticka
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class Pages
{
    /**
     * @return string
     */
    public function index(Render $render)
    {
        return $render->render('pages/index');
    }
}
