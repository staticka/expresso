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
     * @return void
     */
    public function doSetUp()
    {
        $this->app = $this->setApp();
    }

    /**
     * @return void
     */
    public function test_build_without_staticka_yml()
    {
        $expected = '"staticka.yml" not yet created';

        /** @var string */
        $expected = json_encode($expected);

        $this->setRequest('POST', '/build');

        $this->app->run();

        $this->expectOutputString($expected);
    }
}
