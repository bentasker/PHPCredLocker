<?php
/** Add Credential Type
*
* Copyright (C) 2012 B Tasker
* Released under GNU GPL V2
* See LICENSE
*
*/ 
defined('_CREDLOCK') or die;
BTMain::checkSuperAdmin();

global $notifications;


$db = new CredDB;
$crypt = new Crypto;
$crypt->safemode = 0;

$id = BTMain::getVar('id');
$credtype = $db->getCredType($id);
$credtypes = $db->getCredsbyType($id);

if (!$id || !$credtypes){
$notifications->setNotification("NoSuchCustomer");
return;
}

$typename = $crypt->decrypt($credtype->Name,'CredType');




$path = array(array('name'=>'View By '. Lang::_("Credential Type"),'url'=>'#'),array('name'=>$typename, 'url'=>'index.php?option=viewByType&id='.$id));

$notifications->setBreadcrumb($path);



$credtypes = $db->getCredsbyType($id);

foreach ($credtypes as $cred){

$name = $crypt->decrypt($cred->Name,'Customer');

$creds[$name."|".$cred->id] = $cred->id;

}

ksort($creds);
?>

<h1>View by <?php echo Lang::_("Credential Type");?> <i><?php echo $typename;?></i></h1>


<input type="hidden" id="defaultInterval" value="<?php echo BTMain::getConf()->CredDisplay; ?>" disabled="true">
<br />
<button id='AddCredBtnTop' onclick="window.location.href='index.php?option=addCred';" class='btn btn-primary'>Add <?php echo Lang::_("Credential");?></button>
<br />


<table class="table table-hover">
<tr><th><?php echo Lang::_("Customer");?></th><th></th><th><?php echo Lang::_("Address");?></th>
<th><?php echo Lang::_("User");?></th><th><?php echo Lang::_("Password");?></th><th></th><th></th></tr>


<?php 
foreach ($creds as $key=>$value){
$k = explode("|",$key);
$key = $k[0];
?>


<tr id='CredType<?php echo $value;?>'>
  <td><?php echo $key;?></td>
  <td class="passViewNotif" onclick="getCreds('<?php echo $value;?>');">
    <input type="hidden" id="PassCount<?php echo $value;?>" value="<?php echo BTMain::getConf()->CredDisplay;?>">
    <span class='retrievePassword' id='retrievePassword<?php echo $value;?>'>Display Password</span></td>
  <td>
    <span id='Address<?php echo $value;?>' class='CredAddress'></span>
  </td>

  <td>
    <span id='UserName<?php echo $value;?>' class='CredUserName'></span>
  </td>

  <td>
    <span id='Password<?php echo $value;?>' class='CredPassword'></span>
  </td>
  <td class='editicon' onclick="window.location.href = 'index.php?option=option=editCred&id=<?php echo $value;?>';"><i class='icon-pencil'></i></td>
  <td class='delicon' onclick="DelCred('<?php echo $value;?>');"><i class='icon-remove'></i></td>
</tr>


<?php } ?>
</table>
<button id='AddCredBtnTop' onclick="window.location.href='index.php?option=addCred';" class='btn btn-primary'>Add <?php echo Lang::_("Credential");?></button><br />