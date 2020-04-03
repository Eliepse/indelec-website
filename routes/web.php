<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

/**
 * @var App $app
 */

use Slim\App;

$app->get('/', function (Request $request, Response $response, $args) {
	$response->getBody()->write(view("welcome", ["name" => "INDéLEC"]));
	return $response;
});