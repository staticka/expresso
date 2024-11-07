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
        $path = __DIR__ . '/Fixture';

        $app = new Express;

        $app->setAppUrl('http://localhost:3977');
        $app->setSiteUrl('http://localhost:3978');
        $app->setRootPath($path);

        $app->setBuildPath($path . '/build');
        $app->setConfigPath($path . '/config');
        $app->setPagesPath($path . '/pages');
        $app->setBuildPath($path . '/build');
        $app->setTimezone('Asia/Manila');

        $plates = __DIR__ . '/../app/plates';
        $app->setPlatesPath($plates);

        $this->app = $app->setFields(array());
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
    public function test_pages_page()
    {
        $this->setRequest('GET', '/pages');

        $this->app->run();

        $this->expectOutputRegex('/Create New Page/');
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

    /**
     * @param string $method
     * @param string $uri
     *
     * @return void
     */
    protected function setRequest($method, $uri)
    {
        $_SERVER['REQUEST_METHOD'] = $method;
        $_SERVER['REQUEST_URI'] = $uri;
        $_SERVER['SERVER_NAME'] = 'localhost';
        $_SERVER['SERVER_PORT'] = '8000';
    }
}
