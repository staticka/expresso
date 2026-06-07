<?php

namespace Staticka\Expresso\Routes;

use Staticka\Expresso\Testcase;
use Staticka\Page;
use Staticka\Parser;

/**
 * @package Staticka
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class CreateTest extends Testcase
{
    /**
     * @var \Staticka\Expresso\Express
     */
    protected $app;

    /**
     * @return void
     */
    public function test_failed_if_creating_duplicate_page()
    {
        // Create the initial page ----------------
        $this->setRequest('POST', '/pages');

        $name = 'Hello world!';

        $link = '/hello-world';

        $this->setPayload(compact('name', 'link'));
        // ----------------------------------------

        $this->app->run();

        $this->resetPayload();

        // Attempt to create a duplicate page -----
        $this->setRequest('POST', '/pages');

        $name = 'Hello world!';

        $link = '/hello-world';

        $this->setPayload(compact('name', 'link'));
        // ----------------------------------------

        $this->app->run();

        $text = 'URL Link already exists';

        $errors = array('link' => array($text));

        $text = 'Page Title already exists';

        $errors['name'] = array($text);

        $expect = $this->toJson('Page created!');

        $expect .= $this->toJson($errors);

        $this->expectOutputString($expect);

        $this->resetPayload();

        $this->deleteFile('hello-world');
    }

    /**
     * @return void
     */
    public function test_failed_if_create_without_data()
    {
        $this->setRequest('POST', '/pages');

        $this->app->run();

        $text = 'Page Title is required';
        $errors = array('name' => array($text));

        $expect = $this->toJson($errors);

        $this->expectOutputString($expect);
    }

    /**
     * @return void
     */
    public function test_failed_if_page_not_found()
    {
        $this->setRequest('GET', '/pages/0');

        $this->app->run();

        $expect = 'Page (0) not found';

        $this->expectOutputString($expect);
    }

    /**
     * @return void
     */
    public function test_failed_if_updating_to_duplicate()
    {
        // Create first page (Hello world!) -------
        $this->setRequest('POST', '/pages');

        $name = 'Hello world!';

        $link = '/hello-world';

        $this->setPayload(compact('name', 'link'));
        // ----------------------------------------

        $this->app->run();

        $this->resetPayload();

        // Create second page (Another page!) -----
        sleep(1);

        $this->setRequest('POST', '/pages');

        $name = 'Another page!';

        $link = '/another-page';

        $this->setPayload(compact('name', 'link'));
        // ----------------------------------------

        $this->app->run();

        // Get the ID of the second page ------
        $this->resetPayload();

        $id = $this->getPageId('another-page');
        // ------------------------------------

        // Attempt a duplicate page ---------------
        $this->setRequest('POST', '/pages/' . $id);

        $name = 'Hello world!';

        $link = '/hello-world';

        $this->setPayload(compact('name', 'link'));
        // ----------------------------------------

        $this->app->run();

        $text = 'URL Link already exists';

        $errors = array('link' => array($text));

        $text = 'Page Title already exists';

        $errors['name'] = array($text);

        $expect = $this->toJson('Page created!');

        $expect .= $this->toJson('Page created!');

        $expect .= $this->toJson($errors);

        $this->expectOutputString($expect);

        $this->resetPayload();

        $this->deleteFile('hello-world');

        $this->deleteFile('another-page');
    }

    /**
     * @return void
     */
    public function test_passed_if_another_page_created()
    {
        sleep(1);

        $this->setRequest('POST', '/pages');

        $name = 'Another page!';
        $link = '/another-page';
        $this->setPayload(compact('name', 'link'));

        $this->app->run();

        $expect = $this->toJson('Page created!');

        $this->expectOutputString($expect);

        $this->resetPayload();

        $this->deleteFile('another-page');
    }

    /**
     * @return void
     */
    public function test_passed_if_new_page_created()
    {
        $this->setRequest('POST', '/pages');

        $name = 'Hello world!';

        $link = '/hello-world';

        $this->setPayload(compact('name', 'link'));

        $this->app->run();

        $expect = $this->toJson('Page created!');

        $this->expectOutputString($expect);

        $this->resetPayload();

        $this->deleteFile('hello-world');
    }

    /**
     * @return void
     */
    public function test_passed_if_page_details_shown()
    {
        // Create a page first (Hello world!) -----
        $this->setRequest('POST', '/pages');

        $name = 'Hello world!';

        $link = '/hello-world';

        $this->setPayload(compact('name', 'link'));
        // ----------------------------------------

        $this->app->run();

        $this->resetPayload();

        $id = $this->getPageId('hello-world');

        // Show the details of the created page ----
        $this->setRequest('GET', '/pages/' . $id);

        $this->app->run();

        $html = '"input":{"name":"Hello world!",';
        $html .= '"title":"Hello world!"';
        // -----------------------------------------

        $this->expectOutputRegex('/' . $html . '/');

        $this->deleteFile('hello-world');
    }

    /**
     * @return void
     */
    public function test_passed_if_page_updated()
    {
        // Create a page first (Hello world!) -----
        $this->setRequest('POST', '/pages');

        $name = 'Hello world!';

        $link = '/hello-world';

        $this->setPayload(compact('name', 'link'));
        // ----------------------------------------

        $this->app->run();

        $this->resetPayload();

        // Update the newly created page ----------
        $id = $this->getPageId('hello-world');

        $this->setRequest('POST', '/pages/' . $id);

        $name = 'This is something!';

        $link = '/hello-world';

        $this->setPayload(compact('name', 'link'));
        // ----------------------------------------

        $this->app->run();

        $page = $this->getActualPage('hello-world');

        $expect = $this->toJson('Page created!');

        $expect .= $this->toJson('Page updated!');

        $this->expectOutputString($expect);

        $expect = 'This is something!';

        $actual = $page->getName();

        $this->assertEquals($expect, $actual);

        $this->deleteFile('hello-world');
    }

    /**
     * @return void
     */
    public function test_passed_if_pages_list_loaded()
    {
        $this->setRequest('GET', '/pages');

        $this->app->run();

        $this->expectOutputRegex('/Create New Page/');
    }

    /**
     * @param string $name
     *
     * @return void
     */
    protected function deleteFile($name)
    {
        if ($file = $this->getActualFile($name))
        {
            unlink($file);
        }
    }

    /**
     * @return void
     */
    protected function doSetUp()
    {
        $path = __DIR__ . '/../Fixture/pages';

        $files = glob($path . '/*.md');

        $files = $files === false ? array() : $files;

        foreach ($files as $file)
        {
            unlink($file);
        }

        $this->app = $this->setApp();

        $this->resetPayload();
    }

    /**
     * @param string $name
     *
     * @return string|null
     */
    protected function getActualFile($name)
    {
        $path = __DIR__ . '/../Fixture/pages';

        $files = glob($path . '/*.md');

        $files = $files === false ? array() : $files;

        $selected = null;

        foreach ($files as $file)
        {
            $base = basename($file);

            $parsed = substr($base, 15, strlen($base));

            if ($parsed === $name . '.md')
            {
                $selected = $file;

                break;
            }
        }

        return $selected;
    }

    /**
     * @param string $name
     *
     * @return \Staticka\Page
     * @throws \Exception
     */
    protected function getActualPage($name)
    {
        $file = $this->getActualFile($name);

        if (! $file)
        {
            throw new \Exception('File "' . $name . '" not found');
        }

        $parser = new Parser;

        return $parser->parsePage(new Page($file));
    }

    /**
     * @param string $name
     *
     * @return integer
     */
    protected function getPageId($name)
    {
        $file = $this->getActualFile($name);

        if (! $file)
        {
            throw new \Exception('File "' . $name . '" not found');
        }

        $timestamp = substr(basename($file), 0, 14);

        return (int) strtotime($timestamp);
    }

    /**
     * @param mixed $value
     *
     * @return string
     */
    protected function toJson($value)
    {
        $result = json_encode($value);

        return $result === false ? '' : $result;
    }
}
