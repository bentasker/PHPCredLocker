<?php
/** System Information Page
*
* Copyright (C) 2012 B Tasker
* Released under GNU GPL V2
* See LICENSE
*
*/ 
defined('_CREDLOCK') or die;
BTMain::checkSuperAdmin();


global $notifications;

$path = array(array('name'=>'System Information','url'=>'index.php?option=About'));

$notifications->setBreadcrumb($path);


$notifications->setPageTitle("System Information");


?>
<h2>PHPCredLocker Version <?php echo BTMain::getSoftVersion(); ?></h2>

<i>PHPCredLocker is Copyright &copy; <a href="http://www.bentasker.co.uk" target=_blank>Ben Tasker</a> and released under 
the <A href="http://www.gnu.org/licenses/gpl-2.0.html" target=_blank>GNU GPL V2</a></i>


<br /><br />
<h2>Cryptography</h2>
<table class="table table-hover">
<tr><Th>OpenSSL</th><td class="test<?php

if (function_exists("openssl_encrypt")){
echo 'Pass">Available';
}else{
echo 'Fail">Not Available';
}
?></td></tr>


<tr><Th>MCrypt</th><td class="test<?php

if (function_exists("mcrypt_encrypt")){
echo 'Pass">Available';
}else{
echo 'Fail">Not Available';
}
?></td></tr>

</table>

<br />
<a href="http://www.bentasker.co.uk/documentation/70-phpcredlocker/163-phpcredlocker" target=_blank>Project homepage</a>