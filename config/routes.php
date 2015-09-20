<?php

$app->get('/(:controller(/:action(/:parameters+)))', function( $controller = "index", $action = "index" , $parameters = array()) {
	$class = '\\Application\\Controller\\'.ucfirst($controller);
	$obj = new $class;
	call_user_func_array(array($obj, $action), $parameters);
});