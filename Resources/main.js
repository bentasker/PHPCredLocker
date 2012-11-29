var count=30;
var counter=''

	
	function comparePwds(){
	  
	  
	  var pass = document.getElementById('frmPass');
	  var nomatch = document.getElementById('PassNoMatch');
	  var passscore = document.getElementById('passScore');
	  
	  if (passscore.value != 'strong' && passscore.value != 'stronger'){
	    
	    nomatch.innerHTML = "Password is too weak";
	   nomatch.style.display = 'inline-block';
	   
	   return false;
	    
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
	
	
	function validateUserAdd(){
	  var username = document.getElementById('frmUsername');
	  var RealName = document.getElementById('frmRName');
	  var error = 0;
	  
	 if (username.value == null || username.value == ''){
	  username.className = 'frmEntryMissed'; 
	  error = 1;
	 }
	 
	 
	   if (RealName.value == null || RealName.value == ''){
	  RealName.className = 'frmEntryMissed'; 
	  error = 1;
	 }
	  
	  if (!comparePwds()){
	    
	   error = 1; 
	  }
	  
	  if (error == 1){
	    
	   alert("Please correct input errors and re-submit");
	   return false;
	  }
	  
	  return true;
	}
	
	
	
	function validateUserEdit(){
	  
	  var username = document.getElementById('frmUsername');
	  var RealName = document.getElementById('frmRName');
	  var pass = document.getElementById('frmPass');
	  var error = 0;
	  
	  
	  if (username.value == null || username.value == ''){
	  username.className = 'frmEntryMissed'; 
	  error = 1;
	 }
	 
	 
	   if (RealName.value == null || RealName.value == ''){
	  RealName.className = 'frmEntryMissed'; 
	  error = 1;
	 }
	  
	  
	  if (pass.value != null && pass.value != ''){
	  if (!comparePwds()){
	    
	   error = 1; 
	  }
	  }
	  
	  
	  if (error == 1){
	    
	   alert("Please correct input errors and re-submit");
	   return false;
	  }
	  
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
     // Request failed, authentication issue maybe? 
      clicky.innerHTML = 'Failed to retrieve credentials. Click to try again';
      return false;
    }
     
      Address.innerHTML = resp[2];
      Pass.innerHTML = resp[1];
      User.innerHTML = resp[3];
      count=30;
      clicky.innerHTML = 'Displaying Password for ' +count+ ' seconds';
      counter=setInterval("Credtimer('"+id+"')", 1000);
      
      }
      
      
      
      

    }
  
  
  
xmlhttp.open("POST","api.php",true);
xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
xmlhttp.send('option=retCred&id='+id);
 
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




function delGroup(id){

  var xmlhttp;
var resp;
var jsonObj;


if (!confirm("Are you sure you want to delete this group (any credentials recorded against the group will be deleted)?")){
 return false; 
}


var credrow = document.getElementById('GroupDisp'+id);



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
      notify.innerHTML += '<div class="alert alert-success">Group Deleted</div>';
    
     
      
      }
      
      
      
      

    }
  
  
  
xmlhttp.open("POST","api.php",true);
xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
xmlhttp.send('option=delGroup&id='+id);
 
}







function delUser(id){

  var xmlhttp;
var resp;
var jsonObj;


if (!confirm("Are you sure you want to delete this user?")){
 return false; 
}


var credrow = document.getElementById('User'+id);



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
      notify.innerHTML += '<div class="alert alert-success">User Deleted</div>';
    
     
      
      }
      
      
      
      

    }
  
  
  
xmlhttp.open("POST","api.php",true);
xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
xmlhttp.send('option=delUser&id='+id);
 
}






function delCredType(id){

  var xmlhttp;
var resp;
var jsonObj;


if (!confirm("Are you sure you want to delete this Credential Type (any associated credentials will be deleted)?")){
 return false; 
}


var credrow = document.getElementById('CredType'+id);



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
      notify.innerHTML += '<div class="alert alert-success">Credential Type Deleted</div>';
    
     
      
      }
      
      
      
      

    }
  
  
  
xmlhttp.open("POST","api.php",true);
xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
xmlhttp.send('option=delCredType&id='+id);
 
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
      notify.innerHTML += '<div class="alert alert-success">Customer and all associated credentials Deleted</div>';
    
     
      
      }
      
      
      
      

    }
  
  
  
xmlhttp.open("POST","api.php",true);
xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
xmlhttp.send('option=delCred&id='+id);
 
}





function Credtimer(id)
{
  count=count-1;
  
  var field = document.getElementById('retrievePassword'+id);
  
  
  
  if (count <= 0)
  {
     clearInterval(counter);
     field.innerHTML = 'Display Password';
     document.getElementById('Address'+id).innerHTML = '';
     document.getElementById('UserName'+id).innerHTML = '';
     document.getElementById('Password'+id).innerHTML = '';
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