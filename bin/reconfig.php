<?php
namespace bin;


function detect_encoding($string, $ret=null) { 
       
        static $enclist = array( 
            'UTF-8', 'ASCII', 'CP850',
            'ISO-8859-1', 'ISO-8859-2', 'ISO-8859-3', 'ISO-8859-4', 'ISO-8859-5', 
            'ISO-8859-6', 'ISO-8859-7', 'ISO-8859-8', 'ISO-8859-9', 'ISO-8859-10', 
            'ISO-8859-13', 'ISO-8859-14', 'ISO-8859-15', 'ISO-8859-16', 
            'Windows-1251', 'Windows-1252', 'Windows-1254', 
            );
        
        $result = false; 
        
        foreach ($enclist as $item) { 

            $sample = @iconv($item, $item, $string); 

            if (md5($sample) == md5($string)) { 
                if ($ret === NULL) { $result = $item; } else { $result = true; } 
                break; 
            }
        }
        
    return $result; 
} 

class reconfig
{

	private static function toUTF8( $string ) {

		
        
        $enc = detect_encoding($string);
        if ($enc == 'UTF-8') {
        	return $string;
        } else {
        	return iconv($enc, "UTF-8", $string);
        }
	}


	public static function run() {

		// READ COMPOSER.JSON
		$json = json_decode(file_get_contents("composer.json"));

		// CREATE HANDLER TO READ FROM STDIN
		$handler = fopen ("php://stdin","r");

		// // GET THE CURRENT CODEPAGE (NEEDED FOR WINDOWS)
		// $codePage = exec("cmd \"/C chcp\"");
		// $codePage = "CP".trim(substr($codePage, strpos($codePage, ":")+1, strlen($codePage)));

		// MOUNT PROPOSED PACKAGE NAME
		exec('whoami', $out); 
		$proposedUser = strtolower($out[0]);
		$proposedPack = strtolower(basename(__DIR__));

		// READ PACKAGE NAME FROM STDIN
		echo "Package name (<vendor>/<name>) [$proposedUser/$proposedPack]: ";
		$package = trim(self::toUTF8(fgets($handler)));

		// IF PACKAGE IS BLANK USE PROPOSED
		if ($package == "") $package = "$proposedUser/$proposedPack";

		// READ DESCRIPTION FROM STDIN
		echo "Description []: ";
		$description = trim(self::toUTF8(fgets($handler)));


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
		$authorName = trim(self::toUTF8(fgets($handler)));

		// IF AUTHOR NAME IS BLANK USE PROPOSED
		if (trim($authorName) == "") $authorName = "$proposedAuthorName";


		// READ AUTHOR EMAIL FROM STDIN
		echo "Author email [$proposedAuthorMail]: ";
		$authorMail = trim(self::toUTF8(fgets($handler)));

		// IF AUTHOR EMAIL IS BLANK USE PROPOSED
		if (trim($authorMail) == "") $authorMail = "$proposedAuthorMail";

		// READ LICENSE FROM STDIN
		echo "License [MIT]: ";
		$license = trim(self::toUTF8(fgets($handler)));

		if (trim($license) == "") $license = "MIT";

		unset($json->type);
		$json->name = $package;
		$json->description = $description;
		$json->authors[0]->name = $authorName;
		$json->authors[0]->email = $authorMail;
		$json->license = $license;

		$out = json_encode($json, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

		if (json_last_error() != 0) {
			echo "\nOcorreu algum erro com a geração do arquivo JSON, nada será feito!";
			exit;
		} else {
			file_put_contents ('composer.json', $out);	
		}
		
	}
}