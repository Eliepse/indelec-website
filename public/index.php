<?php
declare(strict_types=1);
mb_internal_encoding("UTF-8");

use App\App;
use App\Middlewares\ContentSecurityPolicyMiddleware;
use App\Middlewares\FlashFormInputsMiddleware;
use App\Middlewares\JsonBodyParserMiddleware;
use App\Middlewares\MaintenanceMiddleware;
use App\Middlewares\SecureFrameOptionMiddleware;
use DI\Bridge\Slim\Bridge;
use DI\ContainerBuilder;
use Middlewares\PhpSession;
use Psr\Log\LoggerInterface;
use Slim\Flash\Messages;

require __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();
$dotenv->required('APP_ENV')->notEmpty()->allowedValues(['local', 'production']);
$dotenv->required('APP_ONLINE')->isBoolean();
$dotenv->ifPresent('APP_SESSION_PREFIX')->notEmpty();
$dotenv->ifPresent('APP_CACHE_PREFIX')->notEmpty();
$dotenv->required('META_TITLE')->notEmpty();
$dotenv->required('META_DESCRIPTION')->notEmpty();
$dotenv->required('CONTACT_TARGET_MAIL')->notEmpty();
$dotenv->required('MAIL_FROM_ADDRESS')->notEmpty();
$dotenv->required('MAIL_DRIVER')->notEmpty()->allowedValues(['sendmail', 'smtp']);

if("smtp" === env("MAIL_DRIVER")) {
	$dotenv->required("MAIL_SERVER")->notEmpty();
	$dotenv->required("MAIL_PORT")->isInteger();
	$dotenv->required("MAIL_TLS")->isBoolean();
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

$builder = new ContainerBuilder();
$builder->useAutowiring(true);
$builder->useAnnotations(false);
//if(env("APP_ENV") === "production") {
//	$builder->enableCompilation(__DIR__ . '/../storage/framework/phpdi');
//	$builder->writeProxiesToFile(true, __DIR__ . '/../storage/framework/proxies');
//}
$container = $builder->build();
$slimApp = Bridge::create($container);
$router = $slimApp;

$app = App::make($slimApp);
$app->loadLoggerSystem();
$app->loadCacheSystem();
$app->loadTemplatingSystem();

// Inject services
$container->set(Messages::class, fn() => new Messages());
$container->set(LoggerInterface::class, $app->getLogger());

// Add global middlewares
$slimApp->addMiddleware(new FlashFormInputsMiddleware());
$slimApp->addMiddleware(new JsonBodyParserMiddleware());
$slimApp->addMiddleware(new SecureFrameOptionMiddleware());
$slimApp->addMiddleware(
	new ContentSecurityPolicyMiddleware(
		$app->isLocal(),
		"'self'",
		["style-src" => "'self' 'unsafe-inline'"]
	)
);
$slimApp->addMiddleware(new MaintenanceMiddleware(!env("APP_ONLINE")));
$slimApp->addMiddleware($sessionMiddleware);
//$app->addMiddleware(new EscapeRequestContentMiddleware());
$slimApp->addRoutingMiddleware();
$slimApp->addErrorMiddleware(app()->isLocal(), true, true, $app->getLogger());

// Setup routes
include_once '../routes/web.php';

$slimApp->run();
