<?php
/** Plugins Handler
*
* Copyright (C) 2012 B Tasker
* Released under GNU GPL V2
* See LICENSE
*
*/ 
defined('_CREDLOCK') or die;
BTMain::checkSuperAdmin();

$plug = new Plugins;
$plugins = $plug->listloadedPlugins(); 

global $notifications;

$path = array(array('name'=>'Plugins','url'=>'index.php?option=pluginInfo'));

$notifications->setBreadcrumb($path)


$notifications->setPageTitle("View Plugins");


?>
<i><b>Plugin not shown? </b> To display here, plugins must be enabled in plugins.conf. Status Disabled simply means that the plugin is disabled within it's own configuration</i>
<br /><Br />
<?php

if (isset($plugins->Auth)){

?>
<h2>Authentication Plugins</h2>
<table class="table table-hover">
<tr><th>Plugin</th><th>Status</th><th></th></tr>
<?php

    foreach($plugins->Auth as $plugin=>$status){

    ?>
    <tr>
	<td><?php echo $plugin; ?></td><td><?php echo Plugins::transStatus($status);?></td>
	<td><a href="index.php?option=plgInfo&plg=<?php echo $plugin;?>&type=Auth">Plugin Info</a></td>
    </tr>
    <?php
    }



}


if (isset($plugins->Cron)){

?>
<h2>Cron Plugins</h2>
<table class="table table-hover">
<tr><th>Plugin</th><th>Status</th><th></th></tr>
<?php

    foreach($plugins->Cron as $plugin=>$status){

    ?>
    <tr>
	<td><?php echo $plugin; ?></td><td><?php echo Plugins::transStatus($status);?></td>
	<td><a href="index.php?option=plgInfo&plg=<?php echo $plugin;?>&type=Auth">Plugin Info</a></td>
    </tr>
    <?php
    }



}




if (isset($plugins->Logging)){

?>
<h2>Logging Plugins</h2>
<table class="table table-hover">
<tr><th>Plugin</th><th>Status</th><th></th></tr>
<?php

    foreach($plugins->Logging as $plugin=>$status){

    ?>
    <tr>
	<td><?php echo $plugin; ?></td><td><?php echo Plugins::transStatus($status);?></td><td><a href="index.php?option=plgInfo&plg=<?php echo $plugin;?>&type=Logging">View ReadMe</a></td>
    </tr>
    <?php
    }
?><table><br /><br /><?php


}


if (isset($plugins->Customers)){

?>
<h2>Customer Plugins</h2>
<table class="table table-hover">
<tr><th>Plugin</th><th>Status</th><th></th></tr>
<?php

    foreach($plugins->Customers as $plugin=>$status){

    ?>
    <tr>
	<td><?php echo $plugin; ?></td><td><?php echo Plugins::transStatus($status);?></td><td><a href="index.php?option=plgInfo&plg=<?php echo $plugin;?>&type=Customers">View ReadMe</a></td>
    </tr>
    <?php
    }
?><table><br /><br /><?php


}



if (isset($plugins->Creds)){

?>
<h2>Credential Plugins</h2>
<table class="table table-hover">
<tr><th>Plugin</th><th>Status</th><th></th></tr>
<?php

    foreach($plugins->Creds as $plugin=>$status){

    ?>
    <tr>
	<td><?php echo $plugin; ?></td><td><?php echo Plugins::transStatus($status);?></td><td><a href="index.php?option=plgInfo&plg=<?php echo $plugin;?>&type=Creds">View ReadMe</a></td>
    </tr>
    <?php
    }
?><table><br /><br /><?php


}
