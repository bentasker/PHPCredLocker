<?php
/** AutoAuth plugin - Provides a button allowing a user to log directly into supported systems
*
* Copyright (C) 2012 B Tasker
* Released under GNU GPL V2
* See LICENSE
*
*/
defined('_CREDLOCK') or die;





/** Central class to minimise duplication of code between the different plugin types
*
*/
class plugin_AutoAuth{

function getDetails(){
$details->Name = "plg_AutoAuth";
$details->Description = "Allows certain credtypes to display a 'Login' button";
$details->Author = 'B Tasker';
$details->License = 'GNU GPL V2';
return $details;
}




/** Get stored settings
*
* @arg credtype - int
* @arg db - db object by reference
* @arg crypt- Crypto object by reference
*
* @return object
*
*/
function getStoredSettings($credtype,&$db,&$crypt){

$sql = "SELECT `Settings` FROM #__AutoAuth WHERE `CredType`='" . $db->stringEscape($credtype) ."'";
$db->setQuery($sql);

$settings = $db->loadResult();
if (!$settings){return false;}

return json_decode($crypt->decrypt($settings->Settings,'Cre'.$credtype));
}

}




/**                            CREDENTIALS PLUGIN STARTS                            **/



/** Credentials Plugin class
*
*/
class plugin_AutoAuth_Creds{
/** Load the plugin configuration
*
*/
function config(){
require 'conf/plugins/AutoAuth/config.php';
}



/** Return the plugin details
*
*/
function getPlgDetails(){

return plugin_AutoAuth::getDetails();

}


/** Get current status
*
*/
function getPlgStatus(){
$this->config();
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


$data->credtype = $cred->CredType;
$data->cred = BTMain::getVar('id');
$data->action = 'display';


switch($data->action){

    case 'display':
    return $this->display_cred($data);
    break;


    
}



}




/** Output the log-in button for the configured system
*
*/
function display_cred($data){



$db = new BTDB;
$crypt = new Crypto;

$settings = plugin_AutoAuth::getStoredSettings($data->cred->CredType,$db,$crypt);

if (!$settings){ return; }



    if (($settings->Enabled == 1) && !empty($settings->user) && !empty($settings->pass)){

    $address = $crypt->decrypt($data->cred->Address,'Cre'.$data->cred->CredType);
    $user = $crypt->decrypt($data->cred->UName,'Cre'.$data->cred->CredType);
    $pass = $crypt->decrypt($data->cred->Hash,'Cre'.$data->cred->CredType);





    if (!empty($address) && !empty($user) && !empty($pass)){


    $onsubmit = '';

	if ($this->warnredirect){
	$onsubmit = "onsubmit=\"return confirm('This will take you to another site, are you sure you wish to continue?');\"";
	}

    ob_start();

	?>	
	    <form action="<?php echo $address; if (!empty($settings->frmAutoAuthURL)){ echo $settings->frmAutoAuthURL;} ?>" method="POST" <?php echo $onsubmit;?>>
	    <input type="hidden" name="<?php echo $settings->user; ?>" value="<?php echo $user; ?>"><input type="hidden" name="<?php echo $settings->pass; ?>" value="<?php echo $pass; ?>">
	    <input type="submit" class="btn btn-primary" value="Log-In"></form>
<?php

    unset($address);
    unset($user);
    unset($pass);

    return ob_get_clean();

    }



    }




}




}




/**                                       CREDTYPES PLUGIN STARTS                               **/


/** Class for CredTypes
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

return plugin_AutoAuth::getDetails();

}



/** Return Plugin status (i.e. is it enabled)
*
* 
*
* @return boolean
*/
function getPlgStatus(){
$this->config();

return $this->active;

}



/** Class Entry Point
*
*
* return values are echoed
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

    case 'edit':
    return $this->displayexisting($data);
    break;


    case 'edited':
    return $this->store_edit($data->id);
    break;

    case 'del':
    return $this->delCredType($data->id);
    break;

  }



}


/** Delete settings for the specified credtype
*
*/
function delCredType($id){
$db = new BTDB;
$sql = "DELETE FROM #__AutoAuth WHERE `CredType`='".$db->stringEscape($id)."'";
$db->setQuery($sql);

return $db->runQuery();
}



/** Updated db table with submitted data
*
* @arg id - INT 
*/
function store_edit($id){


      if (BTMain::getVar('frmAutoAuthEnable')){
      $conf->Enabled = 1;

      }else{
      $conf->Enabled = 0;
      }


      foreach (BTMain::getVar('settings') as $key=>$value){
      $conf->$key = $value;

      }
      
$settings = json_encode($conf);

$db = new BTDB;
$crypt = new Crypto;


      $settings = $crypt->encrypt($settings,'Cre'.$id);
      $settings = $db->stringEscape($settings);


      $sql = "UPDATE #__AutoAuth SET `Settings`='$settings' WHERE `CredType`='".$db->stringEscape($id)."'";
      $db->setQuery($sql);
      $db->runQuery();
      
return;
}




/** Add a new record containing the details that have been submitted
*
*/
function store_addsettings($id){

    if (BTMain::getVar('frmAutoAuthEnable')){
      // More settings to be added later

      $conf->Enabled = 1;
      foreach (BTMain::getVar('settings') as $key=>$value){
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



/** Called when editing an existing credtype
*
*/
function displayexisting($data){

$db = new BTDB;
$crypt = new Crypto;

$settings = plugin_AutoAuth::getStoredSettings($data->id,$db,$crypt);

if (!$settings){ return false; }

return $this->display_addsettings($settings);

}





/** Display form elements for editing/setting plugin values
*
* @arg existsvals - used when editing, otherwise null
*
*/
function display_addsettings($settings = null){

$user = 'user';
$pass = 'pass';
$url = '';
$checked = '';

    if (is_object($settings)){
	$user = $settings->user;
	$pass = $settings->pass;
	$url = $settings->frmAutoAuthURL;

	  if ($settings->Enabled == 1){
	  $checked = ' checked';
	  }

     }


ob_start();
?>
<div id="AAuth">


<label for="frmAutoAuthEnable">Enable Auto Login button</label><input type="checkbox" value="1" id="frmAutoAuthEnable" name="frmAutoAuthEnable" onchange="AAuthboxChange(this.checked);" <?php echo $checked;?>>

<div id="AAuthSettings" style="display: none;">

<label for="frmAutoAuthURL">Additional address path</label><input type="text" title="Additional URL params to add, for example for CPanel you need /login/" id="frmAutoAuthURL"  value="<?php echo $url; ?>"name="settings[frmAutoAuthURL]">

<label for="frmAutoAuthUser">User Field</label><input type="text" title="The field name to submit usernames as" id="frmAutoAuthUser" name="settings[user]" value="<?php echo $user; ?>">

<label for="frmAutoAuthPass">Password Field</label><input type="text" title="The field name to submit usernames as" id="frmAutoAuthPass" name="settings[pass]" value="<?php echo $pass;?>">


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
AAuthboxChange(document.getElementById('frmAutoAuthEnable').checked);
</script>

<?php
return ob_get_clean();
}



}
?>