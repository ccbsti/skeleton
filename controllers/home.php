<?php

namespace Application\Controller;

use \Sti\Database;
use \Slim\Slim;

/**
* Hello World Controller
*/
class Home
{

	private $json = null;

	/** Hello World Action */
	public function home()
	{
	
		$app = Slim::getInstance();

		$vars = array(
			'title' => "Hello World!",
			'packageName' => $this->getPackage(),
			'description' => $this->getDescription(),
			'authorName' =>  $this->getAuthorName(),
			'menu' => array(
				'Home' => '/',
				'Documentação' => '/docs'
			),
			'checkList' => array()
		);

		// Efetua uma série de checagens no ambiente e informa o resultado:
		$methods = get_class_methods(__CLASS__);
		foreach ($methods as $methodName) {
			if (substr($methodName, 0, 6) == "check_") {
				$vars['checkList'] = array_merge($vars['checkList'], $this->$methodName());
			}
		}

		$app->render('home.phtml', $vars);
	}

	private function check_apacheVersion() {

		$ver = preg_split("[/|\s]",$_SERVER['SERVER_SOFTWARE']);
		if (isset($ver[1])) {
			return array('Versão do Apache' => $ver[1]);
		} else {
			return array('Versão do Apache' => '<span class="negative">Não foi possível determinar...</span>');
		}
		
	}

	private function check_phpVersion() {
		return array('Versão do PHP' => phpversion());
	}

	private function check_modRewrite() {
		if (in_array('mod_rewrite', apache_get_modules())) {
			return array('mod_rewrite habilitado?' => '<span class="positive">SIM</span>');
		} else {
			return array('mod_rewrite habilitado?' => '<span class="negative">NÃO - Sem este módulo não será possível utilizar URLs amigáveis!</span>');
		}
	}

	private function check_mcrypt() {
		if (extension_loaded('mcrypt')) {
			return array('mcrypt habilitado?' => '<span class="positive">SIM</span>');
		} else {
			return array('mcrypt habilitado?' => '<span class="negative">NÃO - Com isso você não vai poder criptografar seus cookies!</span>');
		}
	}


	private function getPackage() { return str_replace('/', ' / ', $this->getJson()->name); }
	private function getDescription() { return $this->getJson()->description; }
	private function getLicense() { return $this->getJson()->license; }
	private function getAuthorName() { return $this->getJson()->authors[0]->name; }

	private function getJson() {
		if ($this->json === null) $this->json = json_decode(file_get_contents(APP_PATH.'/composer.json'));
		return $this->json;
	}

}

