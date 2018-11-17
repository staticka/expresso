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
     * @var \Staticka\Expresso\Composer
     */
    protected $composer;

    /**
     * @var string
     */
    protected $pages = '';

    /**
     * @var string
     */
    protected $plates = '';

    /**
     * Initializes the content instance.
     *
     * @param \Staticka\Expresso\Composer $composer
     * @param string                      $pages
     * @param string                      $plates
     */
    public function __construct(Composer $composer, $pages, $plates)
    {
        if (! file_exists($pages))
        {
            mkdir((string) $pages);
        }

        $this->plates = $plates;

        $this->pages = $pages;

        $this->composer = $composer;

        if (! file_exists($plates))
        {
            mkdir((string) $plates);
        }
    }

    /**
     * Returns the path of the composer.json file.
     *
     * @param  string $type
     * @return string
     */
    public function path($type)
    {
        return $type === 'pages' ? $this->pages : $this->plates;
    }

    public function root()
    {
        return $this->composer->path();
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
        $files = glob($this->pages . '/*');

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
     * Returns the defined templates.
     *
     * @return array
     */
    public function plates()
    {
        $items = glob($this->plates . '/*');

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
