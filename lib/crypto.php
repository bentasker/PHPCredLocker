<?php
/** Crypto Functions
*
* Copyright (C) 2012 B Tasker
* Released under GNU GPL V2
* See LICENSE
*
*/
defined('_CREDLOCK') or die;

class Crypto{

protected $keys;
protected $cipher;


public $safety = 1;

/** Load the Crypto Settings
*
*/
protected function loadConfig(){
if (!isset($this->keys)){
require 'conf/crypto.php';
$this->keys = $crypt;
$this->cipher = $cipher;
}
}



/** Add a key to the config file
*
* @arg newkey string
* @arg newtype - string
*
* @return
*/
function addKey(&$newkey,$newid){


$this->loadConfig();

// Trim to the required keylength
$newkey = substr( $newkey, 0,$this->cipher->keyLength);


   $cryptconf = fopen(getcwd() . '/conf/crypto.php','a');
	  
	  


$str = "\n\$crypt->Cre$newid = '" . str_replace("'",'"',$newkey) . "';\n";

fwrite($cryptconf,$str);
	


unset($newkey);
unset($str);
fclose($cryptconf);
return true;
}




/** Pass string to configured engine for encryption
*
* @arg string - plaintext string to encrypt
* @arg type - String - Defines the type of string to decrypt (ServerCred, CustomerName etc)
*
* @return string - Ciphertext
*
*/
function encrypt(&$string,$type,$key = null){
$this->loadConfig();


    if ($this->cipher->Engine == 'auto'){

	  if (function_exists('openssl_encrypt')){
	  $this->cipher->Engine = 'OpenSSL';
	  }else{
	  $this->cipher->Engine = 'Mcrypt';
	  }
    }

$fn = "encrypt_{$this->cipher->Engine}";



  if ($type != 'ONEWAY'){
    $ciphertext = $this->$fn($string,$type);

      if ($this->safety == 1){
      unset($this->keys);
      }
  }else{
  unset($this->keys);
  $this->keys->ONEWAY = $key;
  $ciphertext = $this->$fn($string,$type);

  }
return $ciphertext;
}



/** Encrypt the string using OpenSSL 
*
* @arg string string
* @arg type INT
*
* @return ciphertext string
*/ 
function encrypt_OpenSSL(&$string,$type){
return openssl_encrypt($string, $this->cipher->OpenSSL->Cipher, $this->keys->$type);
}


function encrypt_doubleROT13($string,$type){ return "If you've enabled this, you really shouldn't be in charge of Crypto Settings!"; }


/** Encrypt the string using MCrypt
*
* @arg string string
* @arg type INT
*
* @return ciphertext string
*/ 
function encrypt_Mcrypt(&$string,$type){

return mcrypt_encrypt($this->cipher->MCrypt->Encryption,$this->keys->$type,$string, $this->cipher->MCrypt->mode);
}


/** Pass ciphertext to the configured engine for decryption
*
* @arg string string
* @arg type INT
*
* @return plaintext string
*/ 
function decrypt($ciphertext,$type){
$this->loadConfig();

 if ($this->cipher->Engine == 'auto'){

	  if (function_exists('openssl_encrypt')){
	  $this->cipher->Engine = 'OpenSSL';
	  }else{
	  $this->cipher->Engine = 'Mcrypt';
	  }
    }


$fn = "decrypt_{$this->cipher->Engine}";
$plaintext = $this->$fn($ciphertext,$type);


  if ($this->safety == 1){
  unset($this->keys);
  }


return $plaintext;
}



/** Decrypt ciphertext using OpenSSL
*
* @arg string string
* @arg type INT
*
* @return plaintext string
*/
function decrypt_OpenSSL($string,$type){
return openssl_decrypt($string, $this->cipher->OpenSSL->Cipher, $this->keys->$type);
}



/** Decrypt ciphertext using Mcrypt
*
* @arg string string
* @arg type INT
*
* @return plaintext string
*/
function decrypt_Mcrypt($ciphertext,$type){
return mcrypt_decrypt($this->cipher->MCrypt->Encryption,$this->keys->$type,$ciphertext, $this->cipher->MCrypt->mode);
}






}











?> 
