<?php

namespace HashtagTodo\Tests;

use Silex\WebTestCase;

class TodoControllerErrorTest extends WebTestCase
{

    public function testEmpty() {
        $client = $this->createClient();
        $client->request('GET', '/');
        $jsonResponse = json_decode($client->getResponse()->getContent(), JSON_OBJECT_AS_ARRAY);
        $this->assertEmpty($jsonResponse);
    }
    
    /**
     * @depends testEmpty
     */
    public function testGetOneInvalidId()
    {
        $exception = null;
        try {
            $client = $this->createClient();
            $client->request('GET', '/123');
        } catch (\Symfony\Component\HttpKernel\Exception\HttpException $ex) {
            $exception = $ex;
        }
        $this->assertEquals($exception->getStatusCode(), 404);
    }

    public function createApplication()
    {
        $app = require __DIR__.'/../../../src/app.php';
        $app['debug'] = true;
        $app['simpledb.pretty'] = true;
        $app['session.test'] = true;
        $app['exception_handler']->disable();
        return $app;
    }
}
