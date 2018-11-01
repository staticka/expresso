<?php

namespace Staticka\Expresso;

use Zapheus\Renderer\RendererInterface;

class HomePage
{
    public function __construct(RendererInterface $renderer)
    {
        $this->renderer = $renderer;
    }

    public function index()
    {
        return $this->renderer->render('home');
    }
}
