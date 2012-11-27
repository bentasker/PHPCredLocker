<?php
/** System Configuration
*
* Copyright (C) 2012 B Tasker
* Released under GNU GPL V2
* See LICENSE
*
*/

$conf->template = 'EstDeus';

$conf->ProgName = 'PHPCredLocker';

// Set the DB details
$conf->dbname = "";
$conf->dbserver = "";
$conf->dbuser = "";
$conf->dbpass = "";

// Session expiry time (minutes)
$conf->sessionexpiry = 15;


// Display SQL errors?
$conf->showDBErrors = 1;

// Set this to false to disabled the internal logging (logging plugins will still run if they are active)
$conf->loggingenabled = true;





?>