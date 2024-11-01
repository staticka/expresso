<?php

namespace Staticka\Expresso\Routes;

use Staticka\Expresso\Plate;

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
    public function index(Plate $plate)
    {
        return $plate->view('board');
    }
}
