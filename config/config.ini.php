;<?php

[production]
	templates.path = APP_PATH "/views"
	view = "\Slim\LayoutView"
	layout = "layout.phtml"


[testing]
	templates.path = APP_PATH "/views"
	view = "\Slim\LayoutView"
	layout = "layout.phtml"


[development]
	templates.path = APP_PATH "/views"
	view = "\Slim\LayoutView"
	layout = "layout.phtml"

	database.driver   = "mysql"
	database.host     = "localhost"
	database.port	  = "3306"
	database.name     = "mysql"
	database.username = "root"
	database.password = ""
