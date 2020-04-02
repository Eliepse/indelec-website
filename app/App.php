<?php
namespace App;

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
}