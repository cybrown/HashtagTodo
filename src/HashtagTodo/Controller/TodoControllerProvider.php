<?php

namespace HashtagTodo\Controller;

use Silex\Application;
use Silex\ControllerProviderInterface;
use Symfony\Component\HttpFoundation\Request;

use HashtagTodo\Entity\Todo;

class TodoControllerProvider implements ControllerProviderInterface
{

    public function connect(Application $app)
    {
    	$controllers = $app['controllers_factory'];

    	$controllers->get('/', function () use ($app) {
            $result = array();
            foreach ($app['tododao']->findAll() as $k => $v) {
                $result[] = $v->toArray();
            }
            return json_encode($result);
    	});

    	$controllers->get('/{id}', function ($id) use ($app) {
    		return json_encode($app['tododao']->findOne($id)->toArray());
    	});

        $controllers->post('/', function (Request $req) use ($app) {
            $todo = new Todo();
            $todo->setTitle($req->request->get('title'));
            $todo->setDescription($req->request->get('description'));
            $app['tododao']->save($todo);
            return json_encode($todo->toArray());
        });

        $controllers->post('/{id}', function ($id) use ($app) {
            $todo = $app['tododao']->findOne($id);
            if (!is_null($req->request->get('title'))) {
                $todo->setTitle($req->request->get('title'));
            }
            if (!is_null($req->request->get('description'))) {
                $todo->setDescription($req->request->get('description'));
            }
            $app['tododao']->save($todo);
            return json_encode($todo->toArray());
        });

    	$controllers->put('/{id}', function ($id) use ($app) {
            $todo = new Todo();
            $todo->setId($id);
            $todo->setTitle($req->request->get('title'));
            $todo->setDescription($req->request->get('description'));
            $app['tododao']->save($todo);
            return json_encode($todo->toArray());
    	});

    	$controllers->delete('/{id}', function ($id) use ($app) {
            $app['tododao']->deleteById($id);
    		return $id;
    	});

    	return $controllers;
    }
}
