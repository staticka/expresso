<?php

namespace Staticka\Expresso;

use Staticka\Matter;

class Filesystem
{
    protected $path = '';

    public function __construct($path)
    {
        $this->path = $path;

        if (! file_exists($path . '/pages'))
        {
            mkdir((string) $path . '/pages');
        }
    }

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

            array_push($pages, (array) $item);
        }

        return array_reverse($pages);
    }

    public function path()
    {
        return $this->path;
    }

    public function plates()
    {
        $items = glob($this->path . '/plates/*');

        $plates = array();

        foreach ($items as $file)
        {
            $info = pathinfo((string) $file);

            $name = $info['filename'];

            $plates[$name] = basename($file);
        }

        return $plates;
    }
}
