/* ************************************************************
Author:  Ben Tasker - BenTasker.co.uk
Description: Admin Javascript functions for PHPCredLocker. Most 
  functions currently quick and dirty, will improve in future releases!

License: GNU AGPL V3 -  See http://www.gnu.org/licenses/agpl-3.0.html

Repo: https://github.com/bentasker/PHPCredLocker/
---------------------------------------------------------------
Copyright (c) 2012 Ben Tasker

*/
	function checkNewGroup(){
	 var nme =  document.getElementById('frmName');
	 
	 if (nme.value.length <= 0){
	      nme.className += ' frmEntryMissed'; 
	    return false;
	   
	 }
	  
	  return true;
	}


	function checkAddCredType(){
	  var nme = document.getElementById('frmName'),
	  ent = document.getElementById('ClickDiv').className.indexOf('EntropyGenerated');
	  
	  
	  if (nme.value.length <= 0){
	   nme.className += ' frmEntryMissed'; 
	    return false;
	  }
	  
	  if (ent >= 0){
	   return true; 
	  }
	    alert("You must generate Entropy");
	    return false;
	    
	}



	function validateUserAdd(){
	  var error = 0, username = document.getElementById('frmUsername'),
	      RealName = document.getElementById('frmRName');
	  
	  
	 if (username.value.length <= 0){
	  username.className = 'frmEntryMissed'; 
	  error = 1;
	 }
	 
	 
	   if (RealName.value.length <= 0){
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
	  
	  var username = document.getElementById('frmUsername'),
	  RealName = document.getElementById('frmRName'),
	  pass = document.getElementById('frmPass'),
	  error = 0;
	  
	  
	  if (username.value.length <= 0 ){
	  username.className = 'frmEntryMissed'; 
	  error = 1;
	 }
	 
	 
	   if (RealName.value.length <= 0 ){
	  RealName.className = 'frmEntryMissed'; 
	  error = 1;
	 }
	  
	  
	  if (pass.value.length > 0){
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


function delGroup(id){

  var xmlhttp, resp,jsonObj, option, 
  key = retKey(),
  credrow = document.getElementById('GroupDisp'+id),
  notify = document.getElementById('NotificationArea');
  


if (!confirm("Are you sure you want to delete this group (any credentials recorded against the group will be deleted)?")){
 return false; 
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
      notify.innerHTML += '<div class="alert alert-success">Group Deleted</div>';
    
     
      
      }
      
      
      
      

    }
  
  
  option = cryptReq('delGroup',key);
  
xmlhttp.open("POST","api.php",true);
xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
xmlhttp.send('option='+option+'&id='+id);
 
}




function delUser(id){

  var xmlhttp, resp, jsonObj, option, 
  key=retKey(),
  credrow = document.getElementById('User'+id),
  notify = document.getElementById('NotificationArea');  


if (!confirm("Are you sure you want to delete this user?")){
 return false; 
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
      notify.innerHTML += '<div class="alert alert-success">User Deleted</div>';
    
     
      
      }
      
      
      
      

    }
  
  
  option = cryptReq('delUser',key);
  
xmlhttp.open("POST","api.php",true);
xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
xmlhttp.send('option='+option+'&id='+id);
 
}



function submitSearchCred(){
  
  var i,option,
      key=retKey(),
      types = document.getElementsByClassName("CredTypeID"),
      pass = Base64.encode(xorestr(document.getElementById("frmPass").value,key)),
      option = cryptReq('searchCredValue',key),
      results = document.getElementById("ResultsTable");
      
      
  for (i=0; i<types.length; i++){
  
    searchCred(types[i].innerHTML,pass,option,key,results);
    
  }
  
  // Prevent the form from submitting
  return false;
}



function searchCred(id,pass,option,key,results){
    var xmlhttp,resp,res;
  
    if (window.XMLHttpRequest){// code for IE7+, Firefox, Chrome, Opera, Safari
      xmlhttp=new XMLHttpRequest();
    }else{// code for IE6, IE5
      xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
    }

    xmlhttp.onreadystatechange=function(){
      if (xmlhttp.readyState==4 && xmlhttp.status==200)
      {
	resp = decryptAPIResp(xmlhttp.responseText,key).split(getDivider());
	// Check for an invalid verb response
	if (resp[1] == 2){
	  return unknownAPICommand(); 
	}
	  res = document.createElement("Div");
	  res.innerHTML = resp[2];
	  results.appendChild(res);  
	}
	
    }
  
  
  
  xmlhttp.open("POST","api.php",true);
  xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
  xmlhttp.send('option='+option+'&id='+id+'&pass='+pass);
 
}


function delCredType(id){

  var xmlhttp, resp, jsonObj, option, 
  key=retKey(),
  credrow = document.getElementById('CredType'+id),
  notify = document.getElementById('NotificationArea');  


if (!confirm("Are you sure you want to delete this Credential Type (any associated credentials will be deleted)?")){
 return false; 
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
      notify.innerHTML += '<div class="alert alert-success">Credential Type Deleted</div>';
    
     
      
      }
      
      
      
      

    }
  
  
  
  option = cryptReq('delCredType',key);
xmlhttp.open("POST","api.php",true);
xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
xmlhttp.send('option='+option+'&id='+id);
 
}