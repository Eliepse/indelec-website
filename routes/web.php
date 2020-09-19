<?php
/**
 * @var \DI\Bridge\Slim\Bridge $router
 */

use App\Controllers\AboutController;
use App\Controllers\ClientsController;
use App\Controllers\ContactController;
use App\Controllers\ServicesController;
use App\Controllers\SitemapController;
use App\Controllers\WelcomeController;
use App\Middlewares\ValidateContactFormMiddleware;
use Eliepse\Argile\Honeypot\Http\Middleware\HoneypotRequestMiddleware;
use Eliepse\Argile\Honeypot\Http\Middleware\HoneypotResponseMiddleware;


$router->get('/', WelcomeController::class)
	->addMiddleware(new HoneypotResponseMiddleware());

$router->get('/about', AboutController::class);
$router->get('/services', ServicesController::class);
$router->get('/clients', ClientsController::class);

$router->post('/contact', [ContactController::class, 'sendMail'])
	->add(new ValidateContactFormMiddleware())
	->addMiddleware(new HoneypotRequestMiddleware());

$router->get("/message-sent", [ContactController::class, 'showSuccess']);
$router->get("/sitemap.xml", SitemapController::class);