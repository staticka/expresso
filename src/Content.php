<?php

namespace Staticka\Expresso;

use Staticka\Matter;

/**
 * Content
 *
 * @package Expresso
 * @author  Rougin Gutib <rougingutib@gmail.com>
 */
class Content
{
    /**
     * @var string
     */
    protected $path = '';

    /**
     * @var \Staticka\Expresso\Composer
     */
    protected $composer;

    /**
     * Initializes the content instance.
     *
     * @param string                      $path
     * @param \Staticka\Expresso\Composer $composer
     */
    public function __construct($path, Composer $composer)
    {
        $this->path = (string) $path;

        if (! file_exists($path . '/pages'))
        {
            mkdir((string) $path . '/pages');
        }

        $this->composer = $composer;
    }

    /**
     * Returns the composer instance.
     *
     * @return \Staticka\Expresso\Composer
     */
    public function composer()
    {
        return $this->composer;
    }

    /**
     * Returns an array of pages.
     *
     * @return array
     */
    public function pages()
    {
        $files = glob($this->path . '/pages/*');

        $pages = array();

        foreach ($files as $file)
        {
            $filename = basename((string) $file);

            $content = file_get_contents($file);

            $matter = Matter::parse($content);

            $item = array('id' => substr($filename, 0, 14));

            $item = array_merge($item, $matter[0]);

            $item['content'] = (string) $matter[1];

            $item['filename'] = (string) $filename;

            $item['file'] = (string) $file;

            $item['created_at'] = strtotime($item['id']);

            $item['tag_items'] = array();

            if (isset($item['tags']) && is_string($item['tags']))
            {
                $item['tag_items'] = explode(',', $item['tags']);

                foreach ($item['tag_items'] as $index => $value)
                {
                    $item['tag_items'][$index] = trim($value);
                }
            }

            array_push($pages, (array) $item);
        }

        return array_reverse($pages);
    }

    /**
     * Returns the defined root path.
     *
     * @return string
     */
    public function path()
    {
        return $this->path;
    }

    /**
     * Returns the defined templates.
     *
     * @return array
     */
    public function plates()
    {
        $items = glob($this->path . '/plates/*');

        $plates = array();

        foreach ($items as $file)
        {
            $info = pathinfo((string) $file);

            $name = $info['filename'];

            $basename = basename($file);

            if ($basename[0] === '_')
            {
                continue;
            }

            $plates[$name] = $basename;
        }

        return $plates;
    }
}
