<?php
/** Customer related Database functions
*
* Copyright (C) 2012 B Tasker
* Released under GNU GPL V2
* See LICENSE
*
*/ 
defined('_CREDLOCK') or die;


class CustDB extends BTDB{

/** Add a customer to the database
*
* @arg name string
* @arg group INT
* @arg firstname string
* @arg lastname string
* @arg email string
*
* @return boolean
*/
function addCustomer($name,$group,$firstname,$lastname,$email){


$crypt = new Crypto;
$name = $crypt->encrypt($name,'Customer');
$firstname = $crypt->encrypt($firstname,'Customer');
$lastname = $crypt->encrypt($lastname,'Customer');
$email = $crypt->encrypt($email,'Customer');

$firstname = $this->StringEscape($firstname);
$lastname = $this->StringEscape($lastname);
$email = $this->StringEscape($email);
$group = $this->StringEscape($group);
$name = $this->StringEscape($name);
$sql = "INSERT INTO #__Cust (`name`,`Group`,`ContactName`,`ContactSurname`,`Email`) VALUES ('$name','$group','$firstname','$lastname','$email')";
$this->setQuery($sql);

$newcust = $this->insertID();

  if (!$newcust){
  return false;
  }


$log = new Logging;
$log->logEntry($newcust,3);

return true;



}




/** Edit specified customer record
*
* @arg id INT
* @arg name string
* @arg group INT
* @arg firstname string
* @arg lastname string
* @arg email string
*
* @return boolean
*/
function editCustomer($id,$name,$group,$firstname,$lastname,$email){

$ACL = BTMain::buildACLQuery();
$crypt = new Crypto;
$name = $crypt->encrypt($name,'Customer');
$firstname = $crypt->encrypt($firstname,'Customer');
$lastname = $crypt->encrypt($lastname,'Customer');
$email = $crypt->encrypt($email,'Customer');

$id = $this->StringEscape($id);
$firstname = $this->StringEscape($firstname);
$lastname = $this->StringEscape($lastname);
$email = $this->StringEscape($email);
$group = $this->StringEscape($group);
$name = $this->StringEscape($name);

$sql = "UPDATE #__Cust SET `name`='$name',`Group`='$group',`ContactName`='$firstname',".
"`ContactSurname`='$lastname',`Email`='$email' WHERE `id`='$id' AND ($ACL);";
$this->setQuery($sql);



  if (!$this->runQuery()){
  return false;
  }


$log = new Logging;
$log->logEntry($id,4);

return true;



}



/** Delete specified customer and all associated credentials
*
* @arg id - INT 
*
* @return boolean
*/
function DelCust($id){
$id = $this->StringEscape($id);
$ACL = BTMain::buildACLQuery();


$sql = "DELETE FROM #__Cust WHERE id='$id' AND ($ACL)";
$this->setQuery($sql);

if (!$this->runQuery()){
return false;
}

// Log the deletion then clear the creds
$log = new Logging;
$log->logEntry($id,6);

// Remove associated creds
$sql = "DELETE FROM #__Cred WHERE cust='$id' AND ($ACL)";
$this->setQuery($sql);
return $this->runQuery();



}



/** Retrieve all customers (Will need decrypting by the calling function)
*
* @return object
*/
function getAllCustomers(){

$ACL = BTMain::buildACLQuery();

$sql = "SELECT * FROM #__Cust WHERE $ACL";
$this->setQuery($sql);
return $this->loadResults();
}



/** Get Details of all creds for a customer (The creds themselves won't be retrieved yet)
*
* @arg id - INT
*
* @return object
*/
function getCustomerViewData($id){
$id = $this->StringEscape($id);



// Log the request
$log = new Logging;
$log->logEntry($id,5);

$ACL = BTMain::buildACLQuery();

$sql = "SELECT a.CredType, a.id, b.Name as CredName, c.Name FROM #__Cred as a LEFT JOIN #__CredTypes as b on a.CredType = b.id LEFT JOIN #__Cust as c ON a.cust = c.id ".
"WHERE a.cust='$id' AND (" . str_replace("`Group`","a.`Group`",$ACL) . ") AND (" . str_replace("`Group`","c.`Group`",$ACL) . ")";
$this->setQuery($sql);
return $this->loadResults();
}



/** Get Customer Details
*
* @arg id - INT
*
* @return object
*/
function getCustomerDetail($id){
$id = $this->StringEscape($id);
$ACL = BTMain::buildACLQuery();

$sql = "SELECT * FROM #__Cust WHERE id='$id' AND ($ACL)";
$this->setQuery($sql);
return $this->loadResult();
}





}