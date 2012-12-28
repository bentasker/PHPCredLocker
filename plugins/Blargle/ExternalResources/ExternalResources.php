<?php
/** ExternalResources plugin - Allows static assets (JS and CSS) normally in the resources directory to be stored on a seperate server
*
* Copyright (C) 2012 B Tasker
* Released under GNU GPL V2
* See LICENSE
*
*/
defined('_CREDLOCK') or die;


/**                            Resources PLUGIN STARTS                            **/



/** Credentials Plugin class
*
*/
class plugin_ExternalResources_Resources{
/** Load the plugin configuration
*
*/
function config(){
require 'conf/plugins/ExternalResources/config.php';
}



/** Return the plugin details
*
*/
function getDetails(){
$details->Name = "plg_ExternalResources";
$details->Description = "Allows the contents of the Resources directory (excluding info.php) to be moved to another server";
$details->Author = 'B Tasker';
$details->License = 'GNU GPL V2';
return $details;
}


/** Get current status
*
*/
function getPlgStatus(){
$this->config();
return $this->active;

}

/** Class Entry Point
*
* @arg data - object, will contain
*
*/
function PlgCall($data){
// Load the plugin config
$this->config();

// Check the plugin is actually enabled
if (!$this->active){ return; }





  switch($data->action){

    case 'loadresource':
  
    if ($this->url && (!empty($this->url))){
    return $this->url;
    }
    return false;
    break;


    
    }



}


}



?>