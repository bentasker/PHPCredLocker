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
$password = rtrim($password,"");
$username = rtrim($username,"");

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

// Create the database session
$db->EstablishSession($sessID);

// Update the audit log
$log = new Logging;
    $log->logEntry('',11);

// Set the session variable
BTMain::setSessVar('Session',$sessID);

return true;
}




/** Use the provided session ID to set the relevant globals
*
* @arg sessid - string
*
* @return boolean
*/
function SetUserDets($sessID){

$db = new AuthDB;
$user = $db->getUserSession($sessID);

  if (!$user){
  session_destroy();
  header('Location: index.php?InvalidSession=1');
  die;
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
header('Location: index.php?LoggedOut=1');
}






}



?>