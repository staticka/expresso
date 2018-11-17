<?php

namespace Staticka\Expresso;

use Staticka\Website;
use Zapheus\Coordinator;

/**
 * Expresso
 *
 * @package Expresso
 * @author  Rougin Gutib <rougingutib@gmail.com>
 */
class Expresso extends Coordinator
{
    const CONTROLLER = 'Staticka\Expresso\Handler';

    const WEBSITE = 'Staticka\Website';

    const RENDERER = 'Zapheus\Renderer\RendererInterface';

    /**
     * Initializes the application instance.
     *
     * @param \Staticka\Website $website
     */
    public function __construct(Website $website)
    {
        $renderer = $website->renderer();

        parent::__construct();

        $this->set(self::RENDERER, $renderer);

        $this->website = $website;

        $this->set(self::WEBSITE, $website);
    }

    /**
     * Sets the content instance.
     *
     * @param  \Staticka\Expresso\Content $content
     * @return self
     */
    public function content(Content $content)
    {
        $name = get_class($content);

        $this->set($name, $content);

        return $this;
    }

    /**
     * Runs the application and returns the stream instance.
     *
     * @return \Zapheus\Http\Message\StreamInterface
     */
    public function run()
    {
        $controller = (string) self::CONTROLLER;

        $this->get('/', $controller . '@index');

        $this->get('pages/:id', $controller . '@show');

        $this->get('build', $controller . '@build');

        $this->get('pages/create', $controller . '@create');

        $this->post('pages', $controller . '@store');

        $this->post('pages/:id', $controller . '@update');

        return parent::run();
    }
}
