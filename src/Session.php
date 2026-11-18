<?php

namespace Staticka\Expresso;

use Rougin\Weasley\Contract\Session as SessionContract;

/**
 * @package Staticka
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class Session
{
    /**
     * @var \Rougin\Weasley\Contract\Session
     */
    protected $session;

    /**
     * @param \Rougin\Weasley\Contract\Session $session
     */
    public function __construct(SessionContract $session)
    {
        $this->session = $session;
    }

    /**
     * @param string $key
     *
     * @return boolean
     */
    public function delete($key)
    {
        return $this->session->delete($key);
    }

    /**
     * @param string $key
     * @param mixed  $value
     *
     * @return self
     */
    public function flash($key, $value)
    {
        $this->session->set($key, $value);

        return $this;
    }

    /**
     * @param string     $key
     * @param mixed|null $default
     *
     * @return mixed
     */
    public function get($key, $default = null)
    {
        return $this->session->get($key, $default);
    }
}
