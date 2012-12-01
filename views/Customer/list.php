<?php
/** Customer Details View
*
* Copyright (C) 2012 B Tasker
* Released under GNU GPL V2
* See LICENSE
*
*/
defined('_CREDLOCK') or die;
$custom = new CustDB;


$customers = $custom->getAllCustomers();
$auth = new AuthDB;
$auth->connreuse = 1;

$crypt = new Crypto;
$crypt->safety = 0;


global $notifications;

$path = array(array('name'=>'Customers','url'=>'index.php?option=viewCustomers'));

$notifications->setBreadcrumb($path);



?>

<h1>Customers</h1>
<button onclick="window.location.href='index.php?option=addCustomer';" class='btn btn-primary'>Add Customer</button>
<br /><Br />
<table class='credTbl table table-hover' id='CustomerTbl'>
<tr><th>Customer</th><th>Group</th><th></th><th></th></tr>

<?php



foreach ($customers as $customer){


?>

<tr id='CustDisp<?php echo $customer->id;?>'>
  <td>
    <a href='index.php?option=viewCust&id=<?php echo $customer->id;?>' title="View Credentials"><?php echo $crypt->decrypt($customer->Name,'Customer');?></a>
  </td>
  
<td>
      <?php

      switch ($customer->Group){

	      case '0':
	      echo "All Users";
	      break;

	      case '-1':
	      echo "Super Users";
	      break;

	      default:
	      $grp = $auth->retrieveGrpById($cutomer->Group);
	      echo $crypt->decrypt($grp->Name,'Group');
	}
?>
</td>


<td onclick="window.location.href = 'index.php?option=EditCustomer&id=<?php echo $customer->id; ?>';" class='editicon'><i class="icon-pencil"></i></td>

  <td class='delicon' onclick="DelCust('<?php echo $customer->id;?>');">
  <i class="icon-remove"></i>
  </td>
</tr>

<?php


}

?>

</table>
<br />
<button onclick="window.location.href='index.php?option=addCustomer';" class='btn btn-primary'>Add Customer</button>