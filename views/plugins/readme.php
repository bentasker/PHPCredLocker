<?php
/** Plugins Info Page
*
* Copyright (C) 2012 B Tasker
* Released under GNU GPL V2
* See LICENSE
*
*/ 
defined('_CREDLOCK') or die;
BTMain::checkSuperAdmin();
$plugin = new Plugins;

$plg = str_replace("/","",str_replace(".","",BTMain::getVar('plg')));
$plgtype = str_replace("/","",str_replace(".","",BTMain::getVar('type')));
global $notifications;


$notifications->setPageTitle("Plugin Info");

$path = array(array('name'=>'Plugins','url'=>'index.php?option=pluginInfo'),array('name'=>$plg,'url'=>"index.php?option=plgInfo&plg=$plg"));

$notifications->setBreadcrumb($path);


$details = $plugin->getPluginInfo($plg,$plgtype);

?>
<h1>Plugin <?php echo htmlspecialchars($plg);?></h1>

<h2>Details</h2>

<table class="table table-striped pluginDetails">
<tr><th>Status</th><td><?php echo Plugins::transStatus($details->status);?></td></tr>

<?php foreach ($details->info as $key=>$value){?>
<tr><th><?php echo $key;?></th><td><?php echo $value; ?></td></tr>
<?php } ?>
</table>
<div id='PluginReadme'>
<h2>Plugin Readme</h2>

<?php include "plugins/$plg/README.html"; ?>

</div>

