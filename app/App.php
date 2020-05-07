<?php

namespace App;

use Doctrine\Common\Cache\PhpFileCache;
use Eliepse\Templating\ViewFileSystemLoader;
use Error;
use ErrorException;
use Monolog\Formatter\LineFormatter;
use Monolog\Handler\RotatingFileHandler;
use Monolog\Logger;
use Psr\Log\LoggerInterface;
use Symfony\Component\Templating\EngineInterface;
use Symfony\Component\Templating\PhpEngine;
use Symfony\Component\Templating\TemplateNameParser;

class App
{
	private static self $_instance;

	private \Slim\App $app;
	private PhpEngine $templating;
	private PhpFileCache $cache;
	private Logger $logger;


	private function __construct(\Slim\App $app)
	{
		$this->app = $app;
	}


	public static function make(\Slim\App $app): self
	{
		return self::$_instance = new self($app);
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


	public function loadLoggerSystem(): void
	{
		$stream = new RotatingFileHandler($this->storage("logs/log.log"), 7, Logger::DEBUG);
		$stream->setFormatter(new LineFormatter(
			"[%datetime%] %channel%.%level_name%: %message% %context% %extra%\n",
			"Y-m-d H:i:s"
		));

		$this->logger = new Logger("local");
		$this->logger->pushHandler($stream);
	}


	public function loadCacheSystem(): void
	{
		$this->cache = new PhpFileCache($this->storage("framework/cache"));
		$this->cache->setNamespace(env("APP_CACHE_PREFIX", "simpleApp_"));
	}


	public function loadTemplatingSystem(): void
	{
		$filesystem = new ViewFileSystemLoader([$this->resources("views") . "/%name%"]);
		$filesystem->setLogger($this->logger);
		$this->templating = new PhpEngine(new TemplateNameParser(), $filesystem);
	}


	public function getApp(): \Slim\App
	{
		return $this->app;
	}


	public function getTemplateEngine(): EngineInterface
	{
		return $this->templating;
	}


	public function getCache(): PhpFileCache
	{
		return $this->cache;
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
	 * @noinspection PhpUnused
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


	public function getLogger(): LoggerInterface
	{
		return $this->logger;
	}
}