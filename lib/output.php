<?php
/** Main HTML Output generation
*
* Copyright (C) 2012 B Tasker
* Released under GNU GPL V2
* See LICENSE
*
*/

defined('_CREDLOCK') or die;



class genOutput{

/** Ouputs the default content for the homepage. Content can be controlled in the notifications config file
*
*/
function genDefaultPage(){
global $notifications;
$notifications->setPageTitle("Home");

  if (BTMain::getUser()->name){
    $notname= 'HomePageTextLoggedIn';
  }else{
    $notname= 'HomePageTextNotLoggedIn';
  }


$notif = $notifications->getNotification($notname);
$str = "<div class='{$notif->className}'";


  if (isset($notif->id)){
      $str .= " id='{$notif->id}'";
    }



return $str . ">" . $notif->text . "</div>\n";

}





/** Call the relevant template
*
*/
function callTemplate(){
// Load the config so we know which template to call

$template = BTMain::getConf()->template;
require "templates/$template/index.php";

}


/** Load a view and return the output
*
* @arg view string
*/
function loadView($view){

ob_start;
$template = BTMain::getConf()->template;
$view = str_replace(".","/",$view);
	// Check for template level override
    if (file_exists("templates/". $template . "html/views/" . $view . ".php")){
	require "templates/". $template . "html/views/" . $view . ".php";
    }else{
	require "views/" . $view . ".php";

    }
return ob_get_clean();
}


/** Output the Breadcrumbs
*
*/
function BreadCrumbs(){
?>
<ul class="breadcrumb">
 <li>
    <a href="index.php">Home</a>
 </li>
<?php foreach ($GLOBALS['BREADCRUMB'] as $crumb){?>
 <li>
  <span class="divider">/</span>
    <a href="<?php echo $crumb['url']; ?>"><?php echo $crumb['name']; ?></a> 
</li>
  

<?php }?>
 
</ul>
<?php

}



/** Generate the HTML for any relevant Notifications
*
*/
function Notifications(){

global $notifications;
$nots = $notifications->getNotifications();
$str = array();

  if ($nots){
  
      foreach ($nots as $not){
      $id='';

	  if (!empty($not->id)){
	  $id = " id='{$not->id}'";
	  }

      $str[] = "<div class='{$not->className}'$id>{$not->text}</div>";

      }


  }

return implode("\n",$str);

}


/** Defines (almost) every static resource to be included in the document head
*
* @return object
*
*/
function includedresources(){
$coreres->css->bootstrap->fname = 'bootstrap';
$coreres->css->bootstrap->path = 'bootstrap/css/';
$coreres->css->bootstrapresponsive->fname = 'bootstrap-responsive';
$coreres->css->bootstrapresponsive->path = 'bootstrap/css/';
$coreres->css->jquerytooltip->fname = 'jquery.tooltip';


$coreres->js->jquery->fname = 'jquery';
$coreres->js->jquery->forcemin = '.min';
$coreres->js->jquerytooltip->fname = 'jquery.tooltip';
$coreres->js->jquerytooltip->forcemin = '.min';
$coreres->js->bootstrap->fname = 'bootstrap';
$coreres->js->bootstrap->path = 'bootstrap/js/';
$coreres->js->main->fname = 'main';
$coreres->js->base64->fname = 'base64';

$coreres->resourcespath = "Resources";

return $coreres;
}



/** Push the required headers
*
*/
function headContents(){

global $notifications;
$page = $notifications->getPageInfo();
$conf = BTMain::getConf();
$plg = new Plugins;

$coreres = $this->includedresources();

// Call any configured plugins
     $data->resources = $coreres;
     $data->action = 'loadresource';

       
    $coreres = $plg->loadPlugins("Resources",$data);



// I'm knackered, quitting while I'm ahead. Will improve/finish later
$resourcespath = $coreres->resourcespath;


?>
      <title><?php echo $conf->ProgName;?> - <?php echo htmlentities($page->title);?></title>


      <?php
      foreach($coreres->resources->css as $css){

	    if ($css->disable){ continue; }

	if (!empty($css->url)){
	  $path = $css->url;
	}else{
	  $path = $coreres->resources->resourcespath . "/";
	      if (!empty($css->path)){
	      $path .= $css->path;
	      }
	  $path .= $css->fname;

	      if ($css->forcemin){
		$path .= $css->forcemin;
	      }else{
		$path .= $conf->JSMinName;
	      }
	    $path .= '.css';
	}

      ?>
	  <link rel="stylesheet" type="text/css" href="<?php echo $path; ?>" />
      <?php
      }
      ?>

    <?php foreach ($page->css as $css):?>
	    <link rel="stylesheet" type="text/css" href='<?php echo $coreres->resources->resourcespath; ?>/<?php echo $css;?>.css'/>
    <?php endforeach;?>

      <style type="text/css">.inlineTLS {display: inline;}</style>
      <script id='kFile' src="Resources/info.php?<?php $frs = BTMain::getSessVar('cacheFrustrate'); echo md5(session_id().$_SERVER['REMOTE_ADDR']).$frs; ?><?php $notif=BTMain::getVar('notif'); if (!empty($notif) && ($notif == 'LoginFailed' || $notif == 'LoggedOut' || $notif == 'InvalidSession')){ echo "&destSession=Y"; BTMain::setSessVar('cacheFrustrate',mt_rand());}?>" type="text/javascript"></script>


      <?php
      foreach($coreres->resources->js as $js){
	  if ($js->disable){ continue; }
	
	if (!empty($js->url)){
	  $path = $js->url;
	}else{
	  $path = $coreres->resources->resourcespath . "/";
	      if (!empty($js->path)){
	      $path .= $js->path;
	      }
	  $path .= $js->fname;

	      if ($js->forcemin){
		$path .= $js->forcemin;
	      }else{
		$path .= $conf->JSMinName;
	      }
	      $path .= '.js';
	}

      ?>
	  <script type="text/javascript" src="<?php echo $path; ?>" /></script>
      <?php
      }
      ?>


    <?php foreach ($page->reqscripts as $script):?>
      <script src="<?php echo $coreres->resources->resourcespath; ?>/<?php echo $script;?><?php echo $conf->JSMinName;?>.js" type="text/javascript"></script>
    <?php endforeach;  if (!empty($page->custJS[0])):?>

      <script type="text/javascript">
      <?php echo implode("\n",$page->custJS);?>
      </script>

    <?php endif; ?>

<!-- Fire the default scripts when the browser reports document ready -->
    <script type="text/javascript">
    var sesscheck; jQuery(document).ready(function() {  checkKeyAvailable(); inlineDeCrypt();<?php if (BTMain::getUser()->name):?>sesscheck = setInterval("checkSession()",120000);<?php endif;?>});
    </script>

<?php
}




/** Load a module by name
*
* @arg module - string
*
*/
function loadModule($module){

$template = BTMain::getConf()->template;


	// Check for template override
	    if (file_exists("templates/$template/html/modules/$module/$module.php")){
		require "templates/$template/html/modules/$module/$module.php";
	    }else{
		require "modules/$module/$module.php";
	    }



}




}/** Gen Output Class Ends **/








