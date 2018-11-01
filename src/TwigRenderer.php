<?php

namespace Staticka\Expresso;

use Zapheus\Renderer\RendererInterface;

/**
 * Twig Renderer
 *
 * @package Slytherin
 * @author  Rougin Gutib <rougingutib@gmail.com>
 */
class TwigRenderer implements RendererInterface
{
    /**
     * @var \Twig_Environment
     */
    protected $twig;

    /**
     * Initializes the renderer instance.
     *
     * @param \Twig_Environment $twig
     */
    public function __construct(\Twig_Environment $twig)
    {
        $this->twig = $twig;
    }

    /**
     * Renders a template.
     *
     * @param  string $template
     * @param  array  $data
     * @param  string $extension
     * @return string
     */
    public function render($template, array $data = array(), $extension = 'twig')
    {
        return $this->twig->render($template . '.' . $extension, $data);
    }

    /**
     * Calls methods from the Twig instance.
     *
     * @param  string $method
     * @param  mixed  $parameters
     * @return mixed
     */
    public function __call($method, $parameters)
    {
        return call_user_func_array(array($this->twig, $method), $parameters);
    }
}
