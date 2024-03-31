<?php
declare(strict_types=1);
mb_internal_encoding("UTF-8");

use Eliepse\Argile\Core\Application;
use Slim\Flash\Messages;

require __DIR__ . "/../bootstrap/bootstrap.php";

$app = Application::getInstance();

$env = $app->getEnvironment();
$env->validate('META_TITLE', ['required' => true, 'empty' => false]);
$env->validate('META_DESCRIPTION', ['required' => true, 'empty' => false]);
$env->validate('CONTACT_TARGET_MAIL', ['required' => true, 'empty' => false]);
$env->validate('MAIL_FROM_ADDRESS', ['required' => true, 'empty' => false]);
$env->validate('MAIL_DRIVER', ['required' => true, 'empty' => false, 'in' => ['sendmail', 'smtp']]);

if ("smtp" === env("MAIL_DRIVER")) {
    $env->validate('MAIL_SERVER', ['required' => true, 'empty' => false]);
    $env->validate('MAIL_PORT', ['required' => true, 'type' => 'integer']);
    $env->validate('MAIL_TLS', ['required' => true, 'type' => 'boolean']);
}

$app->container->set(Messages::class, fn() => new Messages());

// Setup routes
include_once __DIR__ . '/../routes/web.php';

$app->run();
