<?php
/** Search stored Credentials for a specific password - Super admins only
*
* Copyright (c) 2013 B Tasker
* Released under GNU AGPL V3
* See LICENSE
*
*/ 


defined('_CREDLOCK') or die;
BTMain::checkSuperAdmin();
global $notifications;
$notifications->RequireScript('admin');

$notifications->setPageTitle("Search Credential Values");
$creds = new CredDB;


$credtypes = $creds->getCredTypes();

$path = array(array('name'=>'Credentials','url'=>''),array('name'=>'Search','url'=>'index.php?option=searchCreds'));

$notifications->setBreadcrumb($path);
$frmToken = ProgAuth::generateFormToken();

?>
<h1>Search Credential Values</h1>

<form method="POST" id="searchCredsForm" onsubmit="return submitSearchCred();">
<input type="hidden" name="option" value="searchCreds">
<input type="hidden" name="searchCredSubmitted" value="1">
<input type="hidden" name="FormToken" value="<?php echo $frmToken; ?>">


<label for="frmPass">Password Confirm</label><input type="password" name="frmPass" id="frmPass">

<input type="submit" class="btn btn-primary" value="Search Credentials">

<?php foreach ($credtypes as $credtype): ?>
<span style="display: none" class="CredTypeID"><?php echo $credtype->id;?></span>
<?php endforeach;?>

</form>


<div id="ResultsTable"></div>
