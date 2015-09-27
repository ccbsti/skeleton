<?php

$app->get('/', 'Application\Controller\Home:home');

// jQuery route.
// This route returns jQuery minified, gziped, and also
// makes the browser stores it in cache.
$app->get('/jquery.js', function() use ($app) {

	$file = APP_PATH.'/public/js/jquery-1.11.3.min.js.gz';
	$etag = "db6ba10b939ff4f595862744a51ae365";

	$app->response->headers->set('Content-Type', 'text/javascript');
	$app->response->headers->set('Content-Encoding', 'gzip');
	$app->etag($etag);
	echo file_get_contents($file);

});