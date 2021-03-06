<?php
/** Database functions for all cron jobs
*
* Copyright (C) 2012 B Tasker
* Released under GNU AGPL V3
* See LICENSE
*
*/ 
defined('_CREDLOCK') or die;


class CronDB extends BTDB{ 


/** Clear any expired sessions from the database
*
*/
function clearOldSessions(){

$exp = date("Y-m-d H:i:s");

$sql = "DELETE FROM #__Sessions WHERE `Expires` < '$exp'";
$this->setQuery($sql);

return $this->runQuery();
}




/** Clear any expired IP Bans
*
*/
function clearOldBans(){
$exp = date("Y-m-d H:i:s");

$sql = "DELETE FROM #__bannedIPs WHERE `Expiry` < '$exp'";
$this->setQuery($sql);

return $this->runQuery();


}



/** Clear any failed logins that occurred outside the proximity setting
*
* @arg date
*
*/
function clearFailedLogins($date){

$sql = "DELETE FROM #__FailedLogins WHERE `LastAttempt` < '$date'";
$this->setQuery($sql);
return $this->runQuery();

}




}
