<?php
/** Entry Point for Crypto key - Utilises browser caching so only have to send to the client once per key session
*
* Originally had to send twice because expiry was tied to the user's CredLocker session, 
* meaning Chrome requested twice (expiry of 0 epoch was sent by the login form). Firefox exhibited an odd behaviour and
* ignored the expiry date, so only requested once.
*
*
* Although not currently implemented, this file will eventually define the following
*
*  - Encryption key for received data
*  - Encryption key for sent data
*  - Delimiter to use for API requests
*  - API terminology to use (allowing us to replace known calls such as retCred with a random string)
* 
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
if  ((time() > $expiry) && (isset($_COOKIE['PHPCredLocker'])) && ($_COOKIE['PHPCredLockerKeySet'] == 1) && ($expiry) && (!empty($tls))){
header("HTTP/1.1 304 Not Modified");
die;
}

// Would actually prefer not to include this in an unauthenticated session, but want to put key generation in the most logical place.
require_once 'lib/crypto.php';



// Create an array containing the supported API terms so we can use them in string Obfuscation
      $apiterms = array(
			"retCred",
			"checkSess",
			"delCred",
			"delUser",
			"delCredType",
			"delCust",
			"delGroup"
				    );



    foreach ($apiterms as $term){

	  $x = 0;
	  $new = '';


    while ($x <= 11){

	      $new .= chr(mt_rand(97,122));

	  if (($x == 11) && in_array($new,$usedterms)){
	      // Make sure the termcode isn't already in used, if so, start again
	      $x = 0;
	      $new = '';
	      }
      $x++;
      }	

      $usedterms[] = $new;
      $terms[$new] = $term;
      }



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
	BTMain::setSessVar('apiterms',$terms);

	// By setting a cookie, we provide an easy mechanism for allowing the API to force a key refresh
	setcookie("PHPCredLockerKeySet", 1, $expiry, dirname($_SERVER["REQUEST_URI"]), $_SERVER['HTTP_HOST'], BTMain::getConf()->forceSSL);

?> 
function getKey(){ return '<?php echo base64_encode(BTMain::getSessVar('tls'));?>'; }
function getDelimiter(){ return "|..|";}
function getTerminology(a){ var b,<?php foreach ($terms as $key=>$value){ echo "VarNme$value='".base64_encode($key) ."',"; }?>c;b = eval('VarNme'+a); return b; }


