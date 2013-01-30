<?php
/** Audit logging implementation
*
* Copyright (C) 2012 B Tasker
* Released under GNU GPL V2
* See LICENSE
*
*/
defined('_CREDLOCK') or die;
class Logging extends BTDB{


/** Create a new logEntry
*
* @arg Cred - String - The credential being viewed/edited (this may also be a user id)
* @arg action - Int:
*
*   0: User Created
*   1: User Edited
*   2: User Deleted
*   3: Customer Added
*   4: Customer Edited
*   5: Customer Viewed
*   6: Customer Deleted
*   7: Credential Added
*   8: Credential Edited
*   9: Credential Viewed
*  10: Credential Deleted
*  11: User logged in
*  12: User logged out
*  13: User Group Created
*  14: User Group Deleted
*  15: Credential Type Created
*  16: Credential Type Deleted
*  17: Credential Type Edited
*  18: User Group Edited
*
* @return boolean
*/
function logEntry($cred,$action){

$loggingenabled = BTMain::getConf()->loggingenabled;

// Added to allow logging of Portal actions without revealing email addresses
if (BTMain::getUser()->PortalID){
  $user = BTMain::getUser()->PortalID;
}else{
  $user = BTMain::getUser()->name;
}

$useres = $this->stringEscape($user);
$credes = $this->stringEscape($cred);
$actiones = $this->stringEscape($action);
$timestamp = date('Y-m-d H:i:s');

  if ($loggingenabled){

  $sql = "INSERT INTO #__Audit (`User`,`Cust`,`date`,`Action`) VALUES('$useres','$credes','$timestamp','$actiones')";
  $this->setQuery($sql);
  $res = $this->runQuery();

  }

$data->user = $user;
$data->cred = $cred;
$data->timestamp = $timestamp;
$data->action = $action;

$plg = new Plugins;
$plg->loadPlugins('Logging',$data);

return $res;

}



}

?>