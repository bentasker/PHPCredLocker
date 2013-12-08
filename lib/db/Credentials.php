<?php
/** Credential related Database functions
*
* Copyright (C) 2012 B Tasker
* Released under GNU AGPL V3
* See LICENSE
*
*/ 
defined('_CREDLOCK') or die;


class CredDB extends BTDB{


/** Check Cred Types are specified
*
* @return boolean
*/
function checkCredTypesDefined(){

$sql = "SELECT * FROM #__CredTypes";
$this->setQuery($sql);
return $this->loadResult();
}



/** Delete the credential specified
*
* @arg id INT
*
* @return boolena
*/
function DelCredential($id){
// Log the request
$log = new Logging;
$log->logEntry($id,10);
$ACL = BTMain::buildACLQuery();
$id = $this->stringEscape($id);
$sql = "DELETE FROM #__Cred WHERE id='$id' AND ($ACL)";
$this->setQuery($sql);
return $this->runQuery();

}



/** Retrieve the credential specified
*
* @arg id INT
*
* @return object
*/
function FetchCredential($id){
// Log the request
$log = new Logging;
$log->logEntry($id,9);

$ACL = BTMain::buildACLQuery();

$id = $this->stringEscape($id);

$sql = "SELECT Hash, Clicky, Address, UName, CredType, `hidden`, `Group`,`blind` FROM #__Cred WHERE id='$id' AND ($ACL)";
$this->setQuery($sql);


return $this->loadResult();
}


/** Fetch All Credentials for a given type
*
* @arg type id
*
* @return object
*/
function getCredsbyType($id){
$id = $this->stringEscape($id);
$ACL = BTMain::buildACLQuery('a');
$CustACL = BTMain::buildACLQuery('b');

$sql = "SELECT a.id, b.Name FROM #__Cred as a LEFT JOIN #__Cust as b on a.cust=b.id WHERE CredType='$id' AND ($ACL) AND ($CustACL)";
$this->setQuery($sql);

return $this->loadResults();



}




/** Add a new Credential Type
*
* @arg Name string
*
* @return boolean
*/
function AddCredType($name){
$crypt = new Crypto;
$name = $crypt->encrypt($name,'CredType');

$name = $this->stringEscape($name);

$sql = "INSERT INTO #__CredTypes (`Name`) VALUES ('$name')";
$this->setQuery($sql);

$id = $this->insertID();

if ($id){
$log = new Logging;
$log->logEntry($id,15);
return $id;
}else{
return false;
}
}



/** Retrieve the available credential types
*
*/
function getCredTypes(){

$sql = "SELECT * FROM #__CredTypes";
$this->setQuery($sql);
return $this->loadResults();
}


/** Retrieve credential type
*
* @arg id INT
*
* @return object
*/
function getCredType($id){
$id = $this->stringEscape($id);
$sql = "SELECT * FROM #__CredTypes WHERE id='$id'";
$this->setQuery($sql);
return $this->loadResult();
}



/** Edit Credential Type
*
* @arg id - INT
* @arg name - string
*
* @return boolean
*/
function editCredType($id,$name){
$crypt = new Crypto;
$id = $this->stringEscape($id);

$name = $crypt->encrypt($name,'CredType');
$name = $this->stringEscape($name);

$sql = "UPDATE #__CredTypes SET `Name`='$name' WHERE id='$id'";
$this->setQuery($sql);

return $this->runQuery();

}



/** Delete credential type and all associated creds
*
* @arg id INT
*
* @return boolean
*/
function DelCredentialType($id){
$id = $this->stringEscape($id);

$sql = "DELETE FROM #__Cred WHERE `CredType`='$id'";
$this->setQuery($sql);
$this->runQuery();

$sql = "DELETE FROM #__CredTypes WHERE id='$id'";
$this->setQuery($sql);
if ($this->runQuery()){
$log = new Logging;
$log->logEntry($id,16);
return true;
}else{
return false;
}






}



/** Search for a password string within the database - the string to search must be passed pre-encrypted.
*
* @arg searchpass - cryptostring to search
*
* @return object
*/
function searchKeyVal($searchpass,$credtype){
  $sql = "SELECT `id`,`cust`,`Uname` FROM #__Cred WHERE `blind`=0 AND `CredType`='".(int)$credtype."' AND `hash`='".$this->stringEscape($searchpass)."'";
  $this->setQuery($sql);
  return $this->loadResults();
}



/** Insert a new Credential into the database
*
* @arg cust - INT
* @arg credtype - INT
* @arg cred - string
* @arg clicky - tinyint
* @arg group - INT
* @arg address - string
* @arg uname - string
*
* @return object
*/
function addCred($cust,$credtype,$cred,$comment,$clicky,$group = 1,$address = '', $uname = '',$hidden = 0,$dblblind=0)
{


// Encrypt the relevant parts
$crypt = new Crypto;


if (!empty($address)){
$address = $crypt->encrypt($address,'Cre'.$credtype);
}

if (!empty($uname)){
$uname = $crypt->encrypt($uname,'Cre'.$credtype);
}

if (!empty($cred)){
$cred = $crypt->encrypt($cred,'Cre'.$credtype);
}

if (!empty($comment)){
$comment = $crypt->encrypt($comment,'Cre'.$credtype);
}

$address = $this->stringEscape($address);
$uname = $this->stringEscape($uname);
$credtype = $this->stringEscape($credtype);
$cred = $this->stringEscape($cred);
$comment = $this->stringEscape($comment);
$cust = $this->stringEscape($cust);
$clicky = $this->stringEscape($clicky);
$date = date('Y-m-d H:i:s');
$group = $this->stringEscape($group);
$hidden = $this->stringEscape($hidden);


$sql = "INSERT INTO #__Cred (`cust`,`Added`,`Group`,`Hash`,`CredType`,`Clicky`,`Address`,`UName`,`hidden`,`comment`,`blind`) ".
"VALUES ('$cust','$date','$group','$cred','$credtype','$clicky','$address','$uname','$hidden','$comment',".(int)$dblblind.")";
$this->setQuery($sql);

$id = $this->insertID();

    if ($id){
      $log = new Logging;
      $log->logEntry($id,7);
      return $id;
      }else{
      return false;
      }

}




/** Edit Specified Credential
*
* @arg id - INT
* @arg credtype - INT
* @arg cred - string
* @arg clicky - tinyint
* @arg group - INT
* @arg address - string
* @arg uname - string
*
* @return object
*/
function editCred($id,$credtype,$cred,$comment, $clicky,$group = 1,$address = '', $uname = '', $hidden = 0)
{


// Initialise some vars
$crypt = new Crypto;
$ACL = BTMain::buildACLQuery();
$credtype = $this->stringEscape($credtype);
$hidden = $this->stringEscape($hidden);
$id = $this->stringEscape($id);
$date = date('Y-m-d H:i:s');
$group = $this->stringEscape($group);


// build the SQL

$sql = "UPDATE #__Cred SET `Added`='$date', `Group`='$group', hidden='$hidden',";

if ($cred){
$cred = $crypt->encrypt($cred,'Cre'.$credtype);
$cred = $this->stringEscape($cred);
$sql .= "`Hash`='$cred',";
}


if ($clicky){
$clicky = $this->stringEscape($clicky);
$sql .= "`Clicky`='$clicky',";
}

if ($address){
$address = $crypt->encrypt($address,'Cre'.$credtype);
$address = $this->stringEscape($address);
$sql .= "`Address`='$address',";
}

if ($uname){
$uname = $crypt->encrypt($uname,'Cre'.$credtype);
$uname = $this->stringEscape($uname);
$sql .= "`UName`='$uname',";
}

if ($comment){
$comment = $crypt->encrypt($comment,'Cre'.$credtype);
$comment = $this->stringEscape($comment);
$sql .= "`comment`='$comment',";

}


// Get rid of the last comma to prevent a syntax error
$sql = rtrim($sql,",");

$sql .= " WHERE id='$id' AND ($ACL)";

$this->setQuery($sql);

      if ($this->runQuery()){

      $log = new Logging;
      $log->logEntry($id,8);
      return true;
      }else{
      return false;
      }

}




}




?>