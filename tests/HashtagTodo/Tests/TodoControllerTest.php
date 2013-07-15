<?php

namespace HashtagTodo\Tests;

use Silex\WebTestCase;

class TodoControllerTest extends WebTestCase
{
    public function testPost()
    {
        $this->assertTrue($this->app['simpledb.pretty']);
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
        $jsonResponse = json_decode($client->getResponse()->getContent(), JSON_OBJECT_AS_ARRAY);
        $this->assertArrayHasKey('id', $jsonResponse);
        $this->assertArrayHasKey('title', $jsonResponse);
        $this->assertArrayHasKey('description', $jsonResponse);
        $this->assertEquals($jsonResponse['id'], $id);
        $this->assertEquals($jsonResponse['title'], 'thetitle');
        $this->assertEquals($jsonResponse['description'], 'thedescription');
        $this->assertEquals(count($jsonResponse), 3);
    }

    /**
    * @depends testPost
    */
    public function testGet($id)
    {
        $client = $this->createClient();
        $client->request('GET', '/');
        $this->assertTrue($client->getResponse()->isOk());
        $jsonResponse = json_decode($client->getResponse()->getContent(), JSON_OBJECT_AS_ARRAY);
        $this->assertTrue(is_array($jsonResponse));
        $this->assertCount(1, $jsonResponse);
        $this->assertArrayHasKey('id', $jsonResponse[0]);
        $this->assertArrayHasKey('title', $jsonResponse[0]);
        $this->assertArrayHasKey('description', $jsonResponse[0]);
        $this->assertEquals($jsonResponse[0]['id'], $id);
        $this->assertEquals($jsonResponse[0]['title'], 'thetitle');
        $this->assertEquals($jsonResponse[0]['description'], 'thedescription');
        $this->assertEquals(count($jsonResponse[0]), 3);
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
        $jsonResponse = json_decode($client->getResponse()->getContent(), JSON_OBJECT_AS_ARRAY);
        $this->assertArrayHasKey('id', $jsonResponse);
        $this->assertArrayHasKey('title', $jsonResponse);
        $this->assertArrayHasKey('description', $jsonResponse);
        $this->assertEquals($jsonResponse['title'], 'othertitle');
        $this->assertEquals($jsonResponse['description'], 'otherdescription');
        $this->assertEquals(count($jsonResponse), 3);
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

    /**
    * @depends testDelete
    */
    public function testGetAllEmpty()
    {
        $client = $this->createClient();
        $client->request('GET', '/');
        $this->assertTrue($client->getResponse()->isOk());
        $jsonResponse = json_decode($client->getResponse()->getContent());
        $this->assertEmpty($jsonResponse);
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
