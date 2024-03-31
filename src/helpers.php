<?php
/** @noinspection PhpUnhandledExceptionInspection */

use DI\DependencyException;
use DI\NotFoundException;
use Eliepse\Argile\Core\Application;
use Eliepse\Argile\Http\Responses\ViewResponse;
use Eliepse\Argile\Support\Asset;
use Slim\Flash\Messages;

if (! function_exists("env")) {
    /**
     * @param string $key
     * @param mixed|null $default
     *
     * @return mixed
     * @throws ErrorException
     */
    function env(string $key, mixed $default = null): mixed
    {
        return Application::getInstance()->getEnvironment()->get($key, $default);
    }
}

if (! function_exists('app')) {
    /**
     * @param string|null $service_name
     *
     * @return Application|mixed
     * @throws DependencyException
     * @throws ErrorException
     * @throws NotFoundException
     */
    function app(string $service_name = null): mixed
    {
        if (is_string($service_name)) {
            return Application::getInstance()->resolve($service_name);
        }

        return Application::getInstance();
    }
}

if (! function_exists('webpack')) {
    /**
     * @param string $asset_path
     * @param string|null $default
     *
     * @return string
     * @throws ErrorException
     */
    function webpack(string $asset_path, ?string $default = null): string
    {
        return Asset::webpack($asset_path, $default);
    }
}

if (! function_exists("view")) {
    function view(string $name, array $values = []): ViewResponse
    {
        return new ViewResponse($name, $values);
    }
}

if (! function_exists('flash')) {
    function flash(): Messages
    {
        return Application::getInstance()->resolve(Messages::class);
    }
}

if (! function_exists('errors')) {
    function errors(string $key): array
    {
        $all_errors = flash()->getFirstMessage("errors");

        if (empty($all_errors))
            return [];

        if (! isset($all_errors[$key]))
            return [];

        $key_errors = $all_errors[$key];

        if (empty($key_errors))
            return [];

        return is_array($key_errors) ? $key_errors : [$key_errors];
    }
}

if (! function_exists("old")) {
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