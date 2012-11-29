<?php
/** Use JS to gather some entropy for key generation (additional will be created on submit)
*
* Copyright (C) 2012 B Tasker
* Released under GNU GPL V2
* See LICENSE
*
*
* Set the variable multiselect to 1 before calling this file to display checkboxes
*/

defined('_CREDLOCK') or die;


if (!isset($notifications)){
global $notificiations;
}
$notifications->RequireScript('entropy');


// Are we processing the generated or outputting the field
if ($submitted):

$arr = array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z','0','1','2','3','4','5','6','7','8','9','!','#','?','.','<','[',']','%','$','£','"');


$entropy = sha1(BTMain::getVar('gEntropy'));

$entropyseed = mt_rand(0,90000);

$x = 50;
$arrlength = count($arr) - 1;
while ($x > 0){

$key = mt_rand(0,$arrlength);
$ecryptkey .= mt_rand(0,$arrlength);

$seed .= $arr[$key];
$ecrypt .= $arr[$ecryptkey];

$x--;
}



$newkey = $entropyseed . $entropy . $seed;



if (!$crypt){
$crypt = new Crypto;
}


$newkey = $crypt->encrypt($newkey,'ONEWAY',$ecrypt);








else:


?>
<div>
Move the mouse and click Randomly in the box below to create entropy for Key Generation (continue until the box turns green).
<div class='EntropyDiv' id="ClickDiv"></div>


<textarea id='content' style="display: none;" name="gEntropy"></textarea>
<div style="display: none;"><input type="text" id="countsremaining" value="30" name="clicksremaining"> Clicks Remaining</div>

<script type="text/javascript">
var count=30;
document.getElementById('ClickDiv').onclick=function(e){document.getElementById('content').value += whereAt(e); count=count-1; document.getElementById('countsremaining').value = count; if(count == 0){document.getElementById('ClickDiv').className = 'EntropyDiv EntropyGenerated';}};
</script>
</div>
<?php





endif;


