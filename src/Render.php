<?php

namespace Staticka\Expresso;

use Rougin\Slytherin\Template\RendererInterface;
use Staticka\Expresso\Helpers\UrlHelper;
use Staticka\Helper\PlateHelper;
use Staticka\Render\RenderInterface;

/**
 * @package Staticka
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class Render implements RenderInterface
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
        $data['plate'] = new PlateHelper($this);

        // TODO: Put this in an ".env.example" file ---
        $baseUrl = 'http://localhost:3977';
        // --------------------------------------------

        // TODO: Do not use the "$_SERVER" variable -----
        $data['url'] = new UrlHelper($baseUrl, $_SERVER);
        // ----------------------------------------------

        return $this->parent->render($name, $data);
    }
}
