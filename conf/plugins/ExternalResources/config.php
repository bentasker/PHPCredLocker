<?php
/** ExternalResources plugin Config
*
* Copyright (C) 2012 B Tasker
* Released under GNU AGPL V3
* See LICENSE
*
*/
defined('_CREDLOCK') or die;

// Set this to false to disable the plugin
$this->active = false;


// URL to prefix resources with (don't include a trailing slash)
$this->url = "";

$this->css->jquerytooltip->disable = true;
$this->js->jquery->url = 'https://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js';
?> 
