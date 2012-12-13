<?php
/** API call Handler
*
* Copyright (C) 2012 B Tasker
* Released under GNU GPL V2
* See LICENSE
*
*/
defined('_CREDLOCK') or die;


require_once 'lib/auth.class.php';
require_once 'lib/db/loggingdb.class.php';
require_once 'lib/plugins.php';
require_once 'lib/crypto.php';

 // See if the user has an active session (must have to continue)
    if (BTMain::getsessVar('Session')){
    $auth = new ProgAuth;
    $auth->SetUserDets(BTMain::getsessVar('Session'));
    }


   if (empty(BTMain::getUser()->name)){
    
    ob_end_flush();
    echo "0|..|Access Denied|..|";
    die;
    }
   


echo "1|..|";

switch(BTMain::getVar('option')){


case 'retCred':
require_once 'lib/db/Credentials.php';

$db = new CredDB;
$cred = $db->FetchCredential(BTMain::getVar('id'));

$crypt = new Crypto;
$crypt->safety = 0;

$key = 'Cre'.$cred->CredType;

// Build the response
$pass = $crypt->decrypt($cred->Hash,$key);
$address = $crypt->decrypt($cred->Address,$key);
if ($cred->Clicky){
$pass = "<a href='$pass' target=_blank title='Click to Open'>$pass</a>";
}


echo htmlspecialchars($pass)."|..|<a href='$address' target=_blank>".htmlspecialchars($address)."</a>|..|" . htmlspecialchars($crypt->decrypt($cred->UName,$key)) . "|..|\n";
break;


case 'checkSess':
echo "OK";
break;


case 'delCred':
require_once 'lib/db/Credentials.php';
$db = new CredDB;
if ( $db->DelCredential(BTMain::getVar('id'))){
echo "1|..|\n";
}else{
echo "0|..|\n";
}
break;



case 'delUser':
BTMain::checkSuperAdmin();
$db = new AuthDB;
if ( $db->DelUser(BTMain::getVar('id'))){
echo "1|..|\n";
}else{
echo "0|..|\n";
}

break;



case 'delCredType':
BTMain::checkSuperAdmin();
require_once 'lib/db/Credentials.php';
$db = new CredDB;
if ( $db->DelCredentialType(BTMain::getVar('id'))) {
echo "1|..|\n";
}else{
echo "0|..|\n";
}
break;




case 'delCust':
require_once 'lib/db/Customer.php';
$db = new CustDB;
if ( $db->DelCust(BTMain::getVar('id'))){
echo "1|..|\n";

}else{
echo "0|..|\n";
}
break;




case 'delGroup':
BTMain::checkSuperAdmin();
$auth = new AuthDB;
if($auth->delGroup(BTMain::getVar('id'))){
echo "1|..|\n";

}else{
echo "0|..|\n";
}
break;


}

ob_end_flush();
?>