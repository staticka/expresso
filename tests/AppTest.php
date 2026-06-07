<?php

namespace Staticka\Expresso;

use Rougin\Slytherin\Configuration;
use Rougin\Slytherin\Container\Container;
use Staticka\System;

/**
 * @package Staticka
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class AppTest extends Testcase
{
    /**
     * @var \Staticka\Expresso\Express
     */
    protected $app;

    /**
     * @return void
     */
    public function test_passed_if_app_path_fixed()
    {
        $expect = realpath(__DIR__ . '/../app');

        $expect = $expect === false ? '' : $expect;

        $actual = Express::getAppPath();

        $this->assertEquals($expect, $actual);
    }

    /**
     * @return void
     */
    public function test_passed_if_package_loads_system()
    {
        $expect = __DIR__ . '/Fixture';

        $config = new Configuration;

        $config->set('layout.name', 'post');

        $filters = array('Staticka\Filter\HtmlMinifier');
        $config->set('layout.filters', $filters);

        $filters = array('Staticka\Filter\HtmlMinifier');
        $config->set('parser.filters', $filters);

        $helpers = array('Staticka\Helper\StringHelper');
        $config->set('layout.helpers', $helpers);

        $package = new Package;

        $container = new Container;

        $old = new System($expect);
        $old->setPagesPath($expect . '/pages');
        $container->set(get_class($old), $old);

        $defined = $package->define($container, $config);

        /** @var \Staticka\System */
        $app = $defined->get('Staticka\System');

        $actual = $app->getRootPath();

        $this->assertEquals($expect, $actual);
    }

    /**
     * @return void
     */
    public function test_passed_if_welcome_page_shown()
    {
        $this->setRequest('GET', '/');

        $this->app->run();

        $this->expectOutputRegex('/Welcome to Expresso!/');
    }

    /**
     * @return void
     */
    protected function doSetUp()
    {
        $this->app = $this->setApp();
    }
}
