<?php

namespace Staticka\Expresso\Helpers;

/**
 * @package Staticka
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class FieldHelper
{
    /**
     * @param string[]             $fields
     * @param array<string, mixed> $data
     *
     * @return string
     */
    public static function toJson($fields, $data = array())
    {
        $result = array('input' => array());

        foreach ($fields as $field)
        {
            $result['input'][$field] = null;
        }

        foreach ($data as $key => $value)
        {
            $result['input'][$key] = $data[$key];
        }

        $result['body'] = $data['body'];

        $result['html'] = null;

        $result['loading'] = false;

        /** @var string */
        return json_encode($result);
    }
}
