<?php 
/** Menu module
*
* Copyright (C) 2012 B Tasker
* Released under GNU GPL V2
* See LICENSE
*
*/ 
defined('_CREDLOCK') or die;
?>
<a class="brand" href="index.php"><?php echo BTMain::getConf()->ProgName;?></a>
<ul class="nav">
<li class="divider-vertical"></li>

<?php if (BTMain::getUser()->name):





  $custs = new CustDB;
  $crdtypes=new CredDB;
  $crypt = new Crypto;
  $crypt->safety = 0;


// Output a hidden table so we can use JS to build the menus (avoids having the cleartext strings in Server memory more than once
?>



<table id="SearchListing" style="display: none;">
<tr>
<td>Add <?php echo Lang::_("Customer"); ?></td>
<td><?php echo Lang::_("Customer"); ?>:</td>
<td></td>
<td></td>
<td></td>
<td>addCustomer</td>
</tr>

<tr>
<td>View <?php echo Lang::_("Customers"); ?></td>
<td><?php echo Lang::_("Customer"); ?>:</td>
<td></td>
<td></td>
<td></td>
<td>viewCustomers</td>
</tr>



<?php
		foreach ($custs->getAllCustomers() as $customer){
ob_start();
$plaintext = $crypt->decrypt($customer->Name,'Customer');
?>

<tr>
  <td><?php echo $plaintext; ?></td>
  <td><?php echo Lang::_("Customer"); ?>:</td>
  <td><?php echo $customer->id;?></td>
  <td>1</td>
  <td></td>
  <td>viewCust</td>
</tr>

<?php
$tbl[$plaintext] = ob_get_clean();

}


ksort($tbl);
echo implode("\n",$tbl);

if (BTMain::checkisSuperAdmin()):?>

<tr>
<td>Add <?php echo Lang::_("Credential Type"); ?></td>
<td><?php echo Lang::_("Credential Type"); ?>:</td>
<td></td>
<td></td>
<td></td>
<td>addCredType</td>
</tr>


<tr>
  <td>View <?php echo Lang::_("Cred Types"); ?></td>
  <td><?php echo Lang::_("Credential Type"); ?>:</td>
  <td></td>
  <td></td>
  <td></td>
  <td>viewByType</td>
</tr>





<?php
endif;



	foreach ($crdtypes->getCredTypes() as $credtype){
 ob_start();
 $plaintext = $crypt->decrypt($credtype->Name,'CredType');

?>

<tr>
  <td><?php echo $plaintext; ?></td>
  <td><?php echo Lang::_("Credential Type"); ?>:</td>
  <td><?php echo $credtype->id;?></td>
  <td>2</td>
  <td></td>
  <td>viewByType</td>
</tr>

<?php
			  
$cred[$plaintext] = ob_get_clean();

}

ksort($cred);
echo implode("\n",$cred);

if (BTMain::checkisSuperAdmin()):

?>
<tr>
  <td>View Users</td>
  <td>User:</td>
  <td></td>
  <td></td>
  <td></td>
  <td>viewUsers</td>
</tr>



<?php
$auth = new AuthDB;

foreach($auth->listUsers() as $user){
?>

<tr>
  <td><?php echo $user->Name . " ({$user->username})"; ?></td>
  <td>User:</td>
  <td><?php echo $user->username;?></td>
  <td></td>
  <td>frmUsername</td>
  <td>editUser</td>
</tr>




<?php
}
?>

<tr>
  <td>View <?php echo Lang::_("User Groups");?></td>
  <td><?php echo Lang::_("UserGroup"); ?>:</td>
  <td></td>
  <td></td>
  <td></td>
  <td>viewGrps</td>
</tr>

<?php


foreach ($auth->retrieveGroupNames() as $grp){

?>

<tr>
  <td><?php echo $crypt->decrypt($grp->Name,'Groups'); ?></td>
  <td><?php echo Lang::_("UserGroup"); ?>:</td>
  <td><?php echo $grp->id;?></td>
  <td></td>
  <td></td>
  <td>editGrp</td>
</tr>




<?php
}
endif;?>
</table>









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


<?php endif; ?>


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