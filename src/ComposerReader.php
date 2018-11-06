<?php

namespace Staticka\Expresso;

use Staticka\Filter\ScriptMinifier;

class ComposerReader
{
    protected $data = array();

    public function __construct($json)
    {
        $default = array('filters' => array());

        $default['variables'] = array();

        $default['website'] = array('name' => 'Expresso');

        $default['website']['version'] = 'v0.1.0';

        $data = json_decode($json, true);

        if (! isset($data['expresso']))
        {
            $data = array('expresso' => array());
        }

        $data = (array) $data['expresso'];

        $this->data = array_merge($default, $data);
    }

    public function data()
    {
        return $this->data;
    }
}
