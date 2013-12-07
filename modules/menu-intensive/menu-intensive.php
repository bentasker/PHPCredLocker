<?php 
/** Menu module
*
* Copyright (C) 2012 B Tasker
* Released under GNU AGPL V3
* See LICENSE
*
*/ 
defined('_CREDLOCK') or die;
?>
<a class="brand" href="index.php"><?php echo BTMain::getConf()->ProgName;?></a>
<ul class="nav">
<li class="divider-vertical"></li>

<?php if (BTMain::getUser()->name):?>

  <li class="nav dropdown">
<a class="dropdown-toggle" data-toggle="dropdown" href="#"><?php echo Lang::_("Customers");?></a>
      <ul class="dropdown-menu" role="menu" aria-Labelled-by='dLabel'>
      <li><a href="index.php?option=addCustomer">Add <?php echo Lang::_("Customer");?></a></li>
      <li class="divider"></li>
	  
<?php

	  $itemcount = 0;
	  $custs = new CustDB;
	  $customers = $custs->getAllCustomers();
	  $crypt = new Crypto;
	  $crypt->safety = 0;

		foreach ($customers as $customer){

		$plaintext = $crypt->decrypt($customer->Name,'Customer');
		$cust[$plaintext] = "<li id='Custmenu{$customer->id}'><a href='index.php?option=viewCust&id={$customer->id}'>$plaintext</a></li>";
		$itemcount++;

		    if ($itemcount == 10){
		    break;
		    }
		}
	  
	
	ksort($cust);
	echo implode("\n",$cust);
	echo "<li class='divider'></li>\n<li><a href='index.php?option=viewCustomers'>View All</a></li>";


?>



    </ul>
  </li>
<li class="divider-vertical"></li>

<li class="nav dropdown">
<a class="dropdown-toggle" data-toggle="dropdown" href="#"><?php echo Lang::_("Credential Type");?></a>
      <ul class="dropdown-menu" role="menu" aria-Labelled-by='dLabel'>
      
	    <?php
		  $crdtypes=new CredDB;
		  $credtypes = $crdtypes->getCredTypes();

		      foreach ($credtypes as $credtype){

			  $plaintext = $crypt->decrypt($credtype->Name,'CredType');
			  $cred[$plaintext] = "<li id='Custmenu{$credtype->id}'><a href='index.php?option=viewByType&id={$credtype->id}'>$plaintext</a></li>";

		      }
	
		  ksort($cred);
		  echo implode("\n",$cred);
?>

      </ul>
</li>
<li class="divider-vertical"></li>


<?php endif; ?>


</ul>

<div class="pull-right">
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


