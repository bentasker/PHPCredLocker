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



function BreadCrumbs(){
$count = count($GLOBALS['BREADCRUMB']);
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


$str[] = '';
if (BTMain::getVar('LoginSuccess')){

$str[] = "<div class='alert alert-success'>Logged in Successfully</div>";

}

if (BTMain::getVar('LoginFailed')){

$str[] = "<div class='alert alert-error'>Invalid Username or Password</div>";

}

if (BTMain::getVar('InvalidSession')){
$str[] = "<div class='alert alert-error'>Your session is invalid (it may have expired) please log-in to continue</div>";
}


if (BTMain::getVar('LoggedOut')){

$str[] = "<div class='alert alert-info'>You have been logged out</div>";
}

if ($GLOBALS['Notifications']['addCustSuccess']){
$str[] = "<div class='alert alert-success'>Customer added successfully</div>";
}


if ($GLOBALS['Notifications']['addCustFail']){
$str[] = "<div class='alert alert-error'>Customer not added</div>";
}


if ($GLOBALS['Notifications']['EditCustSuccess']){
$str[] = "<div class='alert alert-success'>Customer edited successfully</div>";
}

if ($GLOBALS['Notifications']['NoSuchCustomer']){
$str[] = "<div class='alert alert-error'>The Specified Record doesn't exist (or you don't have access to it)</div>";
}


if ($GLOBALS['Notifications']['EditCustFail']){
$str[] = "<div class='alert alert-error'>Customer not edited</div>";
}


if ($GLOBALS['Notifications']['addCredSuccess']){
$str[] = "<div class='alert alert-success'>Credential Stored successfully</div>";
}


if ($GLOBALS['Notifications']['addCredFail']){
$str[] = "<div class='alert alert-error'>Credential Failed to Store</div>";
}





if ($GLOBALS['Notifications']['addGroupSuccess']){
$str[] = "<div class='alert alert-success'>Group Successfully Stored</div>";
}

if ($GLOBALS['Notifications']['addGroupFail']){
$str[] = "<div class='alert alert-error'>Group not Stored</div>";
}

if ($GLOBALS['Notifications']['addCredTypeSuccess']){
$str[] = "<div class='alert alert-success'>Credential Type Stored</div>";
}


if ($GLOBALS['Notifications']['addCredTypeFail']){
$str[] = "<div class='alert alert-error'>Credential Type Not Stored</div>";
}

if ($GLOBALS['Notifications']['NoCredTypes']){
$str[] = "<div class='alert alert-info' id='CredTypeNeedsAdding'>You need to specify some Credential Types in System Settings before you can add Credentials</div><script type='text/javascript'>noCredTypes();</script>";
}

if ($GLOBALS['Notifications']['UserStoreSuccess']){
$str[] = "<div class='alert alert-success'>User Stored Successfully</div>";
}

if ($GLOBALS['Notifications']['UserStoreFail']){
$str[] = "<div class='alert alert-error'>User Failed to Store</div>";
}

if ($GLOBALS['Notifications']['KeyGenerationFailed']){

$str[] = "<div class='alert alert-error'>Unable to add Crypto Key to config file. You <b>must</b> do this manually before you can add creds to this CredType</div>";
}


return implode("\n",$str);

}



/** Push the required headers
*
*/
function headContents(){
?>
<title><?php echo BTMain::getConf()->ProgName;?> - <?php echo $this->getPageTitle();?></title>
    <link rel="stylesheet" type="text/css" href="Resources/jquery.tooltip.css" />
<?php
    foreach ($GLOBALS['RequireCSS'] as $css){
	    ?><link rel="stylesheet" type="text/css" href='Resources/<?php echo $css;?>.css'/><?php
	}
?>

    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
    <script type="text/javascript" src="Resources/jquery.tooltip.min.js"></script>
    <script src="Resources/info.php?<?php echo md5(session_id().$_SERVER['REMOTE_ADDR']); ?>" type="text/javascript"></script>
    <script src="Resources/main.js" type="text/javascript"></script>


<?php
  foreach ($GLOBALS['RequireScript'] as $script){?>
    <script src="Resources/<?php echo $script;?>.js" type="text/javascript"></script>
<?php    }


  if (is_array($GLOBALS['CUSTOMJS'])):
    ?>
      <script type="text/javascript">
      <?php echo implode("\n",$GLOBALS['CUSTOMJS']);?>
      </script>
<?php
endif;
?>
<script type="text/javascript">var sesscheck; jQuery(document).ready(function() { checkKeyAvailable(); sesscheck = setInterval("checkSession()",120000);});</script>
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


/** Check the pagetitle global and return the contents
*
*/
function getPageTitle(){

return htmlentities($GLOBALS['PageTitle']);

}

}









class notifications{


function setPageTitle($title){
$GLOBALS['PageTitle'] = $title;
}

function setNotification($notification){

$GLOBALS['Notifications'][$notification] = 1;


}

function RequireCSS($file){

$GLOBALS['RequireCSS'][] = $file;
}

function RequireScript($script){
$GLOBALS['RequireScript'][] = $script;
}

function setBreadcrumb($path){

$GLOBALS['BREADCRUMB'] = $path;

}



function setCustomJS($js){
$GLOBALS['CUSTOMJS'][] = "$js";

}



}

$notifications = new notifications;



?>