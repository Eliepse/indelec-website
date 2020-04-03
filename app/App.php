<?php

namespace App;

use Error;
use ErrorException;

class App
{
	private static self $_instance;
	private \Slim\App $app;


	private function __construct(\Slim\App $app)
	{
		$this->app = $app;
	}


	public static function setApp(\Slim\App $app)
	{
		self::$_instance = new self($app);
	}


	/**
	 * @return static
	 * @throws ErrorException
	 */
	public static function getInstance(): self
	{
		if (empty(self::$_instance))
			throw new ErrorException(self::class . "has not been initialized.");
		return self::$_instance;
	}


	public function getApp(): \Slim\App
	{
		return $this->app;
	}


	public function isProd(): bool
	{
		return env("APP_ENV") === "production";
	}


	public function isLocal(): bool
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
	public function webpack(string $asset_path, ?string $default = null): string
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