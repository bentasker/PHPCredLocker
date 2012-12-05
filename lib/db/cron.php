<?php
/** Database functions for all cron jobs
*
* Copyright (C) 2012 B Tasker
* Released under GNU GPL V2
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

$sql = "DELETE FROM Sessions WHERE `Expires` < '$exp'";
$this->setQuery($sql);

return $this->runQuery();







}










}
