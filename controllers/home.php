<?php

/**
* Home Controller
*/
class Home
{
	
	public function home()
	{
		$app = \Slim\Slim::getInstance();
		$app->render('index.phtml');
	}
}