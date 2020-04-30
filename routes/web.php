<?php

use App\Controllers\AboutController;
use App\Controllers\ClientsController;
use App\Controllers\ContactController;
use App\Controllers\ServicesController;
use App\Controllers\SitemapController;
use App\Controllers\WelcomeController;
use App\Middlewares\InjectHoneypotMiddleware;
use Slim\App;
use App\Middlewares\HoneypotMiddleware;
use App\Middlewares\ValidateContactFormMiddleware;


$router->get('/', WelcomeController::class)
	->add(InjectHoneypotMiddleware::class);

$router->get('/about', AboutController::class);
$router->get('/services', ServicesController::class);
$router->get('/clients', ClientsController::class);

$router->post('/contact', [ContactController::class, 'sendMail'])
	->add(new ValidateContactFormMiddleware())
	->add(new HoneypotMiddleware());

$router->get("/message-sent", [ContactController::class, 'showSuccess']);
$router->get("/sitemap.xml", SitemapController::class);