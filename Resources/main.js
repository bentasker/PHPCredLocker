/* ************************************************************
Author:  Ben Tasker - BenTasker.co.uk
Description: Main Javascript functions for PHPCredLocker. Most 
  functions currently quick and dirty, will improve in future releases!

License: GNU GPL V2 -  See http://www.gnu.org/licenses/gpl-2.0.html

Repo: https://github.com/bentasker/PHPCredLocker/
---------------------------------------------------------------
Copyright (c) 2012 Ben Tasker

*/




function resizebkgrnd(){
var width = document.documentElement.clientHeight;
var height = width;
//var height = eval( width +16);
var img = document.getElementById('ContentWrap');
//img.style.width = height + 'px';
img.style.minHeight = eval(height * 0.8)+'px';



}



var counter=false;
var cancel='';
var dispcred;

function comparePwds(){
	  
	  
	  var pass = document.getElementById('frmPass');
	  var nomatch = document.getElementById('PassNoMatch');
	  var passscore = document.getElementById('passScore');
	  var minpass = document.getElementById('minpassStrength');
	  
	  
	  if (minpass){
	  
	    
	    var strength = minpass.value;
	    var test;
	    if (strength.indexOf("+") >= 0){
		if (parseInt(passScore.value) > 45){
		 test = true; 
		}else{
		 test = false; 
		}
	      
	    }else{
	      
	      var testvars = strength.split("-");
	      
	      if ((parseInt(passScore.value) > testvars[0])){
		test = true;
		
	      }else{
		
		test = false;
	      }
	      
	      
	      
	      
	      
	    }
	    
	      if (!test){
	    
		nomatch.innerHTML = "Password is too weak";
		nomatch.style.display = 'inline-block';
	   
		return false;
	    
	  }
	  
	  }
	  
	  
	 if (pass.value != document.getElementById('frmPassConf').value){
	   nomatch.innerHTML = "Passwords don't match";
	   nomatch.style.display = 'inline-block';
	   
	   return false;
	 }
	 
	 if (pass.value == null || pass.value == ''){
	   
	   document.getElementById('PassNoMatch').innerHTML = "You must set a password";
	   nomatch.style.display = 'inline-block';
	   return false;
	 }
	 nomatch.style.display = 'none';
	  return true;  
	  
	}
	
function getCreds(id){

  var xmlhttp;
var resp;
var jsonObj;

var clicky = document.getElementById('retrievePassword'+id);
var Address = document.getElementById('Address'+id);
var User = document.getElementById('UserName'+id);
var Pass = document.getElementById('Password'+id);
var Pluginout = document.getElementById('CredPluginOutput'+id);
var key = getKey();


var clickcount = document.getElementById("clickCount"+id);

    if (clickcount.value != 0){
	return; 
    }
    
    clickcount.value = 1;
    clicky.innerHTML = '<i>Retrieving.....</i>';


  if (window.XMLHttpRequest)
  {// code for IE7+, Firefox, Chrome, Opera, Safari
xmlhttp=new XMLHttpRequest();
  }
else
 {// code for IE6, IE5
  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
  }
xmlhttp.onreadystatechange=function()
  {
if (xmlhttp.readyState==4 && xmlhttp.status==200)
    {
      
      
      resp = xordstr(xmlhttp.responseText,key).split('|..|');
    //resp = xmlhttp.responseText.split('|..|');
    if (resp[0] == 0){
     // Request failed, authentication issue maybe? 
      clicky.innerHTML = 'Failed to retrieve credentials. Click to try again';
      return false;
    }
    
    var limit = document.getElementById('defaultInterval').value;
    var cnt = document.getElementById('PassCount'+id);
     cnt.value = limit;
     var count = limit;
      Address.innerHTML = resp[2];
      Pass.innerHTML = resp[1];
      User.innerHTML = resp[3];
      Pluginout.innerHTML = resp[4];
      clicky.innerHTML = 'Displaying Password for ' +count+ ' seconds';
      
      if (counter){
	cancel=1;
	document.getElementById("clickCount"+dispcred).value = 0;
	dispcred=id;
	setTimeout(function() {cancel=false; counter=setInterval("Credtimer('"+id+"')", 1000);},1000);
	return;
      }
      dispcred=id;
      counter=setInterval("Credtimer('"+id+"')", 1000);
      
      }
      
      
      
      

    }
  
  
  
xmlhttp.open("POST","api.php",true);
xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
xmlhttp.send('option=retCred&id='+id);
 
}


