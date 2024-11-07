<?php

namespace Staticka\Expresso;

use Rougin\Slytherin\Container\ReflectionContainer;
use Rougin\Slytherin\Http\HttpIntegration;
use Rougin\Slytherin\Routing\RoutingIntegration;
use Rougin\Slytherin\System;
use Rougin\Slytherin\Template\RendererIntegration;
use Staticka\Depots\PageDepot;
use Staticka\Expresso\Helpers\LinkHelper as ExpressoLink;
use Staticka\Helper\LinkHelper as StatickaLink;
use Staticka\Package;
use Staticka\Render;

/**
 * @package Staticka
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class Express extends System
{
    /**
     * @var string|null
     */
    protected $appUrl = null;

    /**
     * @var string|null
     */
    protected $buildPath = null;

    /**
     * @var string|null
     */
    protected $configPath = null;

    /**
     * @var string|null
     */
    protected $pagesPath = null;

    /**
     * @var string|null
     */
    protected $platesPath = null;

    /**
     * @var string[]
     */
    protected $fields = array();

    /**
     * @var \Staticka\Package|null
     */
    protected $package = null;

    /**
     * @var string|null
     */
    protected $rootPath = null;

    /**
     * @var string|null
     */
    protected $siteUrl = null;

    /**
     * @var string
     */
    protected $timezone = null;

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
    public function run()
    {
        $this->setRouter();

        $this->setHttp();

        $this->setRender();

        $this->setPackage();

        $this->setApp();

        $this->setLink();

        $this->setPlate();

        parent::run();
    }

    /**
     * @param string $appUrl
     *
     * @return self
     */
    public function setAppUrl($appUrl)
    {
        $this->appUrl = $appUrl;

        return $this;
    }

    /**
     * @param string $buildPath
     *
     * @return self
     */
    public function setBuildPath($buildPath)
    {
        $this->buildPath = $buildPath;

        return $this;
    }

    /**
     * @param string $configPath
     *
     * @return self
     */
    public function setConfigPath($configPath)
    {
        $this->configPath = $configPath;

        return $this;
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
     * @param string $pagesPath
     *
     * @return self
     */
    public function setPagesPath($pagesPath)
    {
        $this->pagesPath = $pagesPath;

        return $this;
    }

    /**
     * @param string $platesPath
     *
     * @return self
     */
    public function setPlatesPath($platesPath)
    {
        $this->platesPath = $platesPath;

        return $this;
    }

    /**
     * @param string $rootPath
     *
     * @return self
     */
    public function setRootPath($rootPath)
    {
        $this->rootPath = $rootPath;

        return $this;
    }

    /**
     * @param string $siteUrl
     *
     * @return self
     */
    public function setSiteUrl($siteUrl)
    {
        $this->siteUrl = $siteUrl;

        return $this;
    }

    /**
     * @param string $timezone
     *
     * @return self
     */
    public function setTimezone($timezone)
    {
        $this->timezone = $timezone;

        return $this;
    }

    /**
     * @return void
     */
    protected function setApp()
    {
        // Define the fields for each page for Expresso ---
        /** @var \Staticka\System */
        $app = $this->container->get('Staticka\System');

        $this->config->load($app->getConfigPath());

        if (! $this->fields)
        {
            $this->fields[] = 'name';
            $this->fields[] = 'title';
            $this->fields[] = 'description';
            $this->fields[] = 'link';
            $this->fields[] = 'plate';
            $this->fields[] = 'category';
            $this->fields[] = 'tags';
        }

        $this->config->set('app.fields', $this->fields);
        // ------------------------------------------------

        // Initialize the PageDepot ---------------------
        /** @var string[] */
        $fields = $this->config->get('app.fields');

        $depot = new PageDepot($app, $fields);

        $this->container->set(get_class($depot), $depot);
        // ----------------------------------------------

        // Initialize the Render instance --------------
        $name = 'Staticka\Render\RenderInterface';

        if (! $this->container->has($name))
        {
            $render = new Render($app->getPlatesPath());

            $this->container->set($name, $render);
        }
        // ---------------------------------------------
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
        $appUrl = $this->config->get('app.app_url', $this->appUrl);

        $helper = new ExpressoLink($appUrl, $_SERVER);

        $this->container->set(get_class($helper), $helper);

        /** @var string */
        $siteUrl = $this->config->get('app.site_url', $this->siteUrl);

        $helper = new StatickaLink($siteUrl);

        $this->container->set(get_class($helper), $helper);
    }

    /**
     * @return void
     */
    protected function setPackage()
    {
        if (! $this->rootPath)
        {
            $this->rootPath = self::getAppPath();
        }

        $package = new Package($this->rootPath);

        $package->setPathsFromRoot();

        if ($this->buildPath)
        {
            $package->setBuildPath($this->buildPath);
        }

        if ($this->configPath)
        {
            $package->setConfigPath($this->configPath);
        }

        if ($this->pagesPath)
        {
            $package->setPagesPath($this->pagesPath);
        }

        if ($this->platesPath)
        {
            $package->setPlatesPath($this->platesPath);
        }

        if ($this->timezone)
        {
            $package->setTimezone($this->timezone);
        }

        $this->integrate($package);
    }

    /**
     * @return void
     */
    protected function setPlate()
    {
        /** @var \Rougin\Slytherin\Template\RendererInterface */
        $renderer = $this->container->get(System::TEMPLATE);

        $plate = new Plate($renderer);

        // Add the specified filters to Plate -----------------
        $default = array('Staticka\Filter\LayoutFilter');

        /** @var string[] */
        $filters = $this->config->get('app.filters', $default);

        $container = new ReflectionContainer($this->container);

        foreach ($filters as $filter)
        {
            /** @var \Staticka\Filter\FilterInterface */
            $filter = $container->get($filter);

            $plate->addFilter($filter);
        }
        // ----------------------------------------------------

        // Add the specified helpers to Plate -------------------
        $default = array('Staticka\Expresso\Helpers\LinkHelper');

        $default[] = 'Staticka\Helper\BlockHelper';
        $default[] = 'Staticka\Helper\LayoutHelper';
        $default[] = 'Staticka\Helper\LinkHelper';
        $default[] = 'Staticka\Helper\PlateHelper';
        $default[] = 'Staticka\Helper\StringHelper';

        /** @var string[] */
        $helpers = $this->config->get('app.helpers', $default);

        foreach ($helpers as $helper)
        {
            /** @var \Staticka\Helper\HelperInterface */
            $helper = $container->get($helper);

            $plate->addHelper($helper);
        }
        // ------------------------------------------------------

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
