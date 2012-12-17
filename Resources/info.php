<?php
/** API Entry Point
*
* Copyright (C) 2012 B Tasker
* Released under GNU GPL V2
* See LICENSE
*
*/

session_start();
define('_CREDLOCK',1);

// Change the current working dir
chdir(dirname(__FILE__)."/../");

// Load the framework
require_once 'lib/Framework/main.php';

$tls = BTMain::getSessVar('tls');
if ((isset($_COOKIE['PHPCredLocker']))&&(BTMain::getSessVar("TokenSent") == 1)&&(!empty($tls))){
header("HTTP/1.1 304 Not Modified");
die;
}



header("Content-Type: text/javascript");

if (isset($_COOKIE['PHPCredLocker'])){
BTMain::setSessVar('TokenSent',1);
$expiry = BTMain::getSessVar("Expiry");

$seconds_to_cache = $expiry - time();

$ts = gmdate("D, d M Y H:i:s", $expiry) . " GMT";
header("Expires: $ts");
header("Pragma: cache");
header("Cache-Control: max-age=$seconds_to_cache");
}else{

$frmToken = sha1(mt_rand(0,90000) . chr(mt_rand(32,254)) . chr(mt_rand(32,254)) . date() . chr(mt_rand(32,254)) . mt_rand(0,999999));
BTMain::setSessVar('tls',$frmToken);
}

?> 

function getKey(){ 
str = '<?php echo BTMain::getSessVar('tls');?>';
return str; 
}