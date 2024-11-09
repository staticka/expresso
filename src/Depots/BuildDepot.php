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
     * @var string
     */
    protected $script = 'php vendor/bin/staticka build';

    /**
     * @return void
     */
    public function build()
    {
        chdir($this->getRootPath());

        system($this->script);
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

    /**
     * @param string $script
     *
     * @return self
     */
    public function setScript($script)
    {
        $this->script = $script;

        return $this;
    }
}
