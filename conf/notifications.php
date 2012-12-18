<?php 
/** Notifications definition file
*
* Copyright (C) 2012 B Tasker
* Released under GNU GPL V2
* See LICENSE
*
*/

$notifs->addCustSuccess->className = 'alert alert-success';
$notifs->addCustSuccess->text = 'Customer added successfully';

$notifs->addCustFail->className = 'alert alert-error';
$notifs->addCustFail->text = 'Customer not added - is the name unique?';

$notifs->EditCustSuccess->className = 'alert alert-success';
$notifs->EditCustSuccess->text = 'Customer edited successfully';

$notifs->NoSuchCustomer->className = 'alert alert-error';
$notifs->NoSuchCustomer->text = "The Specified Record doesn't exist (or you don't have access to it)";

$notifs->EditCustFail->className = 'alert alert-error';
$notifs->EditCustFail->text = 'Customer not edited, something went wrong';

$notifs->addCredSuccess->className = 'alert alert-success';
$notifs->addCredSuccess->text = "Credential Stored successfully";

$notifs->addCredFail->className = 'alert alert-error';
$notifs->addCredFail->text = 'Credential Failed to Store';

$notifs->addGroupSuccess->className = 'alert alert-success';
$notifs->addGroupSuccess->text = 'Group Successfully Stored';

$notifs->addGroupFail->className = "alert alert-error";
$notifs->addGroupFail->text = 'Group not Stored';

$notifs->addCredTypeSuccess->className = 'alert alert-success';
$notifs->addCredTypeSuccess->text = 'Credential Type Stored';

$notifs->addCredTypeFail->className = "alert alert-error";
$notifs->addCredTypeFail->text = 'Credential Type Not Stored';

$notifs->NoCredTypes->className = 'alert alert-info';
$notifs->NoCredTypes->text = "You need to specify some Credential Types in System Settings before you can add Credentials</div><script type='text/javascript'>noCredTypes();</script>";
$notifs->NoCredTypes->id = 'CredTypeNeedsAdding';

$notifs->UserStoreSuccess->className = 'alert alert-success';
$notifs->UserStoreSuccess->text = 'User Stored Successfully';

$notifs->UserStoreFail->className = 'alert alert-error';
$notifs->UserStoreFail->text = 'User Failed to Store';

$notifs->KeyGenerationFailed->className = 'alert alert-error';
$notifs->KeyGenerationFailed->text = 'Unable to add Crypto Key to config file. You <b>must</b> do this manually before you can add creds to this CredType';




/** Following are generally triggered by the request URI var notif but can be called by views etc as well  **/


$notifs->LoginSuccess->className = 'alert alert-success';
$notifs->LoginSuccess->text = 'Logged in Successfully';


$notifs->LoginFailed->className = 'alert alert-error';
$notifs->LoginFailed->text = 'Invalid Username or Password';


$notifs->InvalidSession->className = 'alert alert-error';
$notifs->InvalidSession->text = 'Your session is invalid (it may have expired), please log-in to continue';

$notifs->LoggedOut->className = 'alert alert-info';
$notifs->LoggedOut->text = 'You have been logged out';

$notifs->frmTokenInvalid->className = 'alert alert-error';
$notifs->frmTokenInvalid->text = 'Invalid Form Token';


?>