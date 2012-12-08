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

// Check the form token
$sessvar = BTMain::getSessVar('FormToken');
$process = true;
BTMain::unsetSessVar('FormToken');


  if ($sessvar != BTMain::getVar('FormToken')){
  echo "<div class='alert alert-error'>Invalid Form Token</div>";
  $process = false;
  }



if (BTMain::getVar('editUserSubmitted') && $process){


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
$frmToken = ProgAuth::generateFormToken();
?>
<h1>Edit User</h1>
<form method="POST" id="frmEditUser" onsubmit="return validateUserEdit();">
<input type="hidden" name="option" value="editUser">
<input type="hidden" name="editUserSubmitted" value="1">
<input type="hidden" name="FormToken" value="<?php echo $frmToken; ?>">

<label for="frmUsername">Username</label><input type="text" name="frmUsername" id="frmUsername" value="<?php echo $username;?>" readonly="true">



<label for="frmPass">Password</label><input type="password" title="Leave Blank if you don't wish to change the user's password" name="frmPass" onkeyup="testPassword(this.value);" id="frmPass" autocomplete='off'>
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
<script type="text/javascript">$('#frmEditUser *').tooltip({track: true, fade: 250});</script>