<?php
/** Authentication: Add User
*
* Copyright (c) 2012 B Tasker
* Released under GNU GPL V2
* See LICENSE
*
*/ 


defined('_CREDLOCK') or die;
BTMain::checkSuperAdmin();
global $notifications;
$notifications->RequireScript('passwordmeter');
$notifications->RequireScript('admin');

$notifications->setPageTitle("Add User");


// Check the form token
$sessvar = BTMain::getSessVar('FormToken');
$process = true;
BTMain::unsetSessVar('FormToken');


  if ($sessvar != BTMain::getVar('FormToken')){
  echo "<div class='alert alert-error'>Invalid Form Token</div>";
  $process = false;
  }



if (BTMain::getVar('addUserSubmitted') && $process){



$username = BTMain::getVar('frmUsername');
$pass = BTMain::getVar('frmPass');
$RName = BTMain::getVar('frmRName');

$groups = BTMain::getVar('frmGroup');

if (BTMain::getVar('frmSuperAdmin')){
$groups[] = "-1";
}



$authname = new ProgAuth;

if ($authname->createUser($username,$pass,$RName, $groups)){
$notifications->setNotification('UserStoreSuccess');

}else{
$notifications->setNotification('UserStoreFail');

}

}


$path = array(array('name'=>'Users','url'=>'index.php?option=viewUsers'),array('name'=>'Add','url'=>'index.php?option=addUser'));

$notifications->setBreadcrumb($path);
$frmToken = ProgAuth::generateFormToken();

?>
<h1>Add User</h1>
<form method="POST" id="AddUserView" onsubmit="return validateUserAdd();">
<input type="hidden" name="option" value="addUser">
<input type="hidden" name="addUserSubmitted" value="1">
<input type="hidden" name="FormToken" value="<?php echo $frmToken; ?>">

<label for="frmUsername">Username</label><input type="text" name="frmUsername" id="frmUsername" autocomplete="off" title="This is the username the user will need to use to log in">



<label for="frmPass">Password</label><input type="password" name="frmPass" autocomplete="off" onkeyup="testPassword(this.value);" id="frmPass">
<span id="passStrength"></span>
<div id="PassNoMatch" style="display: none;" class="alert alert-error"></div>

<label for="frmPassConf">Password Confirm</label><input type="password" name="frmPassConf" id="frmPassConf">

<label for="frmRName">Real Name</label><input type="text" name="frmRName" id="frmRName" title="This is the user's name as you wish it to display in the audit logs and on their user menu">

<label for="frmSuperAdmin">SuperAdmin</label><input type="checkbox" value="1" name="frmSuperAdmin" id="frmSuperAdmin" title="Tick this to make the user a super-admin">
<input type="hidden" id="minpassStrength" disabled="true" value="<?php echo BTMain::getConf()->minpassStrength;?>">
<input type="hidden" id="passScore" disabled="true">
<?php 

$multiselect = 1;
include('lib/includes/groupSelection.php');
?>

<br />
<input type="submit" class="btn btn-primary" value="Add User">
</form>

<script type="text/javascript">$('#AddUserView *').tooltip({track: true, fade: 250});</script>
