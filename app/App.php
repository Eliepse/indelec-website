<?php

namespace App;

use Error;
use ErrorException;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use Twig\TwigFunction;

class App
{
	private static self $_instance;

	private \Slim\App $app;
	private FilesystemLoader $twig_fs;
	private Environment $twig_env;


	private function __construct(\Slim\App $app)
	{
		$this->app = $app;
		$this->twig_fs = new FilesystemLoader($this->resources("views"));
		$this->twig_env = new Environment(
			$this->twig_fs,
			[
				'cache' => $this->isProd() ? $this->storage("cache/views") : false,
				'debug' => $this->isLocal() && env("APP_DEBUG", false),
				'strict_variables' => true
			]);
		$this->twig_env->addFunction(new TwigFunction("webpack", "webpack"));
		$this->twig_env->addFunction(new TwigFunction("app", "app"));
		$this->twig_env->addFunction(new TwigFunction("env", "env"));
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


	public function getTwigEnvironment(): Environment
	{
		return $this->twig_env;
	}


	public function isProd(): bool
	{
		return env("APP_ENV") === "production";
	}


	public function isLocal(): bool
	{
		return env("APP_ENV") === "local";
	}


	public function root(string $path = ""): string
	{
		return __DIR__ . '/../' . $path;
	}


	public function resources(string $path = ""): string
	{
		return $this->root("resources/" . $path);
	}


	public function storage(string $path = ""): string
	{
		return $this->root("storage/" . $path);
	}


	public function public(string $path = ""): string
	{
		return $this->root("public/" . $path);
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