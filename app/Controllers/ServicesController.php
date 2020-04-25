<?php

namespace App\Controllers;

use Slim\Psr7\Response;

class ServicesController
{
	public function __invoke(): Response
	{
		return view("services", [
			"name" => env("META_TITLE"),
			"page" => "services",
		]);
	}
}