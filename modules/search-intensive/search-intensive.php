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

$this->loadModule('search-table');
  $custs = new CustDB;
  $crdtypes=new CredDB;
  $crypt = new Crypto;
  $crypt->safety = 0;

?>

<form class="navbar-search" >
<input type="text" 
    class="search-query" 
    placeholder="Search"
    id='SearchBox'
    onfocus='checkExistingSearch(this.value,"SearchResBox");' 
    onblur='setTimeout("hideSearchDiv(\"SearchResBox\")",300);' 
    onkeyup="SearchTable(this.value,'SearchListing','SearchResBox',0);">
</form>

<div id="SearchResBox" style="display: none"></div>