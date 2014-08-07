<?php
/** PHPCredLocker Re-Key Utility
*
* Re-Generates Crypto keys and re-encrypts all stored data - Likely to be a long process!
*
* Copyright (C) 2014 B Tasker
* Released under GNU AGPL V3
* See LICENSE
*
*/

require_once 'cli_only.php';

define('_CREDLOCK',"1");
error_reporting(0);



/** Wrapper class so that keys can be read from different sources
*
*/
class cryptokeyscli{

	function __construct(){
		require 'conf/crypto.php';
		$this->keys = $crypt;
		$this->cipher = $cipher;
	}


	function writekeyfile(){

		$fh = fopen('conf/crypto.php','w');

		fwrite($fh,"<?php\n /** Crypto Keys\n * KEEP THIS FILE SECRET\n * \n */\n defined('_CREDLOCK') or die; \n");

		fwrite($fh,"/** Cipher settings */\n");

		foreach ($this->cipher as $k=>$v){

			if (!is_object($v)){
				fwrite($fh,"\$cipher->$k = '$v';\n");
			}else{
				foreach ($v as $vk => $vv){
					fwrite($fh,"\$cipher->$k->$vk = '$vv';\n");
				}
			}

		}


		fwrite($fh,"/** KEYS FOLLOW */\n");
		
		foreach ($this->keys as $k=>$v){
			fwrite($fh,"\$crypt->$k = '$v';\n");
		}

		fclose($fh);
	}

}



class Utils{
	static function genKey($len){
	      $newkey = null;
	      while ($len > 0){

	      	$key = Crypto::generateNum(32,254);
		if ($key == 127 ){
		// Skip the delete char
			continue;
		}

	      	$newkey .= chr($key);
	      	$len--;

	      }

		return base64_encode($newkey);

	}
}




// Set the working directory to the root
chdir(dirname(__FILE__)."/../");

// Load the framework and config etc
require_once 'lib/Framework/main.php';
require_once 'lib/crypto.php';


$output = new CLIOutput;
$input = new CLIInput;

$output->_("PHPCredlocker Re-Key Script\n==========================\n");
$output->_("Note: This script may take some time to complete, and the WebUI may not function correctly until complete");

$confirm = $input->read("Type YES to continue");

if ($confirm != "YES"){
	$output->_("Aborting");
	die;
}


$output->_("Backing up crypto.php");
if (!copy("conf/crypto.php","conf/crypto.backup.php")){
	$output->_("Could not back up keyfile. Aborting");
	die;
}



$db = new BTDB;
$crypt = new Crypto;
$currentkeys = new cryptokeyscli(); // We use this object to make sure we've got a copy of the original
$newkeys = new cryptokeyscli(); // We'll be making the changes in here


$keylength = $newkeys->cipher->keyLength;

$output->_("Preparing to Re-Key Users");

$db->setQuery("SELECT * FROM #__Users");
$users = $db->loadResults();

// For users, it's as simple as re-encrypting the Password hash
$passes = array();

foreach ($users as $user){
	$passes[$user->username] = $crypt->decrypt($user->pass,'auth');	
}


$output->_("Generating new encryption key");

$newkeys->keys->auth = Utils::genKey($keylength);

// Write the key
$newkeys->writekeyfile();

// Encrypt using the new key and update the database
foreach ($passes as $user=>$pass){
	$cpass = $crypt->encrypt($pass,'auth');

	$sql = "UPDATE #__Users SET pass='".$db->stringEscape($cpass)."' WHERE username='".$db->stringEscape($user)."'";
	$db->setQuery($sql);
	$db->runQuery();
}

$output->_("");
$confirm = $input->read("User database has been re-keyed. Please LOG IN to the web interface to check it's worked. If it has type YES to continue");

// Probably need to do a little more to hold the users hand here really
if ($confirm != "YES"){
	$output->_("Aborting");
	die;
}







