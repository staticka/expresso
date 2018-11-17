<?php

namespace Staticka\Expresso;

use Zapheus\Provider\Configuration;

/**
 * Composer
 *
 * @package Expresso
 * @author  Rougin Gutib <rougingutib@gmail.com>
 */
class Composer extends Configuration
{
    /**
     * Initializes the reader instance.
     *
     * @param string $json
     */
    public function __construct($json)
    {
        $this->data = json_decode($json, true);

        if ($this->get('expresso.fields'))
        {
            $fields = $this->get('expresso.fields');

            $fields = array_chunk($fields, 3);

            $this->set('expresso.fields', $fields);
        }

        $this->data = $this->get('expresso');
    }
}
