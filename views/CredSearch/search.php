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

<p></p>
<p>Use this page to identify all records for a given password - whether to ensure passwords aren't being re-used or because you know one has been compromised</p>
<p>Double-blind passwords can not be included in the search (as the system doesn't have a decryption key for them).</p>

<label for="frmPass"></label><input type="password" name="frmPass" id="frmPass">

<input type="submit" class="btn btn-primary" value="Search Credentials">

<div id='LoadingIndicator' style="display:none" class="alert-info">Searching.....</div>

<?php foreach ($credtypes as $credtype): ?>
<span style="display: none" class="CredTypeID"><?php echo $credtype->id;?></span>
<span style="display: none" class="CredTypeIndicator" id="CredTypeID<?php echo $credtype->id;?>"></span>
<?php endforeach;?>

</form>


<div id="ResultsTable"></div>
