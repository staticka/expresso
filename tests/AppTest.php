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
    public function doSetUp()
    {
        $this->app = $this->setApp();
    }

    /**
     * @return void
     */
    public function test_fixed_app_path()
    {
        $expect = realpath(__DIR__ . '/../app');

        $expect = $expect === false ? '' : $expect;

        $actual = Express::getAppPath();

        $this->assertEquals($expect, $actual);
    }

    /**
     * @return void
     */
    public function test_package_class()
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

        $result = $package->define($container, $config);

        /** @var \Staticka\System */
        $app = $result->get('Staticka\System');

        $actual = $app->getRootPath();

        $this->assertEquals($expect, $actual);
    }

    /**
     * @return void
     */
    public function test_welcome_page()
    {
        $this->setRequest('GET', '/');

        $this->app->run();

        $this->expectOutputRegex('/Welcome to Expresso!/');
    }
}
