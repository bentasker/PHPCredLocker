<?php
/** Crypto Keys
*
* Copyright (C) 2012 B Tasker
* Released under GNU GPL V2
* See LICENSE
*
* KEEP THIS FILE SECRET!
*
* Use Strong keys - Not the defaults!!!!!
*/ 
defined('_CREDLOCK') or die;

/** Keys                                       **/
// Group keys, these refer to fields that could be used by an attacker to identify which stored creds are worth trying to crack
$crypt->Groups = '9876';
$crypt->Customer = '1234';
$crypt->CredType = 'zxy';
$crypt->auth = 'b170d1920cfe9d1000107c4a59f6cc81';

// These are used to encrypt the creds themselves.
$crypt->Cre1 = 'abcd';
$crypt->Cre2 = 'defg';
$crypt->Cre3 = 'hijk';



/** If you're on PHP >= 5.3 you can set this to OpenSSL, otherwise use Mcrypt **/
$cipher->Engine = 'OpenSSL';


/*     MCrypt Specific             */
$cipher->Encryption = MCRYPT_RIJNDAEL_256;
$cipher->MCrypt->mode = MCRYPT_MODE_ECB;


/*    OpenSSL Specific            */
$cipher->OpenSSL->Cipher = 'des-cbc';






?>