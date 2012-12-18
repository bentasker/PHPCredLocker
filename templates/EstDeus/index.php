<?php defined('_CREDLOCK') or die; ?>
<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<?php $this->headContents(); ?>


<link rel="stylesheet" type="text/css" href="templates/EstDeus/css/EstDeus.css" />






<script type="text/javascript">
window.onresize = resizebkgrnd;

jQuery(document).ready(function() { resizebkgrnd(); });
</script>

</head>
<body>
<!-- Main PageWrap begins -->
<div id='all' class='PageWrap pgbackground'>

<!-- Navbar Begins -->
<div id='Nav' class='navbar row'>
<div id="NavContent">
<div class='navbar-inner'>
<?php $this->loadModule('menu'); ?>
</div>
</div>
</div>
<!-- Navbar Ends -->

<div id='FFContainer'>
<div class='contentArea pgbackground' id='contentArea'>








<div class='content' id='ContentWrap'>

      <div class='BreadCrumbs' id='BreadCrumbs'>
	  <?php echo $this->BreadCrumbs(); ?>
      </div>


    <div class='NotificationArea' id='NotificationArea'>
	<?php echo $this->Notifications(); ?>
    </div>

	<div class="content">

	    <?php echo $this->content;?>

	</div>
</div>
<!-- Content Section Ends -->






</div>






</div>
</div>
</body>
</html> 
