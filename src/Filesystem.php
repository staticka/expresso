<?php

namespace Staticka\Expresso;

use Staticka\Matter;

class Filesystem
{
    protected $json;

    protected $path = '';

    public function __construct($path, ComposerReader $json)
    {
        $this->path = $path;

        if (! file_exists($path . '/pages'))
        {
            mkdir((string) $path . '/pages');
        }

        $this->json = $json;
    }

    public function data()
    {
        return $this->json->data();
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

            $item['created_at'] = strtotime($item['id']);

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
