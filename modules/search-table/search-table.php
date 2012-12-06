<?php 
/** Part of Menu module - Table containing data used for search and menu generation.
*
* Copyright (C) 2012 B Tasker
* Released under GNU GPL V2
* See LICENSE
*
*
* Usage: each row specifies a record to be parsed by the relevant Javascript function (usually SearchTable or CreateMenuContent)
*
*
* <tr>
*    <td>Text to Display </td>
*    <td>Highlighted item (e.g. User:)</td>
*    <td>id - the id to append to any URLS</td>
*    <td>Classification, used by CreateMenuContent 1 = Customer, 2 = Cred Type</td>
*    <td>id field - default id, used to form the URI params (so might set frmUsername when linking to user edit page)</td>
*    <td>option - used to form URI Params</td>
*    <td>Additional Params - Optional </td>
*  </tr>
*
*
*
*/ 
defined('_CREDLOCK') or die;
?>

<?php if (BTMain::getUser()->name):

  $custs = new CustDB;
  $crdtypes=new CredDB;
  $crypt = new Crypto;
  $crypt->safety = 0;


// Output a hidden table so we can use JS to build the menus and implement search funtions (avoids having the cleartext strings in Server memory more than once)
?>

      <!-- Search Table Begins -->

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
    <td>Add User</td>
    <td>User:</td>
    <td></td>
    <td></td>
    <td></td>
    <td>addUser</td>
  </tr>

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
    <td>Add <?php echo Lang::_("UserGroup");?></td>
    <td><?php echo Lang::_("UserGroup"); ?>:</td>
    <td></td>
    <td></td>
    <td></td>
    <td>addGrp</td>
  </tr>


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


?>
 <tr>
    <td>View Plugins</td>
    <td>Plugin:</td>
    <td></td>
    <td></td>
    <td></td>
    <td>pluginInfo</td>
  </tr>
<?php

$plug = new Plugins;
$plugins = $plug->listloadedPlugins(); 

      if (isset($plugins->Auth)):
	  foreach($plugins->Auth as $plugin=>$status){
	  ?>

  <tr>
    <td><?php echo $plugin; ?> (Authentication Plugin)</td>
    <td>Plugin:</td>
    <td><?php echo $plugin; ?></td>
    <td></td>
    <td>plg</td>
    <td>plgInfo</td>
    <td>type=Auth</td>
  </tr>

	<?php
	  }

      endif;




    if (isset($plugins->Cron)):
	  foreach($plugins->Auth as $plugin=>$status){
	  ?>

  <tr>
    <td><?php echo $plugin; ?> (Cron Plugin)</td>
    <td>Plugin:</td>
    <td><?php echo $plugin; ?></td>
    <td></td>
    <td>plg</td>
    <td>plgInfo</td>
    <td>type=Cron</td>
  </tr>

	<?php
	  }

      endif;


      if (isset($plugins->Logging)):
	  foreach($plugins->Logging as $plugin=>$status){
	  ?>

  <tr>
    <td><?php echo $plugin; ?> (Logging Plugin)</td>
    <td>Plugin:</td>
    <td><?php echo $plugin; ?></td>
    <td></td>
    <td>plg</td>
    <td>plgInfo</td>
    <td>type=Logging</td>
  </tr>

	<?php
	  }

      endif;

    if (isset($plugins->Customers)):
  foreach($plugins->Customers as $plugin=>$status){
	  ?>

  <tr>
    <td><?php echo $plugin; ?> (<?php echo Lang::_('Customers');?> Plugin)</td>
    <td>Plugin:</td>
    <td><?php echo $plugin; ?></td>
    <td></td>
    <td>plg</td>
    <td>plgInfo</td>
    <td>type=Customers</td>
  </tr>

	<?php
	  }

    endif;

    if (isset($plugins->Creds)):
  foreach($plugins->Creds as $plugin=>$status){
	  ?>

  <tr>
    <td><?php echo $plugin; ?> (<?php echo Lang::_('Credentials');?> Plugin)</td>
    <td>Plugin:</td>
    <td><?php echo $plugin; ?></td>
    <td></td>
    <td>plg</td>
    <td>plgInfo</td>
    <td>type=Creds</td>
  </tr>

	<?php
	  }


      endif;


  endif;?>


  <tr>
    <td>Log Out</td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td>logout</td>
  </tr>

  <tr>
    <td>Change Password</td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td>changePassword</td>
  </tr>


</table>


	  <!-- Search Table Ends --->
<?php endif;?>