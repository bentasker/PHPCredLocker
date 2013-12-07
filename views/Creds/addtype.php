<?php
/** Add Credential Type
*
* Copyright (C) 2012 B Tasker
* Released under GNU AGPL V3
* See LICENSE
*
*/ 
defined('_CREDLOCK') or die;
BTMain::checkSuperAdmin();

global $notifications;
$plugins = new Plugins;

$notifications->setPageTitle("Add " . Lang::_('Credential Type'));
$notifications->requireScript('admin');

if (BTMain::getVar('AddCredType')){
$db = new CredDB;
$crypt = new Crypto;


  if ($newid = $db->AddCredType(BTMain::getVar('frmName'))){
    $notifications->setNotification("addCredTypeSuccess");
    ?><script type="text/javascript">if (document.getElementById('CredTypeNeedsAdding')){ document.getElementById('CredTypeNeedsAdding').style.display = 'none';}</script><?php
   
   
    }else{
    $notifications->setNotification("addCredTypeFail");


  }





$submitted = 1;
include('lib/includes/gatherEntropy.php');
unset($submitted);
 
$klength = BTMain::getVar('kLength');

if(!$crypt->addKey($newkey,$newid,$klength)){


$notifications->setNotification("KeyGenerationFailed");
}

    $data->action = 'added';
    $data->newid = $newid;
    echo $plugins->loadPlugins("CredTypes",$data)->plgOutput;


}


$path = array(array('name'=>Lang::_("Cred Types"),'url'=>'index.php?option=listCredTypes'),
array('name'=>'Add '.Lang::_("Credential Type"),'url'=>'index.php?option=addCredType'));

$notifications->setBreadcrumb($path);

?>
<h1>Add <?php echo Lang::_("Credential Type");?></h1>

<form method="POST" onsubmit="return checkAddCredType();">
<input type="hidden" name="option" value="addCredType">
<input type="hidden" name="AddCredType" value="1">

<label for="frmName"><?php echo Lang::_("Credential Type");?></label>
<input type="text" id="frmName" name="frmName">


<?php
include('lib/includes/gatherEntropy.php');




$data->action = 'add';
echo $plugins->loadPlugins("CredTypes",$data)->plgOutput;



?>


<input type="submit" class="btn btn-primary" value="Add <?php echo Lang::_("Credential Type");?>">
</form>