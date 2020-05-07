<?php
/** @noinspection PhpUnhandledExceptionInspection */

use App\App;
use Fig\Http\Message\StatusCodeInterface;
use Slim\Flash\Messages;
use Slim\Psr7\Response;

if (!function_exists("env")) {
	/**
	 * @param string $key
	 * @param mixed|null $default
	 *
	 * @return mixed
	 */
	function env(string $key, $default = null)
	{
		if (!isset($_ENV[ $key ])) {
			return $default;
		}

		$value = getenv($key);

		switch (strtolower($value)) {
			case 'true':
				return true;
			case 'false':
				return false;
			case 'null':
				return null;
		}

//		if (Str::startsWith($value, '"') && Str::endsWith($value, '"')) {
//			return substr($value, 1, -1);
//		}

		return $value;
	}
}

if (!function_exists('app')) {
	function app(): App
	{
		return App::getInstance();
	}
}

if (!function_exists('webpack')) {
	/**
	 * @param string $asset_path
	 * @param string|null $default
	 *
	 * @return string
	 * @throws ErrorException
	 */
	function webpack(string $asset_path, ?string $default = null): string
	{
		$m_path = app()->public("manifest.json");
		if (!file_exists($m_path)) {
			if (!is_null($default)) return $default;
			throw new ErrorException("Weback generated manifest (public/manifest.json) not found at $m_path.");
		}

		$manifest = json_decode(file_get_contents($m_path), true);
		if (!array_key_exists($asset_path, $manifest)) {
			if (!is_null($default)) return $default;
			throw new Error("$asset_path not found in webpack generated manifest.");
		}

		return $manifest[ $asset_path ];
	}
}

if (!function_exists("view")) {
	function view(string $name, array $values = []): Response
	{
		$engine = App::getInstance()->getTemplateEngine();
		$name .= pathinfo($name, PATHINFO_EXTENSION) ?: ".view";
		$response = new Response(StatusCodeInterface::STATUS_OK);

		foreach ($values as $key => $value) {
			$engine->addGlobal($key, $value);
		}

		$response->getBody()->write($engine->render($name));
		return $response;
	}
}

if (!function_exists('flash')) {
	function flash(): Messages
	{
		return App::getInstance()->getApp()->getContainer()->get(Messages::class);
	}
}

if (!function_exists('errors')) {
	function errors(string $key): array
	{
		$all_errors = flash()->getFirstMessage("errors");

		if (empty($all_errors))
			return [];

		if (!isset($all_errors[ $key ]))
			return [];

		$key_errors = $all_errors[ $key ];

		if (empty($key_errors))
			return [];

		return is_array($key_errors) ? $key_errors : [$key_errors];
	}
}

if (!function_exists("old")) {
	/**
	 * @param string $key
	 * @param mixed|string|null $default
	 *
	 * @return mixed|string|null
	 */
	function old(string $key, $default = null)
	{
		return flash()->getFirstMessage("old.$key", $default);
	}
}