<?php
declare(strict_types=1);
mb_internal_encoding("UTF-8");

use Eliepse\Argile\App;
use Eliepse\Argile\Http\Middleware\ContentSecurityPolicyMiddleware;
use Eliepse\Argile\Http\Middleware\FlashFormInputsMiddleware;
use Eliepse\Argile\Http\Middleware\JsonBodyParserMiddleware;
use Eliepse\Argile\Http\Middleware\MaintenanceMiddleware;
use Eliepse\Argile\Http\Middleware\SecureFrameOptionMiddleware;
use \Eliepse\Argile\Support\Environment;
use Middlewares\PhpSession;
use Psr\Log\LoggerInterface;
use Slim\Flash\Messages;

require __DIR__ . '/../vendor/autoload.php';

$app = App::init(dirname(__DIR__));

Environment::validate('META_TITLE', ['required' => true, 'empty' => false]);
Environment::validate('META_DESCRIPTION', ['required' => true, 'empty' => false]);
Environment::validate('CONTACT_TARGET_MAIL', ['required' => true, 'empty' => false]);
Environment::validate('MAIL_FROM_ADDRESS', ['required' => true, 'empty' => false]);
Environment::validate('MAIL_DRIVER', ['required' => true, 'empty' => false, 'in' => ['sendmail', 'smtp']]);

if ("smtp" === env("MAIL_DRIVER")) {
	Environment::validate('MAIL_SERVER', ['required' => true, 'empty' => false]);
	Environment::validate('MAIL_PORT', ['required' => true, 'type' => 'integer']);
	Environment::validate('MAIL_TLS', ['required' => true, 'type' => 'boolean']);
}

$sessionMiddleware = (new PhpSession())
	->name(env("APP_SESSION_PREFIX", "simpleApp") . "_session")
	->options([
		'use_strict_mode' => true,
		'cookie_httponly' => true,
		'cookie_samesite' => 'strict',
		'cookie_secure' => env("APP_ENV") === "production",
		'use_only_cookies' => true,
		'use_trans_sid' => true,
		'sid_length' => 64,
		'sid_bits_per_character' => 6,
		'cookie_lifetime' => 3_600 * 24,
	])
	->regenerateId(3_600 * 24);

$app->loadSlim();
$slim = $router = $app->getSlim();

$app->loadLoggerSystem();
$app->loadCacheSystem();
$app->loadTemplatingSystem();

// Inject services
$app->container->set(Messages::class, fn() => new Messages());
$app->container->set(LoggerInterface::class, $app->getLogger());

// Add global middlewares
$slim->addMiddleware(new FlashFormInputsMiddleware());
$slim->addMiddleware(new JsonBodyParserMiddleware());
$slim->addMiddleware(new SecureFrameOptionMiddleware());
$slim->addMiddleware(
	new ContentSecurityPolicyMiddleware(
		! Environment::isProduction(),
		"'self'",
		["style-src" => "'self' 'unsafe-inline'"]
	)
);
$slim->addMiddleware(new MaintenanceMiddleware(! env("APP_ONLINE")));
$slim->addMiddleware($sessionMiddleware);
$slim->addRoutingMiddleware();
$slim->addErrorMiddleware(! Environment::isProduction(), true, true, $app->getLogger());

// Setup routes
include_once '../routes/web.php';

$app->run();
