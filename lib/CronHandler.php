<?php
/** Crontask Handler
*
* Copyright (C) 2012 B Tasker
* Released under GNU GPL V2
* See LICENSE
*
*/
defined('_CREDLOCK') or die;

// Check the password has been specified
if ($_SERVER['CRON_PASS'] != BTMain::getConf()->cronPass){
echo "Access Denied\n\n";
die;
}

if (empty(BTMain::getConf()->cronPass)){

echo "Error: Cron Pass not set in config. Aborting for security reasons\n\n";
die;
}


require_once 'lib/db/cron.php';

$crondb = new CronDB;

// Clear any sessions
echo "Clearing old sessions\n";
$crondb->clearOldSessions();


// Pass off to any cron plugins
require_once 'lib/plugins.php';
$plgs = new Plugins;
$plgs->loadPlugins("Cron","");





?> 
