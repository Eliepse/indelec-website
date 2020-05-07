<?php

namespace Eliepse\Templating;

use App\App;
use Symfony\Component\Templating\Loader\FilesystemLoader;
use Symfony\Component\Templating\Storage\FileStorage;
use Symfony\Component\Templating\Storage\Storage;
use Symfony\Component\Templating\Storage\StringStorage;
use Symfony\Component\Templating\TemplateReferenceInterface;

final class ViewFileSystemLoader extends FilesystemLoader
{
	private string $cachePath;


	public function __construct($templatePathPatterns)
	{
		parent::__construct($templatePathPatterns);
		$this->cachePath = App::getInstance()->storage("framework/views/");
	}


	public function load(TemplateReferenceInterface $template)
	{
		$file = $template->get('name');

		if (self::isAbsolutePath($file) && is_file($file)) {
			return new FileStorage($file);
		}

		$replacements = [];
		foreach ($template->all() as $key => $value) {
			$replacements[ '%' . $key . '%' ] = $value;
		}

		$templateFailures = [];
		foreach ($this->templatePathPatterns as $templatePathPattern) {
			if (is_file($view_path = strtr($templatePathPattern, $replacements)) && is_readable($view_path)) {

				$cache_path = $this->getCachePath($template);

				if ($this->isCached($cache_path, $view_path)) {
					return new FileStorage($cache_path);
				}

				$content = $this->parseTemplate(new FileStorage($view_path));

				if (App::getInstance()->isProd()) {
					$this->cacheView($template, $content);
				}

				return new StringStorage($content);
			}

			if (null !== $this->logger) {
				$templateFailures[] = $template;
			}
		}

		// only log failures if no template could be loaded at all
		foreach ($templateFailures as $temp) {
			if (null !== $this->logger) {
				$this->logger->debug('Failed loading template file.', [
					'file' => $temp->get('name'),
				]);
			}
		}

		return false;
	}


	private function getCachePath(TemplateReferenceInterface $template): string
	{
		return $this->cachePath . md5($template->get("name")) . ".php";
	}


	private function parseTemplate(Storage $file): string
	{
		$content = $file->getContent();
		return preg_replace_callback("/({([{%#])\s*(.+)\s*[}%#]})/miU", function ($matches) {
			switch ($matches[2]) {
				case '{':
					return '<?= $view->escape(' . trim($matches[3]) . ') ?>';
				case '%':
					return $this->parseLogicalBrackets($matches[3]);
				case '#':
					return "<?php # $matches[3] ?>";
			}
			return $matches[0];
		}, $content);
	}


	private function parseLogicalBrackets(string $content): string
	{
		return preg_replace_callback("/(\s*([a-z]+)\s*(.*))/mi", function ($matches) {
			switch ($matches[2]) {
				case 'include':
					return '<?= $view->render(' . trim($matches[3]) . ') ?>';
				case 'if':
					return '<?php if(' . trim($matches[3]) . '): ?>';
				case 'endif':
					return '<?php endif; ?>';
				case 'for':
					return '<?php foreach(' . trim($matches[3]) . '): ?>';
				case 'endfor':
					return '<?php endforeach; ?>';
			}
			return "";
		}, $content);
	}


	private function isCached(string $cache_path, string $view_path): bool
	{
		return App::getInstance()->isProd() && is_file($cache_path) && filemtime($view_path) < filemtime($cache_path);
	}


	private function cacheView(TemplateReferenceInterface $template, string $content): void
	{
		$cache_path = $this->getCachePath($template);

		if (!is_dir($this->cachePath)) {
			if (false === mkdir($this->cachePath, 0664, true)) {
				$this->logger->error("Could not create views cache directory.", [
					"view" => $template->get('name'),
					"cachePath" => $cache_path,
				]);
			}
		}

		if (false === file_put_contents($this->getCachePath($template), $content)) {
			$this->logger->error("Failed to write parsed template to cache.", [
				"view" => $template->get('name'),
				"cachePath" => $cache_path,
			]);
		}
	}

}