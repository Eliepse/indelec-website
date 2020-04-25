<?php

namespace App\Controllers;

use Psr\Http\Message\ResponseInterface as Response;

class AboutController
{
	public function __invoke(): Response
	{
		return view("about", [
			"name" => env("META_TITLE"),
			"page" => "about",
		]);
	}
}