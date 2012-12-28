<?php
/** Plugins Configuration
*
* Copyright (C) 2012 B Tasker
* Released under GNU GPL V2
* See LICENSE
*
*/ 
defined('_CREDLOCK') or die;




$plugins->Auth = array();
$plugins->Logging = array('AffinityLive');
$plugins->Customers = array();
$plugins->Creds = array('AutoAuth');
$plugins->CredTypes = array('AutoAuth');
$plugins->Cron = array();
$plugins->Resources = array('ExternalResources');


