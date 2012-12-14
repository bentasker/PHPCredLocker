<?php
/** Affinity Live API Integration plugin
*
* Copyright (C) 2012 B Tasker
* Released under GNU GPL V2
* See LICENSE
*
*/
defined('_CREDLOCK') or die;

/** Class for logging plugin support
*
*/
class plugin_AutoAuth_CredTypes{

/** Load the plugin configuration
*
*/
function config(){
require 'conf/plugins/AutoAuth/config.php';
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
$details->Name = "plg_AutoAuth";
$details->Description = "Allows certain credtypes to display a 'Login' button";
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


switch($data->action){

    case 'add':
    return $this->display_addsettings();
    break;


    case 'added':
    return $this->store_addsettings($data->newid);
    break;

}



}





function store_addsettings($id){

    if (BTMain::getVar('frmAutoAuthEnable')){
      // More settings to be added later


      foreach (BTMain::getVar('settings') as $key=>value){
      $conf->$key = $value;

      }
      
      
      $settings = json_encode($conf);




      $db = new BTDB;
      $crypt = new Crypto;

      // Make sure the settings table exists - Will make this more graceful later
      $sql = "CREATE TABLE IF NOT EXISTS `#__AutoAuth` (  `CredType` int(11) DEFAULT NULL,  `Settings` blob, PRIMARY KEY (`CredType`));";
      $db->setQuery($sql);
      $db->runQuery();


      // We encrypt using the newly created key as that'll be available when decrypting passwords
      $settings = $crypt->encrypt($settings,'Cre'.$id);
      $settings = $db->stringEscape($settings);


      $sql = "INSERT INTO #__AutoAuth VALUES('$id','$settings')";
      $db->setQuery($sql);
      $db->runQuery();

  }
return;
}



function display_addsettings(){

ob_start();
?>
<div id="AAuth">


<label for="frmAutoAuthEnable">Enable Auto Login button</label><input type="checkbox" value="1" id="frmAutoAuthEnable" name="frmAutoAuthEnable" onchange="AAuthboxChange(this.checked);">

<div id="AAuthSettings" style="display: none;">

<label for="frmAutoAuthURL">Additional address path</label><input type="text" title="Additional URL params to add, for example for CPanel you need /login/" id="frmAutoAuthURL" name="settings[frmAutoAuthURL]">

</div>

</div>

<script type="text/javascript">
function AAuthboxChange(checked){
if (checked){

document.getElementById('AAuthSettings').style.display = 'block';
}else{
document.getElementById('AAuthSettings').style.display = 'none';
}

}

$('#AAuth *').tooltip({track: true, fade: 250});

</script>

<?php
return ob_get_clean();
}



}
?>