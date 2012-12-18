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

/** Will be overhauled at some point, will most likely move the actual page content into the template
*
*/
function genDefaultPage(){
global $notifications;
$notifications->setPageTitle("Home");

$str = "<span class='basic-content default-page'>";
if (BTMain::getUser()->name){

$str .= 'Welcome to the cred handling system. Please use the menus to proceed';

}else{

$str .= 'Please log-in to continue';


}

return $str . "</span>\n";

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

  if ($notifications){
  
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



/** Push the required headers
*
*/
function headContents(){

global $notifications;
$page = $notifications->getPageInfo();
$conf = BTMain::getConf();
?>
      <title><?php echo $conf->ProgName;?> - <?php echo htmlentities($page->title);?></title>
      <link rel="stylesheet" type="text/css" href="Resources/jquery.tooltip.css" />
      <link rel="stylesheet" type="text/css" href="Resources/bootstrap/css/bootstrap.css" />
      <link rel="stylesheet" type="text/css" href="Resources/bootstrap/css/bootstrap-responsive.css" />


    <?php foreach ($page->css as $css):?>
	    <link rel="stylesheet" type="text/css" href='Resources/<?php echo $css;?>.css'/>
    <?php endforeach;?>

      <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
      <script type="text/javascript" src="Resources/bootstrap/js/bootstrap.js"></script>
      <script type="text/javascript" src="Resources/jquery.tooltip.min.js"></script>
      <script src="Resources/info.php?<?php echo md5(session_id().$_SERVER['REMOTE_ADDR']); ?>" type="text/javascript"></script>
      <script src="Resources/main<?php echo $conf->JSMinName;?>.js" type="text/javascript"></script>
      <script src="Resources/base64<?php echo $conf->JSMinName;?>.js" type="text/javascript"></script>

    <?php foreach ($page->reqscripts as $script):?>
      <script src="Resources/<?php echo $script;?><?php echo $conf->JSMinName;?>.js" type="text/javascript"></script>
    <?php endforeach;  if (!empty($page->custJS[0])):?>

      <script type="text/javascript">
      <?php echo implode("\n",$page->custJS);?>
      </script>

    <?php endif; ?>

<!-- Fire the default scripts when the browser reports document ready -->
    <script type="text/javascript">
    var sesscheck; jQuery(document).ready(function() {  checkKeyAvailable(); 
    <?php if (BTMain::getUser()->name):?>sesscheck = setInterval("checkSession()",120000);<?php endif;?>});
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


}





?>