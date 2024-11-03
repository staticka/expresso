<?php

namespace Staticka\Expresso;

use Rougin\Slytherin\Template\RendererInterface;
use Staticka\Filter\FilterInterface;
use Staticka\Helper\HelperInterface;
use Staticka\Render\RenderInterface;

/**
 * @package Staticka
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class Plate implements RenderInterface
{
    /**
     * @var \Staticka\Filter\FilterInterface[]
     */
    protected $filters = array();

    /**
     * @var \Staticka\Helper\HelperInterface[]
     */
    protected $helpers = array();

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
     * @param \Staticka\Filter\FilterInterface $filter
     *
     * @return self
     */
    public function addFilter(FilterInterface $filter)
    {
        $this->filters[] = $filter;

        return $this;
    }

    /**
     * @param \Staticka\Helper\HelperInterface $helper
     *
     * @return self
     */
    public function addHelper(HelperInterface $helper)
    {
        $this->helpers[] = $helper;

        return $this;
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
        foreach ($this->helpers as $helper)
        {
            $data[$helper->name()] = $helper;
        }

        $html = $this->render($name, $data);

        foreach ($this->filters as $filter)
        {
            $html = $filter->filter($html);
        }

        return (string) $html;
    }
}
