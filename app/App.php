<?php

namespace App;

use Error;
use ErrorException;

class App
{
	public static \Slim\App $app;


	public static function setApp(\Slim\App $app)
	{
		self::$app = $app;
	}


	public static function getApp(): \Slim\App
	{
		return self::$app;
	}


	public static function isProd(): bool
	{
		return env("APP_ENV") === "production";
	}


	public static function isLocal(): bool
	{
		return env("APP_ENV") === "local";
	}


	/**
	 * @param string $asset_path Relative path of the asset
	 * @param string|null $default
	 *
	 * @return string
	 * @throws ErrorException
	 */
	public static function webpack(string $asset_path, ?string $default = null): string
	{
		$m_path = __DIR__ . '/../public/manifest.json';
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