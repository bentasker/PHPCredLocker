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


/** Add a user group
*
* @arg name string - New groups name
*
*/
function addGroup($name){
$authdb = new AuthDB;
return $authdb->addGroup($name);
}


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


/** Generate a random password of the specified length
*
* @arg length - INT
*
* @return string
*
*/
function generatePassword($length = 8){

 $key="(=?)+.,abcdefghijklmnopqrstuvwxyz1234567890ABCDEFGHIJKLMNOPQRSTUVWXYZ";
 $x = 0;
 $p = '';

  while ($x < $length){
  $select = mt_rand(1,strlen($key)) - 1;
  $p .= $key[$select];
  $x++;
  }

return $p;
  
}


/** Generate a unique value and add to the session
*
* Moved here to allow easy improvement on randomness at a later date
*
* @return string
*/
function generateFormToken(){

$frmToken = sha1(mt_rand(0,90000) . chr(mt_rand(32,254)) . chr(mt_rand(32,254)) . date('YMDHis') . chr(mt_rand(32,254)) . mt_rand(0,999999));
BTMain::setSessVar("FormToken",$frmToken);
return $frmToken;

}


/** Append a group ID to a user
*
* @arg user - string
* @arg group - Int
*
*/
function addUserToGroup($user,$group){
$db = new AuthDB;
return $db->userAppendGroup($user,$group);
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





/** Log a failed attempt to login as a valid user
*
*.@arg username -string
* @arg db - object
*
*/
function logFailedAttempt($username,$db){

$db->LogFailedAttempt($username,BTMain::getip());

$threshdate = date("Y-m-d H:i:s",strtotime("-" . BTMain::getConf()->banProximity . " hours"));
$bantime = date("Y-m-d H:i:s",strtotime("+" . BTMain::getConf()->banLength . " hours"));

$db->implementBan(BTMain::getConf()->banThresh,$threshdate,$bantime,BTMain::getip());

return false;
}




/** Process an authentication request
*
* @arg username - string
* @arg plaintextpass - Supplied Password
*
* @return boolean
*/
function ProcessLogIn($username,&$password){

    // Check the form token
    $sessvar = BTMain::getSessVar('FormToken');

    BTMain::unsetSessVar('FormToken');
    if ($sessvar != BTMain::getVar('FormToken')){
    header('Location: index.php?notif=frmTokenInvalid');
    die;
    return false;
     }


  $db = new AuthDB;

 if ($db->checkForBan(BTMain::getip())){
  BTMain::setSessVar('Banned',"1");
  return false;
  }


// Trim trailing space from username & password (issue on mobiles with auto-predict)
$password = rtrim($password," ");
$username = rtrim($username," ");

  if (!$user = $db->retrieveUserCreds($username)){
    // Not a user, user. Check the Portal login
    $cust = new CredLockCust;
    if ((!BTMain::getConf()->custPortalEnabled) || (!$user = $cust->checkLogin($username))){
      return false;
    }
  }



$crypt = new Crypto;
$pass = explode(":",$crypt->decrypt($user->pass,'auth'));
unset($crypt);

// Get the valid hash out of memory as we have it in an array anyway
unset($user->pass);

    if( md5($password.$pass[1]) != $pass[0]){
      return $this->logFailedAttempt($username,$db);
      }

// Create a Session ID
    $sessID = md5(date('YmdHis') . mt_rand(10,80000) . mt_rand(11,500) . $username . mt_rand(0,90000));

// Get the hashes out of memory
    unset($password);
    unset($pass);



if ($user->membergroup != "-99,"){
    $groups = explode(",","0,".$user->membergroup);
    }else{
    $groups = array("-99");
    BTMain::setUserDetails('PortalLogin','1');
    $username = $user->id;
  }

// Log the user in
BTMain::setUser($username);

BTMain::setUserDetails('groups',$groups);

BTMain::setUserDetails('RealName',$user->Name);


// Create the expiry time
$expires = strtotime("+".BTMain::getConf()->sessionexpiry.' Minutes');
$expiry = date('Y-m-d H:i:s',$expires);


// Create a secret key
$str = '';
while ($X < 300){

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

// Generate a key for the auth cookie

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

// Don't redirect API requests, it breaks things!
if (strpos(BTMain::getEntryPoint(),'api.php') !== false){
return;
}

  session_destroy();
  header('Location: index.php?notif=InvalidSession');
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

$cust = new CredLockCust;
if (is_numeric($user->User) && (BTMain::getConf()->custPortalEnabled) && ($usedets = $cust->checkSession($user->User))){
$user->username = $usedets->email;
$user->Name = $usedets->Name;
$user->membergroup = "-99,";
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


if ($user->membergroup != "-99,"){
    $groups = explode(",","0,".$user->membergroup);
    }else{
    $groups = array("-99");
    BTMain::setUserDetails('PortalLogin','1');
    BTMain::setUserDetails('PortalID',$user->User);
  }


BTMain::setUserDetails('groups',$groups);
BTMain::setUserDetails('RealName',$user->Name);


return true;
}




/** Log the user out
* Need to remove the session from the database as well as removing the sessions file and killing the cookie
* 
* @return boolean
*
*/
function killSession(){

$sessID = BTMain::getSessVar('Session');
$tls = BTMain::getSessVar('tls');
session_destroy();

$db = new AuthDB;
$exp = strtotime($db->KillSession($sessID));


// Remove the session file
$filename = "sessions-$exp-{$_COOKIE['PHPCredLocker']}.session.php";
unlink(dirname(__FILE__)."/../sessions/$filename");


// unset the auth cookie
$expires = strtotime("-2 days");
setcookie("PHPCredLocker", $_COOKIE['PHPCredLocker'], $expires, dirname($_SERVER["REQUEST_URI"]), $_SERVER['HTTP_HOST'], BTMain::getConf()->forceSSL);

// Redirect the user
header('Location: index.php?notif=LoggedOut');
return true;
}






}



?>