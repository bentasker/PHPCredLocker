


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