<?php

namespace Staticka\Expresso;

use Staticka\Website;

/**
 * Builder
 *
 * @package Expresso
 * @author  Rougin Gutib <rougingutib@gmail.com>
 */
class Builder
{
    /**
     * @var \Staticka\Website
     */
    protected $website;

    /**
     * Initializes the builder instance.
     *
     * @param \Staticka\Website $website
     */
    public function __construct(Website $website)
    {
        $this->website = $website;
    }

    /**
     * Generates static HTML with given data.
     *
     * @param  \Staticka\Expresso\Content $content
     * @return void
     */
    public function build(Content $content)
    {
        $pages = (array) $content->pages();

        $data = $content->composer();

        $this->filters($data->get('filters', array()));

        $variables = $data->get('variables', array());

        $variables['pages'] = (array) $pages;

        $path = realpath($content->path('pages'));

        $this->pages($pages, (array) $variables);

        $path = $content->root() . '/build';

        $this->website->compile((string) $path);
    }

    /**
     * Sets filters into the website instance.
     *
     * @param  array $filters
     * @return void
     */
    protected function filters(array $filters)
    {
        foreach ($filters as $filter)
        {
            $this->website->filter(new $filter);
        }
    }

    /**
     * Sets page instances into the website instance.
     *
     * @param  array $pages
     * @param  array $variables
     * @return void
     */
    protected function pages(array $pages, array $variables)
    {
        foreach ($pages as $page)
        {
            $variables['tag_items'] = $page['tag_items'];

            $variables['item'] = $page;

            $this->website->page($page['file'], $variables);
        }
    }
}