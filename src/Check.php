<?php

namespace Staticka\Expresso;

use Valitron\Validator;

/**
 * TODO: Seperate from "rougin/weasley" and create it as a new package.
 *
 * @package Staticka
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class Check
{
    /**
     * @var array<string, string[]>
     */
    protected $errors = array();

    /**
     * @var array<string, mixed>
     */
    protected $data = array();

    /**
     * @var array<string, string>
     */
    protected $labels = array();

    /**
     * @var array<string, string>
     */
    protected $rules = array();

    /**
     * Returns the generated errors after validation.
     *
     * @return array<string, string[]>
     */
    public function errors()
    {
        return $this->errors;
    }

    /**
     * Returns the first error after validation.
     *
     * @return string|null
     */
    public function firstError()
    {
        if (! $this->errors)
        {
            return null;
        }

        /** @var string[][] */
        $values = array_values($this->errors);

        return (string) $values[0][0];
    }

    /**
     * Returns the specified labels.
     *
     * @return array<string, string>
     */
    public function labels()
    {
        return $this->labels;
    }

    /**
     * Returns the specified rules based from the payload.
     *
     * @param array<string, mixed> $data
     *
     * @return array<string, string>
     */
    public function rules($data)
    {
        return $this->rules;
    }

    /**
     * Adds a new error message to the specified key.
     *
     * @param string $key
     * @param string $text
     *
     * @return self
     */
    public function setError($key, $text)
    {
        if (! isset($this->errors[$key]))
        {
            $this->errors[$key] = array();
        }

        array_push($this->errors[$key], $text);

        return $this;
    }

    /**
     * Checks if the payload is valid againsts the specified rules.
     *
     * @param array<string, mixed>|null $data
     *
     * @return boolean
     */
    public function valid(array $data = null)
    {
        $valid = new Validator;

        $valid->labels($this->labels());

        if (! $data)
        {
            $data = $this->data;
        }

        $rules = $this->rules($data);

        foreach ($rules as $key => $rule)
        {
            // Break down multiple rules ---
            $items = explode('|', $rule);

            // TODO: Allow custom rules from existing, new ones ---
            foreach ($items as $item)
            {
                $valid->rule($item, $key);
            }
            // ----------------------------------------------------
            // -----------------------------
        }

        $valid = $valid->withData($data);

        if ($valid->validate())
        {
            return count($this->errors) === 0;
        }

        /** @var array<string, string[]> */
        $result = $valid->errors();

        foreach ($result as $name => $errors)
        {
            foreach ($errors as $error)
            {
                $this->setError($name, $error);
            }
        }

        return count($this->errors) === 0;
    }
}
