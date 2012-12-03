<?php 
/** Menu module
*
* Copyright (C) 2012 B Tasker
* Released under GNU GPL V2
* See LICENSE
*
*/ 
defined('_CREDLOCK') or die;

require "modules/menu/search-table.php";

?>

<a class="brand" href="index.php"><?php echo BTMain::getConf()->ProgName;?></a>
<ul class="nav">
<li class="divider-vertical"></li>

<?php
if (!BTMain::getUser()->name){

echo "</ul>\n";
return;
}

?>






  <li class="nav dropdown">
<a class="dropdown-toggle" data-toggle="dropdown" href="#"><?php echo Lang::_("Customers");?></a>
      <ul class="dropdown-menu" role="menu" id='CustDropDownMenu' aria-Labelled-by='dLabel'>
      <li><a href="index.php?option=addCustomer">Add <?php echo Lang::_("Customer");?></a></li>
      <li class="divider"></li>


    </ul>
  </li>
<li class="divider-vertical"></li>

<li class="nav dropdown">
<a class="dropdown-toggle" data-toggle="dropdown" href="#"><?php echo Lang::_("Credential Type");?></a>
      <ul class="dropdown-menu" role="menu" id='TypeDropDownMenu' aria-Labelled-by='dLabel'>
      


      </ul>
</li>
<li class="divider-vertical"></li>





</ul>

<div class="pull-right" style="position: relative">
<?php $this->loadModule('search'); ?>


<?php if (BTMain::checkisSuperAdmin()):?>
<ul class="nav settings-menu pull-right">
 <li class="nav dropdown">
<a class="dropdown-toggle" data-toggle="dropdown" href="#"><i class='icon-white icon-cog'></i></a>
      <ul class="dropdown-menu" role="menu" aria-Labelled-by='dLabel'>
      <li><a href="index.php?option=viewUsers">Users</a></li>
      <li><a href="index.php?option=viewGrps"><?php echo Lang::_("User Groups");?></a></li>
      <li><a href="index.php?option=listCredTypes"><?php echo Lang::_("Cred Types");?></a></li>
      <li><a href="index.php?option=pluginInfo">Plugins</a></li>  
      </ul>

</ul>
</div>
<?php endif;?>