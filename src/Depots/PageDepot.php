<?php

namespace Staticka\Expresso\Depots;

use Staticka\System;

/**
 * TODO: Migrate code to "staticka/staticka" instead.
 *
 * @package Staticka
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class PageDepot
{
    const SORT_ASC = 0;

    const SORT_DESC = 1;

    /**
     * @var \Staticka\System
     */
    protected $app;

    /**
     * @param \Staticka\System $app
     */
    public function __construct(System $app)
    {
        $this->app = $app;
    }

    /**
     * @param array<string, string> $data
     *
     * @return boolean
     */
    public function create($data)
    {
        $path = $this->app->getPagesPath();

        // @codeCoverageIgnoreStart
        if (! is_dir($path))
        {
            mkdir($path, 0777, true);
        }
        // @endCoverageIgnoreEnd

        if ($timezone = $this->app->getTimezone())
        {
            date_default_timezone_set($timezone);
        }

        $file = $this->setFilename($data);

        $data = $this->setTemplate($data);

        return file_put_contents($file, $data) !== false;
    }

    /**
     * @param string $name
     *
     * @return \Staticka\Page|null
     */
    public function findByName($name)
    {
        $result = null;

        foreach ($this->get() as $page)
        {
            if ($page->getName() === $name)
            {
                $result = $page;
            }
        }

        return $result;
    }

    /**
     * @param string $link
     *
     * @return \Staticka\Page|null
     */
    public function findByLink($link)
    {
        $result = null;

        foreach ($this->get() as $page)
        {
            if ($page->getLink() === $link)
            {
                $result = $page;
            }
        }

        return $result;
    }

    /**
     * @return \Staticka\Page[]
     */
    public function get()
    {
        return $this->app->getPages();
    }

    /**
     * @param integer $sort
     *
     * @return array<string, mixed>[]
     */
    public function getAsData($sort = self::SORT_ASC)
    {
        $pages = $this->get();

        if ($sort === self::SORT_DESC)
        {
            $pages = array_reverse($pages);
        }

        $items = array();

        foreach ($pages as $page)
        {
            $items[] = $page->getData();
        }

        return $items;
    }

    /**
     * @link https://stackoverflow.com/a/2103815
     *
     * @param string $text
     *
     * @return string
     */
    protected function getSlug($text)
    {
        // Convert to entities --------------------------
        $text = htmlentities($text, ENT_QUOTES, 'UTF-8');
        // ----------------------------------------------

        // Regex to convert accented chars into their closest a-z ASCII equivelent --------------
        $pattern = '~&([a-z]{1,2})(?:acute|cedil|circ|grave|lig|orn|ring|slash|th|tilde|uml);~i';

        /** @var string */
        $text = preg_replace($pattern, '$1', $text);
        // --------------------------------------------------------------------------------------

        // Convert back from entities -------------------------
        $text = html_entity_decode($text, ENT_QUOTES, 'UTF-8');
        // ----------------------------------------------------

        // Any straggling caracters that are not strict alphanumeric are replaced with a dash ---
        /** @var string */
        $text = preg_replace('~[^0-9a-z]+~i', '-', $text);
        // --------------------------------------------------------------------------------------

        // Trim / cleanup / all lowercase ---
        $text = trim($text, '-');

        return strtolower($text);
        // ----------------------------------
    }

    /**
     * @param array<string, string> $data
     *
     * @return string
     */
    protected function setFilename($data)
    {
        $slug = $this->getSlug($data['name']);

        if (array_key_exists('link', $data))
        {
            $slug = str_replace('/', '', $data['link']);
        }

        $prefix = date('YmdHis');

        $file = $prefix . '_' . $slug . '.md';

        $path = $this->app->getPagesPath();

        return (string) $path . '/' . $file;
    }

    /**
     * @param array<string, string> $data
     *
     * @return string
     */
    protected function setTemplate($data)
    {
        // TODO: Contents should be returned as constant in Page ---
        $path = __DIR__ . '/../Plates/default.md';

        /** @var string */
        $md = file_get_contents($path);
        // ---------------------------------------------------------

        $exists = array_key_exists('description', $data);

        $text = $exists ? $data['description'] : '';

        $md = str_replace('[TITLE]', $data['name'], $md);

        $md = str_replace('[DESCRIPTION]', $text, $md);

        $slug = $this->getSlug($data['name']);

        $exists = array_key_exists('link', $data);

        $slug = $exists ? $data['link'] : $slug;

        return str_replace('[LINK]', $slug, $md);
    }
}
