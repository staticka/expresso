<?php

return array(
    /**
     * @var string
     */
    'app_url' => 'http://localhost:3977',

    /**
     * @var string
     */
    'base_url' => 'http://localhost:3978',

    /**
     * @var string[]
     */
    'fields' => array(
        'name',
        'title',
        'description',
        'link',
        'plate',
        'category',
        'tags',
    ),

    /**
     * The filters specified below will be executed in exact order.
     *
     * @var \Staticka\Filter\FilterInterface[]
     */
    'filters' => array(
        'Staticka\Filter\LayoutFilter',
    ),

    /**
     * @var \Staticka\Helper\HelperInterface[]
     */
    'helpers' => array(
        'Staticka\Expresso\Helpers\LinkHelper',
        'Staticka\Helper\BlockHelper',
        'Staticka\Helper\LayoutHelper',
        'Staticka\Helper\LinkHelper',
        'Staticka\Helper\PlateHelper',
        'Staticka\Helper\StringHelper',
    ),
);
