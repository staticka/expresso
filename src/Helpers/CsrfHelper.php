<?php

namespace Staticka\Expresso\Helpers;

use Psr\Http\Message\ServerRequestInterface;
use Staticka\Helper\HelperInterface;

/**
 * @package Staticka
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class CsrfHelper implements HelperInterface
{
    /**
     * Returns the name of the helper.
     *
     * @return string
     */
    public function name()
    {
        return 'csrf';
    }

    /**
     * Returns the CSRF token for the current session.
     *
     * @return string
     */
    public function token()
    {
        $self = new CsrfHelper;

        $self->start();

        if (! array_key_exists('_csrf', $_SESSION))
        {
            $_SESSION['_csrf'] = $self->random();
        }

        /** @var string */
        return $_SESSION['_csrf'];
    }

    /**
     * Verifies the CSRF token from the specified request.
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request
     *
     * @return boolean
     */
    public function verify(ServerRequestInterface $request)
    {
        $token = $request->getHeaderLine('X-CSRF-Token');

        if ($token === '')
        {
            $server = $request->getServerParams();

            if (array_key_exists('HTTP_X_CSRF_TOKEN', $server))
            {
                /** @var string */
                $token = $server['HTTP_X_CSRF_TOKEN'];
            }
        }

        if ($token !== '')
        {
            return $this->equals($token, $this->token());
        }

        $data = $request->getParsedBody();

        if (is_array($data) && array_key_exists('_csrf', $data))
        {
            /** @var string */
            $token = $data['_csrf'];
        }

        return $this->equals($token, $this->token());
    }

    /**
     * Compares two strings in constant time.
     *
     * @param string $known
     * @param string $user
     *
     * @return boolean
     */
    protected function equals($known, $user)
    {
        $length = strlen($known);

        if ($length !== strlen($user))
        {
            return false;
        }

        $result = 0;

        for ($i = 0; $i < $length; $i++)
        {
            $result |= ord($known[$i]) ^ ord($user[$i]);
        }

        return $result === 0;
    }

    /**
     * Generates a cryptographically secure token.
     *
     * @return string
     */
    protected function random()
    {
        if (function_exists('random_bytes'))
        {
            return bin2hex(random_bytes(32));
        }

        // @codeCoverageIgnoreStart
        $random = (string) mt_rand();

        return md5(uniqid($random, true));
        // @codeCoverageIgnoreEnd
    }

    /**
     * Starts the session if not yet started.
     *
     * @return void
     */
    protected function start()
    {
        if (session_id() === '')
        {
            session_start();
        }
    }
}
