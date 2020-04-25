<?php
declare(strict_types=1);

use App\App;
use App\Middlewares\EscapeRequestContentMiddleware;
use App\Middlewares\JsonBodyParserMiddleware;
use DI\Bridge\Slim\Bridge;

require __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();
$dotenv->required('APP_ENV')->notEmpty()->allowedValues(['local', 'production']);
$dotenv->required('META_TITLE')->notEmpty();
$dotenv->required('META_DESCRIPTION')->notEmpty();
$dotenv->required('CONTACT_TARGET_MAIL')->notEmpty();
$dotenv->required('MAIL_FROM_ADDRESS')->notEmpty();

$app = Bridge::create();

App::setApp($app);


// Add global middlewares
$app->addMiddleware(new JsonBodyParserMiddleware());
$app->addMiddleware(new EscapeRequestContentMiddleware());
$app->addRoutingMiddleware();
$app->addErrorMiddleware(app()->isLocal(), true, true);

// Setup routes
include_once '../routes/web.php';

$app->run();
