<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;

require __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();
$dotenv->required('APP_ENV')->notEmpty()->allowedValues(['local', 'production']);

if (!function_exists("env")) {
	/**
	 * @param string $key
	 * @param mixed|null $default
	 *
	 * @return mixed
	 */
	function env(string $key, $default = null)
	{
		return getenv($key) ?? $default;
	}
}

$app = AppFactory::create();

$app->addRoutingMiddleware();

$app->get('/', function (Request $request, Response $response, $args) {
	$response->getBody()->write("<html><head><meta charset='UTF-8'><title>" . env("META_TITLE") . "</title></head>");
	$response->getBody()->write("Hello world!");
	$response->getBody()->write("</html>");
	return $response;
});

$app->run();
