<?php

namespace Staticka\Expresso;

use Rougin\Slytherin\Http\HttpIntegration;
use Rougin\Slytherin\Routing\RoutingIntegration;
use Rougin\Slytherin\System;
use Rougin\Slytherin\Template\RendererIntegration;
use Staticka\Expresso\Package as Expresso;
use Staticka\Package as Staticka;

/**
 * @package Staticka
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class Express extends System
{
    /**
     * @var string
     */
    protected $root;

    /**
     * @return void
     */
    public function run()
    {
        // Prepare the RendererIntegration -------------------------
        $this->config->set('app.views', __DIR__ . '/../app/plates');

        $this->integrate(new RendererIntegration);
        // ---------------------------------------------------------

        // Prepare the HttpIntegration -------------------
        $this->config->set('app.http.cookies', $_COOKIE);

        $this->config->set('app.http.files', $_FILES);

        $this->config->set('app.http.get', (array) $_GET);

        $this->config->set('app.http.post', $_POST);

        $this->config->set('app.http.server', $_SERVER);

        $this->integrate(new HttpIntegration);
        // -----------------------------------------------

        // Prepare the RoutingIntegration ----------------
        $this->integrate(new RoutingIntegration);

        $this->container->set(System::ROUTER, new Router);
        // -----------------------------------------------

        // Prepare from Staticka instance --------------
        $staticka = new Staticka($this->root);

        $this->integrate($staticka->setPathsFromRoot());
        // ---------------------------------------------

        // Modify Staticka instance for Expresso ---
        $this->integrate(new Expresso);
        // -----------------------------------------

        parent::run();
    }

    /**
     * @param string $root
     *
     * @return self
     */
    public function setRootPath($root)
    {
        $this->root = $root;

        return $this;
    }
}
