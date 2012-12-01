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

/** Basic Keys                                       **/
// Group keys, these refer to fields that could be used by an attacker to identify which stored creds are worth trying to crack
$crypt->Groups = '9876';
$crypt->Customer = '1234';
$crypt->CredType = 'zxy';
$crypt->auth = 'b170d1920cfe9d1000107c4a59f6cc81';


$cipher->keyLength = 1024;


/**  Default: auto  - will use OpenSSL if available

Usually you won't want to change this, if however you've just migrated from a server that only had Mcrypt you may want to set to use that

Values:
auto
OpenSSL
Mcrypt

**/
$cipher->Engine = 'auto';


/*     MCrypt Specific             */
$cipher->MCrypt->Encryption = MCRYPT_RIJNDAEL_256;
$cipher->MCrypt->mode = MCRYPT_MODE_ECB;


/*    OpenSSL Specific            */
$cipher->OpenSSL->Cipher = 'des-cbc';




// These are used to encrypt the creds themselves.
$crypt->Cre1 = 'abcd';
$crypt->Cre2 = 'defg';
$crypt->Cre3 = 'hijk';