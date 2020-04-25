<?php

use App\Controllers\AboutController;
use App\Controllers\ClientsController;
use App\Controllers\ContactController;
use App\Controllers\ServicesController;
use App\Controllers\WelcomeController;
use App\Middlewares\InjectHoneypotMiddleware;
use Slim\App;
use App\Middlewares\HoneypotMiddleware;
use App\Middlewares\ValidateContactFormMiddleware;

/**
 * @var App $app
 */


$app->get('/', WelcomeController::class)
	->add(InjectHoneypotMiddleware::class);

$app->get('/about', AboutController::class);
$app->get('/services', ServicesController::class);
$app->get('/clients', ClientsController::class);

$app->post('/contact', [ContactController::class, 'sendMail'])
	->add(new HoneypotMiddleware())
	->add(new ValidateContactFormMiddleware());

$app->get("/message-sent", [ContactController::class, 'showSuccess']);