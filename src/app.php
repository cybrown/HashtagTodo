<?php

require_once __DIR__.'/../vendor/autoload.php';

use \Symfony\Component\HttpFoundation\Request;
use \Symfony\Component\HttpFoundation\Response;

use \Sigh\SimpleDB\Database;
use \Sigh\SimpleDB\Handlers\OneFileJsonHandler;

use \HashtagTodo\Controller\TodoControllerProvider;
use \HashtagTodo\Dao\TodoSimpledbDao;

$app = new Silex\Application();

// Parameters
$app['db_path'] = __DIR__ . '/db.json';
$app['todo_tablename'] = 'todos';


// Services
$app['sdb'] = $app->share(function () use ($app) {
	return new Database(new OneFileJsonHandler($app['db_path'], true));
});

$app['tododao'] = $app->share(function () use ($app) {
	return new \HashtagTodo\Dao\TodoSimpledbDao($app['sdb'], $app['todo_tablename']);
});


// Initialisation and finalisation
$app->before(function (Request $request) use ($app) {
	date_default_timezone_set("UTC");
    $app['sdb']->open();
});

$app->finish(function (Request $request, Response $response) use ($app) {
    $app['sdb']->close();
});


// Routes
$app->mount('/', new TodoControllerProvider());

$app->get('/hello/{name}', function ($name) use ($app) {
    return 'Hello '.$app->escape($name);
});


$app->run();

return $app;
