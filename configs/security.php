<?php

use Eliepse\Argile\Support\Env;

return [
	// Content Security Policy
	"csp" => [
		"reportOnly" => Env::isDevelopment(),
		"defaultSrc" => "'self' https://umami.eliepse.fr/",
		"directives" => [
            "style-src" => "'self' 'unsafe-inline'",
            "script-src" => "'self' 'unsafe-inline' https://umami.eliepse.fr/",
        ],
	],
];