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
<div id='Nav' class='navbar'>
<div class='navbar-inner'>
<?php $this->loadModule('menu'); ?>

</div>
</div>
<!-- Navbar Ends -->

<div id='FFContainer'>
<div class='contentArea pgbackground' id='contentArea'>
<div class='BreadCrumbs' id='BreadCrumbs'>
<?php echo $this->BreadCrumbs(); ?>
</div>
<div class='NotificationArea' id='NotificationArea'>
<?php echo $this->Notifications(); ?>
</div>


<!-- Modules go here -->
<div id='Sidebar'>
<?php $this->loadModule("login");?>
</div>
<!-- Modules end -->

<!-- Content Section -->
<div class='content' id='ContentWrap'>

<?php echo $this->content;?>


</div>
<!-- Content Section Ends -->

</div>






</div>
</div>
</body>
</html> 
