<?php
namespace bin;

class reconfig
{
	public static function run() {
		// READ COMPOSER.JSON
		$json = json_decode(file_get_contents("composer.json"));

		// CREATE HANDLER TO READ FROM STDIN
		$handler = fopen ("php://stdin","r");

		// MOUNT PROPOSED PACKAGE NAME
		exec('whoami', $out); 
		$proposedUser = strtolower($out[0]);
		$proposedPack = strtolower(basename(__DIR__));

		// READ PACKAGE NAME FROM STDIN
		echo "Package name (<vendor>/<name>) [$proposedUser/$proposedPack]: ";
		$package = fgets($handler);

		// IF PACKAGE IS BLANK USE PROPOSED
		if ($package == "\n") {
			$package = "$proposedUser/$proposedPack";
		} else {
			$package = str_replace("\n", "", $package);
		}

		// READ DESCRIPTION FROM STDIN
		echo "Description []: ";
		$description = fgets($handler);

		// IF DESCRIPTION IS BLANK, USE A EMPTY STRING
		if ($description == "\n") {
			$description = "";
		} else {
			$description = str_replace("\n", "", $description);
		}


		// MOUNT PROPOSED AUTHOR
		$gitFile = getenv("HOME").'/.gitconfig';
		if (file_exists($gitFile)) {
			$gitConfig = parse_ini_file($gitFile);
			$proposedAuthorName = $gitConfig['name'];
			$proposedAuthorMail = $gitConfig['email'];
		} else {
			$proposedAuthorName = 'nome';
			$proposedAuthorMail = 'email';
		}

		// READ AUTHOR NAME FROM STDIN
		echo "Author name [$proposedAuthorName]: ";
		$authorName = fgets($handler);

		// IF AUTHOR NAME IS BLANK USE PROPOSED
		if ($authorName == "\n") {
			$authorName = "$proposedAuthorName";
		} else {
			$authorName = str_replace("\n", "", $authorName);
		}

		// READ AUTHOR EMAIL FROM STDIN
		echo "Author email [$proposedAuthorMail]: ";
		$authorMail = fgets($handler);

		// IF AUTHOR EMAIL IS BLANK USE PROPOSED
		if ($authorMail == "\n") {
			$authorMail = "$proposedAuthorMail";
		} else {
			$authorMail = str_replace("\n", "", $authorMail);
		}


		// READ LICENSE FROM STDIN
		echo "License [MIT]: ";
		$license = fgets($handler);

		if ($license == "\n") {
			$license = "MIT";
		} else {
			$license = str_replace("\n", "", $license);
		}

		unset($json->type);
		$json->name = $package;
		$json->description = $description;
		$json->authors[0]->name = $authorName;
		$json->authors[0]->email = $authorMail;
		$json->license = $license;

		file_put_contents('composer.json', json_encode($json, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
	}
}