<?php

namespace Staticka\Expresso\Depots;

/**
 * TODO: Migrate code to "staticka/staticka" instead.
 *
 * @package Staticka
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class PageDepot
{
    /**
     * @param array<string, string> $data
     * @param string                $path
     * @param string|null           $timezone
     *
     * @return boolean
     */
    public function create($data, $path, $timezone = null)
    {
        // @codeCoverageIgnoreStart
        if (! is_dir($path))
        {
            mkdir($path, 0777, true);
        }
        // @endCoverageIgnoreEnd

        if ($timezone)
        {
            date_default_timezone_set($timezone);
        }

        // Set the file name of the new page ---------------
        $slug = $this->getSlug($data['name']);

        if (array_key_exists('link', $data))
        {
            $slug = str_replace('/', '', $data['link']);
        }

        $prefix = date('YmdHis');

        $file = $path . '/' . $prefix . '_' . $slug . '.md';
        // -------------------------------------------------

        $text = '';

        if (array_key_exists('description', $data))
        {
            $text = $data['description'];
        }

        $data = $this->setData($data['name'], $text, $slug);

        return file_put_contents($file, $data) !== false;
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
     * @param string      $name
     * @param string      $description
     * @param string|null $link
     *
     * @return string
     */
    protected function setData($name, $description, $link = null)
    {
        // TODO: Contents should be returned as constant in Page ---
        $path = __DIR__ . '/../Plates/default.md';

        /** @var string */
        $data = file_get_contents($path);
        // ---------------------------------------------------------

        $data = str_replace('[TITLE]', $name, $data);

        $data = str_replace('[DESCRIPTION]', $description, $data);

        if (! $link)
        {
            $link = $this->getSlug($name);
        }

        return str_replace('[LINK]', $link, $data);
    }
}
