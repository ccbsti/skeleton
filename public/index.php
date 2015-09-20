<?php

// Define application mode here...
//////////////////////////////////
define("APP_ENV", 'development'); 
//////////////////////////////////



define("APP_PATH", realpath('../'));

require APP_PATH.'/vendor/autoload.php';

$app = new \Slim\Slim(array(
    'mode' => APP_ENV
));

$app->configureMode('production', function () use ($app) {
	$config = parse_ini_file(APP_PATH.'/config/config.ini.php', true);
	$app->config( @$config['production'] ? $config['production'] : array() );
});

$app->configureMode('testing', function () use ($app) {
    $config = parse_ini_file(APP_PATH.'/config/config.ini.php', true);
	$app->config( @$config['testing'] ? $config['testing'] : array() );
});

$app->configureMode('development', function () use ($app) {
	$config = parse_ini_file(APP_PATH.'/config/config.ini.php', true);
	$app->config( @$config['development'] ? $config['development'] : array() );
});


require APP_PATH.'/config/routes.php';

$app->run();