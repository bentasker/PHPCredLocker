<?php defined('_CREDLOCK') or die; ?>
<html>
<head>

<?php $this->headContents(); ?>
<!-- Temporary, need to add directly to project -->
<link rel="stylesheet" type="text/css" href="templates/EstDeus/css/bootstrap/css/bootstrap-responsive.css" />
<link rel="stylesheet" type="text/css" href="templates/EstDeus/css/bootstrap/css/bootstrap.css" />



<script type="text/javascript" src="templates/EstDeus/css/bootstrap/js/bootstrap.js"></script>
<link rel="stylesheet" type="text/css" href="templates/EstDeus/css/EstDeus.css" />
</head>
<body>
<!-- Main PageWrap begins -->
<div id='all' class='PageWrap pgbackground'>

<!-- Navbar Begins -->
<div id='Nav' class='navbar row'>
<div class='navbar-inner'>
<?php $this->loadModule('menu'); ?>

</div>
</div>
<!-- Navbar Ends -->

<div id='FFContainer'>
<div class='contentArea pgbackground' id='contentArea'>

<div class="row hidden-phone">
<div class="blankCol span2">&nbsp;</div>

<div class='BreadCrumbs span4' id='BreadCrumbs'>
<?php echo $this->BreadCrumbs(); ?>
</div>

<div class="blankCol span2">&nbsp;</div>

</div><!-- Row Ends -->

<div class="row">
<div class="blankCol span2">&nbsp;</div>
<!-- Content Section -->
<div class='content span8' id='ContentWrap'>


    <div class='NotificationArea' id='NotificationArea'>
	<?php echo $this->Notifications(); ?>
    </div>


<?php echo $this->content;?>


</div>
<!-- Content Section Ends -->

<!-- Modules go here -->
<div id='Sidebar hidden-phone hidden-tablet' class='span2'>
<?php //$this->loadModule("login");?>
</div>
<!-- Modules end -->


</div> <!-- Row end -->
</div>






</div>
</div>
</body>
</html> 
