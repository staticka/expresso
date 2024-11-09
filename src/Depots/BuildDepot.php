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
        $vendor = __DIR__ . '/../../../../../';

        $root = __DIR__ . '/../../';

        $exists = file_exists($vendor . '.gitignore');

        return $exists ? $vendor : $root;
    }
}
