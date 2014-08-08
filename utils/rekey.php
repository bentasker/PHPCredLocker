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



	/** Write the objects keys to crypto.php
	*
	*/
	function writekeyfile(){

		$fh = fopen('conf/crypto.php','w');

		fwrite($fh,"<?php\n /** Crypto Keys\n * KEEP THIS FILE SECRET\n * \n */\n defined('_CREDLOCK') or die; \n");

		fwrite($fh,"\n\n/** Cipher settings */\n\n");

		foreach ($this->cipher as $k=>$v){

			if (!is_object($v)){
				fwrite($fh,"\$cipher->$k = '$v';\n");
			}else{
				foreach ($v as $vk => $vv){
					fwrite($fh,"\$cipher->$k->$vk = '$vv';\n");
				}
			}

		}


		fwrite($fh,"\n\n/** KEYS FOLLOW */\n");
		
		foreach ($this->keys as $k=>$v){
			fwrite($fh,"\$crypt->$k = '$v';\n\n");
		}

		fclose($fh);
	}



	/** Remove keys for any CredTypes that are no longer in the database
	*
	* @arg credtypeids - array
	*/
	function tidyKeys($credtypeids){

		foreach ($this->keys as $k => $v){
			// Ignore non Credtype keys
			if ($k == 'CredType' || substr($k,0,3) != 'Cre'){
				continue;
			}

			$id = substr($k,3);

			// Check whether the key is in use
			if (!in_array($id,$credtypeids)){

				// Nope, drop it from the object
				unset($this->keys->$k);
			}

		}

	}

}



/** Utility class
*
*/
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
require_once 'conf/plugins.php';


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


// Prepare a few bits
$db = new BTDB;
$crypt = new Crypto;
//$currentkeys = new cryptokeyscli(); // We use this object to make sure we've got a copy of the original
$newkeys = new cryptokeyscli(); // We'll be making the changes in here


$keylength = $newkeys->cipher->keyLength;

// TODO: Do we want to let the user change the keylength (and maybe the cipher?)



		/** User Re-Key */

$output->_("Preparing to Re-Key Users");

$db->setQuery("SELECT * FROM #__Users");
$users = $db->loadResults();

// For users, it's as simple as re-encrypting the Password hash
$passes = array();

foreach ($users as $user){
	$passes[$user->username] = $crypt->decrypt($user->pass,'auth');	
}

// We also need to grab any data in the Customer Portal table and re-encrypt that
$sql = "SELECT * FROM #__CustPortal";
$db->setQuery($sql);
$cportal = $db->loadResults();
$ccportal = array();

