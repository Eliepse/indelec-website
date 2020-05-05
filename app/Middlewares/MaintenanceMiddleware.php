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
	private string $token;
	private string $tokenKey = "maintenanceToken";


	public function __construct(bool $isMaintenance = false, string $viewPath = null)
	{
		$this->viewPath = $viewPath;
		$this->isMaintenance = $isMaintenance;

		if ($this->isMaintenance) {
			$this->token = $this->getOrNewToken();
		}
	}


	public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
	{
		if (!$this->isMaintenance) {
			return $handler->handle($request);
		}

		$queryToken = $request->getQueryParams()["bypassToken"] ?? null;

		if (!empty($queryToken) && $queryToken === $this->token) {
			return $handler->handle($request);
		}

		return $this->viewPath ? view($this->viewPath)->withStatus(503) : new Response(503);
	}


	private function generateBypassToken(): string
	{
		$token = base64_encode(random_bytes(32));
		app()->getCache()->save($this->tokenKey, $token, 0);
		return $token;
	}


	private function getOrNewToken()
	{
		if (app()->getCache()->contains($this->tokenKey)) {
			return app()->getCache()->fetch($this->tokenKey);
		}

		return $this->generateBypassToken();
	}
}