<?php

namespace Application\Controller;

/**
* Hello World Controller
*/
class Index
{
	/** Hello World Action */
	public function index( $name = null)
	{
		
		$app = \Slim\Slim::getInstance();
		if ($name === null) {
			$message = "Hello world from ".__METHOD__."!";
		} else {
			$message = "Hello $name!";
		}
		$app->render('index.phtml', ['message'=>$message]);
	}
}