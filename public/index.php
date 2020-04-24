<?php

use App\App;
use App\Middlewares\JsonBodyParserMiddleware;
use Slim\Factory\AppFactory;

require __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();
$dotenv->required('APP_ENV')->notEmpty()->allowedValues(['local', 'production']);
$dotenv->required('META_TITLE')->notEmpty();
$dotenv->required('META_DESCRIPTION')->notEmpty();
$dotenv->required('CONTACT_TARGET_MAIL')->notEmpty();

$app = AppFactory::create();

App::setApp($app);


$app->addRoutingMiddleware();
$app->addMiddleware(new JsonBodyParserMiddleware());

$app->addErrorMiddleware(app()->isLocal(), true, true);

include_once '../routes/web.php';

$app->run();
