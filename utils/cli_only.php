<?php
/** PHPCredLocker CLI Utility functions
*
* Re-Generates Crypto keys and re-encrypts all stored data - Likely to be a long process!
*
* Copyright (C) 2014 B Tasker
* Released under GNU AGPL V3
* See LICENSE
*
*/


// Limit to command line usage
if (!isset($GLOBALS['argv'])){
  echo "No Web access";
  die;
} 




class CLIOutput{

	function _($str){
		echo "$str\n";
	}

}


class CLIInput{

	function read($msg){
	      fwrite(STDOUT,"$msg: ");
	      return trim(fgets(STDIN));
	}

}