function checkSession(){

  var xmlhttp;
var resp;



  if (window.XMLHttpRequest)
  {// code for IE7+, Firefox, Chrome, Opera, Safari
xmlhttp=new XMLHttpRequest();
  }
else
 {// code for IE6, IE5
  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
  }
xmlhttp.onreadystatechange=function()
  {
if (xmlhttp.readyState==4 && xmlhttp.status==200)
    {
    resp = xmlhttp.responseText.split('|..|');
    if (resp[0] == 0){
     // Session Invalid
     
    var cookies = document.cookie.split(";");
   
    for (var i = 0; i < cookies.length; i++){
    KillCookie(cookies[i].split("=")[0]);
    }
     
     window.location.href = "index.php?notif=InvalidSession";
     
     
     
     
      return false;
    }
    
      
      }
      
      
      
      

    }
  
  
  
xmlhttp.open("POST","api.php",true);
xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
xmlhttp.send('option=checkSess');
 
}


function DelCust(id){

  var xmlhttp;
var resp;
var jsonObj;


if (!confirm("Are you sure you want to delete this customer and all associated credentials?")){
 return false; 
}


var credrow = document.getElementById('CustDisp'+id);
var notify = document.getElementById('NotificationArea');
   
if (document.getElementById('Custmenu'+id)){
	var menu = document.getElementById('Custmenu'+id);
      }
     


  if (window.XMLHttpRequest)
  {// code for IE7+, Firefox, Chrome, Opera, Safari
xmlhttp=new XMLHttpRequest();
  }
else
 {// code for IE6, IE5
  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
  }
xmlhttp.onreadystatechange=function()
  {
if (xmlhttp.readyState==4 && xmlhttp.status==200)
    {
    resp = xmlhttp.responseText.split('|..|');
    if (resp[0] == 0 || resp[1] == 0){
     // Request failed, authentication issue maybe? 
      notify.innerHTML += '<div class="alert alert-error">Failed to Delete</div>';
      return false;
    }
      credrow.parentNode.removeChild(credrow);
    notify.innerHTML += '<div class="alert alert-success">Customer and all associated credentials Deleted</div>';


      
	  if (menu){
	  menu.parentNode.removeChild(menu);
	  }
      
      }
      
      
      
      

    }
  
  
  
xmlhttp.open("POST","api.php",true);
xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
xmlhttp.send('option=delCust&id='+id);
 
}


function DelCred(id){

  var xmlhttp;
var resp;
var jsonObj;


if (!confirm("Are you sure you want to delete this credential?")){
 return false; 
}


var credrow = document.getElementById('CredDisp'+id);



var notify = document.getElementById('NotificationArea');


  if (window.XMLHttpRequest)
  {// code for IE7+, Firefox, Chrome, Opera, Safari
xmlhttp=new XMLHttpRequest();
  }
else
 {// code for IE6, IE5
  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
  }
xmlhttp.onreadystatechange=function()
  {
if (xmlhttp.readyState==4 && xmlhttp.status==200)
    {
    resp = xmlhttp.responseText.split('|..|');
    if (resp[0] == 0 || resp[1] == 0){
     // Request failed, authentication issue maybe? 
       notify.innerHTML += '<div class="alert alert-error">Failed to Delete</div>';
      return false;
    }
      credrow.parentNode.removeChild(credrow);
      notify.innerHTML += '<div class="alert alert-success">Credential Deleted</div>';
    
     
      
      }
      
      
      
      

    }
  
  
  
xmlhttp.open("POST","api.php",true);
xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
xmlhttp.send('option=delCred&id='+id);
 
}


function Credtimer(id)
{
  var cnt = document.getElementById('PassCount'+id);
  var count=cnt.value-1;
  cnt.value = count;
  var field = document.getElementById('retrievePassword'+id);
  
  
  
  if (count <= 0 || cancel == 1)
  {
     clearInterval(counter);
     
     field.innerHTML = 'Display Password';
     document.getElementById('Address'+id).innerHTML = '';
     document.getElementById('UserName'+id).innerHTML = '';
     document.getElementById('Password'+id).innerHTML = '';
     document.getElementById('CredPluginOutput'+id).innerHTML = '';
     document.getElementById("clickCount"+id).value = 0;
     return;
  }

  field.innerHTML = 'Displaying Password for ' +count+ ' seconds';
}


function checkNewCred(){

  
  var cred = document.getElementById('frmCredential');
  var user = document.getElementById('frmUser');
  var addr = document.getElementById('frmAddress');
  
if (cred.value.indexOf("http") !== -1){


if (confirm("Click OK to make this credential a hyperlink in the database, click cancel to set not clicky")){

document.getElementById('frmClicky').value = 1;
}







}
}


function checkEditCred(){

  
  var cred = document.getElementById('frmCredential');
  var user = document.getElementById('frmUser');
  var addr = document.getElementById('frmAddress');
  
if (cred.value.indexOf("http") !== -1){


if (confirm("Click OK to make this credential a hyperlink in the database, click cancel to set not clicky")){

document.getElementById('frmClicky').value = 1;
}







}


// See if any have been blanked

if (cred.value == null || cred.value == ''){
 cred.value = ' '; 
}

if (user.value == null || user.value == ''){
 user.value = ' '; 
}

if (addr.value == null || addr.value == ''){
 cred.value = ' '; 
}


return true;
}


