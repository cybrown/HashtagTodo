<?php

namespace HashtagTodo\Tests;

use Silex\WebTestCase;

class TodoControllerTest extends WebTestCase
{
    public function testPost()
    {
        $client = $this->createClient();
        $client->request('POST', '/', array(
            "title" => "thetitle",
            "description" => "thedescription"
        ));
        $this->assertTrue($client->getResponse()->isOk());
        $jsonResponse = json_decode($client->getResponse()->getContent(), JSON_OBJECT_AS_ARRAY);
        $this->assertArrayHasKey('id', $jsonResponse);
        $this->assertArrayHasKey('title', $jsonResponse);
        $this->assertArrayHasKey('description', $jsonResponse);
        $this->assertEquals($jsonResponse['title'], 'thetitle');
        $this->assertEquals($jsonResponse['description'], 'thedescription');
        $this->assertEquals(count($jsonResponse), 3);
        return $jsonResponse['id'];
    }

    /**
    * @depends testPost
    */
    public function testGetOne($id)
    {
        $client = $this->createClient();
        $client->request('GET', '/' . $id);
        $this->assertTrue($client->getResponse()->isOK());
        $jsonResponse = json_decode($client->getResponse()->getContent());
        $this->assertEquals($jsonResponse->id, $id);
    }

    /**
    * @depends testPost
    */
    public function testGet()
    {
        $client = $this->createClient();
        $client->request('GET', '/');
        $this->assertTrue($client->getResponse()->isOk());
        $jsonResponse = json_decode($client->getResponse()->getContent());
        $this->assertTrue(is_array($jsonResponse));
    }

    /**
    * @depends testPost
    */
    public function testPut($id)
    {
        $client = $this->createClient();
        $client->request('PUT', '/' . $id, array(
            "title" => 'othertitle',
            "description" => 'otherdescription'
        ));
        $this->assertTrue($client->getResponse()->isOk());
    }

    /**
    * @depends testPost
    */
    public function testDelete($id)
    {
        $client = $this->createClient();
        $client->request('DELETE', '/' . $id);
        $this->assertTrue($client->getResponse()->isOk());
    }

    public function createApplication()
    {
        $app = require __DIR__.'/../../../src/app.php';
        $app['debug'] = true;
        $app['session.test'] = true;
        $app['exception_handler']->disable();
        return $app;
    }
}
