<?php

use Eliepse\Argile\Core\Application;

require __DIR__ . '/../vendor/autoload.php';

$app = Application::init(dirname(__DIR__));

$app->boot();