function noCredTypes(){
  
  $(document).ready(function(){
    if (document.getElementById('AddCredBtnTop')){
     var btntop =  document.getElementById('AddCredBtnTop');
     btntop.parentNode.removeChild(btntop);
    }

    if (document.getElementById('AddCredBtnBottom')){
     var btntop =  document.getElementById('AddCredBtnBottom');
     btntop.parentNode.removeChild(btntop);
    }
  });
  
  
  
  
  
}


function CreateMenuContent(menu,type,tbl,cellNr, limit, menucode){
  
  var menu = document.getElementById(menu);
  
  var menuentry;
  
  
    var table = document.getElementById(tbl);
  
    if (!table){ return false; }
    
  var lim = 0;
  
  
  var ind;
  var item;
  var str;
  
	for (var r = 0; r < table.rows.length; r++){
		if ( lim == limit) { break; }
		ind = table.rows[r].cells[3].innerHTML;
		if (ind == type ){
		  
		  
		  item = document.createElement('li');
		  item.id = menucode + table.rows[r].cells[2].innerHTML;		  
		  
		  item.innerHTML = "<a href='index.php?option="+table.rows[r].cells[5].innerHTML+"&id="+table.rows[r].cells[2].innerHTML+"'>"+table.rows[r].cells[cellNr].innerHTML+"</a>";
		  menu.appendChild(item);
		  
		  lim = lim + 1;
		  }
		
	}
  
  
  
  
}


/****                  SEARCH FUNCTIONS                 *******/


function positionResults(SearchBox,ResBox){
  
 var search = document.getElementById(SearchBox);
 var res = document.getElementById(ResBox);
 
 res.style.left = search.offsetLeft +'px';
 
 // Set the position, but account for bootstrap's border and padding
 res.style.top = eval( search.offsetTop + search.offsetHeight + 6 )+'px';
 
 res.style.width = search.offsetWidth +'px';
}


function SearchTable(val,tbl,dispdiv,cellNr,e){
  
  // Many thanks to http://www.vonloesch.de/node/23 for the headstart on this function!

var keynum = 0;

if(window.event) { keynum = window.event.keyCode; }  // IE (sucks)
else if(e.which) { keynum = e.which; }    // Netscape/Firefox/Opera

if(keynum === 38) { // up
    //Move selection up
    selectResult('up');
    return;
}

if(keynum === 40) { // down
    //Move selection down
    selectResult('down');
    
    return;
}



  // Reset the display div
  var disp = document.getElementById(dispdiv);   
  disp.innerHTML = '';
  

  // Only search after 3 chars have been entered
  if (val.length < 3){
    return;     
    }
  
  
  document.getElementById('SelectedValue').value=0;
  
  positionResults("SearchBox",dispdiv);
  
  var suche = val.toLowerCase();
  var table = document.getElementById(tbl);
  var res;
  var num = 0;
  var id;
  var ele;
  var add;
  
  
  
  
	for (var r = 0; r < table.rows.length; r++){
		ele = table.rows[r].cells[cellNr].innerHTML.replace(/<[^>]+>/g,"");
		
		if ((ele.toLowerCase().indexOf(suche)>=0 ) || ((suche.indexOf(":") >= 0) && (table.rows[r].cells[1].innerHTML.toLowerCase().indexOf(suche)>=0))){
		  
		num=num+1;  
		  // Work out how to display
		  
		   res = document.createElement('div');
		   res.id = 'SearchResult'+num; 
		   res.className = 'SearchResult';
		   res.setAttribute('link',table.rows[r].cells[5].innerHTML);
		   res.setAttribute('entID',table.rows[r].cells[2].innerHTML);
		   
		   if (table.rows[r].cells[4].innerHTML != null && table.rows[r].cells[4].innerHTML != ''){
		      id = table.rows[r].cells[4].innerHTML;
		     
		   }else{
		     id = 'id';
		     
		   }
		   
		    if (table.rows[r].cells[6]){
		      
		     add = "&"+ table.rows[r].cells[6].innerHTML;
		    }else{
		     add = ''; 
		    }
		    
		   res.setAttribute('onclick',"window.location.href = 'index.php?option="+table.rows[r].cells[5].innerHTML + "&"+id+"="+table.rows[r].cells[2].innerHTML+add+"';");
		   res.setAttribute('frmName',id);
		    
		    res.innerHTML = table.rows[r].cells[1].innerHTML + " " +table.rows[r].cells[cellNr].innerHTML;
		    
		    
		  disp.appendChild(res);
		  
		  disp.style.display = 'block';
		  

		  }
		
	}
  
}


