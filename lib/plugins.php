<?php
/** Plugins Handler
*
* Copyright (C) 2012 B Tasker
* Released under GNU GPL V2
* See LICENSE
*
*/ 
defined('_CREDLOCK') or die;
class Plugins{

/** Load the configuration (usually just which plugins are active)
*
*/
function loadConfig(){


include 'conf/plugins.php';
$this->plugins = $plugins;

// Set the default
defined("CREDLOCK_PLUGIN__PATH") or define('CREDLOCK_PLUGIN__PATH','plugins/Blargle');
}



function getPluginInfo($plugin,$plug){
  
  if (empty($plugin) || empty($plug)){ return; }


  include_once "plugins/".$this->plugins->path."/$plugin/$plugin.php";

  $cls = "plugin_".$plugin."_$plug";
  

  $plg = new $cls;
  $status->status = $plg->getPlgStatus();
  $status->info = $plg->getPlgDetails();
  return $status;

}


/** List all plugins activated in plugins.conf and return their current status (active, Disabled etc)
*
* @return object
*/
function listloadedPlugins(){

$this->loadConfig();



foreach ($this->plugins as $plugintype=>$plugins){

$plug = $plugintype;

  foreach ($plugins as $plugin){

    
  if (empty($plugin)){ continue; }


  include_once "plugins/".$this->plugins->path."/$plugin/$plugin.php";

  $cls = "plugin_".$plugin."_$plug";
  

  $plg = new $cls;

  $loaded->$plug->$plugin = $plg->getPlgStatus();

  unset($plg);
  }




}

return $loaded;

}



/** Load the relevant plugins
*
* @arg type - Plugin type (logging, auth etc)
* @arg data - object - Any data to be passed to the plugins
*
* 
*/
function loadPlugins($type,$data){
$this->loadConfig();

foreach ($this->plugins->$type as $value){

if (empty($value)){ continue; }


include_once "plugins/".$this->plugins->path."/$value/$value.php";

$cls = "plugin_".$value."_$type";
$fn = "PlgCall";

$plg = new $cls;

$data->plgOutput .= $plg->$fn($data);



}

return $data;
}



/** Translate the plugin status code into human readable text
*
* @arg status string
*
* @return string
*/
function transStatus($status){

if ($status){

if (strpos($status,"Test") !== false){
return $status;

}else{
return 'Active';
}


}else{
return 'Disabled';
}

}







}

?>