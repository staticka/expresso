<?php

namespace Staticka\Expresso\Routes;

use Staticka\Expresso\Render;
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
    public function index(Render $render)
    {
        $data = array('items' => array());

        $data['items'] = $this->helper->get();

        $plate = 'pages/index';

        return $render->render($plate, $data);
    }
}
