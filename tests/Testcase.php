<?php

namespace Staticka\Expresso;

use LegacyPHPUnit\TestCase as Legacy;

/**
 * @codeCoverageIgnore
 *
 * @package Staticka
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class Testcase extends Legacy
{
    /** @phpstan-ignore-next-line */
    public function setExpectedException($exception)
    {
        if (method_exists($this, 'expectException'))
        {
            $this->expectException($exception);

            return;
        }

        /** @phpstan-ignore-next-line */
        parent::setExpectedException($exception);
    }

    /**
     * @param string[] $fields
     *
     * @return \Staticka\Expresso\Express
     */
    protected function setApp($fields = array())
    {
        $path = __DIR__ . '/Fixture';

        $app = new Express;

        $app->setAppUrl('http://localhost:3977');
        $app->setSiteUrl('http://localhost:3978');
        $app->setRootPath($path);

        $app->setBuildPath($path . '/build');
        $app->setConfigPath($path . '/config');
        $app->setPagesPath($path . '/pages');
        $app->setBuildPath($path . '/build');
        $app->setTimezone('Asia/Manila');

        $plates = __DIR__ . '/../app/plates';
        $app->setPlatesPath($plates);

        return $app->setFields($fields);
    }

    /**
     * @param string $method
     * @param string $uri
     *
     * @return void
     */
    protected function setRequest($method, $uri)
    {
        $_SERVER['REQUEST_METHOD'] = $method;
        $_SERVER['REQUEST_URI'] = $uri;
        $_SERVER['SERVER_NAME'] = 'localhost';
        $_SERVER['SERVER_PORT'] = '8000';
        $_SERVER['HTTP_HOST'] = 'https';
    }

    /**
     * @param array<string, mixed> $data
     *
     * @return void
     */
    protected function setPayload($data)
    {
        foreach ($data as $key => $value)
        {
            $_POST[$key] = $value;
        }
    }

    /**
     * @return void
     */
    protected function resetPayload()
    {
        foreach ($_POST as $key => $value)
        {
            unset($_POST[$key]);
        }
    }
}
