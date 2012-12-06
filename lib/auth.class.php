<?php
/** Authentication processing
*
* Copyright (c) 2012 B Tasker
* Released under GNU GPL V2
* See LICENSE
*
*/
defined('_CREDLOCK') or die;

require_once 'lib/db/authdb.class.php';

class ProgAuth{



/** Create a salt for the user
*
* @return string
*/
function createSalt(){

$x=0;
while ($x <= 100){
$salt .= mt_rand(10,10000);
$x++;
}

return md5($salt.date('y-m-dHis'));

}






/** Edit the specified User
*
* @arg username - string
* @arg pass - string
* @arg RealName - String
* @arg groups - array
*/
function editUser($username,$pass,$RName, $groups){


if (!empty($pass)){
// We need to create a salt for the password
$user->salt = $this->createSalt();

// Salt the password
$user->pass = md5($pass.$user->salt);
// Get the plaintext password out of memory
unset($pass);

}else{
$user->pass = false;
}

$user->RealName = $RName;
$user->groups = $groups;
$user->username = $username;




$db = new AuthDB;
return $db->editUser($user);
}





/** Create a user with the specified Username
*
* @arg username - string
* @arg plaintextpass - string
* @arg RealName - String
* @arg groups - array
*/
function createUser($username,$plaintextPass,$RealName, $groups){

// We need to create a salt for the password
$user->salt = $this->createSalt();

// Salt the password
$user->pass = md5($plaintextPass.$user->salt);
$user->RealName = $RealName;
$user->groups = $groups;
$user->username = $username;


// Get the plaintext password out of memory
unset($plaintextPass);


$db = new AuthDB;
return $db->addUser($user);
}




/** Process an authentication request
*
* @arg username - string
* @arg plaintextpass - Supplied Password
*
* @return boolean
*/
function ProcessLogIn($username,$password){
$db = new AuthDB;


// Trim trailing space from username & password (issue on mobiles with auto-predict)
$password = rtrim($password," ");
$username = rtrim($username," ");

  if (!$user = $db->retrieveUserCreds($username)){
  return false;
   }




$crypt = new Crypto;
$pass = explode(":",$crypt->decrypt($user->pass,'auth'));
unset($crypt);

// Get the valid hash out of memory as we have it in an array anyway
unset($user->pass);

if( md5($password.$pass[1]) != $pass[0]){
return false;
}

// Create a Session ID
$sessID = md5(date('YmdHis') . mt_rand(10,80000) . mt_rand(11,500) . $username . mt_rand(0,90000));
// Get the hashes out of memory
unset($password);
unset($pass);





// Log the user in
BTMain::setUser($username);
BTMain::setUserDetails('groups',explode(",","0,".$user->membergroup));
BTMain::setUserDetails('RealName',$user->Name);

// Create the expiry time
$expires = strtotime("+".BTMain::getConf()->sessionexpiry.' Minutes');
$expiry = date('Y-m-d H:i:s',$expires);

// Create a secret key
$str = '';
while ($X < 400){

$key = mt_rand(32,254);
$str .= chr($key);

$X++;
}

$sesskey = sha1($key);


// Create the database session
$db->EstablishSession($sessID,$expiry,$sesskey);

// Update the audit log
$log = new Logging;
    $log->logEntry('',11);

// Set the session variable
BTMain::setSessVar('Session',$sessID);


// Set the auth cookie



// Generate a key

$X=0;
$str = '';
while ($X < 100){

$key = mt_rand(32,254);
$str .= chr($key);

$X++;
}

// Create a string for the cookie
$cookieVal = md5($str . mt_rand(10,80000) . mt_rand(11,500) . mt_rand(0,90000) );

// Set the cookie
setcookie("PHPCredLocker", $cookieVal, $expires, dirname($_SERVER["REQUEST_URI"]), $_SERVER['HTTP_HOST'], BTMain::getConf()->forceSSL);

// Write to the sessions directory
$filename = "$expires-$cookieVal";
$fh = fopen(dirname(__FILE__)."/../sessions/sessions-$filename.session.php",'w');

$str = "<?php\n defined('_CREDLOCK') or die;\n \$sessKey='$sesskey';\n?>";

fwrite($fh,$str);
fclose($fh);


return true;
}


/** Invalid Login
*
*/
function LoginInvalid(){
  session_destroy();
  header('Location: index.php?InvalidSession=1');
  die;
}

/** Use the provided session ID to set the relevant globals
*
* @arg sessid - string
*
* @return boolean
*/
function SetUserDets($sessID){

if (!isset($_COOKIE['PHPCredLocker'])){
$this->LoginInvalid();
}


$db = new AuthDB;
$user = $db->getUserSession($sessID);

  if (!$user){
 $this->LoginInvalid();
  }

$expiry = strtotime($user->Expires);

// Check for a session file
if(file_exists(dirname(__FILE__)."/../sessions/sessions-$expiry-{$_COOKIE['PHPCredLocker']}.session.php")){

require "sessions/sessions-$expiry-{$_COOKIE['PHPCredLocker']}.session.php";

	if ($sessKey != $user->SessKey){
	$this->LoginInvalid();
	}

}else{
// Session file doesn't exist, so can't be valid
$this->LoginInvalid();
}




// Users session is valid
BTMain::setUser($user->username);
BTMain::setUserDetails('groups',explode(",","0,".$user->membergroup));
BTMain::setUserDetails('RealName',$user->Name);


return true;
}



/** Log the user out
* Need to remove the session from the database as well
* 
* @return boolean
*
*/
function killSession(){
$sessID = BTMain::getSessVar('Session');
session_destroy();
$db = new AuthDB;
$db->KillSession($sessID);


// Remove the session file


header('Location: index.php?LoggedOut=1');
}






}



?>