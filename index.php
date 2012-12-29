<?php
/** System entry point for non-API calls
*
* Copyright (C) 2012 B Tasker
* Released under GNU GPL V2
* See LICENSE
*
*/
session_start();
ob_start();
error_reporting(0);

define('_CREDLOCK',1);

// Prevent Caching
header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
header('Pragma: no-cache');
header('Expires: Thu, 01 Jan 1970 00:00:00 GMT');


// Force use of the installer if it's present
if (is_dir (dirname(__FILE__)."/Install")){
header("Location: Install/");
die;
}


//error_reporting(E_ALL);

// Load the framework
require_once 'lib/Framework/main.php';

    if (  BTMain::getSessVar('Banned') ){
	echo "Excessive authentication attempts";
    die;
    }




// Force SSL if configured to do so
if (BTMain::getConf()->forceSSL && !BTMain::getConnTypeSSL()){
header("Location: " . BTMain::getConf()->SSLURL);
die;
}

// pass off to the handler
include_once 'lib/Handler.php';

?>
