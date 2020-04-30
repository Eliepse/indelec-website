<?php

namespace App\Controllers;

use Slim\Psr7\Response;

final class SitemapController
{
	public function __invoke(): Response
	{
		return view("sitemap")->withHeader('Content-Type', 'application/xml');
	}
}