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




/** XOR functions Although the keys currently used are symmetric, 
    the JS function doesn't seem to cope well with converting the 
    codes back to char, so a character identifier (the space) was
    implemented instead. 

    End result is a symetric key which requires different functions 
    to en/decrypt

    Essentially in EBC mode at the moment, will change to CBC once 
    the performance impact (especially for the JS Counterpart) has
    been measured

    Will obviously be revisited at some point!
*/


/** Xor the provided string to decrypt it
*
* @arg str - string
* @arg key - string
*
* @return string
*/
function &xordstring($str,&$key){

$keylength = strlen($key);
$kpos = 0;
$en = "";
$k = explode(":",$key);
$str = explode(" ",$str);

foreach ($str as $string){
	 if (strlen($string) == 0){ continue; }
        
	// Convert the character in the key to a charcode and use bitwise XOR
        $b = $string ^ ord($k[1][$kpos]);
	$b = $b ^ ord($k[0][$kpos]);
	
	// Convert the result back to the appropriate character
        $en .= chr($b);

	// Move the key position
	$kpos++;

	// If we're at the end of the key (or something weird's happened and we're at the end of the key, move back to the beginning
	if ($kpos >= $keylength){ $kpos = 0;}
    }
    
    return $en;
}



/** Xor the provided string to encrypt it
*
* @arg str - string
* @arg key - string
*
* @return string
*/
function xorestring(&$str,&$key){

$keylength = strlen($key);
$strlength = strlen($str);
$kpos = 0;
$en = "";
$k = explode(":",$key);

$i = 0;

      while ($i <= $strlength){
	// Convert the char into a charcode
	// Originally passed this through substr, not sure why - tired I guess
        $a = ord($str[$i]);

	// Perform a bitwise XOR
        $b = ($a ^ ord($k[0][$kpos])) ^ ord($k[1][$kpos]);    
        
    
	// add to the string
	$en .= $b." ";
	
	// Move the pointers
	$i++;
	$kpos++;

	// If we're at the end of the cipher, move back to the beginning (EBC mode)
	if ($kpos >= $keylength){ $kpos = 0;}
      }
    
    return $en;
}



/** Generate a key that we know is safe to use with the xorencrypt function
*
* Because we are currently using EBC, key length doesn't have too great an effect on performance, but a big effect on security
*
*
* @return string
*
*/
function genxorekey(){

$x = 0;
$str = '';

// Was excluding certain charcodes because the JS counterpart seems to have occasional issues with them
// leaves 128^62 possible permutations, but figured issue may well be resolved after a few tests
$excludes = array("58","59","60","61","62","63","64","91","92","93","94","95","96");



// Gives us a 2048bit string
// Upped from 1024 because commit cae0ac5 increases the likelihood of key repetition
  while ($x <= 256){
	$key = mt_rand(48,122);
	$key2 = mt_rand(48,122);

	if (in_array($key,$excludes)){ continue; }
	if (in_array($key2,$excludes)){ continue; }
	$str .= chr($key);
	$str2 .= chr($key2);
	$x++;
  }

return $str.":".$str2;
}



/** Create a small amount of padding to prefix and affix cleartext stuff with
*
* Helps to frustrate attackers trying to use known plaintext attacks as the first and last characters of the plaintext string are no longer known
*
* Will be improved further
*
*
*
* @return string
*
*/
function genXorPadding(){

if (BTMain::getConnTypeSSL()){
return "a";
}



$x =0;
$count = mt_rand(5,40);

    while ($x <= $count){

      $chr = mt_rand(65,122);
	if ($chr >=91 && $chr <=96){ continue; }
      $str .= chr($chr) . mt_rand(0,300);
      $x++;
    }


return $str;
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
function encrypt($string,$type,$key = null){
$this->loadConfig();

// Fix for issue 16
if (empty($string)){ $string = ' '; }

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




function encrypt_doubleROT13(&$string,$type){ return "If you've enabled this, you really shouldn't be in charge of Crypto Settings!"; }



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

	  // At some point will add ciphertext analysis to improve this,
	  // though it probably shouldn't ever be used without good reason

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
function decrypt_OpenSSL($ciphertext,$type){
return openssl_decrypt($ciphertext, $this->cipher->OpenSSL->Cipher, $this->keys->$type);
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