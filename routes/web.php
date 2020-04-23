<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

/**
 * @var App $app
 */

use Slim\App;

$app->get('/', function (Request $request, Response $response, $args) {
	$response->getBody()->write(view("welcome", ["name" => env("META_TITLE")]));
	return $response;
});

$app->get('/about', function (Request $request, Response $response, $args) {
	$response->getBody()->write(view("about", ["name" => env("META_TITLE")]));
	return $response;
});

$app->get('/services', function (Request $request, Response $response, $args) {
	$response->getBody()->write(view("services", ["name" => env("META_TITLE")]));
	return $response;
});

$app->get('/clients', function (Request $request, Response $response, $args) {
	$response->getBody()->write(view("clients", [
		"name" => env("META_TITLE"),
		"clients" => [
			[
				"logo" => "matmut.png",
				"brand" => "Matmut",
				"year" => "depuis 1988",
				"description" => "Création et entretien électrique et d'agences en Ile-de-France.",
			],
			[
				"logo" => "gecina.png",
				"brand" => "Gecina",
				"year" => "depuis 1998",
				"description" => "Maintenance électrique du parc locatif et rénovations "
					. "de logements familiaux et bureaux en Ile-de-France.",
			],
			[
				"logo" => "apprentis-auteuil.png",
				"brand" => "Fondation Apprentis d'Auteuil",
				"year" => "depuis 1998",
				"description" => "Divers travaux d'aménagement ainsi que l'entretien "
					. "électrique des installations réalisées.",
			],
			[
				"logo" => "campusea.png",
				"brand" => "Campuséa",
				"year" => "depuis 2007",
				"description" => "Maintenance électrique du parc locatif (résidences étudiantes dont parties "
					. "communces) et rénovations de logements étudiants en Île-de-France.",
			],
			[
				"logo" => "hotel-vignon.png",
				"brand" => "Hôtel Le Vignon",
				"year" => "depuis 2013",
				"description" => "Maintenance électrique et divers travaux "
					. "d’entretien pour un hôtel 4 étoiles à Paris.",
			],
			[
				"logo" => "agro-paris-tech.png",
				"brand" => "AgroParisTech",
				"year" => "depuis 2014",
				"description" => "Maintenance électrique du parc locatif (résidences étudiantes à la Cité "
					. "Internationale Universitaire de Paris et au Kremlin Bicêtre).",
			],
			[
				"logo" => "sauvegarde-yvelines.svg",
				"brand" => "La Sauvegarde des Yvelines",
				"year" => "2017, 2019",
				"description" => "Rénovation complète d’une crèche. Aménagement d'ateliers techniques et pédagogiques "
					. "à destination des jeunes du territoire au sein de l'Institut Médico Educatif.",
			],
		],
	]));
	return $response;
});