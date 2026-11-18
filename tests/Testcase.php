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
    /**
     * @var \Staticka\Expresso\Express
     */
    protected $app;

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

    /**
     * @return void
     */
    protected function setCsrfToken()
    {
        $path = __DIR__ . '/Fixture/cache/session';

        if (! is_dir($path))
        {
            mkdir($path, 0777, true);
        }

        $_COOKIE['weasley_session'] = 'test-session';

        $file = $path . '/test-session';

        $data = array('_csrf' => 'test-token');

        $serial = serialize($data);

        file_put_contents($file, $serial);

        $_SERVER['HTTP_X_CSRF_TOKEN'] = 'test-token';
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

        // Rebuild the app so each request gets ------
        // a fresh container (HttpIntegration --------
        // caches the request across run() calls). ---
        $this->app = $this->setApp();
        // -------------------------------------------
    }
}
