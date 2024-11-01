<?php

namespace Staticka\Expresso\Checks;

use Staticka\Expresso\Check;

/**
 * @package Staticka
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class PageCheck extends Check
{
    /**
     * @var array<string, string>
     */
    protected $labels = array(
        'name' => 'Page Title',
        'link' => 'URL Link',
        'description' => 'Description',
    );

    /**
     * @var array<string, string>
     */
    protected $rules = array(
        'name' => 'required',
    );
}
