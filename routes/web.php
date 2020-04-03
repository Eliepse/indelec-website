<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

/**
 * @var App $app
 */

use Slim\App;

$app->get('/', function (Request $request, Response $response, $args) {
	$response->getBody()->write(
		"<!DOCTYPE html>\n"
		. '<html lang="fr">'. "\n"
		. "<head>\n"
		. '<meta charset="UTF-8">' . "\n"
		. "<title>" . env("META_TITLE") . "</title>\n"
		. "<link href='" . webpack("css/styles.css", "") . "' rel='stylesheet' type='text/css' />\n"
		. "</head>\n"
		. "<body>\n"
		. "\tHello world!\n"
		. "\t<script src='" . webpack("js/index.js", "") . "'></script>\n"
		. "</body>\n"
		. "</html>\n");
	return $response;
});