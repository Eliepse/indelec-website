<?php

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

include_once '../routes/web.php';

$app->run();
