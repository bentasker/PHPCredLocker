<?php
/** Edit Credential
*
* Copyright (C) 2012 B Tasker
* Released under GNU GPL V2
* See LICENSE
*
*/ 

defined('_CREDLOCK') or die;


global $notifications;
$creds = new CredDB;
$id = BTMain::getVar('id');



$notifications->setPageTitle("Edit " .Lang::_('Credential'));


if (BTMain::getVar('editCredSubmitted')){

$id = BTMain::getVar('id');
$credtype = BTMain::getVar('FrmCredType');
$cred = BTMain::getVar('frmCredential');
$clicky = BTMain::getVar('frmClicky');
$group = BTMain::getVar('frmGroup');
$address = BTMain::getVar('frmAddress');
$uname = BTMain::getVar('frmUser');
$group = BTMain::getVar('frmGroup');

if ($id == "NOCHANGE"){ $id = false; }
if ($cred == "NOCHANGE"){ $cred = false; }
if ($credtype == "NOCHANGE"){ $credtype = false; }
if ($clicky == "NOCHANGE"){ $clicky = false; }
if ($group == "NOCHANGE"){ $group = false; }
if ($address == "NOCHANGE"){ $address = false; }
if ($uname == "NOCHANGE"){ $uname = false; }


  // Add the cred to the db
  if ($creds->editCred($id,$credtype,$cred,$clicky,$group,$address,$uname)){
  // Success
  $notifications->setNotification("addCredSuccess");
  }else{
  $notifications->setNotification("addCredFail");
  }





}

$cred = $creds->FetchCredential($id);
$preselect = $cred->Group;

  if (!$cred){
  // Invalid credential
  $notifications->setNotification("NoSuchCustomer");
  return;

  }





$path = array(array('name'=>Lang::_("Credentials"),'url'=>'#'),array('name'=>'Edit','url'=>'index.php?option=editCred&id='.$id));

$notifications->setBreadcrumb($path);


$credtype = $cred->CredType;

$auth = new AuthDB;
$credtypes = $creds->getCredTypes();




$cust = BTMain::getVar('cust');
$custs = new CustDB;
$customers = $custs->getAllCustomers();

$crypt = new Crypto;
$crypt->safety = 0;


?>
<h1>Edit <?php echo Lang::_("Credential");?></h1>

<i>Leave a field blank to delete the <?php echo Lang::_("Credential");?> element</i>

<form method="POST" onsubmit="return checkEditCred();">

<input type="hidden" name="option" value="editCred">
<input type="hidden" name="editCredSubmitted" value="1">
<input type="hidden" name="frmClicky" id="frmClicky" value="NOCHANGE">
<input type="hidden" name="id" value="<?php echo $id; ?>">



<label for='FrmCredType'><?php echo Lang::_("Credential Type");?></label><select id="FrmCredType" name="FrmCredType" readonly='readonly'>
<?php 
foreach ($credtypes as $cred){

?>
<option value="<?php echo $cred->id;?>" 
<?php if ($credtype == $cred->id):?>
selected
<?php endif; ?>
><?php echo $crypt->decrypt($cred->Name,'CredType');?></option>
<?php

}
unset($crypt);
?>
</select>

<label for="frmUser"><?php echo Lang::_("User");?></label><input type="text" name="frmUser" id="frmUser" value="NOCHANGE">

<label for="frmCredential"><?php echo Lang::_("Password");?></label><textarea id="frmCredential" name="frmCredential">NOCHANGE</textarea>

<label for="frmAddress"><?php echo Lang::_("Address");?></label><input type="text" name="frmAddress" id="frmAddress" value="NOCHANGE">



<?php include 'lib/includes/groupSelection.php'; ?>

<input type="submit" class="btn btn-primary" value="Edit <?php echo Lang::_("Credential");?>">
</form>