<?php
/** Entry Point for Crypto key - Utilises browser caching so only have to send to the client once per key session
*
* Originally had to send twice because expiry was tied to the user's CredLocker session, 
* meaning Chrome requested twice (expiry of 0 epoch was sent by the login form). Firefox exhibited an odd behaviour and
* ignored the expiry date, so only requested once.
*
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
$expiry = BTMain::setSessVar('KeyExpiry');


// If the key is still valid and we know the browser has already retrieved it, just tell the browser to use the cache
if  ((time() > $expiry) && (isset($_COOKIE['PHPCredLocker']))&&($expiry)&&(!empty($tls))){
header("HTTP/1.1 304 Not Modified");
die;
}

// Would actually prefer not to include this in an unauthenticated session, but want to put key generation in the most logical place.
require_once 'lib/crypto.php';


// Set MIME-Header
header("Content-Type: text/javascript");
	
	$expiry = strtotime('+10 minutes');
	$seconds_to_cache = $expiry - time();
	$gmt = gmdate("D, d M Y H:i:s", $expiry) . " GMT";

	// Set caching headers
	header("Expires: $gmt");
	header("Pragma: cache");
	header("Cache-Control: max-age=$seconds_to_cache");

	// Add the key and it's expiry to the session
	BTMain::setSessVar('KeyExpiry',$expiry);
	BTMain::setSessVar('tls',Crypto::genxorekey());

?> 

function getKey(){ 
str = '<?php echo base64_encode(BTMain::getSessVar('tls'));?>';
return str; 
}