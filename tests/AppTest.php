<?php

namespace Staticka\Expresso;

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
        /** @var string */
        $expected = realpath(__DIR__ . '/../app');

        /** @var string */
        $actual = realpath(Express::getAppPath());

        $this->assertEquals($expected, $actual);
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
