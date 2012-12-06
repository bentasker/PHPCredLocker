<?php
/** Authentication related DB functions
*
* Copyright (C) 2012 B Tasker
* Released under GNU GPL V2
* See LICENSE
*
*/
defined('_CREDLOCK') or die;

class AuthDB extends BTDB{



/** Delete the specified User
*
* @arg username string
*
* @return boolean
*/
function DelUser($username){

$username = $this->StringEscape($username);
$sql = "DELETE FROM Users WHERE `username`='$username'";
$this->setQuery($sql);

return $this->runQuery();
}





/** Create a new user in the database
*
* @arg user - Object containing all User details to be inserted
* 
* @return boolean
*/
function addUser($user){


$crypt = new Crypto;
$user->username = $this->StringEscape($user->username);
$user->groups = $this->StringEscape(implode(",",$user->groups));
$user->pass = $this->StringEscape($crypt->encrypt($user->pass.":".$user->salt,'auth'));
$user->RealName = $this->StringEscape($user->RealName);

$sql = "INSERT INTO Users (`username`,`Name`,`pass`,`membergroup`) ".
"VALUES('{$user->username}','{$user->RealName}','{$user->pass}','{$user->groups}')";

$this->setQuery($sql);

$result = $this->runQuery();

    
    if ($result){
    // Insert was successful, we need to add to the audit log
    $log = new Logging;
    $log->logEntry($user->username,0);
    return true;

    }else{
    // Insert failed, return false.

    return false;

    }

}


/** Edit user
*
* @arg user - Object containing all User details to be inserted
* 
* @return boolean
*/
function editUser($user){



$user->username = $this->StringEscape($user->username);
$user->groups = $this->StringEscape(implode(",",$user->groups));
$user->RealName = $this->StringEscape($user->RealName);


$sql = "Update Users SET `username`='{$user->username}',`Name`='{$user->RealName}',`membergroup`='{$user->groups}'";


if ($user->pass){

$crypt = new Crypto;

$user->pass = $this->StringEscape($crypt->encrypt($user->pass.":".$user->salt,'auth'));
$sql .= ", `pass`= '{$user->pass}'";
}

$sql .= " WHERE `username`='{$user->username}'";





$this->setQuery($sql);

$result = $this->runQuery();

    
    if ($result){
    // Insert was successful, we need to add to the audit log
    $log = new Logging;
    $log->logEntry($user->username,1);
    return true;

    }else{
    // Insert failed, return false.

    return false;

    }

}





/** Called when a user attempts to log in
* Retrieves everything needed to verify authentication and create the session
*
* @arg username - string
*
* @return object or boolean
*/
function retrieveUserCreds($username){


$username = $this->StringEscape($username);

$sql = "SELECT pass,membergroup,Name FROM Users WHERE username='$username'";
$this->setQuery($sql);

if ($res = $this->runQuery()){

return mysql_fetch_object($res);
}

return false;


}




/** Get a user's details
*
* @arg username string
*
* @return object
*/
function getUserDets($username){

$username = $this->StringEscape($username);
$sql = "SELECT Name, membergroup FROM Users WHERE username='$username'";
$this->setQuery($sql);

return $this->loadResult();



}


/** Add a User Group
*
* @arg group string
*
*/
function addGroup($name){

$crypt = new Crypto;
$name = $crypt->encrypt($name,'Groups');

$name = $this->StringEscape($name);
$sql = "INSERT INTO Groups (`Name`) VALUES('$name')";
$this->setQuery($sql);

$id = $this->insertID();

if ($id){
 $log = new Logging;
    $log->logEntry($id,13);
return true;
}else{
return false;
}
}


/** Edit a User Group
*
* @arg id - INT 
* @arg group string
*
*/
function editGroup($id,$name){

$crypt = new Crypto;
$name = $crypt->encrypt($name,'Groups');
$id = $this->StringEscape($id);
$name = $this->StringEscape($name);
$sql = "UPDATE Groups SET `Name`='$name' WHERE `id`='$id'";
$this->setQuery($sql);



  if ($this->runQuery()){
    $log = new Logging;
    $log->logEntry($id,18);
    return true;
    }else{
    return false;
    }

}


/** Establish a database session for the current user
*
* @arg SessionID - string
* @arg expires - datetime
* @arg sesskey - string
*
* @return boolean
*/
function EstablishSession($sessionID,$expires,$sesskey){
$sessionID = $this->StringEscape($sessionID);
$expires = $this->StringEscape($expires);
$sesskey = $this->StringEscape($sesskey);

$user = BTMain::getUser()->name;
$created = date('Y-m-d H:i:s');


// IP is provided by the client, so we're not going to just trust it!
$ip = $this->StringEscape(BTMain::getip());

$sql = "INSERT INTO Sessions (`SessionID`,`Created`,`User`,`Expires`,`ClientIP`,`SessKey`) ".
"VALUES ('$sessionID','$created','$user','$expires','$ip','$sesskey')";

$this->setQuery($sql);
return $this->runQuery();



}


/** Grab user details based on the provided sessionID
*
* @arg sess - String 
*
* @return object
*/
function getUserSession($sess){

$sess = $this->StringEscape($sess);
$date = date('Y-m-d H:i:s');
$ip = BTMain::getip();

$sql = "SELECT a.ClientIP, a.SessKey, a.`Expires`, b.* FROM Sessions as a LEFT JOIN Users as b ON a.User = b.username WHERE a.SessionID = '$sess' AND a.Expires > '$date' AND a.`ClientIP` = '$ip'";
$this->setQuery($sql);

return $this->loadResult();


}



/** Remove a session from the database
*
* @arg SessionID - string
*
* @return boolean
*/
function KillSession($sessID){

$sessID = $this->StringEscape($sessID);

$sql = "SELECT `Expires` FROM Sessions WHERE `SessionID`='$sessID'";
$this->setQuery($sql);
$exp = $this->loadResult();

$sql = "DELETE FROM Sessions WHERE `SessionID`='$sessID'";
$this->setQuery($sql);
$res = $this->runQuery();

// Log the event
$log = new Logging;
$log->logEntry('',12);

return $exp->Expires;
}


/** Retrieve group data (Names will need decrypting)
*
*/
function retrieveGroupNames(){


$sql = "SELECT * FROM Groups";
$this->setQuery($sql);
return $this->loadResults();

}



/** Retrieve the name of a Group based on ID
*
* @arg id INT
*
* @return object
*/
function retrieveGrpById($id){

$id = $this->StringEscape($id);
$sql = "SELECT Name FROM Groups WHERE id='$id'";
$this->setQuery($sql);
return $this->loadResult();

}




/** Delete the specified group
*
* Any credentials still recorded against the group will be set to SuperAdmin only
*
* @arg id -INT
*
* @return boolean
*/
function delGroup($id){

$id = $this->StringEscape($id);
$sql = "DELETE FROM Cred WHERE `Group`='$id'";
$this->setQuery($sql);
$this->runQuery();

$sql = "DELETE FROM Groups WHERE `id`='$id'";
$this->setQuery($sql);

if($this->runQuery()){


$log = new Logging;
$log->logEntry($id,14);
return true;
}else{
return false;
}

}




/** Retrieve a User list
*
* @return object
*/
function listUsers(){

$sql = "SELECT username, Name FROM Users ORDER BY username ASC";
$this->setQuery($sql);

return $this->loadResults();



}





}










?>