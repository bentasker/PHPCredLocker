<?php
/** System Information Page
*
* Copyright (C) 2012 B Tasker
* Released under GNU AGPL V3
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

<i><a href="http://www.bentasker.co.uk/documentation/70-phpcredlocker/163-phpcredlocker" target=_blank>PHPCredLocker</a> is Copyright &copy; <a href="http://www.bentasker.co.uk" target=_blank>Ben Tasker</a> and released under 
the <A href="http://www.gnu.org/licenses/agpl-3.0.html" target=_blank>GNU AGPL V3</a>

<p>PHPCredLocker is a PHP based Credential Locker (AKA Password Vault) with a strong focus on security.</p>
<p>You can view and download the source code on <a href="https://github.com/bentasker/PHPCredLocker" target=_blank>GitHub</a>

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

<tr><th>Connection Type</th><td class="test<?php if (BTMain::getConnTypeSSL()){
echo 'Pass">SSL Enabled';
}else{
echo 'Fail">SSL Not Enabled';
}
?>
</td></tr>

</table>

<br />
