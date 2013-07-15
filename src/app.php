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
$app['db.path'] = __DIR__ . '/../data/db.json';
$app['db.todo.tablename'] = 'todos';
$app['simpledb.pretty'] = false;


// Services
$app['simpledb'] = $app->share(function () use ($app) {
    return new Database(new OneFileJsonHandler($app['db.path'], $app['simpledb.pretty']));
});

$app['tododao'] = $app->share(function () use ($app) {
    return new TodoSimpledbDao($app['simpledb'], $app['db.todo.tablename']);
});


// Initialisation and finalisation
$app->before(function (Request $request) use ($app) {
    date_default_timezone_set("UTC");
    $app['simpledb']->open();
});

$app->finish(function (Request $request, Response $response) use ($app) {
    $app['simpledb']->close();
});


// Routes
$app->mount('/', new TodoControllerProvider());

$app->get('/hello/{name}', function ($name) use ($app) {
    return 'Hello '.$app->escape($name);
});

return $app;
