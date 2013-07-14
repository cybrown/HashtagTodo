<?php

require_once __DIR__.'/../vendor/autoload.php';

use \Symfony\Component\HttpFoundation\Request;
use \Symfony\Component\HttpFoundation\Response;

use \Sigh\SimpleDB\Database;
use \Sigh\SimpleDB\Handlers\OneFileJsonHandler;

use \HashtagTodo\Controller\TodoControllerProvider;
use \HashtagTodo\Dao\TodoSimpledbDao;

date_default_timezone_set("UTC");

$app = new Silex\Application();

$app['db_path'] = 'db.json';

$app['sdb'] = $app->share(function () use ($app) {
	$handler = new OneFileJsonHandler($app['db_path'], true);
	$sdb = new Database($handler);
	$sdb->open();
	return $sdb;
});

$app['todo_tablename'] = 'todos';

$app['tododao'] = $app->share(function () use ($app) {
	$tododao = new \HashtagTodo\Dao\TodoSimpledbDao($app['sdb'], $app['todo_tablename']);
	return $tododao;
});

$app->mount('/', new TodoControllerProvider());

$app->get('/hello/{name}', function ($name) use ($app) {
    return 'Hello '.$app->escape($name);
});

$app->finish(function (Request $request, Response $response) use ($app) {
    $app['sdb']->close();
});

$app->run();
