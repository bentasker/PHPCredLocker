<?php
/** Crypto Functions
*
* Copyright (C) 2012 B Tasker
* Released under GNU GPL V2
* See LICENSE
*
*/

class Crypto{


public $safety = 1;

/** Load the Crypto Settings
*
*/
function loadConfig(){
if (!isset($this->keys)){
require 'conf/crypto.php';
$this->keys = $crypt;
$this->cipher = $cipher;
}
}



/** Pass string to configured engine for encryption
*
* @arg string - plaintext string to encrypt
* @arg type - String - Defines the type of string to decrypt (ServerCred, CustomerName etc)
*
* @return string - Ciphertext
*
*/
function encrypt($string,$type){
$this->loadConfig();
$fn = "encrypt_{$this->cipher->Engine}";
$ciphertext = $this->$fn($string,$type);

if ($this->safety == 1){
unset($this->keys);
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
function encrypt_OpenSSL($string,$type){
return openssl_encrypt($string, $this->cipher->OpenSSL->Cipher, $this->keys->$type);
}




/** Encrypt the string using MCrypt
*
* @arg string string
* @arg type INT
*
* @return ciphertext string
*/ 
function encrypt_Mcrypt($string,$type){

return mcrypt_encrypt($this->cipher->Encryption,$this->keys->$type,$string, $this->cipher->MCrypt->mode);
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
return mcrypt_decrypt($this->cipher->Encryption,$this->keys->$type,$ciphertext, $this->cipher->MCrypt->mode);
}






}











?> 
