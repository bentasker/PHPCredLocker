<?php
/** Login Module
*
* Copyright (C) 2012 B Tasker
* Released under GNU AGPL V3
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
$frmToken = ProgAuth::generateFormToken();
?>





<form method='POST'>
<input type="hidden" name="option" value="LogIn">
<input type="hidden" name="FormToken" value="<?php echo $frmToken; ?>">
<label for='FrmUsername'>Username:</label><input type='text' name='FrmUsername' id='FrmUsername'>
<label for='FrmPassPlace'>Password:</label><input type='password' name='FrmPassPlace' id='FrmPassPlace'>
<input type="hidden" name="SubmittedLoginFrm" value="1">


      

<input type="submit" class="btn btn-primary" value="Login">
</form>

<?php endif; ?>


</div>