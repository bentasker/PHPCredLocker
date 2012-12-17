<?php
/** Add Credential Type
*
* Copyright (C) 2012 B Tasker
* Released under GNU GPL V2
* See LICENSE
*
*/ 
defined('_CREDLOCK') or die;

global $notifications;


$db = new CredDB;
$crypt = new Crypto;
$crypt->safemode = 0;

$id = BTMain::getVar('id');
$credtype = $db->getCredType($id);
$credtypes = $db->getCredsbyType($id);


$notifications->setPageTitle("View By " .Lang::_('Credential Type'));

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


<table class="table table-hover" id="CredsByTypetbl">
<tr><th><?php echo Lang::_("Customer");?></th><th></th><th><?php echo Lang::_("Address");?></th>
<th><?php echo Lang::_("User");?></th><th><?php echo Lang::_("Password");?></th><th></th><th></th></tr>


<?php 
foreach ($creds as $key=>$value){
$k = explode("|",$key);
$key = $k[0];
?>


<tr id='CredDisp<?php echo $value;?>'>
  <td><?php echo $key;?></td>
  <td class="passViewNotif" onclick="getCreds('<?php echo $value;?>');">
  <input type="hidden" id="clickCount<?php echo $value;?>" value="0" disabled="disabled">
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
  <td class='editicon' onclick="window.location.href = 'index.php?option=editCred&id=<?php echo $value;?>';"><i class='icon-pencil'></i></td>
  <td class='delicon' onclick="DelCred('<?php echo $value;?>');"><i class='icon-remove'></i></td>

  <td id='CredPluginOutput<?php echo $value;?>'>

  </td>

</tr>


<?php } ?>
</table>
<button id='AddCredBtnTop' onclick="window.location.href='index.php?option=addCred';" class='btn btn-primary'>Add <?php echo Lang::_("Credential");?></button><br />