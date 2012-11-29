<html>
  <head>
    <title></title>
    <meta content="">
    <style></style>






  </head>
  <body>

<div><span id="countsremaining">100</span> Clicks Remaining</div>
<div style="width: 700px; height: 400px; border: 1px black solid" id="ClickDiv"></div>

Random
<div id='content' style="width: 700px;"></div>








<script type="text/javascript">

var count=100;









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


document.getElementById('ClickDiv').onclick=function(e){document.getElementById('content').innerHTML += whereAt(e); count=count-1; document.getElementById('countsremaining').innerHTML = count};
</script>

</body>
</html>