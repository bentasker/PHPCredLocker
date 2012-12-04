/* ************************************************************
Author:  Ben Tasker - BenTasker.co.uk
Description: Javascript function for PHPCredLocker. 

  Quick and dirty function to gain a little entropy from the user. Entropy 
  gathered here will be added to server side, but it helps to spread the 
  randomness round a bit!
  
  Will definitely be improved upon in later releases.  
  
License: GNU GPL V2 -  See http://www.gnu.org/licenses/gpl-2.0.html

Repo: https://github.com/bentasker/PHPCredLocker/
---------------------------------------------------------------
Copyright (c) 2012 Ben Tasker

*/


var whereAt= (function(){
    if(window.pageXOffset!= undefined){
        return function(ev){
            return [ev.clientX+window.pageXOffset,
            ev.clientY+window.pageYOffset];
        }
    }
    else return function(){
        var ev= window.event,
        d= document.documentElement, b= document.body;
        return [ev.clientX+d.scrollLeft+ b.scrollLeft,
        ev.clientY+d.scrollTop+ b.scrollTop];
    }
})()



/*
 * 
 * Usage: 
 * 
 * var count=100;
 * document.getElementById('ClickDiv').onclick=function(e){document.getElementById('content').innerHTML += whereAt(e); 
 * count=count-1; 
 * document.getElementById('countsremaining').innerHTML = count};
 * 
 * 
 * 
 * 
 * 
 */