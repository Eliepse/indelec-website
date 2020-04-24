<?php

use App\Middlewares\InjectHoneypotMiddleware;
use Slim\App;
use App\Middlewares\HoneypotMiddleware;
use App\Middlewares\ValidateContactFormMiddleware;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

/**
 * @var App $app
 */


$app->get('/', function (Request $request, Response $response, $args) {
	$response->getBody()->write(view("welcome", [
		"name" => env("META_TITLE"),
		"page" => "welcome",
	]));
	return $response;
})
	->add(new InjectHoneypotMiddleware());

$app->get('/about', function (Request $request, Response $response, $args) {
	$response->getBody()->write(view("about", [
		"name" => env("META_TITLE"),
		"page" => "about",
	]));
	return $response;
});

$app->get('/services', function (Request $request, Response $response, $args) {
	$response->getBody()->write(view("services", [
		"name" => env("META_TITLE"),
		"page" => "services",
	]));
	return $response;
});

$app->get('/clients', function (Request $request, Response $response, $args) {
	$response->getBody()->write(view("clients", [
		"name" => env("META_TITLE"),
		"page" => "clients",
	]));
	return $response;
});