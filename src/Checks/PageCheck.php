<?php

namespace Staticka\Expresso\Checks;

use Staticka\Depots\PageDepot;
use Staticka\Expresso\Check;

/**
 * @package Staticka
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class PageCheck extends Check
{
    /**
     * @var array<string, string>
     */
    protected $labels = array(
        'name' => 'Page Title',
        'link' => 'URL Link',
        'description' => 'Description',
    );

    /**
     * @var array<string, string>
     */
    protected $rules = array(
        'name' => 'required',
    );

    /**
     * @var integer|null
     */
    protected $id = null;

    /**
     * @var \Staticka\Depots\PageDepot
     */
    protected $page;

    /**
     * @param \Staticka\Depots\PageDepot $page
     * @param integer|null               $id
     */
    public function __construct(PageDepot $page, $id = null)
    {
        $this->id = $id;

        $this->page = $page;
    }

    /**
     * @param array<string, mixed>|null $data
     *
     * @return boolean
     */
    public function valid(array $data = null)
    {
        $valid = parent::valid($data);

        if (! $data || ! $valid)
        {
            return false;
        }

        /** @var string */
        $name = $data['name'];

        $link = $this->page->getSlug($name);

        if (array_key_exists('link', $data))
        {
            /** @var string */
            $link = $data['link'];
        }

        $page = $this->page->findByLink($link);

        if (! $this->id && $page)
        {
            $this->setError('link', 'URL Link already exists');
        }

        if ($this->id)
        {
            if ($page && $page->getLink() !== $link)
            {
                $this->setError('link', 'URL Link already exists');
            }
        }

        $page = $this->page->findByName($name);

        if (! $this->id && $page)
        {
            $this->setError('name', 'Page Title already exists');
        }

        if ($this->id && $page)
        {
            $pageName = strtolower((string) $page->getName());

            if ($pageName !== strtolower($name))
            {
                $this->setError('name', 'Page Title already exists');
            }
        }

        return count($this->errors) === 0;
    }
}
