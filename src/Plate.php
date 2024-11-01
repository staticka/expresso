<?php

namespace Staticka\Expresso;

use Rougin\Slytherin\Template\RendererInterface;
use Staticka\Expresso\Helpers\UrlHelper;
use Staticka\Filter\LayoutFilter;
use Staticka\Helper\BlockHelper;
use Staticka\Helper\LayoutHelper;
use Staticka\Helper\PlateHelper;
use Staticka\Render\RenderInterface;

/**
 * @package Staticka
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class Plate implements RenderInterface
{
    /**
     * @var \Rougin\Slytherin\Template\RendererInterface
     */
    protected $parent;

    /**
     * @param \Rougin\Slytherin\Template\RendererInterface $parent
     */
    public function __construct(RendererInterface $parent)
    {
        $this->parent = $parent;
    }

    /**
     * @param string               $name
     * @param array<string, mixed> $data
     *
     * @return string
     * @throws \InvalidArgumentException
     */
    public function render($name, $data = array())
    {
        return $this->parent->render($name, $data);
    }

    /**
     * TODO: Should be using render instead.
     *
     * @param string               $name
     * @param array<string, mixed> $data
     *
     * @return string
     * @throws \InvalidArgumentException
     */
    public function view($name, $data = array())
    {
        $data['plate'] = new PlateHelper($this);

        // TODO: Put this in an ".env.example" file ---
        $baseUrl = 'http://localhost:3977';
        // --------------------------------------------

        // TODO: Do not use the "$_SERVER" variable -----
        $data['url'] = new UrlHelper($baseUrl, $_SERVER);
        // ----------------------------------------------

        $data['layout'] = new LayoutHelper($this);

        $data['block'] = new BlockHelper;

        $html = $this->render($name, $data);

        $layout = new LayoutFilter;

        return $layout->filter($html);
    }
}
