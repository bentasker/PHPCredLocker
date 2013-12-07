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

$crypt = new Crypto;
$plugins = new Plugins;
$id = BTMain::getVar('id');
$db = new CredDB;
$notifications->setPageTitle("Edit " .Lang::_('Credential Type'));

if (BTMain::getVar('editCredType')){




  if ($db->editCredType(BTMain::getVar('id'),BTMain::getVar('frmName'))){
    $notifications->setNotification("addCredTypeSuccess");
    ?><script type="text/javascript">if (document.getElementById('CredTypeNeedsAdding')){ document.getElementById('CredTypeNeedsAdding').style.display = 'none';}</script><?php
    
    $data->action = 'edited';
    $data->id = BTMain::getVar('id');
    echo $plugins->loadPlugins("CredTypes",$data)->plgOutput;


    }else{
    $notifications->setNotification("addCredTypeFail");


  }
}


$path = array(array('name'=>Lang::_("Cred Types"),'url'=>'index.php?option=listCredTypes'),array('name'=>'Edit','url'=>'index.php?option=editCredType&id='.$id));
$notifications->setBreadcrumb($path);

$cred = $db->getCredType($id);



?>
<h1>Edit <?php echo Lang::_("Credential Type");?></h1>

<form method="POST">
<input type="hidden" name="option" value="editCredType">
<input type="hidden" name="id" value="<?php echo $id; ?>">
<input type="hidden" name="editCredType" value="1">

<label for="frmName"><?php echo Lang::_("Credential Type");?></label>
<input type="text" id="frmName" name="frmName" value='<?php echo $crypt->decrypt($cred->Name,'CredType');?>'>


<?php


    $data->action = 'edit';
    $data->id = $id;
    echo $plugins->loadPlugins("CredTypes",$data)->plgOutput;

?>

<input type="submit" class="btn btn-primary" value="Edit <?php echo Lang::_("Credential Type");?>">
</form>