/**                     Notifications class                                    **/
class notifications{




/** Return an object containing any notification items that have been set (or at least those suited for embedding in the head)
*
* @return object
*
*/
function getPageInfo(){

$page->title = '';
$page->css = array();
$page->reqscripts = array();
$page->custJS = array();

  if (isset($this->pagetitle)){
  $page->title = $this->pagetitle;
  }

  if (is_array($this->css)){
  $page->css = $this->css;
  }

  if (is_array($this->requiredscripts)){
  $page->reqscripts = $this->requiredscripts;
  }

  if (is_array($this->customJS)){
  $page->custJS = $this->customJS;
  }

return $page;
}


/** Set the page title
*
* @arg title
*
*/
function setPageTitle($title){
$this->pagetitle = $title;
}




/** Return the content of a single named notification
*
* @arg notname - string - notification name
*
* @return object
*
*/
function getNotification($notname){

if (empty($notname)){ return false; }

include 'conf/notifications.php';

return $notifs->$notname;


}





/** Get any notifications that have been triggered
*
* @return object (or false if no notifications)
*
*/
function getNotifications(){

$notif = BTMain::getVar('notif');
$triggernotifs = is_array($this->notifications);


// Check whether there are any notifications to push
if (!$notif && !$triggernotifs){
return false;
}


$nots = new stdClass();
include 'conf/notifications.php';

// Check for notifications triggered by views
if ($triggernotifs){

    foreach ($this->notifications as $msg){
	$nots->$msg = $notifs->$msg;
      }

  }


// Check for notifications triggered by the request
if ($notif){
  $nots->$notif = $notifs->$notif;
  }


return $nots;
}


/** Set a notification to display when getNotifications is called
*
* @arg notification - string containing notification name
*
*/
function setNotification($notification){
$this->notifications[] = $notification;
}


/** Trigger the inclusion of a CSS file in the document head
*
* @arg file - filename (Will be automatically prefixed with Resources/ and appended with .css)
*
*/
function RequireCSS($file){
$this->css[] = $file;
}


/** Trigger the inclusion of a JS file in the document head
*
* @arg file - filename (Will be automatically prefixed with Resources/ and appended with .js)
*
*/
function RequireScript($script){
$this->requiredscripts[] = $script;
}


/** Set the breadcrumb path
*
* @arg path - array
*
* Exact schema of the array is dictated by class genOutput but at time of writing, 
* each breadcrumb item should be an array containing elements name and url
*
*/
function setBreadcrumb($path){

$GLOBALS['BREADCRUMB'] = $path;

}


/** Embed a JS string into the document head, will automatically be placed between script tags
*
* @arg js - string
*
*/
function setCustomJS($js){
$this->customJS[] = "$js";
}

/** TODO add support for custom notifications passed as an argument **/
}





?>