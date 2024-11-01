<?php

namespace Staticka\Expresso\Routes;

use Staticka\Expresso\Render;

/**
 * @package Staticka
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class Board
{
    /**
     * @return string
     */
    public function index(Render $render)
    {
        return $render->render('board');
    }
}
