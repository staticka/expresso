<?php

namespace Staticka\Expresso\Checks;

use Staticka\Expresso\Check;
use Staticka\Expresso\Depots\PageDepot;

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
     * @var \Staticka\Expresso\Depots\PageDepot
     */
    protected $page;

    /**
     * @param \Staticka\Expresso\Depots\PageDepot $page
     */
    public function __construct(PageDepot $page)
    {
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

        if (array_key_exists('link', $data))
        {
            /** @var string */
            $link = $data['link'];

            $page = $this->page->findByLink($link);

            if ($page)
            {
                $this->setError('link', 'URL Link already exists');
            }
        }

        /** @var string */
        $name = $data['name'];

        $page = $this->page->findByName($name);

        if ($page)
        {
            $this->setError('name', 'Page Title already exists');
        }

        return count($this->errors) === 0;
    }
}
