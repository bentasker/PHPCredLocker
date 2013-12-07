<?php
/** Add Credential
*
* Copyright (C) 2012 B Tasker
* Released under GNU AGPL V3
* See LICENSE
*
*/ 
defined('_CREDLOCK') or die;



$creds = new CredDB;
global $notifications;
$notifications->setPageTitle("Add ". Lang::_('Credential'));
$plg = new Plugins;

if (BTMain::getVar('addCredSubmitted')){

  $cred = BTMain::getVar('frmCredential');
  $addr = BTMain::getVar('frmAddress');
  $user = BTMain::getVar('frmUser');
  $hidden = BTMain::getVar('frmHidden');
  $comment = BTMain::getVar('frmComment');
  
  if (!BTMain::getConnTypeSSL() || BTMain::getConf()->forceTLS){
	    $crypt = new Crypto;
	    $tlskey = BTMain::getsessVar('tls');
	    $cred = $crypt->xordstring(base64_decode($cred),$tlskey);
	    $addr = $crypt->xordstring(base64_decode($addr),$tlskey);
	    $user = $crypt->xordstring(base64_decode($user),$tlskey);
	    $comment = $crypt->xordstring(base64_decode($comment),$tlskey);
	 }


  $newcred = $creds->addCred(BTMain::getVar('cust'),BTMain::getVar('FrmCredType'),$cred,$comment,BTMain::getVar('frmClicky'),BTMain::getVar('frmGroup'),$addr,$user,$hidden);
  // Add the cred to the db
  if ($newcred){
  // Success
  $notifications->setNotification("addCredSuccess");

     $data->cred->id = $newcred;
     $data->action = 'edit';

    
    echo $plg->loadPlugins("Creds",$data)->plgOutput;


  }else{
  $notifications->setNotification("addCredFail");
  }





}

$path = array(array('name'=>Lang::_("Credentials"),'url'=>'#'),array('name'=>'Add','url'=>'index.php?option=addCred&cust='.BTMain::getVar('cust')));

$notifications->setBreadcrumb($path);

$auth = new AuthDB;
$credtypes = $creds->getCredTypes();
$cust = BTMain::getVar('cust');
$custs = new CustDB;
$customers = $custs->getAllCustomers();



?>

<form method="POST" onsubmit="return checkNewCred();">

<input type="hidden" name="option" value="addCred">
<input type="hidden" name="addCredSubmitted" value="1">
<input type="hidden" name="frmClicky" id="frmClicky" value="0">




<label for='FrmCustomer'><?php echo Lang::_("Customer");?></label><select name="cust" id="FrmCustomer">

<?php
$crypt = new Crypto;
$crypt->safety = 0;

foreach ($customers as $customer){

$plaintext = $crypt->decrypt($customer->Name,'Customer');

$custdets[$plaintext] = "<option value='{$customer->id}'" ;

    if ($customer->id == $cust){
    $custdets[$plaintext] .= " selected";
    }

$custdets[$plaintext] .= ">$plaintext</option>";

}

ksort($cust);
echo implode("\n",$custdets);
?>


</select>

<label for='FrmCredType'><?php echo Lang::_("Credential Type");?></label><select id="FrmCredType" name="FrmCredType">
<?php 
      foreach ($credtypes as $cred){

      ?>
	  <option value="<?php echo $cred->id;?>"><?php echo $crypt->decrypt($cred->Name,'CredType');?></option>
      <?php

      }
      unset($crypt);
  ?>
</select>

<label for="frmUser"><?php echo Lang::_("User");?></label><input type="text" name="frmUser" id="frmUser">

<label for="frmCredential"><?php echo Lang::_("Password");?></label><textarea id="frmCredential" name="frmCredential"></textarea>
<a href="javascript: genPwd('frmCredential',10);">Generate Password</a>

<label for="frmComment"><?php echo Lang::_("Comment");?></label><input type="text" name="frmComment" id="frmComment">

<label for="frmAddress"><?php echo Lang::_("Address");?></label><input type="text" name="frmAddress" id="frmAddress">


<label for="frmCredentialHidden">Hide from Customer</label><input type="checkbox" name="frmHidden" id="frmHidden" value="1">
<?php include 'lib/includes/groupSelection.php'; ?>


<?php

    // Call any configured plugins
         
     $data->action = 'editfrmnew';

    
    echo $plg->loadPlugins("Creds",$data)->plgOutput;


?>

<input type="submit" class="btn btn-primary" value="Add <?php echo Lang::_("Credential");?>">
</form>