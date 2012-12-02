<?php
/** Search Module
*
* Copyright (C) 2012 B Tasker
* Released under GNU GPL V2
* See LICENSE
*
*/
defined('_CREDLOCK') or die;

if (!BTMain::getUser()->name){ return; }


  $custs = new CustDB;
  $crdtypes=new CredDB;
  $crypt = new Crypto;
  $crypt->safety = 0;

?>

<form class="navbar-search" >
<input type="text" 
    class="search-query" 
    placeholder="Search"
    id='SearchBox'
    onfocus='checkExistingSearch(this.value,"SearchResBox");' 
    onblur='setTimeout("hideSearchDiv(\"SearchResBox\")",300);' 
    onkeyup="SearchTable(this.value,'SearchListing','SearchResBox',0);">
</form>

<div id="SearchResBox" style="display: none"></div>

<table id="SearchListing" style="display: none;">
<?php
		foreach ($custs->getAllCustomers() as $customer){
ob_start();
$plaintext = $crypt->decrypt($customer->Name,'Customer');
?>

<tr>
  <td>
      <div class='SearchResult' onclick="window.location.href='index.php?option=viewCust&id=<?php echo $customer->id; ?>';">
	    <?php echo Lang::_("Customer"); ?>: <?php echo $plaintext; ?>
      </div>
  </td><td>Customer:</td>
</tr>

<?php
$tbl[$plaintext] = ob_get_clean();

}


ksort($tbl);
echo implode("\n",$tbl);

	foreach ($crdtypes->getCredTypes() as $credtype){
 ob_start();
 $plaintext = $crypt->decrypt($credtype->Name,'CredType');

?>

<tr>
  <td>
      <div class='SearchResult' onclick="window.location.href='index.php?option=viewByType&id=<?php echo $credtype->id; ?>';">
	    <?php echo Lang::_("Credential Type"); ?>: <?php echo $plaintext; ?>
      </div>
  </td><td>Credential Type</td>
</tr>

<?php
			  
$cred[$plaintext] = ob_get_clean();

}

ksort($cred);
echo implode("\n",$cred);
?>
</table>
<script type="text/javascript">
positionResults("SearchBox","SearchResBox");
</script>