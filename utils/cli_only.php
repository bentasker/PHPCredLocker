<?php

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
