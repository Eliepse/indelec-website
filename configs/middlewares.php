<?php

use Eliepse\Argile\Http\Middleware\CompiledRouteMiddleware;
use Eliepse\Argile\Http\Middleware\ContentSecurityPolicyMiddleware;
use Eliepse\Argile\Http\Middleware\FlashFormInputsMiddleware;
use Eliepse\Argile\Http\Middleware\JsonBodyParserMiddleware;
use Eliepse\Argile\Http\Middleware\MaintenanceMiddleware;
use Eliepse\Argile\Http\Middleware\SecureFrameOptionMiddleware;
use Eliepse\Argile\Http\Middleware\SessionMiddleware;

return [
	"global" => [
		FlashFormInputsMiddleware::class,
		JsonBodyParserMiddleware::class,
		CompiledRouteMiddleware::class,
		SecureFrameOptionMiddleware::class,
		ContentSecurityPolicyMiddleware::class,
		MaintenanceMiddleware::class,
		SessionMiddleware::class,
	],
];