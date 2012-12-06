<?php
/** Customer Details View
*
* Copyright (C) 2012 B Tasker
* Released under GNU GPL V2
* See LICENSE
*
*/
defined('_CREDLOCK') or die;
global $notifications;
$custom = new CustDB;
$custom->connreuse = 1;



// Get the customer details
$custdetails = $custom->getCustomerDetail(BTMain::getVar('id'));

$notifications->setPageTitle("View ".Lang::_('Customer'));
  if (!$custdetails){
  // Invalid customer
  $notifications->setNotification("NoSuchCustomer");
  return;

  }


// Get credentials
$customers = $custom->getCustomerViewData(BTMain::getVar('id'));

// Set up the crypto
$crypt = new Crypto;
$crypt->safety = 0;
$customer = $crypt->decrypt($custdetails->Name,'Customer');


$path = array(
array('name'=>'Customers','url'=>'index.php?option=viewCustomers'),
array('name'=>"$customer",'url'=>'index.php?option=viewCust&id='.BTMain::getVar('id'))
);

$notifications->setBreadcrumb($path);

?>

<h1>Credentials for <?php echo $customer; ?></h1>
<button id='AddCredBtnTop' onclick="window.location.href='index.php?option=addCred&cust=<?php echo BTMain::getVar('id'); ?>';" class='btn btn-primary'>Add Credential</button>
<br /><Br />
<input type="hidden" id="defaultInterval" value="<?php echo BTMain::getConf()->CredDisplay; ?>">
<table class='credTbl table table-hover' id='CredsTbl'>
<tr><th>Credential Type</th><th></th><th>Address</th><th>Username</th><th>Password</th><th></th><th></th></tr>

<?php



foreach ($customers as $customer){


?>

<tr id='CredDisp<?php echo $customer->id;?>'>
  <td>
    <?php echo $crypt->decrypt($customer->CredName,'CredType');?>
  </td>
  
  <td class="passViewNotif" onclick="getCreds('<?php echo $customer->id;?>');">
    <input type="hidden" id="PassCount<?php echo $customer->id;?>" value="<?php echo BTMain::getConf()->CredDisplay; ?>">
    <span class='retrievePassword' id='retrievePassword<?php echo $customer->id;?>'>Display Password</span>
  </td>

  <td>
    <span id='Address<?php echo $customer->id;?>' class='CredAddress'></span>
  </td>

  <td>
    <span id='UserName<?php echo $customer->id;?>' class='CredUserName'></span>
  </td>

  <td>
    <span id='Password<?php echo $customer->id;?>' class='CredPassword'></span>
  </td>


<td class='editicon' onclick="window.location.href = 'index.php?option=editCred&id=<?php echo $customer->id;?>'">
<i class="icon-pencil"></i>
</td>

  <td class='delicon' onclick="DelCred('<?php echo $customer->id;?>');">
  <i class="icon-remove"></i>
  </td>
</tr>

<?php


}

?>

</table>
<br />
<button id='AddCredBtnBottom' onclick="window.location.href='index.php?option=addCred&cust=<?php echo BTMain::getVar('id'); ?>';" class='btn btn-primary'>Add Credential</button>