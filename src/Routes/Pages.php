<?php

namespace Staticka\Expresso\Routes;

use Staticka\Expresso\Plate;
use Staticka\Helper\PagesHelper;

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
     * @param \Staticka\Helper\PagesHelper $helper
     */
    public function __construct(PagesHelper $helper)
    {
        $this->helper = $helper;
    }

    /**
     * @return string
     */
    public function index(Plate $plate)
    {
        $data = array('items' => $this->helper->get());

        return $plate->view('pages', $data);
    }
}
