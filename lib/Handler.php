<?php
/** Main Handler
*
* Copyright (C) 2012 B Tasker
* Released under GNU GPL V2
* See LICENSE
*
*/
defined('_CREDLOCK') or die;
require_once 'lib/output.php';
//require_once 'lib/db/db_common.php';
require_once 'lib/lang.php';
require_once 'lib/auth.class.php';
require_once 'lib/plugins.php';
require_once 'lib/db/loggingdb.class.php';
require_once 'lib/crypto.php';


$html = new genOutput;
$notifications = new notifications;
$option = BTMain::getVar('option');
$auth = new ProgAuth;

    // See if the user has an active session
    if (BTMain::getsessVar('Session')){
    
    $auth->SetUserDets(BTMain::getsessVar('Session'));
    }


   if (empty(BTMain::getUser()->name)){

    if ($option == "LogIn"){

    $crypt = new Crypto;
    $key = BTMain::getSessVar('AuthKey');
    $pass = BTMain::getVar('FrmPass');
    
    if (!BTMain::getConnTypeSSL()){
    $pass =& $crypt->xordstring(base64_decode($pass),$key);
    }


	if ($auth->ProcessLogIn(BTMain::getVar('FrmUsername'),$pass)){
	    // Login successful
	    header('Location: index.php?notif=LoginSuccess');
	  }else{
	    header('Location: index.php?notif=LoginFailed');
	  }
      die;
  }


   $html->content = $html->genDefaultPage();
   $html->callTemplate();
   ob_end_flush();
   die;
   }


// Load some extra libraries
require_once 'lib/db/Customer.php';
require_once 'lib/db/Credentials.php';

$cred = new CredDB;
if(!$cred->checkCredTypesDefined()){
$notifications->setNotification("NoCredTypes");
}
unset($cred);






switch ($option){

// Log the current user out
case 'logout':
$auth->killSession();
break;

case 'About':
$html->content = $html->loadView('about.about');
break;

case 'addCustomer':
$html->content = $html->loadView('Customer.add');
break;

case 'viewCust':
$html->content = $html->loadView('Customer.view');
break;

case 'EditCustomer':
$html->content = $html->loadView('Customer.edit');
break;

case 'viewCustomers':
$html->content = $html->loadView('Customer.list');
break;




case 'addCred':
$html->content = $html->loadView('Creds.add');
break;


case 'viewByType':
$html->content = $html->loadView('Creds.bytype');
break;

case 'editCred':
$html->content = $html->loadView('Creds.edit');
break;

case 'addCredType':
$html->content = $html->loadView('Creds.addtype');
break;

case 'listCredTypes':
$html->content = $html->loadView('Creds.listtypes');
break;

case 'editCredType':
$html->content = $html->loadView('Creds.edittype');
break;



case 'addGrp':
$html->content = $html->loadView('Group.add');
break;

case 'editGrp':
$html->content = $html->loadView('Group.edit');
break;


case 'viewGrps':
$html->content = $html->loadView('Group.list');
break;






case 'pluginInfo':
$html->content = $html->loadView('plugins.loaded');
break;


case 'plgInfo':
$html->content = $html->loadView('plugins.readme');
break;


case 'addUser':
$html->content = $html->loadView('user.add');
break;

case 'viewUsers':
$html->content = $html->loadView('user.list');
break;

case 'editUser':
$html->content = $html->loadView('user.edit');
break;

case 'changePassword':
$html->content = $html->loadView('user.changePass');
break;


default:
$html->content = $html->genDefaultPage();


}


$html->callTemplate();

ob_end_flush();




?>