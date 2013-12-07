<?php 
/** Menu module
*
* Copyright (C) 2012 B Tasker
* Released under GNU AGPL V3
* See LICENSE
*
*/ 
defined('_CREDLOCK') or die;
global $notifications;




?>
<div class="span12" style="width: 100%;">
<a class="brand" href="index.php"><?php echo BTMain::getConf()->ProgName;?></a>
<ul class="nav">
<li id="menuDivi1" class="divider-vertical"></li>

<?php
if (BTMain::getUser()->name && (!in_array("-99",BTMain::getUser()->groups))):

$this->loadModule('search-table');
?>

  <!-- Customer Menu -->
  <li class="nav dropdown">
<a class="dropdown-toggle" data-toggle="dropdown" href="#"><?php echo Lang::_("Customers");?></a>
      <ul class="dropdown-menu" role="menu" id='CustDropDownMenu' aria-Labelled-by='dLabel'>
      <li><a href="index.php?option=addCustomer">Add <?php echo Lang::_("Customer");?></a></li>
      <li class="divider"></li>


    </ul>
  </li>
  <!-- Customer menu ends -->

  <!-- CredType menu -->
<li id="menuDivi2" class="divider-vertical"></li>

<li id="navbyCredType" class="nav dropdown">
  <a class="dropdown-toggle" data-toggle="dropdown" href="#"><?php echo Lang::_("Credential Type");?></a>
      <ul class="dropdown-menu" role="menu" id='TypeDropDownMenu' aria-Labelled-by='dLabel'>
      


      </ul>
</li>
  <!-- CredType menu ends -->
<li class="divider-vertical"></li>





</ul>
<?php endif; ?>
</ul>
<div class="pull-right" style="position: relative">
 

<?php if (BTMain::getUser()->name && (!in_array("-99",BTMain::getUser()->groups))): ?>

  <!-- Search Box -->
  <?php $this->loadModule('search'); ?>
  <!-- Search Box ends -->

<?php endif; ?>




<?php if (BTMain::checkisSuperAdmin()):?>
<!-- Admin Menu -->
<ul class="nav settings-menu pull-right">
 <li class="nav dropdown">
  <a class="dropdown-toggle" data-toggle="dropdown" href="#"><i class='icon-white icon-cog'></i></a>
      <ul class="dropdown-menu" role="menu" aria-Labelled-by='dLabel'>
      <li><a href="index.php?option=viewUsers">Users</a></li>
      <li><a href="index.php?option=viewGrps"><?php echo Lang::_("User Groups");?></a></li>
      <li><a href="index.php?option=listCredTypes"><?php echo Lang::_("Cred Types");?></a></li>
      <li><a href="index.php?option=pluginInfo">Plugins</a></li>  
      <li><a href="index.php?option=About">About</a></li>
      </ul>

</ul>
<!-- Admin Menu Ends -->


<?php endif;?>

<?php $this->loadModule('login-navbar'); ?>

</div>


    <!-- Call the Setup Script -->
    <script type="text/javascript">
    setUpMenus();
    </script>

</div>