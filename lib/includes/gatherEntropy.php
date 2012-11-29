<?php
/** Use JS to gather some entropy for key generation (additional will be created on submit)
*
* Copyright (C) 2012 B Tasker
* Released under GNU GPL V2
* See LICENSE
*
*
* 
*/

defined('_CREDLOCK') or die;


if (!isset($notifications)){
global $notificiations;
}
$notifications->RequireScript('entropy');


// Are we processing the generated or outputting the field
if ($submitted):


$entropy = BTMain::getVar('gEntropy');

$entropyseed = mt_rand(1000,999000);

$x = 500;
$arrlength = count($arr) - 1;


      while ($x > 0){

      $key = mt_rand(32,254);
      $ecryptkey = mt_rand(32,254);
      $prekey = mt_rand(32,254);

	if ($key == 127 || $ecryptkey == 127){
	// Skip the delete char
	continue;
	}


      $seed .= chr($key);
      $ecrypt .= mt_rand(0,99000) . chr($ecryptkey);
      $prkey .= chr($prekey);

      $x--;
      }

$prkey = substr($prkey, mt_rand(0,475), 25);


$newkey = $entropyseed . $prkey . $entropy . $seed;



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
var count=15;
document.getElementById('ClickDiv').onclick=function(e){document.getElementById('content').value += whereAt(e); count=count-1; document.getElementById('countsremaining').value = count; if(count == 0){document.getElementById('ClickDiv').className = 'EntropyDiv EntropyGenerated';}};
</script>
</div>
<?php





endif;


