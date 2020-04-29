<?php

namespace App\Middlewares;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Psr7\Response;

class MaintenanceMiddleware implements MiddlewareInterface
{
	private ?string $viewPath;
	private bool $isMaintenance;


	public function __construct(bool $isMaintenance = false, string $viewPath = null)
	{
		$this->viewPath = $viewPath;
		$this->isMaintenance = $isMaintenance;
	}


	public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
	{
		if (!$this->isMaintenance) {
			return $handler->handle($request);
		}

		return $this->viewPath ? view($this->viewPath)->withStatus(503) : new Response(503);
	}
}