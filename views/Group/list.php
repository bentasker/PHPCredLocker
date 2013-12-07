<?php
/** List Groups
*
* Copyright (C) 2012 B Tasker
* Released under GNU AGPL V3
* See LICENSE
*
*/ 
defined('_CREDLOCK') or die;
BTMain::checkSuperAdmin();

global $notifications;
$notifications->RequireScript('admin');

$crypt = new Crypto;
$crypt->safemode = 0;



$auth = new AuthDB;


$notifications->setPageTitle("List ".Lang::_('User Groups'));


$grps = $auth->retrieveGroupNames();
$path = array(array('name'=>'Groups','url'=>'index.php?option=viewGrps'));

$notifications->setBreadcrumb($path);

foreach ($grps as $grp){

$plaintext = $crypt->decrypt($grp->Name,'Groups');
$groups[$plaintext] = $grp->id;

}


ksort($groups);


?>
<h1>User Groups</h1>
<br >
<button class="btn btn-primary" onclick="window.location.href = 'index.php?option=addGrp';">Add Group</button><br /><br />

<table id="GroupsTbl" class="table table-hover">
<tr><th>Group</th><th></th><th></th></tr>
<?php
foreach ($groups as $key=>$value){
?>
<tr id='GroupDisp<?php echo $value;?>'>
  <td><?php echo $key;?></td>
  <td class='editicon' title="Edit this group" onclick="window.location.href='index.php?option=editGrp&id=<?php echo $value; ?>';"><i class="icon-pencil"></i></td>
  <td class='delicon' title="Delete this group" onclick='delGroup(<?php echo $value;?>);'><i class='icon-remove'></i></td>
</tr>
<?php } ?>

</table>

<button class="btn btn-primary" onclick="window.location.href = 'index.php?option=addGrp';">Add Group</button><br /><br />


<script type="text/javascript">$('#GroupsTbl *').tooltip({track: true, fade: 250});</script>