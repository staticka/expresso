<?php

namespace Staticka\Expresso\Depots;

/**
 * @package Staticka
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class BuildDepot
{
    /**
     * @codeCoverageIgnore
     *
     * @return void
     */
    public function build()
    {
        // Requires "staticka/console" package to test this manually ---
        chdir($this->getRootPath());

        system('php vendor/bin/staticka build');
        // -------------------------------------------------------------
    }

    /**
     * @return string
     */
    public function getRootPath()
    {
        $main = __DIR__ . '/../../../../../';

        $exists = file_exists($main . '/vendor/autoload.php');

        return $exists ? $main : __DIR__ . '/../../';
    }
}
