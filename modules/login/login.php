<?php
/** Login Module
*
* Copyright (C) 2012 B Tasker
* Released under GNU GPL V2
* See LICENSE
*
*/

?>
<div id='mod_login' class='login-module'>

<?php if (!empty(BTMain::getUser()->name)): ?>
Hi <?php echo BTMain::getUser()->name; ?>
<li><a href='index.php?option=logout'>Log Out</a></li>


<?php else: 
	      // Attempt to log user in if they've already submitted
      if (BTMain::getVar('SubmittedLoginFrm')){
      $auth = new ProgAuth;
	if ($auth->ProcessLogIn(BTMain::getVar('FrmUsername'),BTMain::getVar('FrmPass'))){
	    // Login successful
	    header('Location: index.php?LoginSuccess=1');
	    }else{
	    header('Location: index.php?LoginFailed=1');
	    }
	    die;
      }

?>





<form method='POST'>

<label for='FrmUsername'>Username:</label><input type='text' name='FrmUsername' id='FrmUsername'>
<label for='FrmPass'>Password:</label><input type='password' name='FrmPass' id='FrmPass'>
<input type="hidden" name="SubmittedLoginFrm" value="1">

<input type="submit" class="btn btn-primary" value="Login">
</form>

<?php endif; ?>


</div>