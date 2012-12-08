<?php
/** Login Module
*
* Copyright (C) 2012 B Tasker
* Released under GNU GPL V2
* See LICENSE
*
*/
defined('_CREDLOCK') or die;
?>
<div id='mod_login' class='login-module'>

<?php if (!empty(BTMain::getUser()->name)): ?>
<div class="btn-group"><a class="btn dropdown-toggle" data-toggle="dropdown" href="#"><?php echo BTMain::getUser()->name; ?>

<span class="caret"></span></a>
  <ul class="dropdown-menu">

      <li><a href='index.php?option=logout'>Log Out</a></li>
      <li><a href='index.php?option=changePassword'>Change Password</a></li>

  </ul>
</div>

<?php else:
$frmToken = sha1(mt_rand(0,90000) . chr(mt_rand(32,254)) . chr(mt_rand(32,254)) . date() . chr(mt_rand(32,254)) . mt_rand(0,999999));
BTMain::setSessVar("FormToken",$frmToken);

?>





<form method='POST'>
<input type="hidden" name="option" value="LogIn">
<input type="hidden" name="FormToken" value="<?php echo $frmToken; ?>">
<label for='FrmUsername'>Username:</label><input type='text' name='FrmUsername' id='FrmUsername'>
<label for='FrmPass'>Password:</label><input type='password' name='FrmPass' id='FrmPass'>
<input type="hidden" name="SubmittedLoginFrm" value="1">

<input type="submit" class="btn btn-primary" value="Login">
</form>

<?php endif; ?>


</div>