<?php

use App\Mails\ContactFromVisitorMail;
use App\Middlewares\InjectHoneypotMiddleware;
use Slim\App;
use App\Middlewares\HoneypotMiddleware;
use App\Middlewares\ValidateContactFormMiddleware;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mailer\Transport\SendmailTransport;

/**
 * @var App $app
 */


$app->get('/', function (Request $request, Response $response) {
	$response->getBody()->write(view("welcome", [
		"name" => env("META_TITLE"),
		"page" => "welcome",
	]));
	return $response;
})
	->add(new InjectHoneypotMiddleware());

$app->get('/about', function (Request $request, Response $response) {
	$response->getBody()->write(view("about", [
		"name" => env("META_TITLE"),
		"page" => "about",
	]));
	return $response;
});

$app->get('/services', function (Request $request, Response $response) {
	$response->getBody()->write(view("services", [
		"name" => env("META_TITLE"),
		"page" => "services",
	]));
	return $response;
});

$app->get('/clients', function (Request $request, Response $response) {
	$response->getBody()->write(view("clients", [
		"name" => env("META_TITLE"),
		"page" => "clients",
	]));
	return $response;
});

$app->post('/contact', function (Request $request, Response $response) {
	$transport = new SendmailTransport(env("SENDMAIL_PATH") . " -bs");
	$mailer = new Mailer($transport);
	$mail = new ContactFromVisitorMail($request->getParsedBody());
	$mailer->send($mail);
	$message = "Merci de votre message, il a bien été envoyé à notre équipe.<br>"
		. "Nous reviendrons vers vous dès que possible.";
	$response = $response->withHeader("content-type", "application/json; charset=utf-8");
	$response->getBody()->write(json_encode(["message" => $message]));
	return $response;
})
	->add(new HoneypotMiddleware())
	->add(new ValidateContactFormMiddleware());