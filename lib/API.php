<?php
/** API call Handler
*
* Copyright (C) 2012 B Tasker
* Released under GNU GPL V2
* See LICENSE
*
*/
defined('_CREDLOCK') or die;

require_once 'lib/lang.php';
require_once 'lib/auth.class.php';
require_once 'lib/db/loggingdb.class.php';
require_once 'lib/plugins.php';
require_once 'lib/crypto.php';

$plg = new Plugins;


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
    echo "0".$opDivider."Access Denied".$opDivider;
    die;
    }
   


echo "1".$opDivider;

// Decrypt the request

$option = base64_decode(BTMain::getVar('option'));
$tlskey = BTMain::getsessVar('tls');
$crypt = new Crypto;

$option = explode($opDivider,base64_decode($crypt->xordstring($option,$tlskey)));

switch($option[1]){


case 'retCred':
    require_once 'lib/db/Credentials.php';

    $db = new CredDB;
    $cred = $db->FetchCredential(BTMain::getVar('id'));

    
    $crypt->safety = 0;

    $key = 'Cre'.$cred->CredType;

    // Build the response
    $pass = $crypt->decrypt($cred->Hash,$key);
    $address = $crypt->decrypt($cred->Address,$key);

      if ($cred->Clicky){
	  $pass = "<a href='$pass' target=_blank title='Click to Open'>$pass</a>";
      }


    echo htmlspecialchars($pass).$opDivider."<a href='$address' target=_blank>".htmlspecialchars($address)."</a>" .$opDivider. 
	 htmlspecialchars($crypt->decrypt($cred->UName,$key)) . $opDivider;


    // Call any configured plugins
     $data->cred = $cred;
     $data->cred->id = BTMain::getVar('id');
     $data->action = 'display';

    
    echo $plg->loadPlugins("Creds",$data)->plgOutput;

   
    break;



case 'checkSess':
    echo "OK";
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


}


// Encrypt the output and send back
$padding = $crypt->genXorPadding();
$endpadding = $crypt->genXorPadding();

$op = base64_encode($padding.$opDivider.ob_get_clean().$opDivider.$endpadding);

echo base64_encode($crypt->xorestring($op,$tlskey));


?>