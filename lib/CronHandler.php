<?php
/** Crontask Handler
*
* Copyright (C) 2012 B Tasker
* Released under GNU GPL V2
* See LICENSE
*
*/
defined('_CREDLOCK') or die;
error_reporting(0);

// Check the password has been specified
if (($_SERVER['CRON_PASS'] != BTMain::getConf()->cronPass) || (isset($_SERVER['REMOTE_ADDR']))){
echo "Access Denied\n\n";
ob_end_flush();
die;
}

if (empty(BTMain::getConf()->cronPass)){
echo "Error: Cron Pass not set in config. Aborting for security reasons\n\n";
ob_end_flush();
die;
}


// Load the database library
require_once 'lib/db/cron.php';
$crondb = new CronDB;



// Clear any sessions
echo "Clearing old sessions\n";
$crondb->clearOldSessions();


echo "Checking Session files\n";
$time = date('U');

// Tidy up the sessions files
$dir = new DirectoryIterator(dirname(__FILE__)."/../sessions/");
foreach ($dir as $fileinfo) {
    if (!$fileinfo->isDot()) {
        

    $fn = $fileinfo->getFilename();

   
    if ($fn == "index.html"){ continue; }

    $fname = explode("-",$fn);
    
    if ($fname[1] < $time){
       echo "Removing $fn\n";
    unlink(dirname(__FILE__)."/../sessions/$fn");
    }


    }
}


// Clear any expired IP Bans
echo "Clearing any expired IP Bans\n";
$crondb->clearOldBans();

// Pass off to any cron plugins
require_once 'lib/plugins.php';
$plgs = new Plugins;

// Put the output so far into an object
$output->plgOutput = ob_get_clean();

// Pass to the plugin handler
$output = $plgs->loadPlugins("Cron",$output);

// Output to the CLI for the benefit of the cron logs
echo $output->plgOutput;

?>