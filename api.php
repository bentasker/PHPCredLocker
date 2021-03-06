<?php
/** API Entry Point
*
* Copyright (C) 2012 B Tasker
* Released under GNU AGPL V3
* See LICENSE
*
*/

session_start();
error_reporting(0);

ob_start();


define('_CREDLOCK',1);

// Prevent Caching
header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
header('Pragma: no-cache');
header('Expires: Thu, 01 Jan 1970 00:00:00 GMT');

// Load the framework
require_once 'lib/Framework/main.php';


 if (  BTMain::getSessVar('Banned') ){
	echo "Excessive authentication attempts";
    die;
    }

require_once 'lib/API.php';

?>
