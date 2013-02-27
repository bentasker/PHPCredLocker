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
<ul class="nav pull-right">


<li class="nav dropdown">

    <?php if (!empty(BTMain::getUser()->name)): ?>
<a class="dropdown-toggle" data-toggle="dropdown" href="#"><i class="icon-user icon-white"></i>
    <span class="UserMenuUserName"><?php echo BTMain::getUser()->name; ?></span>

    </a>


	<ul class="dropdown-menu" role="menu" id='CurUserMenu' aria-Labelled-by='dLabel'>
	  <li><a href="index.php?option=logout">Log Out</a></li>
	 <?php if (BTMain::getUser()->PortalLogin != 1):?> <li><a href="index.php?option=changePassword">Change Password</a></li><?php endif; ?>
	</ul>


<?php else: 

$frmToken = ProgAuth::generateFormToken();
?>


      <form method='POST' id='NavBarLoginForm' class="navbar-form navbar-search pull-right" onsubmit="return loginReqProcess();">
      <input type="hidden" disabled="disabled" value="<?php echo $tls;?>" id="tls">
      <input type="hidden" name="option" value="LogIn">
      <input type="hidden" name="FormToken" value="<?php echo $frmToken; ?>">

    <input type='text' name='FrmUsername' class="search-query" id='FrmUsername' placeholder="Username">
    <input type="hidden" id="FrmPass" name="FrmPass"><!-- The form element that's actually submitted -->
    <input type='password' name='FrmPassPlace' class="search-query" id='FrmPassPlace' placeholder="Password">
      

      <input type="submit" class="btn btn-inverse" value="Login">
      </form>
<?php endif; ?>
</li>
</ul>
