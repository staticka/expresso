<?php

namespace Staticka\Expresso\Checks;

use Staticka\Expresso\Check;

/**
 * @package Staticka
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class BuildCheck extends Check
{
    /**
     * @var string
     */
    protected $root;

    /**
     * @param string $root
     */
    public function __construct($root)
    {
        $this->root = $root;
    }

    /**
     * @param array<string, mixed>|null $data
     *
     * @return boolean
     */
    public function valid(array $data = null)
    {
        if (! file_exists($this->root . '/staticka.yml'))
        {
            $this->setError('file', '"staticka.yml" not yet created');
        }

        return count($this->errors) === 0;
    }
}
