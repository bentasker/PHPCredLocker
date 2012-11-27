<?php
/** Edit Customer
*
* Copyright (C) 2012 B Tasker
* Released under GNU GPL V2
* See LICENSE
*
*/
defined('_CREDLOCK') or die;
global $notifications;
$id = BTMain::getVar('id');
$db = new CustDB;

if (BTMain::getVar('EditCustSubmitted')){



if ($db->editCustomer(BTMain::getVar('id'),BTMain::getVar('FrmName'),BTMain::getVar('frmGroup'),BTMain::getVar('FrmconName'),BTMain::getVar('FrmSurname'),BTMain::getVar('FrmEmail'))){


$notifications->setNotification("EditCustSuccess");

}else{
$notifications->setNotification("EditCustFail");

}






}





$customer = $db->getCustomerDetail($id);

if (!$customer){

$notifications->setNotification("NoSuchCustomer");
return;

}
$crypt = new Crypto;
$crypt->safemode = 0;

$custname = $crypt->decrypt($customer->Name,'Customer');


$path = array(array('name'=>'Customers','url'=>'index.php?option=viewCustomers'),array('name'=>$custname,'url'=>'index.php?option=viewCust&id='.$id),array('name'=>'Edit','url'=>'index.php?option=EditCustomer&id='.$id));

$notifications->setBreadcrumb($path);






$preselect = $customer->Group;
?>
<h1>Edit Customer</h1>
<form method="POST">
<input type="hidden" name="option" value="EditCustomer">
<input type="hidden" name="EditCustSubmitted" value="1">
<input type="hidden" name="id" value="<?php echo $id; ?>">

<label for="FrmName">Company Name</label><input type="text" name="FrmName" id="FrmName" value="<?php echo $custname;?>">
<label for="FrmconName">Contact First Name</label><input type="text" name="FrmconName" id="FrmconName" value="<?php echo $crypt->decrypt($customer->ContactName,'Customer');?>">
<label for="FrmSurname">Surname</label><input type="text" name="FrmSurname" id="FrmSurname" value="<?php echo $crypt->decrypt($customer->ContactSurname,'Customer');?>">
<label for="FrmEmail">Email</label><input type="text" name="FrmEmail" id="FrmEmail" value="<?php echo $crypt->decrypt($customer->Email,'Customer');?>">

<?php include 'lib/includes/groupSelection.php'; ?>

<input type="submit" class="btn btn-primary" value="Edit Customer">
</form>


<?php
unset($crypt->keys);
?>