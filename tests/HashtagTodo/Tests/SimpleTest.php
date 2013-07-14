<?php

namespace HashtagTodo\Tests;

use Silex\WebTestCase;

class SimpleTest extends WebTestCase
{

	public function testGet()
	{
	    $client = $this->createClient();
	    $client->request('GET', '/');
	    $jsonResponse = json_decode($client->getResponse()->getContent());
	    $this->assertTrue(is_array($jsonResponse));
	}

	public function testGetOne()
	{
	    $client = $this->createClient();
	    $client->request('GET', '/1');
	    $this->assertTrue($client->getResponse()->isOK());
	    $jsonResponse = json_decode($client->getResponse()->getContent());
	    $this->assertEquals($jsonResponse->id, '1');
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
