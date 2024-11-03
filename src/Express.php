<?php

namespace Staticka\Expresso;

use Rougin\Slytherin\Container\ReflectionContainer;
use Rougin\Slytherin\Http\HttpIntegration;
use Rougin\Slytherin\Routing\RoutingIntegration;
use Rougin\Slytherin\System;
use Rougin\Slytherin\Template\RendererIntegration;
use Staticka\Expresso\Helpers\LinkHelper as ExpressoLink;
use Staticka\Expresso\Package as Expresso;
use Staticka\Helper\LinkHelper as StatickaLink;
use Staticka\Package as Staticka;

/**
 * @package Staticka
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class Express extends System
{
    /**
     * @var string[]
     */
    protected $fields = array();

    /**
     * @var \Staticka\Package|null
     */
    protected $package = null;

    /**
     * @var string
     */
    protected $root;

    /**
     * @return void
     */
    public function run()
    {
        $this->setRouter();

        $this->setHttp();

        $this->setRender();

        $this->setApp();

        $this->setLink();

        // Modify Staticka instance for Expresso ---
        $this->integrate(new Expresso);
        // -----------------------------------------

        $this->setPlate();

        parent::run();
    }

    /**
     * @param string[] $fields
     *
     * @return self
     */
    public function setFields($fields)
    {
        $this->fields = $fields;

        return $this;
    }

    /**
     * @param \Staticka\Package $package
     * @return self
     */
    public function setPackage(Staticka $package)
    {
        $this->package = $package;

        return $this;
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

    /**
     * @return string
     */
    public static function getAppPath()
    {
        return dirname(dirname(__FILE__)) . '/app';
    }

    /**
     * @return void
     */
    protected function setApp()
    {
        // Prepare from Staticka instance -------
        if (! $this->package)
        {
            $package = new Staticka($this->root);

            $package->setPathsFromRoot();

            $this->package = $package;
        }

        $this->integrate($this->package);
        // --------------------------------------

        // Define the fields for each page for Expresso ----
        /** @var \Staticka\System */
        $app = $this->container->get('Staticka\System');

        $this->config->load($app->getConfigPath());

        if ($this->fields)
        {
            $this->config->set('app.fields', $this->fields);
        }
        // -------------------------------------------------
    }

    /**
     * @return void
     */
    protected function setHttp()
    {
        $this->config->set('app.http.cookies', $_COOKIE);

        $this->config->set('app.http.files', $_FILES);

        $this->config->set('app.http.get', (array) $_GET);

        $this->config->set('app.http.post', $_POST);

        $this->config->set('app.http.server', $_SERVER);

        $this->integrate(new HttpIntegration);
    }

    /**
     * @return void
     */
    protected function setLink()
    {
        /** @var string */
        $appUrl = $this->config->get('app.app_url');

        $helper = new ExpressoLink($appUrl, $_SERVER);

        $this->container->set(get_class($helper), $helper);

        /** @var string */
        $baseUrl = $this->config->get('app.base_url');

        $helper = new StatickaLink($baseUrl);

        $this->container->set(get_class($helper), $helper);
    }

    /**
     * @return void
     */
    protected function setPlate()
    {
        /** @var \Rougin\Slytherin\Template\RendererInterface */
        $renderer = $this->container->get(System::TEMPLATE);

        $plate = new Plate($renderer);

        /** @var string[] */
        $filters = $this->config->get('app.filters', array());

        $container = new ReflectionContainer($this->container);

        foreach ($filters as $filter)
        {
            /** @var \Staticka\Filter\FilterInterface */
            $filter = $container->get($filter);

            $plate->addFilter($filter);
        }

        /** @var string[] */
        $helpers = $this->config->get('app.helpers', array());

        foreach ($helpers as $helper)
        {
            /** @var \Staticka\Helper\HelperInterface */
            $helper = $container->get($helper);

            $plate->addHelper($helper);
        }

        $this->container->set(get_class($plate), $plate);
    }

    /**
     * @return void
     */
    protected function setRender()
    {
        $path = (string) __DIR__ . '/../app/plates';

        $this->config->set('app.views', $path);

        $this->integrate(new RendererIntegration);
    }

    /**
     * @return void
     */
    protected function setRouter()
    {
        $this->integrate(new RoutingIntegration);

        $this->container->set(System::ROUTER, new Router);
    }
}
