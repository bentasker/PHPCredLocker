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


/** Check whether the current IP has been banned
*
* @arg ip
*
* @return boolean - true if ip banned
*/
function checkForBan($ip){
$expire = date('Y-m-d H:i:s');
$ip = $this->stringEscape($ip);
$sql = "SELECT * FROM #__bannedIPs WHERE `IP`='$ip' AND `Expiry` > '$expire'";
$this->setQuery($sql);
return $this->loadResult();

}




/** Add a customer record to the portal authentication table
*
* @arg id - Customers Id
* @arg email - Customer's login email address
* @arg pass - A pre-salted pass phrase
* @arg active - tinyint(1)
*
* @return mysql object
*/
function addCusttoPortal($id,$email,$pass,$active = 0){

$crypt = new Crypto;

$id = $this->stringEscape($id);
$email = $this->stringEscape($crypt->encrypt($email,'auth'));
$pass = $this->stringEscape($crypt->encrypt($pass,'auth'));
$active = $this->stringEscape($active);



$sql = "INSERT INTO #__CustPortal VALUES('$id','$email','$pass','$active')";
$this->setQuery($sql);
return $this->runQuery();

}



/** If an IP has crossed the ban threshold, ban them
*
* @arg threshold - How many attempts are they allowed?
* @arg thresholddate - Whens the earliest date that should be considered?
* @arg ip - The IP to check (and possibly ban)
*
*/
function implementBan($threshold,$thresholddate,$bantime,$ip){
$ip = $this->stringEscape($ip);

$sql = "SELECT SUM(FailedAttempts) as failcount FROM #__FailedLogins WHERE FailedIP='$ip' AND LastAttempt > '$thresholddate'";
$this->setQuery($sql);

$tries =$this->loadResult();

    if ($tries->failcount > $threshold){
     $sql = "INSERT INTO #__bannedIPs (`IP`,`Expiry`) VALUES ('$ip','$bantime') ON DUPLICATE KEY UPDATE `Expiry`='$bantime'";
      $this->setQuery($sql);
    $this->runQuery();

    }


}

/** Log Failed Attempt
*
* @arg username string
* @arg username ip
*
*/
function LogFailedAttempt($username,$ip){

$username = $this->stringEscape($username);
$ip = $this->stringEscape($ip);
$date = date("Y-m-d H:i:s");

$sql = "INSERT INTO #__FailedLogins (`username`,`FailedAttempts`,`LastAttempt`,`FailedIP`) ".
"VALUES('$username','1','$date','$ip') ON DUPLICATE KEY UPDATE `FailedAttempts`=`FailedAttempts`+1, `LastAttempt`='$date'";
$this->setQuery($sql);

$this->runQuery();



}



/** Delete the specified User
*
* @arg username string
*
* @return boolean
*/
function DelUser($username){

$username = $this->stringEscape($username);
$sql = "DELETE FROM #__Users WHERE `username`='$username'";
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
$user->username = $this->stringEscape($user->username);
$user->groups = $this->stringEscape(implode(",",$user->groups));
$user->pass = $this->stringEscape($crypt->encrypt($user->pass.":".$user->salt,'auth'));
$user->RealName = $this->stringEscape($user->RealName);

$sql = "INSERT INTO #__Users (`username`,`Name`,`pass`,`membergroup`) ".
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




/** Add a user to a group
*
*/
function userAppendGroup($user,$group){

$user = $this->stringEscape($user);
$group = $this->stringEscape($group);

$sql = "UPDATE #__Users SET membergroup = CONCAT(membergroup,',$group') WHERE username='$user'";
$this->setQuery($sql);

return $this->runQuery();
}


/** Edit user
*
* @arg user - Object containing all User details to be inserted
* 
* @return boolean
*/
function editUser($user){



$user->username = $this->stringEscape($user->username);
$user->groups = $this->stringEscape(implode(",",$user->groups));
$user->RealName = $this->stringEscape($user->RealName);


$sql = "Update #__Users SET `username`='{$user->username}',`Name`='{$user->RealName}',`membergroup`='{$user->groups}'";


if ($user->pass){

$crypt = new Crypto;

$user->pass = $this->stringEscape($crypt->encrypt($user->pass.":".$user->salt,'auth'));
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


$username = $this->stringEscape($username);

$sql = "SELECT pass,membergroup,Name FROM #__Users WHERE username='$username'";
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

$username = $this->stringEscape($username);
$sql = "SELECT Name, membergroup FROM #__Users WHERE username='$username'";
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

$name = $this->stringEscape($name);
$sql = "INSERT INTO #__Groups (`Name`) VALUES('$name')";
$this->setQuery($sql);

$id = $this->insertID();

if ($id){
 $log = new Logging;
    $log->logEntry($id,13);
return $id;
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
$id = $this->stringEscape($id);
$name = $this->stringEscape($name);
$sql = "UPDATE #__Groups SET `Name`='$name' WHERE `id`='$id'";
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
$sessionID = $this->stringEscape($sessionID);
$expires = $this->stringEscape($expires);
$sesskey = $this->stringEscape($sesskey);

$user = BTMain::getUser()->name;
$created = date('Y-m-d H:i:s');


// IP is provided by the client, so we're not going to just trust it!
$ip = $this->stringEscape(BTMain::getip());

$sql = "INSERT INTO #__Sessions (`SessionID`,`Created`,`User`,`Expires`,`ClientIP`,`SessKey`) ".
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

$sess = $this->stringEscape($sess);
$date = date('Y-m-d H:i:s');
$ip = BTMain::getip();

$sql = "SELECT a.ClientIP, a.SessKey, a.`Expires`, b.* FROM #__Sessions as a LEFT JOIN #__Users as b ON a.User = b.username WHERE a.SessionID = '$sess' AND a.Expires > '$date' AND a.`ClientIP` = '$ip'";
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

$sessID = $this->stringEscape($sessID);

$sql = "SELECT `Expires` FROM #__Sessions WHERE `SessionID`='$sessID'";
$this->setQuery($sql);
$exp = $this->loadResult();

$sql = "DELETE FROM #__Sessions WHERE `SessionID`='$sessID'";
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

  if(BTMain::checkisSuperAdmin()){
    $where = "1=1";
  }else{

    $grps = implode(",",BTMain::getUser()->groups);

    $where = "id IN ($grps)";
  }


$sql = "SELECT * FROM #__Groups WHERE $where";
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

$id = $this->stringEscape($id);
$sql = "SELECT Name FROM #__Groups WHERE id='$id'";
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

$id = $this->stringEscape($id);
$sql = "DELETE FROM #__Cred WHERE `Group`='$id'";
$this->setQuery($sql);
$this->runQuery();

$sql = "DELETE FROM #__Groups WHERE `id`='$id'";
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

$sql = "SELECT username, Name FROM #__Users ORDER BY username ASC";
$this->setQuery($sql);

return $this->loadResults();



}





}

?>