<?php
/** Edit Group
*
* Copyright (C) 2012 B Tasker
* Released under GNU GPL V2
* See LICENSE
*
*/ 
defined('_CREDLOCK') or die;
BTMain::checkSuperAdmin();

global $notifications;
$notifications->RequireScript('admin');
$id = BTMain::getVar('id');
$auth = new AuthDB;
$crypt = new Crypto;
$notifications->setPageTitle("Edit ".Lang::_('UserGroup'));


if (BTMain::getVar('GrpEditSubmitted')){


if ($auth->editGroup(BTMain::getVar('id'),BTMain::getVar('frmName'))){
$notifications->setNotification("addGroupSuccess");

}else{

$notifications->setNotification("addGroupFail");
}





}



$grp = $auth->retrieveGrpById($id);

  if (!$grp){
  // Invalid group
  $notifications->setNotification("NoSuchCustomer");
  return;
  }


$name = $crypt->decrypt($grp->Name,'Groups');


$path = array(array('name'=>'Groups','url'=>'index.php?option=viewGrps'),array('name'=>'Edit Group','url'=>'index.php?option=editGrp&id='.$id));

$notifications->setBreadcrumb($path);



?>
<h1>Edit Group</h1>
<form method="POST">
<input type="hidden" name="option" value="editGrp" >
<input type="hidden" name="id" value="<?php echo $id; ?>">
<input type="hidden" name="GrpEditSubmitted" value="1">
<label for="frmName">Group Name</label><input type="text" id="frmName" name="frmName" value="<?php echo $name; ?>">

<input type="submit" class="btn btn-primary" value="Edit Group">
</form>