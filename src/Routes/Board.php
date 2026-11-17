<?php

namespace Staticka\Expresso\Routes;

use Staticka\Depots\PageDepot;
use Staticka\Expresso\Plate;

/**
 * @package Staticka
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class Board
{
    /**
     * @param \Staticka\Depots\PageDepot $page
     * @param \Staticka\Expresso\Plate   $plate
     *
     * @return string
     */
    public function index(PageDepot $page, Plate $plate)
    {
        $pages = array_reverse($page->get());

        $items = array();

        foreach ($pages as $item)
        {
            $data = $item->getData();

            $file = $item->getFile();

            $updated = null;

            if ($file)
            {
                $updated = filemtime($file);

                if ($updated === false)
                {
                    // @codeCoverageIgnoreStart
                    $updated = null;
                    // @codeCoverageIgnoreEnd
                }
            }

            $data['updated_at'] = $updated;

            $items[] = $data;
        }

        $count = count($items);

        $latest = $items ? $items[0] : null;

        return $plate->view('board', compact('count', 'latest'));
    }
}
