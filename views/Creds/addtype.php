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

if (BTMain::getVar('AddCredType')){
$db = new CredDB;



  if ($db->AddCredType(BTMain::getVar('frmName'))){
    $notifications->setNotification("addCredTypeSuccess");
    ?><script type="text/javascript">if (document.getElementById('CredTypeNeedsAdding')){ document.getElementById('CredTypeNeedsAdding').style.display = 'none';}</script><?php
    }else{
    $notifications->setNotification("addCredTypeFail");


  }
}


$path = array(array('name'=>'Credential Types','url'=>'index.php?option=listCredTypes'),array('name'=>'Add Credential Type','url'=>'index.php?option=addCredType'));

$notifications->setBreadcrumb($path);

?>
<h1>Add Credential Type</h1>

<form method="POST">
<input type="hidden" name="option" value="addCredType">
<input type="hidden" name="AddCredType" value="1">

<label for="frmName">Credential Type</label>
<input type="text" id="frmName" name="frmName">

<input type="submit" class="btn btn-primary" value="Add Credential Type">
</form>