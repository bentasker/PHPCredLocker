<?php
/** Authentication: Edit User
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
$notifications->setPageTitle("Edit User");

$username = BTMain::getVar('frmUsername');

if (BTMain::getVar('editUserSubmitted')){


$pass = BTMain::getVar('frmPass');
$RName = BTMain::getVar('frmRName');

$groups = BTMain::getVar('frmGroup');

    if (BTMain::getVar('frmSuperAdmin')){
    $groups[] = "-1";
    }



$authname = new ProgAuth;

    if ($authname->editUser($username,$pass,$RName, $groups)){
    $notifications->setNotification('UserStoreSuccess');
    }else{
    $notifications->setNotification('UserStoreFail');
    }

}

$auth = new AuthDB;

$user = $auth->getUserDets($username);


  if (!$user){
  // User doesn't exist
  $notifications->setNotification("NoSuchCustomer");
  return;

  }

$Ugroups = explode(",",$user->membergroup);

$path = array(array('name'=>'Users','url'=>'index.php?option=viewUsers'),array('name'=>'Edit','url'=>'index.php?option=editUser?frmUsername='.$username));

$notifications->setBreadcrumb($path);

?>
<h1>Edit User</h1>
<form method="POST" onsubmit="return validateUserEdit();">
<input type="hidden" name="option" value="editUser">
<input type="hidden" name="editUserSubmitted" value="1">

<label for="frmUsername">Username</label><input type="text" name="frmUsername" id="frmUsername" value="<?php echo $username;?>" readonly="true">



<label for="frmPass">Password</label><input type="password" name="frmPass" onkeyup="testPassword(this.value);" id="frmPass" autocomplete='off'>
<span id="passStrength"></span>
<div id="PassNoMatch" style="display: none;" class="alert alert-error"></div>

<label for="frmPassConf">Password Confirm</label><input type="password" name="frmPassConf" id="frmPassConf" autocomplete='off'>

<label for="frmRName">Real Name</label><input type="text" name="frmRName" id="frmRName" value="<?php echo $user->Name; ?>">

<label for="frmSuperAdmin">SuperAdmin</label><input type="checkbox" value="1" name="frmSuperAdmin" id="frmSuperAdmin" <?php if (in_array("-1",$Ugroups)): echo 'checked="true"'; endif;?>>



<input type="hidden" id="passScore" disabled="true">

<?php 

$multiselect = 1;
include('lib/includes/groupSelection.php');
?>

<br />
<input type="submit" class="btn btn-primary" value="Edit User">
</form>
