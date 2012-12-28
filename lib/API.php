<?php
/** API call Handler
*
* Copyright (C) 2012 B Tasker
* Released under GNU GPL V2
* See LICENSE
*
*/
defined('_CREDLOCK') or die;

ob_start();

require_once 'lib/lang.php';
require_once 'lib/auth.class.php';
require_once 'lib/db/loggingdb.class.php';
require_once 'lib/plugins.php';
require_once 'lib/crypto.php';

$plg = new Plugins;
$crypt = new Crypto;

/**
 Implemented so that we can treat the divider as a key and reduce the likelihood/effectiveness 
 of a known plaintext attack by changing it occasionally throughout the session. Just need to 
 work out a good mechanism for doing so first!
*/
$opDivider = "|..|";



 // See if the user has an active session (must have to continue)
    if (BTMain::getsessVar('Session')){
    $auth = new ProgAuth;
    $auth->SetUserDets(BTMain::getsessVar('Session'));
    }


   if (empty(BTMain::getUser()->name)){
    
    ob_end_flush();
    echo BTMain::getip().$opDivider."0".$opDivider."Access Denied".$opDivider;
    die;
    }
   


echo "1".$opDivider;

// Decrypt the request

$option = BTMain::getVar('option');




	if (!BTMain::getConnTypeSSL()){
	    $tlskey = BTMain::getsessVar('tls');
	    $option = base64_decode($crypt->xordstring(base64_decode($option),$tlskey));
	 }


$option = explode($opDivider,$option);

$terms = BTMain::getSessVar('apiterms');

$option = $terms[$option[1]];


switch($option){


case 'retCred':
    require_once 'lib/db/Credentials.php';

    $db = new CredDB;
    $cred = $db->FetchCredential(BTMain::getVar('id'));

    
    $crypt->safety = 0;

    $key = 'Cre'.$cred->CredType;

    // Build the response
    $pass = htmlspecialchars($crypt->decrypt($cred->Hash,$key));
    $address = htmlspecialchars($crypt->decrypt($cred->Address,$key));
    $uname = htmlspecialchars($crypt->decrypt($cred->UName,$key));

      if ($cred->Clicky){
	  $pass = "<a href='$pass' target=_blank title='Click to Open'>$pass</a>";
      }


    echo $pass.$opDivider."<a href='$address' target=_blank>".$address."</a>" .$opDivider. 
	 $uname . $opDivider;


    // Call any configured plugins
     $data->cred = $cred;
     $data->cred->id = BTMain::getVar('id');
     $data->action = 'display';

    
    echo $plg->loadPlugins("Creds",$data)->plgOutput;

   
    break;



case 'checkSess':
    ob_end_clean();
    echo BTMain::getip().$opDivider."1".$opDivider."OK".$opDivider;
    die;
    break;


case 'delCred':
    require_once 'lib/db/Credentials.php';
    $db = new CredDB;
    if ( $db->DelCredential(BTMain::getVar('id'))){
	echo "1$opDivider\n";
	}else{
	echo "0$opDivider\n";
	}
    break;



case 'delUser':
    BTMain::checkSuperAdmin();
    $db = new AuthDB;
      if ( $db->DelUser(BTMain::getVar('id'))){
	  echo "1$opDivider\n";
      }else{
	  echo "0$opDivider\n";
      }
    break;



case 'delCredType':
      BTMain::checkSuperAdmin();
      require_once 'lib/db/Credentials.php';
      $db = new CredDB;
	    if ( $db->DelCredentialType(BTMain::getVar('id'))) {
		$data->id = BTMain::getVar('id');
		$data->action = 'del';
		echo $plg->loadPlugins("CredTypes",$data)->plgOutput;

		echo "1$opDivider\n";
	    }else{
		echo "0$opDivider|\n";
	    }
      break;




case 'delCust':
    require_once 'lib/db/Customer.php';
    $db = new CustDB;
      if ( $db->DelCust(BTMain::getVar('id'))){
	  echo "1$opDivider\n";
      }else{
	  echo "0$opDivider\n";
      }
    break;




case 'delGroup':
    BTMain::checkSuperAdmin();
    $auth = new AuthDB;

	if($auth->delGroup(BTMain::getVar('id'))){
	   echo "1$opDivider\n";
	}else{	
	  echo "0$opDivider\n";
	}
    break;



default:
  ob_clean();
  echo "2".$opDivider;
  break;

}


// Encrypt the output and send back
$padding = $crypt->genXorPadding();
$endpadding = $crypt->genXorPadding();

$op = $padding.$opDivider.ob_get_clean().$opDivider.$endpadding;

if (!BTMain::getConnTypeSSL()){
$op = base64_encode($crypt->xorestring(base64_encode($op),$tlskey));
}

echo $op;

ob_end_flush();
?>