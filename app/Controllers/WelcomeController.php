<?php

namespace App\Controllers;

use Psr\Http\Message\ResponseInterface as Response;

class WelcomeController
{
	public function __invoke(): Response
	{
		return view("welcome", ["page" => "welcome"]);
	}
}