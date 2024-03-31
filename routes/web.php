<?php
/**
 * @var Bridge $router
 */

use App\Controllers\AboutController;
use App\Controllers\ClientsController;
use App\Controllers\ContactController;
use App\Controllers\ServicesController;
use App\Controllers\SitemapController;
use App\Controllers\WelcomeController;
use App\Middlewares\ValidateContactFormMiddleware;
use DI\Bridge\Slim\Bridge;
use Eliepse\Argile\Honeypot\Http\Middleware\HoneypotRequestMiddleware;
use Eliepse\Argile\Honeypot\Http\Middleware\HoneypotResponseMiddleware;
use Eliepse\Argile\Http\Router;


Router::get('/', WelcomeController::class)
	->addMiddleware(new HoneypotResponseMiddleware());

Router::get('/about', AboutController::class);
Router::get('/services', ServicesController::class);
Router::get('/clients', ClientsController::class);

Router::post('/contact', [ContactController::class, 'sendMail'])
	->addMiddleware(new ValidateContactFormMiddleware())
	->addMiddleware(new HoneypotRequestMiddleware());

Router::get("/message-sent", [ContactController::class, 'showSuccess']);
Router::get("/sitemap.xml", SitemapController::class);