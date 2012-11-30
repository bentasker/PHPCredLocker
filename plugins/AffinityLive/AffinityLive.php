<?php
/** Affinity Live API Integration plugin
*
* Copyright (C) 2012 B Tasker
* Released under GNU GPL V2
* See LICENSE
*
*/


/** Class for logging plugin support
*
*/
class plugin_AffinityLive_Logging{

/** Load the plugin configuration
*
*/
function config(){
require 'plugins/AffinityLive/config.php';
}


/** Return details for the Plugin information view
*
* Will be displayed in a table, with the key as the head and the value as the content
* HTML is _NOT_ allowed
*
*
* @return object
*/
function getPlgDetails(){
$details->Name = "plg_AffinityLive_Logging";
$details->author = 'B Tasker';
$details->License = 'GNU GPL V2';

return $details;

}



/** Return Plugin status (i.e. is it enabled)
*
* Return true, false or 'In Test Mode'
*
* @return boolean or string
*/
function getPlgStatus(){
$this->config();

if ($this->testmode && $this->active){
return "In Test Mode";
}

return $this->active;

}



/** Class Entry Point
*
* @arg data - object, will contain
*  user - string - username of user performing the logged action
*  cred - string - Identified for the credential, User, Group or Credential type being edited. May be null (login, logout)
*  timestamp - PHP date format timestamp for the action. Exact format controlled by main config, use strtotime to convert if needed
*  action - INT - Action identifier (see loggingdb documentation)
*
*  If you need extra data, run a query. Don't decrypt encrypted data unless you know you can do so without creating a security risk!
*
* return values are ignored
*/
function PlgCall($data){
// Load the plugin config
$this->config();

// Check the plugin is actually enabled
if (!$this->active){ return; }


// Create an array containing the action identifiers for cred access/deletion etc.
$supportedCred = array("7","8","9","10");

// See if we have a match
if (in_array($data->action,$supportedCred)){
return $this->logCredAction($data);
}

// Will add support for Customer actions at a later date - TODO 


}



/** Log Credential addition/view/deletion/edit
*
* @arg data - object - See PlgCall
*
* @return
*/
function logCredAction($data){

// Create the DB object
$db = new BTDB;

// Protect against SQLi
$id = $db->StringEscape($data->cred);
$username = $db->StringEscape($data->user);

// Build the query
$sql = "SELECT a.cust, b.Name as Name, b.ContactName, b.ContactSurname, b.Email, c.Name as CredType ".
"FROM Cred as a ".
"LEFT JOIN Cust as b on a.cust = b.id ".
"LEFT JOIN CredTypes as c on a.CredType = c.id ".
"WHERE a.`id` = '$id'";

$db->setQuery($sql);
$res = $db->loadResult();


// Get the users real name
$sql = "SELECT Name FROM Users WHERE username='$username'";
$db->setQuery($sql);
$user = $db->loadResult();

// Load the crypto library
$crypt = new Crypto;

// Prevent unloading of keys from memory (as we'll be decrypting a few things)
$crypt->safety = 0;

$company = $crypt->decrypt($res->Name,'Customer');
$firstname = $crypt->decrypt($res->ContactName,'Customer');
$lastname = $crypt->decrypt($res->ContactSurname,'Customer');
$email = $crypt->decrypt($res->Email,'Customer');
$credtype = $crypt->decrypt($res->CredType,'CredType');





  switch($data->action){

  case 7:
  $access = 'Added';
  break;

  case 8:
  $access = 'Edited';

  case 9:
  $access = 'Viewed';
  break;

  case 10:
  $access = 'Deleted';
  break;


  }


// Create the support ticket subject
$subject = BTMain::getConf()->ProgName . ": $credtype Credentials $access by {$user->Name} at ". date($this->dateformat);

// Create the support ticket content
$desc = date($this->dateformat) . ": User {$user->Name} $access the $credtype credentials for this account";

// Build the request
$fields = array(
'company_name' => $company,
'contact_firstname' => $firstname,
'contact_surname' => $lastname,
'issue_subject' => $subject,
'issue_type_id' => $this->loggingissuetype,
'affiliation_email' => $email,
'issue_status_id' => $this->loggingstatus,
'issue_description' => $desc,
'issue_priority_id' => $this->loggingpriority
);

// Check all required data is available
if (empty($company) || empty($firstname) || empty($lastname) || empty($email)){ return; }


// Create the POST string
foreach ($fields as $key=>$value){ $fields_string .= $key.'='.urlencode($value).'&';}
rtrim($fields_string,'&');


    if ($this->testmode){
      echo "DEBUG: AL Plugin Would send $fields_string<br/><Br/>\n\n";
      return;
    }


// Place the request
$ch = curl_init();
curl_setopt($ch,CURLOPT_URL,$this->url);
curl_setopt($ch,CURLOPT_POST,count($fields));
curl_setopt($ch,CURLOPT_POSTFIELDS,$fields_string);
curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);

$result = curl_exec($ch);
curl_close($ch);

return;

}


}
?>