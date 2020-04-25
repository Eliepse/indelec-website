<?php

namespace App\Controllers;

use Slim\Psr7\Response;

class ClientsController
{
	public function __invoke(): Response
	{
		return view("clients", ["pageTitle" => " | Nos clients", "page" => "clients"]);
	}
}