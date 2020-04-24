<?php

namespace App\Middlewares;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;

class HoneypotMiddleware
{
	public static int $minDelaySec = 5;


	public function __invoke(Request $request, RequestHandler $handler): ResponseInterface
	{
		if ($request->getMethod() !== "POST")
			return new Response(403);

		$inputs = $request->getParsedBody();
		$inputs = is_array($inputs) ? $inputs : [];

		if ($this->isFormTruncated($inputs))
			return new Response(403);

		if ($this->areHoneypotsFilled($inputs))
			return new Response(403);

		if ($this->isRequestTooEarly($inputs))
			return new Response(403);

		return $handler->handle($request);
	}


	private function isFormTruncated(array $inputs): bool
	{
		$honeypots = array_intersect(InjectHoneypotMiddleware::$listOfTraps, array_keys($inputs));
		return count($honeypots) !== 1 || !empty($honeypots["my_time"]);
	}


	private function areHoneypotsFilled(array $inputs): bool
	{
		$honeypots = array_intersect_key(array_fill_keys(InjectHoneypotMiddleware::$listOfTraps, null), $inputs);
		foreach ($honeypots as $honeypot) {
			if (!empty($honeypot))
				return true;
		}
		return false;
	}


	private function isRequestTooEarly(array $inputs): bool
	{
		return time() - intval($inputs["my_time"]) < self::$minDelaySec;
	}
}