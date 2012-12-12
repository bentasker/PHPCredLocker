<?php
/** BTMain Class
*
* System's central class, contains often used resources
*
* Copyright (C) 2012 Ben Tasker
* Released under GNU GPL V2
* See LICENSE
*
*/


defined('_CREDLOCK') or die;

define('_BTFrameWork',1);

require_once 'lib/Framework/db_common.php';

class BTMain{

/** Identify whether the connection is over HTTPS
*
*/
function getConnTypeSSL(){
return $_SERVER['HTTPS'];
}


/** Return the Framework Version
*
*/
function getFrameWorkVers(){
include_once(dirname(__FILE__)."/.version.php");
$vers = $versionmaj;

if (!empty($versionmin)){

$vers .= ".$versionmin";
}


if (!empty($status)){
$vers .= ".$status";
}

return $vers;


}




/** Get version identifier for the software
*
* To work, the version identification must be stored in lib/.version.php
*
*/
function getSoftVersion(){
include_once 'lib/.version.php';

$vers = $versionmaj;

if (!empty($versionmin)){

$vers .= ".$versionmin";
}


if (!empty($status)){
$vers .= ".$status";
}

return $vers;

}




/** Load in the system config and return as an object
*
* @return object
*
*/
function getConf(){

include 'conf/config.php';
return $conf;

}


/** Set the name of the current user
*
* @arg user - username
*
*/
function setUser($user){
$GLOBALS['curruser']->name = $user;
}


/** Get details of current user
*
* @return object
*/
function getUser(){
return $GLOBALS['curruser'];
}


/** Set details for the current user
*
* @arg detail - the element of the user object to set
* @arg value - the value to set for that element
*
*/
function setUserDetails($detail,$value){
$GLOBALS['curruser']->$detail = $value;
}



/** Get the IP of the currently connected client
*
* @return string
*/
function getip(){

return $_SERVER['REMOTE_ADDR'];

}



/** Create a URI string containing Session ID and Hash
*
* @return string
*/
function embedSessionURI(){

return "sess=" . BTMain::getVar('sess') . "&hsh=" . BTMain::getVar('hsh');

}


/** Take the User's groups and turn into part of a WHERE statement
*
*/
function buildACLQuery($tbl = false){
$groups = BTMain::getUser()->groups;
$tab ='';

if ($tbl){
$tab = "$tbl.";
}

if (!in_array("-1",$groups)){
return "$tab`Group`=" . implode(" OR $tab`Group`=",$groups) ;
}else{
return "$tab`Group` LIKE \"%\" ";
}





}



function FixPostVars(){
$postdata = file_get_contents("php://input");


if (strpos($postdata,"%80") !== false){

 $pairs = explode("&", file_get_contents("php://input"));
    $vars = array();   
    foreach ($pairs as $pair) {
        $nv = explode("=", $pair);
        $name = urldecode($nv[0]);
        //$value = urldecode(str_replace("%0D","",$nv[1]));

if (strpos($nv[1],"%80") !== false){
$field = explode("[",$name);
$field = str_replace("]","",$field[1]);

$GLOBALS['_POST']['fields'][$field] = urldecode(str_replace("%80","&euro;",$nv[1]));
}





    }
$GLOBALS['POSTDATAFIXED'] = 1;
}






}




/** Retrieve a variable from the request
*
* May return an array if one was submitted by a form, usually a string though
*
* @return string/array
*/
function getVar($req){

if (!$GLOBALS['POSTDATAFIXED']){
BTMain::FixPostVars();
}


if (isset($_POST[$req])){

return $_POST[$req];
}

return $_GET[$req];


}

/** Push a value to a global used by getVar
*
*/
function setVar($name,$value){
$GLOBALS['_POST'][$name] = $value;



}


/** Retrieve a variable from the session
*
* May return an array if one was submitted by a form, usually a string though
*
* @return string/array
*/
function getSessVar($req){

if (isset($_SESSION[$req])){

return $_SESSION[$req];
}

return false;


}


/** Push a variable from the session
*
*/
function setSessVar($req,$val){



$_SESSION[$req] = $val;



}


/** Unset a session variable
*
*/
function unsetSessVar($req){



unset($_SESSION[$req]);



}



/** Check the user is an Admin
* If not, output an error and stop output
*
*/
function checkAdmin(){
$user = BTMain::getUser()->Role;

if (substr($user,0,1) != "A"){

echo "Access Denied";
die;

}




}





/** Check the user is an Admin
* If not, output an error and stop output
*
* @return boolean
*
*/
function checkisAdmin(){
$user = BTMain::getUser()->Role;

if (substr($user,0,1) == "A"){

return true;

}

return false;




}


/** Check the user is an Admin
* If not, output an error and stop output
*
* @return boolean
*
*/
function checkSuperAdmin(){
$groups = BTMain::getUser()->groups;

if (!in_array("-1",$groups)){

echo 'Access Denied';
die;

}






}


/** Check the user is an Admin
* If not, output an error and stop output
*
* @return boolean
*
*/
function checkisSuperAdmin(){
$groups = BTMain::getUser()->groups;

if (in_array("-1",$groups)){
return true;

}

return false;




}




/** Remove characters that may cause issues in URLs
*
* @arg str - String to be processed
*
* @return string
*/
function stripDodgyChars($str){

return str_replace(" ","",str_replace("&","",str_replace("?","",$str)));



}










}






?>