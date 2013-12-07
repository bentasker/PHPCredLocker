<?php
/** Customer Details View
*
* Copyright (C) 2012 B Tasker
* Released under GNU AGPL V3
* See LICENSE
*
*/
defined('_CREDLOCK') or die;
global $notifications;
$custom = new CustDB;
$custom->connreuse = 1;
$portallogin = BTMain::getUser()->PortalLogin;


if ($portallogin != 1){
// Get the customer details
$custdetails = $custom->getCustomerDetail(BTMain::getVar('id'));

$notifications->setPageTitle("View ".Lang::_('Customer'));
  if (!$custdetails){
  // Invalid customer
  $notifications->setNotification("NoSuchCustomer");
  return;

  }

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




<?php if ($portallogin != 1): ?>
<h1>Credentials for <?php echo $customer; ?></h1>


<div class='viewButtons'>

<button id='EditCustBtnTop' onclick="window.location.href='index.php?option=EditCustomer&id=<?php echo htmlspecialchars(BTMain::getVar('id')); ?>';" class='btn btn-primary'>Edit <?php echo Lang::_('Customer');?></button>
<button id='AddCredBtnTop' onclick="window.location.href='index.php?option=addCred&cust=<?php echo htmlspecialchars(BTMain::getVar('id')); ?>';" class='btn btn-primary'>Add Credential</button>

</div>

<?php endif; ?>

<input type="hidden" id="defaultInterval" value="<?php echo BTMain::getConf()->CredDisplay; ?>">
<table class='credTbl table table-hover' id='CredsTbl'>
<tr><th><span class='DisPwdText'>Credential </span>Type</th><th></th>
<th><span class='DisPwdText'>Address</span><span class='DisPwdTextMob'>URL</span></th>
<th>User<span class='DisPwdText'>name</span></th>
<th><span class='DisPwdText'>Password</span><span class='DisPwdTextMob'>Pwd</span></th>
<th></th><th></th><th></th></tr>

<?php
$x = 0;


foreach ($customers as $customer){
$x++;
ob_start();
$cname = $crypt->decrypt($customer->CredName,'CredType');
$comment = $crypt->decrypt($customer->comment,'Cre'.$customer->CredType);
?>

<tr class="CredDisp" id='CredDisp<?php echo $customer->id;?>'>
  <td <?php if (!empty($comment)):?>title="<?php echo htmlspecialchars($comment);?>"<?php endif;?>>
    <?php echo $cname;?>
  </td>


  <td class="passViewNotif" onclick="getCreds('<?php echo $customer->id;?>');">
  <input type="hidden" id="clickCount<?php echo $customer->id;?>" value="0" disabled="disabled">
    <input type="hidden" id="PassCount<?php echo $customer->id;?>" value="<?php echo BTMain::getConf()->CredDisplay; ?>">
    <span class='retrievePassword' id='retrievePassword<?php echo $customer->id;?>'>Display<span class='DisPwdText'> 

  <?php if (($portallogin != 1) || ($customer->hidden !=1)): ?>
      Password
  <?php else: ?>
      Username <input type="hidden" disabled="disabled" id="credHidden<?php echo $customer->id; ?>">
  <?php endif; ?>
</span></span>
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
 <?php if (($portallogin != 1) || ($customer->hidden !=1)): ?><i class="icon-pencil"></i><?php endif; ?>
</td>

  <td class='delicon' onclick="DelCred('<?php echo $customer->id;?>');">
  <?php if ($portallogin != 1): ?><i class="icon-remove"></i><?php endif; ?>
  </td>

  <td id='CredPluginOutput<?php echo $customer->id;?>' class="CredPluginOutput">

  </td>

</tr>

<?php

if (!isset($custs[$cname])){
$custs[$cname] = ob_get_clean();
}else{
$custs[$cname."-".$x] = ob_get_clean();

}


}
ksort($custs);
echo implode("\n",$custs);

?>

</table>
<br />


<?php if ($portallogin != 1): ?>

<div class='viewButtons'>

<button id='EditCustBtnBottom' onclick="window.location.href='index.php?option=EditCustomer&id=<?php echo htmlspecialchars(BTMain::getVar('id')); ?>';" class='btn btn-primary'>Edit <?php echo Lang::_('Customer');?></button>
<button id='AddCredBtnBottom' onclick="window.location.href='index.php?option=addCred&cust=<?php echo htmlspecialchars(BTMain::getVar('id')); ?>';" class='btn btn-primary'>Add Credential</button>

</div>

<?php endif; ?>


<script type="text/javascript">
$('#CredsTbl *').tooltip({track: true, fade: 250});
</script>

