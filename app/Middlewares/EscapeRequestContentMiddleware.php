<?php


namespace App\Middlewares;


use ErrorException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class EscapeRequestContentMiddleware implements MiddlewareInterface
{

	/**
	 * @inheritDoc
	 * @throws ErrorException
	 */
	public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
	{
		$inputs = $request->getParsedBody();

		if (is_array($inputs)) {
			$rslt = array_walk_recursive($inputs, fn(&$value) => $value = htmlentities($value, ENT_QUOTES | ENT_HTML5, true));
			if (!$rslt) {
				throw new ErrorException("Input could not be escaped, aborting to prevent processing unsafe data.", 500);
			}
		}

		if (is_object($inputs)) {
			throw new ErrorException("Cannot escape objects, aborting to prevent processing unsafe data.", 501);
		}

		$request = $request->withParsedBody($inputs);
		return $handler->handle($request);
	}
}