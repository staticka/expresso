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
     * @return void
     */
    public function build()
    {
        chdir($this->getRootPath());

        $script = 'vendor/bin/staticka';

        system($script . ' build');
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
