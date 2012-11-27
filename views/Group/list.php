<?php
/** List Groups
*
* Copyright (C) 2012 B Tasker
* Released under GNU GPL V2
* See LICENSE
*
*/ 
defined('_CREDLOCK') or die;
BTMain::checkSuperAdmin();

global $notifications;


$crypt = new Crypto;
$crypt->safemode = 0;



$auth = new AuthDB;





$grps = $auth->retrieveGroupNames();
$path = array(array('name'=>'Groups','url'=>'index.php?option=viewGrps'));

$notifications->setBreadcrumb($path);

foreach ($grps as $grp){

$plaintext = $crypt->decrypt($grp->Name,'Groups');
$groups[$plaintext] = $grp->id;

}
unset($crypt->keys);

ksort($groups);


?>
<h1>User Groups</h1>
<br >
<button class="btn btn-primary" onclick="window.location.href = 'index.php?option=addGrp';">Add Group</button><br /><br />

<table class="table table-hover">
<tr><th>Group</th><th></th><th></th></tr>
<?php
foreach ($groups as $key=>$value){
?>
<tr id='GroupDisp<?php echo $value;?>'>
  <td><?php echo $key;?></td>
  <td class='editicon' onclick="window.location.href='index.php?option=editGrp&id=<?php echo $value; ?>';"><i class="icon-pencil"></i></td>
  <td class='delicon' onclick='delGroup(<?php echo $value;?>);'><i class='icon-remove'></i></td>
</tr>
<?php } ?>

</table>

<button class="btn btn-primary" onclick="window.location.href = 'index.php?option=addGrp';">Add Group</button><br /><br />


