<?php
/** Change User Password
*
* Copyright (C) 2012 B Tasker
* Released under GNU GPL V2
* See LICENSE
*
*/ 
defined('_CREDLOCK') or die; 

global $notifications;
$notifications->RequireScript('passwordmeter');


$notifications->setPageTitle("Change Password");

// Check the form token
$sessvar = BTMain::getSessVar('FormToken');
$process = true;
BTMain::unsetSessVar('FormToken');


  if ($sessvar != BTMain::getVar('FormToken')){
  echo "<div class='alert alert-error'>Invalid Form Token</div>";
  $process = false;
  }


    if (BTMain::getVar('ChangePassSubmitted') && $process):
      $user = BTMain::getUser();

      $auth = new ProgAuth;

      if($auth->editUser($user->name,BTMain::getVar('frmPass'),$user->RealName, $user->groups)){

      $notifications->setNotification('UserStoreSuccess');
      }else{

      $notifications->setNotification('UserStoreFail');
      }

    endif;


$path = array(array('name'=>'Users','url'=>'index.php?option=viewUsers'),array('name'=>'Change Password','url'=>'index.php?option=changePassword'));

$notifications->setBreadcrumb($path);
$frmToken = ProgAuth::generateFormToken();

?>
<h1>Change Password</h1>

<form method="POST" onsubmit="return comparePwds();">
<input type="hidden" name="Option" value="ChangePass">
<input type="hidden" name="ChangePassSubmitted" value="1">
<input type="hidden" name="FormToken" value="<?php echo $frmToken; ?>">


<label for="frmPass">Password</label><input type="password" name="frmPass" onkeyup="testPassword(this.value);" id="frmPass" autocomplete='off'>
<span id="passStrength"></span>
<div id="PassNoMatch" style="display: none;" class="alert alert-error"></div>

<label for="frmPassConf">Password Confirm</label><input type="password" name="frmPassConf" id="frmPassConf" autocomplete='off'>

<input type="hidden" id="minpassStrength" disabled="true" value="<?php echo BTMain::getConf()->minpassStrength;?>">
<input type="hidden" id="passScore" disabled="true">


<input type="submit" class="btn btn-primary" value="Change Password">
</form>

