<?php

namespace App\Middlewares;

use App\RedirectResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Headers;
use Slim\Psr7\Response;

class ValidateContactFormMiddleware
{
	private array $requiredFields = ["name", "email", "phone", "message"];
	private array $errors = [];


	public function __invoke(Request $request, RequestHandler $handler): ResponseInterface
	{
		if (strtoupper($request->getMethod()) !== "POST")
			return new Response(403);

		$inputs = $request->getParsedBody();
		$inputs = is_array($inputs) ? $inputs : [];

		if ($this->areInputMissing($inputs)) {
			$response = new Response(403);
			$response->getBody()->write("Missing required inputs.");
			return $response;
		}

		$this->validateName($inputs['name']);
		$this->validateEmail($inputs['email']);
		$this->validatePhone($inputs['phone']);
		$this->validateMessage($inputs['message']);

		if (!empty($this->errors)) {
			flash()->addMessage("errors", $this->errors);
			return new RedirectResponse(
				$request->getHeader("referer")[0] . "#contact",
				new Headers(["method" => "get"])
			);
		}

		return $handler->handle($request);
	}


	private function areInputMissing(array $inputs): bool
	{
		$inputs = array_intersect($this->requiredFields, array_keys($inputs));

		if (count($inputs) < count($this->requiredFields))
			return true;

		foreach ($inputs as $input)
			if (empty($input))
				return true;
		return false;
	}


	private function validateName(string $name): void
	{
		$length = mb_strlen($name);
		if ($length < 5) $this->errors["name"] = "Au moins 5 caractères doivent être indiqués.";
		if ($length > 50) $this->errors["name"] = "Ce champ doit comporter moins de 50 caractères.";
	}


	private function validateEmail(string $email): void
	{
		$length = mb_strlen($email);
		if ($length < 10) $this->errors["email"] = "Au moins 10 caractères doivent être indiqués.";
		if ($length > 200) $this->errors["email"] = "Ce champ doit comporter moins de 200 caractères.";
		if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $this->errors["email"] = "Cet email n'est pas valide.";
	}


	private function validatePhone(string $phone): void
	{
		$length = mb_strlen($phone);
		if ($length < 8) $this->errors["phone"] = "Au moins 8 caractères doivent être indiqués.";
		if ($length > 32) $this->errors["phone"] = "Ce champ doit comporter moins de 32 caractères.";
	}


	private function validateMessage(string $message): void
	{
		$length = mb_strlen($message);
		if ($length < 8) $this->errors["message"] = "Au moins 16 caractères doivent être indiqués.";
		if ($length > 500) $this->errors["message"] = "Ce champ doit comporter moins de 500 caractères.";
	}
}