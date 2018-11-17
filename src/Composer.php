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
     * @var string
     */
    protected $path = '';

    /**
     * Initializes the reader instance.
     *
     * @param string $path
     */
    public function __construct($path)
    {
        $json = file_get_contents($path);

        $this->data = json_decode($json, true);

        if ($this->get('expresso.fields'))
        {
            $fields = $this->get('expresso.fields');

            $fields = array_chunk($fields, 3);

            $this->set('expresso.fields', $fields);
        }

        $this->path = substr($path, 0, -14);

        $this->data = $this->get('expresso');
    }

    /**
     * Returns the path of the composer.json file.
     *
     * @return string
     */
    public function path()
    {
        return $this->path;
    }
}