foreach ($cportal as $cust){

	$cust->email = $crypt->decrypt($cust->email,'auth');
	$cust->pass = $crypt->decrypt($cust->pass,'auth');
	$ccportal[] = $cust;

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


foreach ($ccportal as $cust){
	$cust->email = $crypt->encrypt($cust->email,'auth');
	$cust->pass = $crypt->encrypt($cust->pass,'auth');

	$sql = "UPDATE #__CustPortal SET `email`='".$db->stringEscape($cust->email)."', `pass`='".$db->stringEscape($cust->pass)."' WHERE id=".(int) $cust->id;
	$db->setQuery($sql);
	$db->runQuery();
}


unset($passes);
$output->_("");
$confirm = $input->read("User database has been re-keyed. Please LOG IN to the web interface to check it's worked. If it has type YES to continue");


// Probably need to do a little more to hold the users hand here really
if ($confirm != "YES"){
	$output->_("Aborting");
	die;
}






		/** Credtypes Re-Key */



// Credtypes are similarly simple, just the name to switch

$output->_("Preparing to Re-Key Credential Types");

$db->setQuery("SELECT * FROM #__CredTypes");
$credtypes = $db->loadResults();

$ctypes = array();

foreach ($credtypes as $credtype){
	$credtype->Name = $crypt->decrypt($credtype->Name,'CredType');
	$ctypes[] = $credtype;
}


$output->_("Generating new encryption key");
$newkeys->keys->CredType = Utils::genKey($keylength);
$newkeys->writekeyfile();

// Encrypt and update

foreach ($ctypes as $credtype){
	$name = $crypt->encrypt($credtype->Name,'CredType');
	$db->setQuery("UPDATE #__CredTypes SET `Name`='".$db->stringEscape($name)."' WHERE `id`=".(int)$credtype->id);
	$db->runQuery();
}
unset($ctypes);
// unset($credtypes); // Deliberately leaving this set, saves a query later

$output->_("");
$confirm = $input->read("CredTypes have been re-keyed, Please log into the front end and ensure that you can view Credential Type names correctly");


// Probably need to do a little more to hold the users hand here really
if ($confirm != "YES"){
	$output->_("Aborting");
	die;
}







		/** Customers Re-Key */


// Customers require a little more work!

$output->_("Preparing to Re-Key Customers");

$db->setQuery("SELECT * FROM #__Cust");
$customers = $db->loadResults();
$ccustomers = array();

foreach ($customers as $customer){

	$customer->Name = $crypt->decrypt($customer->Name,'Customer');
	$customer->ContactName = $crypt->decrypt($customer->ContactName,'Customer');
	$customer->ContactSurname = $crypt->decrypt($customer->ContactSurname,'Customer');
	$customer->Email = $crypt->decrypt($customer->Email,'Customer');
	$ccustomers[] = $customer;

}


$output->_("Generating new encryption key");
$newkeys->keys->Customer = Utils::genKey($keylength);
$newkeys->writekeyfile();


// Encrypt and update

foreach ($ccustomers as $customer){

	$customer->Name = $crypt->encrypt($customer->Name,'Customer');
	$customer->ContactName = $crypt->encrypt($customer->ContactName,'Customer');
	$customer->ContactSurname = $crypt->encrypt($customer->ContactSurname,'Customer');
	$customer->Email = $crypt->encrypt($customer->Email,'Customer');


	$sql = "UPDATE #__Cust SET `Name`='".$db->stringEscape($customer->Name)."', `ContactName`='".$db->stringEscape($customer->ContactName)."',".
		"`ContactSurname`='".$db->stringEscape($customer->ContactSurname)."',`Email`='".$db->stringEscape($customer->Email)."' WHERE `id`=".(int)$customer->id;
	$db->setQuery($sql);
	$db->runQuery();
}
unset($ccustomers);
unset($customers);
$output->_("");
$confirm = $input->read("Customers have been re-keyed, Please log into the front end and ensure that you can view Customer names and details correctly");

// Probably need to do a little more to hold the users hand here really
if ($confirm != "YES"){
	$output->_("Aborting");
	die;
}







		/** Groups Re-Key */



// Groups next, relatively straight forward

$output->_("Preparing to Re-Key Groups");
$db->setQuery("SELECT * FROM #__Groups");
$groups = $db->loadResults();
$cgroups = array();

foreach ($groups as $group){

	$group->Name = $crypt->decrypt($group->Name,'Groups');
	$cgroups[] = $group;
}

$output->_("Generating new encryption key");
$newkeys->keys->Groups = Utils::genKey($keylength);
$newkeys->writekeyfile();


foreach ($cgroups as $group){

	$group->Name = $crypt->encrypt($group->Name,'Groups');
	$sql = "UPDATE #__Groups SET `Name`='".$group->Name."' WHERE `id`=".(int)$group->id;
	$db->setQuery($sql);
	$db->runQuery();

}

unset($cgroups);
unset($groups);
$output->_("");
$confirm = $input->read("Groups have been re-keyed, Please log into the front end and ensure that you can view Group names correctly");

// Probably need to do a little more to hold the users hand here really
if ($confirm != "YES"){
	$output->_("Aborting");
	die;
}





		/** Credentials Re-Key */


// Now the tricky bit... Creds!
// We already have the credtypes in memory, so we'll work through them one by one.

$output->_("Preparing to re-key credentials (likely to take some time)");

$autoauth = false;

if (file_exists(CREDLOCK_PLUGIN_PATH."/AutoAuth/AutoAuth.php")){
	$autoauth = true;
	$output->_("AutoAuth plugin detected. Will also be re-keyed");
}


// Work through a credtype at a time
$credtypeids = array(); // We'll use this later

foreach ($credtypes as $credtype){

	$credtypeids[] = $credtype->id;

	$sql = "SELECT * FROM #__Cred WHERE `CredType`=".(int)$credtype->id;
	$db->setQuery($sql);
	$creds = $db->loadResults();
	$ccreds = array();

	$output->_("\tProcessing Credtype ".$credtype->id);


	// Work through each of the creds with this credtype
	foreach ($creds as $cred){
		$cred->Hash = $crypt->decrypt($cred->Hash,'Cre'.$cred->CredType);
		$cred->Address = $crypt->decrypt($cred->Address,'Cre'.$cred->CredType);
		$cred->UName = $crypt->decrypt($cred->UName,'Cre'.$cred->CredType);
		$cred->Custom = $crypt->decrypt($cred->Custom,'Cre'.$cred->CredType);
		$cred->comment = $crypt->decrypt($cred->comment,'Cre'.$cred->CredType);
		$ccreds[] = $cred;
	}


	// Process the AutoAuth records if any exist
	if ($autoauth){
		$sql = "SELECT * FROM #__AutoAuth";
		$db->setQuery($sql);
		$aauth = $db->loadResults();
		$caauth = array();
		

		foreach ($aauth as $auth){
			$auth->Settings = $crypt->decrypt($auth->Settings,'Cre'.$cred->CredType);
			$caauth[] = $auth;
		}

	}


	$output->_("\t Generating new encryption key");
	$newkeys->keys->Cre.$credtype->id = Utils::genKey($keylength);
	$newkeys->writekeyfile();


	// Encrypt and save
	foreach ($ccreds as $cred){
		$cred->Hash = $crypt->encrypt($cred->Hash,'Cre'.$cred->CredType);
		$cred->Address = $crypt->encrypt($cred->Address,'Cre'.$cred->CredType);
		$cred->UName = $crypt->encrypt($cred->UName,'Cre'.$cred->CredType);
		$cred->Custom = $crypt->encrypt($cred->Custom,'Cre'.$cred->CredType);
		$cred->comment = $crypt->encrypt($cred->comment,'Cre'.$cred->CredType);

		$sql = "UPDATE #__Cred SET `Hash`='".$db->stringEscape($cred->Hash)."', `Address`='".$db->stringEscape($cred->Address)."',".
			"`UName`='".$db->stringEscape($cred->UName)."',`Custom`='".$db->stringEscape($cred->Custom)."',`comment`='".$db->stringEscape($cred->comment)."' ".
			"WHERE id=".(int)$cred->id;
		$db->setQuery($sql);
		$db->runQuery();
	}


	if ($autoauth){

		foreach ($caauth as $auth){
			$auth->Settings = $crypt->encrypt($auth->Settings,'Cre'.$cred->CredType);
			$db->setQuery("UPDATE #__AutoAuth SET `Settings`='".$db->stringEscape($auth->Settings)."' WHERE `CredType`=".(int)$auth->CredType);
			$db->runQuery();
		}
	}

	$output->_(" ");
	unset($ccreds);

}

// Get rid of any defunct keys
$output->_("Tidying Keys");
$newkeys->tidyKeys($credtypeids);
$newkeys->writekeyfile();


$output->_("");
$confirm = $input->read("Credentials have been re-keyed, Please log into the front end and ensure that you can view credentials correctly");



// Probably need to do a little more to hold the users hand here really
if ($confirm != "YES"){
	$output->_("Aborting");
	die;
}


$output->_("Re-Key complete, exiting....");


