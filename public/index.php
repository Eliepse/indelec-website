<?php

use App\App;
use Slim\Factory\AppFactory;

require __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();
$dotenv->required('APP_ENV')->notEmpty()->allowedValues(['local', 'production']);
$dotenv->required('META_TITLE')->notEmpty();
$dotenv->required('META_DESCRIPTION')->notEmpty();

$app = AppFactory::create();

App::setApp($app);


$app->addRoutingMiddleware();
$app->addErrorMiddleware(app()->isLocal(), true, true);

include_once '../routes/web.php';

$app->run();
