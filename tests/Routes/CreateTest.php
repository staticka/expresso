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
    public function doSetUp()
    {
        $this->app = $this->setApp();

        $this->resetPayload();
    }

    /**
     * @depends test_update_page
     *
     * @return integer
     */
    public function test_create_another_page()
    {
        sleep(1);

        $this->setRequest('POST', '/pages');

        $name = 'Another page!';
        $link = '/another-page';
        $this->setPayload(compact('name', 'link'));

        $this->app->run();

        $expected = $this->toJson('Page created!');

        $this->expectOutputString($expected);

        $this->resetPayload();

        return $this->getPageId('another-page');
    }

    /**
     * @return integer
     */
    public function test_create_new_page()
    {
        $this->setRequest('POST', '/pages');

        $name = 'Hello world!';
        $link = '/hello-world';
        $this->setPayload(compact('name', 'link'));

        $this->app->run();

        $expected = $this->toJson('Page created!');

        $this->expectOutputString($expected);

        $this->resetPayload();

        return $this->getPageId('hello-world');
    }

    /**
     * @return void
     */
    public function test_create_new_page_without_data()
    {
        $this->setRequest('POST', '/pages');

        $this->app->run();

        $text = 'Page Title is required';
        $errors = array('name' => array($text));

        $expected = $this->toJson($errors);

        $this->expectOutputString($expected);
    }

    /**
     * @depends test_create_another_page
     *
     * @param integer $id
     *
     * @return void
     */
    public function test_duplicate_error_page($id)
    {
        $this->setRequest('POST', '/pages/' . $id);

        $name = 'This is something!';
        $link = '/hello-world';
        $this->setPayload(compact('name', 'link'));

        $this->app->run();

        $text = 'URL Link already exists';
        $errors = array('link' => array($text));

        $text = 'Page Title already exists';
        $errors['name'] = array($text);

        $expected = $this->toJson($errors);

        $this->expectOutputString($expected);

        $this->resetPayload();

        $this->deleteFile('hello-world');

        $this->deleteFile('another-page');
    }

    /**
     * @depends test_create_new_page
     *
     * @return void
     */
    public function test_error_existing_page()
    {
        $this->setRequest('POST', '/pages');

        $name = 'Hello world!';
        $link = '/hello-world';
        $this->setPayload(compact('name', 'link'));

        $this->app->run();

        $text = 'URL Link already exists';
        $errors = array('link' => array($text));

        $text = 'Page Title already exists';
        $errors['name'] = array($text);

        $expected = $this->toJson($errors);

        $this->expectOutputString($expected);

        $this->resetPayload();
    }

    /**
     * @return void
     */
    public function test_page_not_found()
    {
        $this->setRequest('GET', '/pages/0');

        $this->app->run();

        $expected = 'Page (0) not found';

        $this->expectOutputString($expected);
    }

    /**
     * @return void
     */
    public function test_pages_page()
    {
        $this->setRequest('GET', '/pages');

        $this->app->run();

        $this->expectOutputRegex('/Create New Page/');
    }

    /**
     * @depends test_create_new_page
     *
     * @param integer $id
     *
     * @return integer
     */
    public function test_show_page_details($id)
    {
        $this->setRequest('GET', '/pages/' . $id);

        $this->app->run();

        $html = '"input":{"name":"Hello world!","title":"Hello world!"';

        $this->expectOutputRegex('/' . $html . '/');

        return $id;
    }

    /**
     * @depends test_show_page_details
     *
     * @param integer $id
     *
     * @return void
     */
    public function test_update_page($id)
    {
        $this->setRequest('POST', '/pages/' . $id);

        $name = 'This is something!';
        $link = '/hello-world';
        $this->setPayload(compact('name', 'link'));

        $this->app->run();

        $page = $this->getActualPage('hello-world');

        $expected = $this->toJson('Page updated!');
        $this->expectOutputString($expected);

        $expected = 'This is something!';

        $actual = $page->getName();

        $this->assertEquals($expected, $actual);

        // $this->deleteFile('hello-world');
    }

    /**
     * @param string $name
     *
     * @return void
     */
    protected function deleteFile($name)
    {
        $file = $this->getActualFile($name);

        if (! $file)
        {
            throw new \Exception('File "' . $name . '" not found');
        }

        unlink($file);
    }

    /**
     * @param string $name
     *
     * @return string|null
     */
    protected function getActualFile($name)
    {
        $path = __DIR__ . '/../Fixture/pages';

        /** @var string[] */
        $files = glob($path . '/*.md');

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
        /** @var string */
        return json_encode($value);
    }
}
