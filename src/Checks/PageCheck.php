<?php

namespace Staticka\Expresso\Checks;

use Rougin\Valla\Check;
use Staticka\Depots\PageDepot;

/**
 * @package Staticka
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class PageCheck extends Check
{
    /**
     * @var integer|null
     */
    protected $id = null;

    /**
     * @var array<string, string>
     */
    protected $labels = array(

        'name' => 'Page Title',
        'link' => 'URL Link',
        'description' => 'Description',

    );

    /**
     * @var \Staticka\Depots\PageDepot
     */
    protected $page;

    /**
     * @var array<string, string>
     */
    protected $rules = array(

        'name' => 'required',

    );

    /**
     * @param \Staticka\Depots\PageDepot $page
     */
    public function __construct(PageDepot $page)
    {
        $this->page = $page;
    }

    /**
     * @param integer $id
     *
     * @return self
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @param array<string, mixed> $data
     *
     * @return boolean
     */
    public function valid(array $data)
    {
        $valid = parent::valid($data);

        // @codeCoverageStartIgnore
        if (! $data || ! $valid)
        {
            return false;
        }
        // @codeCoverageEndIgnore

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

        if ($this->id && $page)
        {
            if ($page->getId() !== $this->id)
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
            if ($page->getId() !== $this->id)
            {
                $this->setError('name', 'Page Title already exists');
            }
        }

        return count($this->errors) === 0;
    }
}
