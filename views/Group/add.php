<?php
/** Add Group
*
* Copyright (C) 2012 B Tasker
* Released under GNU GPL V2
* See LICENSE
*
*/ 
defined('_CREDLOCK') or die;
BTMain::checkSuperAdmin();

global $notifications;
$notifications->setPageTitle("Add ".Lang::_('UserGroup'));

if (BTMain::getVar('GrpAddSubmitted')){

$auth = new AuthDB;
if ($auth->addGroup(BTMain::getVar('frmName'))){
$notifications->setNotification("addGroupSuccess");

}else{

$notifications->setNotification("addGroupFail");
}





}
$path = array(array('name'=>'Groups','url'=>'index.php?option=viewGrps'),array('name'=>'Add Group','url'=>'index.php?option=addGrp'));

$notifications->setBreadcrumb($path);



?>
<h1>Add User Group</h1>
<form method="POST">
<input type="hidden" name="option" value="addGrp">
<input type="hidden" name="GrpAddSubmitted" value="1">
<label for="frmName">Group Name</label><input type="text" id="frmName" name="frmName">

<input type="submit" class="btn btn-primary" value="Add Group">
</form>