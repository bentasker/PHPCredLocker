/* ************************************************************
Author:  Ben Tasker - BenTasker.co.uk
Description: Main Javascript functions for PHPCredLocker. Most 
  functions currently quick and dirty, will improve in future releases!

License: GNU GPL V2 -  See http://www.gnu.org/licenses/gpl-2.0.html

Repo: https://github.com/bentasker/PHPCredLocker/
---------------------------------------------------------------
Copyright (c) 2012 Ben Tasker

*/


var counter=false, cancel='', dispcred, interval;


function resizebkgrnd(){
  
var width = document.documentElement.clientHeight, height = width, img;
img = document.getElementById('ContentWrap');
img.style.minHeight = eval(height * 0.8)+'px';



}


function CreateMenuContent(menu,type,tbl,cellNr, limit, menucode){
  
  var  menuentry, ind, item, str,
  lim = 0, 
  menu = document.getElementById(menu),  
  table = document.getElementById(tbl);
  
    if (!table){ return false; }
    
  
  
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




function Credtimer(id)
{
  var count,
  cnt = document.getElementById('PassCount'+id),
  field = document.getElementById('retrievePassword'+id);
 
 
  count=cnt.value-1;
  cnt.value = count;
  
   
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





function noCredTypes(){
  
  $(document).ready(function(){
    var btntop;
    
    if (document.getElementById('AddCredBtnTop')){
     btntop =  document.getElementById('AddCredBtnTop');
     btntop.parentNode.removeChild(btntop);
    }

    if (document.getElementById('AddCredBtnBottom')){
     btntop =  document.getElementById('AddCredBtnBottom');
     btntop.parentNode.removeChild(btntop);
    }
  });
  
  
  
  
  
}






/*********              Validation Stuff                       ****/



function loginReqProcess(){
  
 var i,
 a='',
 entered = document.getElementById('FrmPassPlace'),
 pass = document.getElementById('FrmPass');
 
 // Calculate the encrypted value
 pass.value = xorestr(entered.value,retAuthKey());
  
 // Update the placeholder so we're not accompanying our encrypted text with the plaintext value
 
 for (i = 0;i < entered.length; i++){   
   a += "a";
 }
 entered.value = a;
 
  return true;
}



function checkNewCred(){

  
  var cred = document.getElementById('frmCredential'),
      user = document.getElementById('frmUser'),
      addr = document.getElementById('frmAddress');
  
if (cred.value.indexOf("http") !== -1){


if (confirm("Click OK to make this credential a hyperlink in the database, click cancel to set not clicky")){

document.getElementById('frmClicky').value = 1;
}
}
}




function checkEditCred(){

  
  var cred = document.getElementById('frmCredential'),
      user = document.getElementById('frmUser'),
      addr = document.getElementById('frmAddress');
  
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



function comparePwds(){
	  
	  
	  var strength, test,testvars,
	  pass = document.getElementById('frmPass'),
	  nomatch = document.getElementById('PassNoMatch'),
	  passscore = document.getElementById('passScore'),
	  minpass = document.getElementById('minpassStrength');
	  
	  
	  if (minpass){
	  
	    
	    strength = minpass.value;
	    
	    if (strength.indexOf("+") >= 0){
		if (parseInt(passScore.value) > 45){
		 test = true; 
		}else{
		 test = false; 
		}
	      
	    }else{
	      
	      testvars = strength.split("-");
	      
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
	
	
	
	
	
/**********                 AJAX                                  *****/	
	
	
	
function getCreds(id){

var xmlhttp, resp, jsonObj, limit, cnt, count, option,
    clicky = document.getElementById('retrievePassword'+id),
    Address = document.getElementById('Address'+id),
    User = document.getElementById('UserName'+id),
    Pass = document.getElementById('Password'+id),
    Pluginout = document.getElementById('CredPluginOutput'+id),
    key = retKey(),
    clickcount = document.getElementById("clickCount"+id);

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
      
      
      
      resp = decryptAPIResp(xmlhttp.responseText,key).split(getDivider());
    
      // Check for an invalid verb response
    if (resp[1] == 2){
     return unknownAPICommand(); 
    }
      
    if (resp[1] == 0){
     // Request failed, authentication issue maybe? 
      clicky.innerHTML = 'Failed to retrieve credentials. Click to try again';
      return false;
    }
    
    limit = document.getElementById('defaultInterval').value;
    cnt = document.getElementById('PassCount'+id);
     cnt.value = limit;
    count = limit;
      Address.innerHTML = resp[3];
      Pass.innerHTML = resp[2];
      User.innerHTML = resp[4];
      Pluginout.innerHTML = resp[5];
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
  
  
  
  
 
  option = cryptReq('retCred');
xmlhttp.open("POST","api.php",true);
xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
xmlhttp.send('option='+option+'&id='+id);
 
}






function checkSession(){

  var xmlhttp, resp, cookies, option, key = retKey();



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
    resp = decryptAPIResp(xmlhttp.responseText,key).split(getDivider());
    // Check for an invalid verb response
    if (resp[1] == 2){
     return unknownAPICommand(); 
    }
    
    if (resp[1] == 0){
     // Session Invalid
     
    cookies = document.cookie.split(";");
   
    for (var i = 0; i < cookies.length; i++){
    KillCookie(cookies[i].split("=")[0]);
    }
     
     window.location.href = "index.php?notif=InvalidSession";
     
     
     
     
      return false;
    }
    
      
      }
      
      
      
      

    }
  
  
  
  option = cryptReq('checkSess');
  // Add an id, it's completely pointless but sessioncheck requests are the only ones not specifying an id - bit easy to check
xmlhttp.open("POST","api.php",true);
xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
xmlhttp.send('option='+option+'&id='+Math.floor((Math.random()*100)+1));
 
}


function DelCust(id){

  var xmlhttp, resp, jsonObj, credrow, notify, option, key = retKey();


if (!confirm("Are you sure you want to delete this customer and all associated credentials?")){
 return false; 
}


    credrow = document.getElementById('CustDisp'+id);
    notify = document.getElementById('NotificationArea');
   
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
    resp = decryptAPIResp(xmlhttp.responseText,key).split(getDivider());
    // Check for an invalid verb response
    if (resp[1] == 2){
     return unknownAPICommand(); 
    }
    if (resp[1] == 0 || resp[2] == 0){
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
  
  
  
  option = cryptReq('delCust');
xmlhttp.open("POST","api.php",true);
xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
xmlhttp.send('option='+option+'&id='+id);
 
}


function DelCred(id){

  var xmlhttp, resp, jsonObj, credrow, notify, option, key = retKey();


if (!confirm("Are you sure you want to delete this credential?")){
 return false; 
}


  credrow = document.getElementById('CredDisp'+id);
  notify = document.getElementById('NotificationArea');


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
    resp = decryptAPIResp(xmlhttp.responseText,key).split(getDivider());
    // Check for an invalid verb response
    if (resp[1] == 2){
     return unknownAPICommand(); 
    }
    if (resp[1] == 0 || resp[2] == 0){
     // Request failed, authentication issue maybe? 
       notify.innerHTML += '<div class="alert alert-error">Failed to Delete</div>';
      return false;
    }
      credrow.parentNode.removeChild(credrow);
      notify.innerHTML += '<div class="alert alert-success">Credential Deleted</div>';
    
     
      
      }
      
      
      
      

    }
  
  
  option = cryptReq('delCred');
xmlhttp.open("POST","api.php",true);
xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
xmlhttp.send('option='+option+'&id='+id);
 
}








/****                  SEARCH FUNCTIONS                 *******/


function positionResults(SearchBox,ResBox){
  
 var search = document.getElementById(SearchBox),
      res = document.getElementById(ResBox);
 
 res.style.left = search.offsetLeft +'px';
 
 // Set the position, but account for bootstrap's border and padding
 res.style.top = eval( search.offsetTop + search.offsetHeight + 6 )+'px';
 
 res.style.width = search.offsetWidth +'px';
}


function SearchTable(val,tbl,dispdiv,cellNr,e){
  
  // Many thanks to http://www.vonloesch.de/node/23 for the headstart on this function!

var disp, suche, table, res, num=0, id, ele, add, r,
    keynum = 0;

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
  disp = document.getElementById(dispdiv);   
  disp.innerHTML = '';
  

  // Only search after 3 chars have been entered
  if (val.length < 3){
    return;     
    }
  
  
  document.getElementById('SelectedValue').value=0;
  
  positionResults("SearchBox",dispdiv);
  
  suche = val.toLowerCase();
  table = document.getElementById(tbl);

  
  
  
	for ( r = 0; r < table.rows.length; r++){
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
 var ind, SearchResult,
     SelIndex = document.getElementById('SelectedValue'),
     SearchLength = document.getElementById('SearchResBox').childNodes.length;
 

 
 
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
   var menu,ele;
   
CreateMenuContent('TypeDropDownMenu',2,'SearchListing',0, 100, 'TypeMenu');
CreateMenuContent('CustDropDownMenu',1,'SearchListing',0, 5, 'Custmenu');
menu = document.getElementById('CustDropDownMenu');
ele = document.createElement('li');
ele.className='divider';

menu.appendChild(ele);

ele = document.createElement('li');
ele.className = 'viewAll';
ele.innerHTML = "<a href='index.php?option=viewCustomers'>View All</a></li>";
menu.appendChild(ele);
});



}


/*****                            Crypto Functions                                  ******/


/** Use bitwise Xor to encrypt the supplied string with the supplied key and return a base64 encoded representation of the character codes
 * Did try converting back to char, but things broke quite monumentally. Realistically makes little difference to an attacker, though it is a pain
 * as it means a longer request.
 * 
 */
function xorestr(str,key){

var a, b,
    enc='',
    keypos = 0;

  for (var i=0; i<str.length;i++) {


        a = str.charCodeAt(i);
        b = a ^ key.charCodeAt(keypos) ;    
        enc += b.toString()+" ";

	keypos++;
	if (keypos >= key.length){ keypos = 0;}
    }





return enc;
}


function xordstr(str,key){

var a, b,
    enc='',
    keypos = 0,
    str = str.split(" ");

  for (var i=0; i<str.length;i++) {

	if (str[i].length == 0){ continue; }
        a = str[i];
        b = a ^ key.charCodeAt(keypos) ;    
        enc += String.fromCharCode(b);

	keypos++;
	if (keypos >= key.length){ keypos = 0;}
    }

return enc;
}


function unknownAPICommand(){
 // The API reports that the verb used wasn't recognised. We need to refresh the key file  
  
  var sess,sessid,parent,frm,notify;
  
 
  
  
  
  sess = document.getElementById("kFile");
  sessid = sess.getAttribute('src');
  parent = sess.parentNode;
  notify = document.getElementById('NotificationArea')
  clearInterval(sesscheck);
 
 
 
 notify.innerHTML += "<div id='apiError' class='alert alert-error'>API Error Detected</div>";
 
 if(!confirm("The API reported an error, attempting to rectify. Click OK to try and rectify (screen will refresh)")){
  return; 
 }
  notify.removeChild(document.getElementById('apiError'));
  notify.innerHTML = "<div id='apiError' class='alert alert-info'>Attempting to rectify API issue. Window will refresh when ready</div>";
 
 
  parent.removeChild(sess);
  
  
  frm = document.createElement('iframe');
  frm.setAttribute('id','kfile');
  frm.setAttribute('src',sessid);
  frm.style.width = '0px';
  frm.style.height = '0px';
  frm.style.border = '0px';
  document.body.appendChild(frm);
  // Wait 500 milliseconds so we can be sure it's loaded
  interval = setInterval("reloadKeyf()",500);
  
 
}



function reloadKeyf(){
  var frm;
  

  
  frm = document.getElementById('kfile');
  frm.contentWindow.document.cookie = 'PHPCredLockerKeySet=0;';

  
  frm.contentWindow.location.reload(true);
  window.location.reload(true);
  
}







function decryptAPIResp(str,key){
 return Base64.decode(xordstr(Base64.decode(str),key));
}


function getDivider(){
 return getDelimiter(); 
}


function getTerms(a){
  return Base64.decode(getTerminology(a));
}


function cryptReq(str){
  /* We retrieve the key here (even though it's available to the parent) 
   * because we may want to implement a second key used for sending requests,
   * whether that's a symmetric or asymetric key.
   */  
  var key = retKey(), 
  div = getDivider();
 return encodeURIComponent(Base64.encode(xorestr(Base64.encode(genPadding() + div + getTerms(str) + div + genPadding()),key))); 
}


/** Really not that familiar with random string generation in JS, but this seems to work! */
function genPadding(){
  var i,c,
      a='';

  c = Math.random().toString(10).substring(2,3);
  
  for (i=0;i < c;i++){  
   a += Math.random().toString(10).substring(Math.random().toString(10).substring(2,3));
  }

 return a; 
}


function retAuthKey(){
 return retKey(); 
}

function retKey(){
 return Base64.decode(getKey());
}


function checkKeyAvailable(){
  
 if(typeof getKey != 'function') {
   
   confirm("Key retrieval failed - Attempting to rectify, Click OK to continue");
   
   var i,
	cookies = document.cookie.split(";");
   
  for (i = 0; i < cookies.length; i++){
    KillCookie(cookies[i].split("=")[0]);
    }
   
   window.location.href = location.reload();
   
 }
  
  
  
  
  
}






/*********                  MMMMMMMMMMMM COOKIES!                 ******/


function KillCookie(name) {
    createCookie(name,"",-1);
}



function createCookie(nme,val,expire) {
  var expires, date;
  
    if (expire) {
    	var date = new Date();
    	date.setTime(date.getTime()+(expire*24*60*60*1000));
    	expires = "; expires="+date.toGMTString();
    }
    else expires = "";
    document.cookie = nme+"="+val+expires+"; path=/";
}
