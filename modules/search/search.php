<?php
/** Search Module
*
* Copyright (C) 2012 B Tasker
* Released under GNU GPL V2
* See LICENSE
*
*/
defined('_CREDLOCK') or die;

if (!BTMain::getUser()->name){ return; }


  $custs = new CustDB;
  $crdtypes=new CredDB;
  $crypt = new Crypto;
  $crypt->safety = 0;

?>

<form class="navbar-search" id='SearchForm' name='SearchForm'>
<input type="hidden" name="option" value="" id="SrchOpt">
<input type="hidden" name="id" value="" id="SrchID">


<input type="text" 
    autocomplete="off"
    class="search-query" 
    placeholder="Search"
    id='SearchBox'
    onfocus='checkExistingSearch(this.value,"SearchResBox");' 
    onblur='setTimeout("hideSearchDiv(\"SearchResBox\")",300);' 
    onkeyup="SearchTable(this.value,'SearchListing','SearchResBox',0);">


<input type="hidden" id="SelectedValue" autocomplete="off" value="0" disabled="disabled">
</form>

<div id="SearchResBox" style="display: none"></div>



