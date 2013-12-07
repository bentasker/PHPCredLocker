<?php
/** Edit Credential
*
* Copyright (C) 2012 B Tasker
* Released under GNU AGPL V3
* See LICENSE
*
*/ 

defined('_CREDLOCK') or die;


global $notifications;
$creds = new CredDB;
$id = BTMain::getVar('id');
$plg = new Plugins;



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
$comment = BTMain::getVar('frmComment');

if (BTMain::getUser()->PortalLogin != 1){
$hidden = BTMain::getVar('frmHidden');
}else{
$hidden = 0;
}
  
  if (!BTMain::getConnTypeSSL() || BTMain::getConf()->forceTLS){
	    $crypt = new Crypto;
	    $tlskey = BTMain::getsessVar('tls');
	    $cred = $crypt->xordstring(base64_decode($cred),$tlskey);
	    $address = $crypt->xordstring(base64_decode($address),$tlskey);
	    $uname = $crypt->xordstring(base64_decode($uname),$tlskey);
	    $comment = $crypt->xordstring(base64_decode($comment),$tlskey);
	 }



if ($id == "NOCHANGE"){ $id = false; }
if ($cred == "NOCHANGE"){ $cred = false; }
if ($credtype == "NOCHANGE"){ $credtype = false; }
if ($clicky == "NOCHANGE"){ $clicky = false; }
if ($group == "NOCHANGE"){ $group = false; }
if ($address == "NOCHANGE"){ $address = false; }
if ($uname == "NOCHANGE"){ $uname = false; }
if ($comment == 'NOCHANGE'){ $comment = false; }


  // Add the cred to the db
  if ($creds->editCred($id,$credtype,$cred,$comment, $clicky,$group,$address,$uname,$hidden)){
  // Success
  $notifications->setNotification("addCredSuccess");
      $data->cred->id = $id;
      $data->action = 'edit';
      echo $plg->loadPlugins("Creds",$data)->plgOutput;

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
foreach ($credtypes as $credt){

?>
<option value="<?php echo $credt->id;?>" 
<?php if ($credtype == $credt->id):?>
selected
<?php endif; ?>
><?php echo htmlspecialchars($crypt->decrypt($credt->Name,'CredType'));?></option>
<?php

}
unset($crypt);
?>
</select>

<label for="frmUser"><?php echo Lang::_("User");?></label><input type="text" name="frmUser" id="frmUser" value="NOCHANGE">

<label for="frmCredential"><?php echo Lang::_("Password");?></label><textarea id="frmCredential" name="frmCredential">NOCHANGE</textarea>
<a href="javascript: genPwd('frmCredential',10);">Generate Password</a>

<label for="frmComment"><?php echo Lang::_("Comment");?></label><input type="text" name="frmComment" id="frmComment" value="NOCHANGE">

<label for="frmAddress"><?php echo Lang::_("Address");?></label><input type="text" name="frmAddress" id="frmAddress" value="NOCHANGE">

<?php if (BTMain::getUser()->PortalLogin != 1): ?>
<label for="frmCredentialHidden">Hide from Customer</label><input type="checkbox" name="frmHidden" id="frmHidden" value="1" <?php if ($cred->hidden){ echo "checked"; }?>>
<?php endif; ?>

<?php

    // Call any configured plugins
         
     $data->action = 'editfrmnew';
     $data->cred->id = $id;    
     echo $plg->loadPlugins("Creds",$data)->plgOutput;

?>


<?php include 'lib/includes/groupSelection.php'; ?>

<input type="submit" class="btn btn-primary" value="Edit <?php echo Lang::_("Credential");?>">
</form>