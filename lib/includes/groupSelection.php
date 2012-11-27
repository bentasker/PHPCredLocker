<?php
/** Single Group Selection Select Menu
*
* Copyright (C) 2012 B Tasker
* Released under GNU GPL V2
* See LICENSE
*
*
* Set the variable multiselect to 1 before calling this file to display checkboxes
*/

defined('_CREDLOCK') or die;




if (!isset($auth)){
$auth = new AuthDB;
}

if (!isset($crypt)){
$crypt = new Crypto;
}



$groups = $auth->retrieveGroupNames();

if ($multiselect != 1):
?> 
<label for="frmGroup">Group</label><select name="frmGroup" id="frmGroup">
<option value="0">All users</option>
<?php

foreach ($groups as $group){

$plaintext = $crypt->decrypt($group->Name,'Groups');

$str = "<option value='{$group->id}' ";

if ($group->id == $preselect){
$str .= "selected";
}

$str .= ">$plaintext</option>";

$grps[$plaintext] = $str;

}
unset($crypt->keys);
ksort($grps);
echo implode("\n",$grps);
unset($grps);
?>
</select>

<?php else:?>
<fieldset class='groupsMultiSelect'><legend>Select Groups</legend>
<?php
foreach ($groups as $group){

$plaintext = $crypt->decrypt($group->Name,'Groups');

$grps[$plaintext] = "<span class='checkbox'><input type='checkbox' name='frmGroup[]' value='{$group->id}'>$plaintext</span>";

}
unset($crypt->keys);
ksort($grps);
echo implode("\n",$grps);
unset($grps);
?>
</fieldset>
<?php endif;?>