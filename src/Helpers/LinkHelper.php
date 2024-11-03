<?php

namespace Staticka\Expresso\Helpers;

use Staticka\Helper\LinkHelper as Staticka;

/**
 * @package Staticka
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class LinkHelper extends Staticka
{
    /**
     * @var array<string, string>
     */
    protected $server;

    /**
     * @param string                $base
     * @param array<string, string> $server
     */
    public function __construct($base, $server = array())
    {
        parent::__construct($base);

        $this->server = $server;
    }

    /**
     * @return string
     */
    public function getCurrent()
    {
        $host = '';

        if (array_key_exists('HTTP_HOST', $this->server))
        {
            $host = $this->server['HTTP_HOST'];
        }

        $uri = '';

        if (array_key_exists('REQUEST_URI', $this->server))
        {
            $uri = $this->server['REQUEST_URI'];
        }

        $exists = array_key_exists('HTTPS', $this->server);

        $html = $exists ? 'https' : 'http';

        return $html . '://' . $host . $uri;
    }

    /**
     * @param string $link
     *
     * @return boolean
     */
    public function isCurrent($link)
    {
        $isMain = $link === '/';

        $link = $this->set($link);

        $current = $this->getCurrent();

        if (! $isMain)
        {
            return strpos($current, $link) !== false;
        }

        return $current === $link;
    }

    /**
     * @return string
     */
    public function name()
    {
        return 'link';
    }
}
