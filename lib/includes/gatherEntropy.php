<?php
/** Use JS to gather some entropy for key generation (additional will be created on submit)
*
* Copyright (C) 2012 B Tasker
* Released under GNU AGPL V3
* See LICENSE
*
*
* 
*/

defined('_CREDLOCK') or die;

if (!$crypt){
  $crypt = new Crypto;
}


// Are we processing the generated or outputting the field
if ($submitted){


// Submitted details will be full of commas making part of the key predictable. Get rid of it

$x = BTMain::getVar('kLength');
$newkey = '';


      while ($x > 0){

      	$key = Crypto::generateNum(32,254);
	if ($key == 127 ){
	// Skip the delete char
		continue;
	}

      	$newkey .= chr($key);
      	$x--;

      }

	$newkey = base64_encode($newkey);
}else{


?>
	<label for="keyLength">Key Length</label>
	<input id="keyLength" type="text" name="kLength" value="<?php echo $crypt->getKeyLength();?>">
	<input type="hidden" id="countsremaining" value="0" name="clicksremaining">

<?php
}


