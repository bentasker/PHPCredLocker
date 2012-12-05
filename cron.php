<?php
/** System entry point for cron calls
*
* Copyright (C) 2012 B Tasker
* Released under GNU GPL V2
* See LICENSE
*
*/
session_start();

// Cronjobs are called from the CLI which will ignore the header. Redirect anyone in a browser
header("Location: index.php");

define('_CREDLOCK',1);



//error_reporting(E_ALL);
error_reporting(^E_NOTICE);

// Load the framework
require_once 'lib/Framework/main.php';


// pass off to the handler
include_once 'lib/CronHandler.php';