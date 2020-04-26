<?php

namespace App\Middlewares;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class FlashFormInputsMiddleware implements MiddlewareInterface
{
	private array $allowedContentType = [
		"application/x-www-form-urlencoded",
		"multipart/form-data",
	];


	public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
	{
		if (strtoupper($request->getMethod()) === "GET") {
			return $handler->handle($request);
		}

		if (!in_array(strtolower($request->getHeaderLine("content-type")), $this->allowedContentType, true)) {
			return $handler->handle($request);
		}

		$inputs = $request->getParsedBody();
		foreach ($inputs as $name => $value) {
			flash()->addMessage("old.$name", $value);
		}

		return $handler->handle($request);
	}
}