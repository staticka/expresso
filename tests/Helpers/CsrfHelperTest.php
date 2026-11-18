<?php

namespace Staticka\Expresso\Helpers;

use Rougin\Slytherin\Http\ServerRequest;
use Rougin\Weasley\Session\FileSessionHandler;
use Rougin\Weasley\Session\Session;
use Staticka\Expresso\Testcase;

/**
 * @package Staticka
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class CsrfHelperTest extends Testcase
{
    /**
     * @return void
     */
    public function test_passed_if_token_verified_from_server_params()
    {
        $path = __DIR__ . '/../Fixture/cache/session';

        $id = 'csrf-helper';

        $file = $path . '/' . $id;

        // Seed a session with a known token ------
        $data = array('_csrf' => 'test-token');

        file_put_contents($file, serialize($data));

        $handler = new FileSessionHandler;

        $handler->open($path, $id);

        $session = new Session($handler, $id);

        $csrf = new CsrfHelper($session);
        // ----------------------------------------

        // Request without the X-CSRF-Token header ---
        // but with the token in the server params ---
        $server = array('REQUEST_METHOD' => 'POST');

        $server['REQUEST_URI'] = '/pages';

        $server['SERVER_NAME'] = 'localhost';

        $server['SERVER_PORT'] = '8000';

        $server['HTTP_X_CSRF_TOKEN'] = 'test-token';

        $request = new ServerRequest($server);
        // -------------------------------------------

        $this->assertTrue($csrf->verify($request));

        $handler->destroy($id);
    }
}
