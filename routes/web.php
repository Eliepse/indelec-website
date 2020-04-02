<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

/**
 * @var App $app
 */

use Slim\App;

$app->get('/', function (Request $request, Response $response, $args) {
	$response->getBody()->write("<html lang='fr'>"
		. "<head><meta charset='UTF-8'><title>" . env("META_TITLE") . "</title></head>"
		. "<body>Hello world!</body>"
		. "</html>");
	return $response;
});