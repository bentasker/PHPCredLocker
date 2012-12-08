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
<ul class="nav">


<li class="nav dropdown">

    <?php if (!empty(BTMain::getUser()->name)): ?>
<a class="dropdown-toggle" data-toggle="dropdown" href="#">
    <?php echo BTMain::getUser()->name; ?>

    </a>


	<ul class="dropdown-menu" role="menu" id='CurUserMenu' aria-Labelled-by='dLabel'>
	  <li><a href="index.php?option=logout">Log Out</a></li>
	  <li><a href="index.php?option=changePassword">Change Password</a></li>
	</ul>


<?php else: ?>


      <form method='POST' class="navbar-form navbar-search pull-right">
      <input type="hidden" name="option" value="LogIn">
    <input type='text' name='FrmUsername' class="search-query" id='FrmUsername' placeholder="Username">
    <input type='password' name='FrmPass' class="search-query" id='FrmPass' placeholder="Password">
      

      <input type="submit" class="btn btn-inverse" value="Login">
      </form>
<?php endif; ?>
</li>
</ul>
