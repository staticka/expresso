<?php

namespace Staticka\Expresso\Helpers;

/**
 * @package Staticka
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class DataHelper
{
    /**
     * @param string[]             $fields
     * @param array<string, mixed> $data
     *
     * @return string
     */
    public static function toJson($fields, $data = array())
    {
        $item = array('input' => array());

        foreach ($fields as $field)
        {
            $item['input'][$field] = null;
        }

        foreach ($data as $key => $value)
        {
            $item['input'][$key] = $data[$key];
        }

        $item['body'] = $data['body'];

        $item['html'] = null;

        $item['loading'] = false;

        $item = json_encode($item);

        return $item === false ? '' : $item;
    }
}
