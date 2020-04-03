<?php

use App\App;
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

if (!function_exists('webpack')) {
	/**
	 * @param string $asset_path
	 * @param string|null $default
	 *
	 * @return string
	 * @throws ErrorException
	 */
	function webpack(string $asset_path, ?string $default = null): string
	{
		return App::webpack($asset_path, $default);
	}
}

$app = AppFactory::create();

App::setApp($app);

$app->addRoutingMiddleware();

/**
 * @param bool $displayErrorDetails -> Should be set to false in production
 * @param bool $logErrors -> Parameter is passed to the default ErrorHandler
 * @param bool $logErrorDetails -> Display error details in error log
 * which can be replaced by a callable of your choice.
 * Note: This middleware should be added last. It will not handle any exceptions/errors
 * for middleware added after it.
 */
$errorMiddleware = $app->addErrorMiddleware(App::isLocal(), true, true);

include_once '../routes/web.php';

$app->run();
