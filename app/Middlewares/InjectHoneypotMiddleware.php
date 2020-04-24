<?php

namespace App\Middlewares;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;

class InjectHoneypotMiddleware
{
	public static array $listOfTraps = [
		"surname",
		"lastname",
		"firstname",
		"last_name",
		"first_name",
		"birthday",
		"fax",
		"age",
	];


	public function __invoke(Request $request, RequestHandler $handler): ResponseInterface
	{
		$response = $handler->handle($request);
		$content = (string)$response->getBody();

		$content = preg_replace(
			"/<!--\s*honeypot\s*-->/m",
			$this->getWrappedHoneypot(),
			$content
		);

		$response = new Response();
		$response->getBody()->write($content);

		return $response;
	}


	private function getWrappedHoneypot(): string
	{
		return "<div style=\"display: none;\">\r\n"
			. $this->getHoneypotInput() . "\r\n"
			. $this->getTimestampInput() . "\r\n"
			. "</div>\r\n";
	}


	private function getTimestampInput(): string
	{
		return '<input type="text" name="my_time" value="' . time() . '">';
	}


	private function getHoneypotInput(): string
	{
		$key = self::$listOfTraps[ array_rand(self::$listOfTraps) ];
		return '<input type="text" name="' . $key . '">';
	}
}