<?php
/** Language Strings Handler
*
* Implemented to allow users to easily change terminology used in the UI
*
* Copyright (C) 2012 B Tasker
* Released under GNU GPL V2
* See LICENSE
*
*/ 
defined('_CREDLOCK') or die;
class Lang{


    function _($string){
	// This function gets called a lot, reduce disk reads
	if (!isset($GLOBALS['LANGSTRINGS'])){
	require 'conf/lang.php';
	$GLOBALS['LANGSTRINGS'] = $lang;
	return $lang[$string];

	}else{

	return $GLOBALS['LANGSTRINGS'][$string];
	}


    }


}

?>