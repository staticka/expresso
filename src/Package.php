<?php

namespace Staticka\Expresso;

use Rougin\Slytherin\Container\ContainerInterface;
use Rougin\Slytherin\Container\ReflectionContainer;
use Rougin\Slytherin\Integration\Configuration;
use Rougin\Slytherin\Integration\IntegrationInterface;
use Staticka\Expresso\Depots\PageDepot;
use Staticka\Helper\PagesHelper;
use Staticka\Layout;
use Staticka\Parser;
use Staticka\Render;
use Staticka\Site;

/**
 * @package Staticka
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class Package implements IntegrationInterface
{
    /**
     * @param \Rougin\Slytherin\Container\ContainerInterface $container
     * @param \Rougin\Slytherin\Integration\Configuration    $config
     *
     * @return \Rougin\Slytherin\Container\ContainerInterface
     */
    public function define(ContainerInterface $container, Configuration $config)
    {
        /** @var \Staticka\System */
        $app = $container->get('Staticka\System');

        // Initialize the Render instance --------------
        $name = 'Staticka\Render\RenderInterface';

        if (! $container->has($name))
        {
            $render = new Render($app->getPlatesPath());

            $container->set($name, $render);
        }
        // ---------------------------------------------

        $reflect = new ReflectionContainer($container);

        // Initialize the Parser instance ------------
        $parser = $this->getParser($config, $reflect);

        $name = get_class($parser);
        $container->set($name, $parser);

        $app->setParser($parser);
        // -------------------------------------------

        // Initialize the Layout instance ------------
        $pages = new PagesHelper($app->getPages());
        $container->set(get_class($pages), $pages);

        $layout = $this->getLayout($config, $reflect);
        $layout->addHelper($pages);

        $name = get_class($layout);
        $container->set($name, $layout);
        // -------------------------------------------

        // Initialize the Site instance ---
        /** @var array<string, mixed> */
        $data = $config->get('site');
        $site = $this->getSite($data);

        $name = get_class($site);
        $container->set($name, $site);
        // --------------------------------

        /** @var string[] */
        $fields = $config->get('app.fields');

        $depot = new PageDepot($app, $fields);
        $container->set(get_class($depot), $depot);

        return $container->set('Staticka\System', $app);
    }

    /**
     * @param \Rougin\Slytherin\Integration\Configuration     $config
     * @param \Rougin\Slytherin\Container\ReflectionContainer $container
     *
     * @return \Staticka\Layout
     */
    protected function getLayout(Configuration $config, ReflectionContainer $container)
    {
        // Return the RenderInterface from container ---
        $render = 'Staticka\Render\RenderInterface';

        /** @var \Staticka\Render\RenderInterface */
        $render = $container->get($render);
        // ---------------------------------------------

        $layout = new Layout;

        // Set the layout name, if defined ---
        /** @var string|null */
        $name = $config->get('layout.name');

        if ($name)
        {
            $layout->setName($name);
        }
        // -----------------------------------

        // Initialize the defined filters -----------------
        /** @var class-string[] */
        $filters = $config->get('layout.filters', array());

        foreach ($filters as $filter)
        {
            /** @var \Staticka\Filter\FilterInterface */
            $filter = $container->get($filter);

            $layout->addFilter($filter);
        }
        // ------------------------------------------------

        // Initialize the defined helpers -----------------
        /** @var class-string[] */
        $helpers = $config->get('layout.helpers', array());

        foreach ($helpers as $helper)
        {
            /** @var \Staticka\Helper\HelperInterface */
            $helper = $container->get($helper);

            $layout->addHelper($helper);
        }
        // ------------------------------------------------

        return $layout;
    }

    /**
     * @param \Rougin\Slytherin\Integration\Configuration     $config
     * @param \Rougin\Slytherin\Container\ReflectionContainer $container
     *
     * @return \Staticka\Parser
     */
    protected function getParser(Configuration $config, ReflectionContainer $container)
    {
        $parser = new Parser;

        // Initialize the defined filters -----------------
        /** @var class-string[] */
        $filters = $config->get('parser.filters', array());

        foreach ($filters as $filter)
        {
            /** @var \Staticka\Filter\FilterInterface */
            $filter = $container->get($filter);

            $parser->addFilter($filter);
        }
        // ------------------------------------------------

        return $parser;
    }

    /**
     * @param array<string, mixed> $data
     *
     * @return \Staticka\Site
     */
    protected function getSite($data)
    {
        $site = new Site;

        return $site->setData($data);
    }
}
