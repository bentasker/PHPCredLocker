<?php
/** Update Login passwords using the old schema to bcrypt
*
*/
define('_CREDLOCK',1);

// Load the required libraries
chdir(dirname(__FILE__)."/../");
require_once 'lib/Framework/main.php';
require_once 'lib/crypto.php';
require_once 'lib/auth.class.php';


$crypt = new Crypto;
$db = new AuthDB;

// Need to set the correct table name
$sql = "SELECT * FROM #__Logins";
$db->setQuery($sql);
$res = $db->loadResults();

foreach ($res as $pass){
// Need to decrypt the relevant values

$p = explode(":",$pass->password);

$newhash = $auth->blowfishCrypt(md5($p[0].$p1[1]),12);



}





?> 