function selectResult(dir){
 var ind;
 var SelIndex = document.getElementById('SelectedValue');
 var SearchLength = document.getElementById('SearchResBox').childNodes.length;
 
 var SearchResult ;
 
 
 if (dir == 'down'){
   
   if (SelIndex.value != 0){
   document.getElementById("SearchResult" + parseInt(SelIndex.value)).className = 'SearchResult';
   }
 
 if (SearchLength == SelIndex.value){
  SelIndex.value=0; 
 }
 
 ind = eval(parseInt(SelIndex.value) + 1);
 

 
   
  }else{
    document.getElementById("SearchResult" + parseInt(SelIndex.value)).className = 'SearchResult';
    
    if (SelIndex.value == 1){
 ind = SearchLength;     
    }else{
 ind = eval(parseInt(SelIndex.value) - 1); 
    }
    
    
   
   
 }
 
 SearchResult = document.getElementById('SearchResult'+ind);
 SearchResult.className = 'SearchResult SearchResultActive';
  SelIndex.value = ind; 
  document.getElementById('SrchOpt').value = SearchResult.getAttribute('link');
  document.getElementById('SrchID').name = SearchResult.getAttribute('frmName');
  document.getElementById('SrchID').value = SearchResult.getAttribute('entID');
  document.getElementById('SearchBox').focus();
 
}


function hideSearchDiv(dispdiv){
  var div = document.getElementById(dispdiv);
  
  
  
  for (opacity = 10; opacity > 0; opacity--){
    
    
  div.style.opacity = '0.'+opacity;
    
  }
 
  
  div.style.display = 'none';
  div.style.opacity = '1';
}


function checkExistingSearch(val,div){
      if (val.length > 3){
	  document.getElementById(div).style.display = 'block';
	  }
}


function setUpMenus(){

jQuery(document).ready(function() {

  if (!document.getElementById('SearchListing')){ return; }
  
CreateMenuContent('TypeDropDownMenu',2,'SearchListing',0, 100, 'TypeMenu');
CreateMenuContent('CustDropDownMenu',1,'SearchListing',0, 5, 'Custmenu');
var menu = document.getElementById('CustDropDownMenu');
var ele = document.createElement('li');
ele.className='divider';

menu.appendChild(ele);

ele = document.createElement('li');
ele.className = 'viewAll';
ele.innerHTML = "<a href='index.php?option=viewCustomers'>View All</a></li>";
menu.appendChild(ele);
});



}





/** Use bitwise Xor to encrypt the supplied string with the supplied key and return a base64 encoded representation of the character codes
 * Did try converting back to char, but things broke quite monumentally. Realistically makes little difference to an attacker, though it is a pain
 * as it means a longer request.
 * 
 */
function xorestr(str,key){

var enc='';
var keypos = 0;

  for (var i=0; i<str.length;i++) {


        var a = str.charCodeAt(i);
        var b = a ^ key.charCodeAt(keypos) ;    
        enc += b.toString()+" ";

	keypos++;
	if (keypos >= key.length){ keypos = 0;}
    }





return enc;
}


function xordstr(str,key){

var enc='';
var keypos = 0;
var str = str.split(" ");

  for (var i=0; i<str.length;i++) {

	if (str[i].length == 0){ continue; }
        var a = str[i];
        var b = a ^ key.charCodeAt(keypos) ;    
        enc += String.fromCharCode(b);

	keypos++;
	if (keypos >= key.length){ keypos = 0;}
    }





return enc;
}



function loginReqProcess(){
  
 var entered = document.getElementById('FrmPassPlace'); 
 var pass = document.getElementById('FrmPass');
 
 // Calculate the encrypted value
 pass.value = xorestr(entered.value,getKey());
  
 // Update the placeholder so we're not accompanying our encrypted text with the plaintext value
 var a = '';
 for (var i = 0;i < entered.length; i++){   
   a += "a";
 }
 entered.value = a;
 
  return true;
}




function checkKeyAvailable(){
  
 if(typeof getKey != 'function') {
   
   confirm("Key retrieval failed - Attempting to rectify, Click OK to continue");
   
   var cookies = document.cookie.split(";");
   
  for (var i = 0; i < cookies.length; i++){
    KillCookie(cookies[i].split("=")[0]);
    }
   
   window.location.href = location.reload();
   
 }
  
  
  
  
  
}



function KillCookie(name) {
    createCookie(name,"",-1);
}



function createCookie(nme,val,expire) {
    if (expire) {
    	var date = new Date();
    	date.setTime(date.getTime()+(expire*24*60*60*1000));
    	var expires = "; expires="+date.toGMTString();
    }
    else var expires = "";
    document.cookie = nme+"="+val+expires+"; path=/";
}
