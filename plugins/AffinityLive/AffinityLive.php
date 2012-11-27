<?php
/** Affinity Live API Integration plugin
*
* Copyright (C) 2012 B Tasker
* Released under GNU GPL V2
* See LICENSE
*
*/


class plugin_AffinityLive_Logging{


function config(){
require 'plugins/AffinityLive/config.php';
}


function getPlgDetails(){
$details->Name = "plg_AffinityLive_Logging";
$details->author = 'B Tasker';
$details->License = 'GNU GPL V2';

return $details;

}

function getPlgStatus(){
$this->config();

if ($this->testmode && $this->active){
return "In Test Mode";
}

return $this->active;

}


function PlgCall($data){
$this->config();
if (!$this->active){ return; }

/* Available fields 

$data->user = $user;
$data->cred = $cred;
$data->timestamp = $timestamp;
$data->action = $action;
*/

$supported = array("7","8","9","10");

if (!in_array($data->action,$supported)){
return;
}

$db = new BTDB;
$id = $db->StringEscape($data->cred);
$username = $db->StringEscape($data->user);

$sql = "SELECT a.cust, b.Name as Name, b.ContactName, b.ContactSurname, b.Email, c.Name as CredType ".
"FROM Cred as a ".
"LEFT JOIN Cust as b on a.cust = b.id ".
"LEFT JOIN CredTypes as c on a.CredType = c.id ".
"WHERE a.`id` = '$id'";

$db->setQuery($sql);
$res = $db->loadResult();

$sql = "SELECT Name FROM Users WHERE username='$username'";
$db->setQuery($sql);
$user = $db->loadResult();

$crypt = new Crypto;
$crypt->safety = 0;

$company = $crypt->decrypt($res->Name,'Customer');
$firstname = $crypt->decrypt($res->ContactName,'Customer');
$lastname = $crypt->decrypt($res->ContactSurname,'Customer');
$email = $crypt->decrypt($res->Email,'Customer');
$credtype = $crypt->decrypt($res->CredType,'CredType');
unset($crypt->keys);




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



$subject = BTMain::getConf()->ProgName . ": $credtype Credentials $access by {$user->Name} at ". date($this->dateformat);
$desc = date($this->dateformat) . ": User {$user->Name} $access the $credtype credentials for this account";

// Build the request
$fields = array(
'company_name' => urlencode($company),
'contact_firstname' => urlencode($firstname),
'contact_surname' => urlencode($lastname),
'issue_subject' => urlencode($subject),
'issue_type_id' => urlencode($this->loggingissuetype),
'affiliation_email' => urlencode($email),
'issue_status_id' => urlencode($this->loggingstatus),
'issue_description' => urlencode($desc),
'issue_priority_id' => urlencode($this->loggingpriority)
);


if (empty($company) || empty($firstname) || empty($lastname) || empty($email)){ return; }



foreach ($fields as $key=>$value){ $fields_string .= $key.'='.$value.'&';}
rtrim($fields_string,'&');

if ($this->testmode){
echo "DEBUG: AL Plugin Would send $fields_string<br/><Br/>\n\n";
return;
}


$ch = curl_init();
curl_setopt($ch,CURLOPT_URL,$this->url);
curl_setopt($ch,CURLOPT_POST,count($fields));
curl_setopt($ch,CURLOPT_POSTFIELDS,$fields_string);
curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);

$result = curl_exec($ch);
curl_close($ch);



}















}
