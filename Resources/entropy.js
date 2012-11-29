
var countdown=30;
var count;
var gather;

 function GetMousePos(e){ 
   if(!e){ var e=window.event; } 
   var s=e.clientX +  '-' + window.event.clientY ;

   document.getElementById("content").innerHTML +=s +','; 
   
} 



function gatherEntropy(){
  
  
 count = setInterval("Credtimer", 1000); 
 gather = setInterval("GetMousePos",10);
  
}



function Credtimer()
{
  countdown=countdown-1;
  
  if (countdown <= 0)
  {
     clearInterval(count);
     clearInterval(gather);
  }
  
  
  
  
  
  
}