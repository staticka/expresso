<?php

namespace Staticka\Expresso\Routes;

use Staticka\Expresso\Testcase;

/**
 * @package Staticka
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class BuildTest extends Testcase
{
    /**
     * @var \Staticka\Expresso\Express
     */
    protected $app;

    /**
     * @runInSeparateProcess
     *
     * @return void
     */
    public function test_failed_if_build_without_config()
    {
        $expect = '"staticka.yml" not yet created';

        $expect = json_encode($expect);

        $expect = $expect === false ? '' : $expect;

        $this->setRequest('POST', '/build');

        $this->app->run();

        $this->expectOutputString($expect);
    }

    /**
     * @runInSeparateProcess
     *
     * @return void
     */
    public function test_failed_if_build_without_csrf()
    {
        unset($_SERVER['HTTP_X_CSRF_TOKEN']);

        $this->setRequest('POST', '/build');

        $this->app->run();

        $expect = json_encode('Invalid CSRF token');

        $expect = $expect === false ? '' : $expect;

        $this->expectOutputString($expect);
    }

    /**
     * @return void
     */
    protected function doSetUp()
    {
        $this->app = $this->setApp();

        $this->setCsrfToken();
    }
}
