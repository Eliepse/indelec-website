<?php
/** @noinspection PhpUnhandledExceptionInspection */

use App\App;

if (!function_exists("env")) {
	/**
	 * @param string $key
	 * @param mixed|null $default
	 *
	 * @return mixed
	 */
	function env(string $key, $default = null)
	{
		return getenv($key) ?? $default;
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
	function view(string $name, array $values = []): string
	{
		$name .= pathinfo($name, PATHINFO_EXTENSION) ?: ".twig";
		return app()->getTwigEnvironment()->render($name, $values);
	}
}