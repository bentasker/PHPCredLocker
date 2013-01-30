<?php
/** Customer Portal related functions
*
* Copyright (c) 2012 B Tasker
* Released under GNU GPL V2
* See LICENSE
*
*/ 

defined('_CREDLOCK') or die;

class CredLockCust{

/** Add a customer 
*
* @arg name - Customer's name
* @arg group - Group ID
* @arg firstname - Their given name
* @arg surname - Family name
* @arg email - Email address
*
*/
function add($name,$group,$firstname,$surname,$email){
$db = new CustDB;
$id = $db->addCustomer($name,$group,$firstname,$surname,$email);
$auth = new ProgAuth;
$db = new AuthDB;

if (!$id){
  return false;
}

// We add the customer to the portal, even if we won't let them log-in (i.e. the portal is disabled)
$password = $auth->generatePassword();
$salt = $auth->createSalt();
$pass = md5($password.$salt);


if ($db->addCusttoPortal($id,$email,$pass.":".$salt,1)) {

    if (BTMain::getConf()->custPortalEnabled){
    global $notifications;
    $not->className = 'alert alert-success';
    $not->text = "The customer has been successfully added to the customer portal and can use the password <i>$password</i> to manage their credentials";

    $notifications->setNotification($not);
    }



}

return $id;

}






function edit($id,$name,$group,$firstname,$surname,$email){

$db = new CustDB;

if (!$db->editCustomer($id,$name,$group,$firstname,$surname,$email)){
return false;
}

$db = new AuthDB;
if ($db->editPortalCustDetails($id,$email)){
  return true;
  }else{
  global $notifications;
  $notifications->setNotification('CustPortalFail');

  }





}












}




?>
