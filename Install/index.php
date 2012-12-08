<?php
/** PHPCredLocker Installer File
*
* Copyright (C) 2012 B Tasker
* Released under GNU GPL V2
* See LICENSE
*
*/
define('_CREDLOCK',"1");



// Prevent some of the required modules from bumming out
class notifications{

function RequireScript($script){
echo "<script src='../Resources/$script.js' type='text/javascript'></script>\n";
}

function RequireCSS($css){
echo "<link rel='stylesheet' type='text/css' href='../Resources/$css.css' />\n";
}

}

class Logging{

function LogEntry($cred,$action){
return;
}

}

/** Remove the installation directory
*
*/
function credlocker_install_stage_17(){

if (unlink(__FILE__) && rmdir(dirname(__FILE__))){
// Successfully removed
header('Location: ../index.php');
die;
}else{

echo "<div class='alert alert-error' title='The directory couldn't be removed - usually a permissions issue. Remove it manually to continue'>Unable to remove the Installation directory. Please remove <i>Install</i> manually</div>";

}

}



/** Inform the user of the final step
*
*/
function credlocker_install_stage_16(){
?>
<h1>System Configured</h1>

PHPCredLocker is installed and configured, all that remains is to remove the installation directory! 

To attempt to do this automatically, click the button below. Alternatively manually remove the directory <i>Install</i>. 

Once removed, you'll be able to log into the system with the details you just provided. Before you can add Credentials to the system, you'll need to create at least one Credential Type from the administration menu.

<form method="POST">
<input type="hidden" name="stage" value="17">
<input type="submit" class="btn btn-primary" value="Remove Installation Directory">
</form>
<?php

}



/** Create the admin user
*
*/
function credlocker_install_stage_15(){
require 'lib/auth.class.php';

$auth = new ProgAuth;

if ($auth->createUser($_POST['UserName'],$_POST['frmPass'],$_POST['RealName'], array("-1"))){

echo "<div class='alert alert-success'>Added User Successfully</div>";

}else{
echo "<div class='alert alert-error'>Could not add user, please try again</div>";
}


credlocker_install_stage_16();
}


/** Get the user to provide details necessary to create the admin user
*
*/
function credlocker_install_stage_14(){
notifications::RequireScript('passwordmeter');
notifications::RequireScript('main');
?>
<h1>Create Administrator Account</h1>
Next we'll create the Administrator account for this install


<form method="POST" onsubmit="return validateUserAdd();">
<input type="hidden" name="stage" value="15">
<input type="hidden" id="passScore" disabled="true">
<input type="hidden" id="minpassStrength" disabled="true" value="<?php echo BTMain::getConf()->minpassStrength;?>">

<table>
<tr><th>Username</th><td title="Your desired username, try to avoid 'admin' as it's a little easy for an attacker to guess"><input type="text" name="UserName" id="frmUsername"></td><td></td></tr>
<tr><th>Real Name</th><td title="Your real name (will be used in Logs)"><input type="text" name="RealName" id="frmRName"></td><td></td></tr>
<tr><th>Password</th><td title="Enter a password, minimum strength is whatever you selected earlier"><input type="password" name="frmPass" id="frmPass" autocomplete="off" onkeyup="testPassword(this.value);"></td><td><span id="passStrength"></span><div id="PassNoMatch" style="display: none;" class="alert alert-error"></div></td></tr>
<tr><th>Confirm Password</th><td title="Confirm your password"><input type="password" name="frmPassConf" autocomplete="off" id="frmPassConf"></td><td></td></tr>
</table>
<input type="submit" class="btn btn-primary" value="Create User">
</form>

<?php

}






/** Use the Entropy to create a key
*
*/
function credlocker_install_stage_13(){

writeCryptoKey("Groups","Groups");
credlocker_install_stage_14();


}



/** Generate a key for authentication
*
*/
function credlocker_install_stage_12(){
$notifications = new notifications;
?>

<h1>Create Group Key - Last One!</h1>

Now we're going to create an encryption key which will be used to secure all Group Data. To do so, we need to generate random data, so please move your mouse and click randomly in the box below until it turns green
<br/><br /><form method="POST">
<input type="hidden" name="stage" value="13">
<?php require 'lib/includes/gatherEntropy.php'; ?>
<input type="submit" class="btn btn-primary" value="Generate Key">
</form>
<?php
}



/** Use the Entropy to create a key
*
*/
function credlocker_install_stage_11(){

writeCryptoKey("Customer","Customer Data");
credlocker_install_stage_12();


}



/** Generate a key for authentication
*
*/
function credlocker_install_stage_10(){
$notifications = new notifications;
?>

<h1>Create Customer Key</h1>

Now we're going to create an encryption key which will be used to secure all Customer Data. To do so, we need to generate random data, so please move your mouse and click randomly in the box below until it turns green
<br/><br /><form method="POST">
<input type="hidden" name="stage" value="11">
<?php require 'lib/includes/gatherEntropy.php'; ?>
<input type="submit" class="btn btn-primary" value="Generate Key">
</form>
<?php
}




/** Use the Entropy to create a key
*
*/
function credlocker_install_stage_9(){
writeCryptoKey("CredType","Credential Types");
credlocker_install_stage_10();


}



/** Generate a key for authentication
*
*/
function credlocker_install_stage_8(){
$notifications = new notifications;
?>

<h1>Create CredType Key</h1>

Now we're going to create an encryption key which will be used to secure all Credential Categories. To do so, we need to generate random data, so please move your mouse and click randomly in the box below until it turns green
<br/><br /><form method="POST">
<input type="hidden" name="stage" value="9">
<?php require 'lib/includes/gatherEntropy.php'; ?>
<input type="submit" class="btn btn-primary" value="Generate Key">
</form>
<?php
}






/** Use the Entropy to create a key
*
*/
function credlocker_install_stage_7(){
writeCryptoKey("auth","Authentication");
credlocker_install_stage_8();


}


/** Generate a key for authentication
*
*/
function credlocker_install_stage_6(){
$notifications = new notifications;
?>

<h1>Create Authentication Key</h1>

Now we're going to create an encryption key which will be used to secure all user accounts. To do so, we need to generate random data, so please move your mouse and click randomly in the box below until it turns green
<br/><br /><form method="POST">
<input type="hidden" name="stage" value="7">
<?php require 'lib/includes/gatherEntropy.php'; ?>
<input type="submit" class="btn btn-primary" value="Generate Key">
</form>
<?php
}



/** Set Crypto settings then have the user generate entropy so we can create keys
*
*/
function credlocker_install_stage_5(){

if (!$fh = fopen(dirname(__FILE__)."/../conf/crypto.php",'w')){
echo "<div class='alert alert-error'>Unable to create Cryptographic config file, please check permissions and try again</div>";
return;

}



// Create the file header
$str = "<?php\n/** Crypto Keys\n*\n*KEEP THIS FILE SECRET!\n*\n* Copyright (C) 2012 B Tasker\n* Released under GNU GPL V2\n* See LICENSE\n*\n*/\ndefined('_CREDLOCK') or die;\n\n";

fwrite($fh,$str);


$str = "\t\t /** General Settings **/\n\$cipher->Engine = '{$_POST['Engine']}';\n\$cipher->keyLength='{$_POST['keyLength']}';\n\n";

if (is_array($_POST['MCrypt'])){
$str .= "\t\t /** MCrypt Specific **/\n\$cipher->MCrypt->Encryption = '{$_POST['MCrypt']['Encryption']}';\n\$cipher->MCrypt->mode = '{$_POST['MCrypt']['mode']}';\n\n";
}

if (is_array($_POST['openssl'])){
$str .= "\t\t/** OpenSSL Specific **/\n\$cipher->OpenSSL->Cipher = '{$_POST['openssl']['cipher']}';\n\n";
}

$str .= "\n\n \t\t /** KEYS FOLLOW **/\n\n";

fwrite($fh,$str);
fclose($fh);

echo "<div class='alert alert-success'>Cryptographic Settings Saved</div>";

credlocker_install_stage_6();
}


/** Set up Crypto
*
*/
function credlocker_install_stage_4(){

?><h1>CryptoGraphic Settings</h1>

Before we generate keys, we need to set which engine and cipher will be used

<form method="POST">
<input type="hidden" name="stage" value="5">

<table>
<tr><td colspan="2"><h2>Crypto Settings</h2></td></tr>

<tr>
<td>Encryption Engine</td>
<td title="The Encryption engine to use, OpenSSL is generally recommended - if you have it (requires PHP 5.3)">
<select name="Engine">
<?php

$ssl = function_exists('openssl_encrypt');
$mcrypt = function_exists('mcrypt_encrypt');

if ($ssl){
echo "<option value='OpenSSL' selected>OpenSSL</option>\n";
}

if ($mcrypt){
echo "<option value='MCrypt'>MCrypt</option>\n";
}

?>
</select>
</td></tr>

<tr>
<td>Key Length</td>
<td title="The length of encryption key to use. Higher is generally more secure but may have a performance impact"><input type="text" name="keyLength" value="2048"></td>
</tr>



	<?php if ($ssl):?>
	  <tr>
		<td>&nbsp;</td><td>&nbsp;</td>
	  </tr>
	  <tr>
	    <td colspan="2"><h2>OpenSSL Specific Settings</h2></td>
	  </tr>

	  <tr>
	    <td>Algorithm</td><td title="The OpenSSL algorithm to use. PHPCredLocker has been most heavily tested with des-cbc"><select name="openssl[cipher]">
		<?php foreach (openssl_get_cipher_methods() as $cipher) {?>
		      <option value='<?php echo $cipher; ?>'><?php echo $cipher; ?></option>
		<?php }?>
	    </select></td>
	  </tr>
	<?php endif; ?>

	<?php if ($mcrypt):?>
	  <tr>
		<td>&nbsp;</td><td>&nbsp;</td>
	  </tr>

	  <tr>
	    <td colspan="2"><h2>MCrypt Specific Settings</h2></td>
	  </tr>

	  <tr>
	    <td>Algorithm</td><td title="The MCrypt Algorithm to use"><select name="MCrypt[Encryption]">
		<?php foreach (mcrypt_list_algorithms() as $cipher) {?>
		      <option value='<?php echo $cipher; ?>'><?php echo $cipher; ?></option>
		<?php }?>
	    </select></td>
	  </tr>
	  <tr>
	    <td>Mode</td><td title="The Mcrypt Mode to use"><select name="MCrypt[mode]">
		<?php foreach (mcrypt_list_modes() as $cipher) {?>
		      <option value='<?php echo $cipher; ?>'><?php echo $cipher; ?></option>
		<?php }?>
	    </select></td>
	  </tr>
	    
	<?php endif; ?>

</table>
 <input type="submit" class="btn btn-primary" value="Proceed to Key Generation">
  </form>



<?php

}

/** Write the config file and create the database
*
*/
function credlocker_install_stage_3(){
unset($_POST['stage']);


if (!$fh = fopen(dirname(__FILE__)."/../conf/config.php",'w')){
echo "<div class='alert alert-error'>Unable to create config file, please check permissions and try again</div>";
return;

}



// Create the file header
$str = "<?php\n/** System Configuration\n*\n* Copyright (C) 2012 B Tasker\n* Released under GNU GPL V2\n* See LICENSE\n*\n*/\ndefined('_CREDLOCK') or die;\n\n";

fwrite($fh,$str);
foreach ($_POST as $key=>$value){

$str = "\$conf->$key = '$value';\n";
fwrite($fh,$str);

}

fwrite($fh,"\n\n?>");
fclose($fh);

echo "<div class='alert alert-success'>Config file created</div>";


// Create the plugins obfuscation string and write to the config file
if (!$fh = fopen(dirname(__FILE__)."/../conf/plugins.php",'a')){

$obfus = sha1(mt_rand(0,90000) . date('s') . mt_rand(500,9999999) . mt_rand(1000,50000));

if (rename(dirname(__FILE__)."/../plugins/Blargle", dirname(__FILE__)."/../plugins/$obfus")){

$str = "\n\n defined(\"CREDLOCK_PLUGIN__PATH\") or define('CREDLOCK_PLUGIN__PATH','plugins/$obfus');\n";
fwrite($fh,$str);
fclose($fh);
}



}else{
echo "<div class='alert alert-error'>Could not Obfuscate Plugin path, you will need to do this manually</div>";


}








$sqls = array(
'DROP TABLE IF EXISTS `Audit`;',

'CREATE TABLE `Audit` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `date` datetime NOT NULL,
  `User` varchar(150) DEFAULT NULL,
  `Cust` varchar(150) DEFAULT NULL,
  `Action` tinyint(4) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;',

'DROP TABLE IF EXISTS `Cred`;',

'CREATE TABLE `Cred` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cust` int(11) NOT NULL,
  `Added` datetime NOT NULL,
  `Group` int(11) NOT NULL,
  `Hash` blob,
  `CredType` int(11) NOT NULL,
  `Clicky` tinyint(1) unsigned NOT NULL DEFAULT \'0\',
  `Address` blob,
  `UName` blob,
  PRIMARY KEY (`id`),
  KEY `idx_Cred_Group` (`Group`),
  KEY `idx_cred_cust` (`cust`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=latin1;',

'DROP TABLE IF EXISTS `CredTypes`;',

'CREATE TABLE `CredTypes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Name` blob,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=12 DEFAULT CHARSET=latin1;',

'DROP TABLE IF EXISTS `Cust`;',

'CREATE TABLE `Cust` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Name` blob,
  `Group` int(11) NOT NULL,
  `ContactName` blob,
  `ContactSurname` blob,
  `Email` blob,
  UNIQUE KEY `id` (`id`),
  UNIQUE KEY `idx_Customers` (`Name`(255)),
  KEY `idx_Cust_Group` (`Group`)
) ENGINE=MyISAM AUTO_INCREMENT=20 DEFAULT CHARSET=latin1;',

'DROP TABLE IF EXISTS `Groups`;',

'CREATE TABLE `Groups` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Name` blob,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;',

'DROP TABLE IF EXISTS `Sessions`;',

'CREATE TABLE `Sessions` (
  `SessionID` varchar(150) NOT NULL,
  `Created` datetime NOT NULL,
  `User` varchar(150) NOT NULL,
  `Expires` datetime NOT NULL,
  `ClientIP` varchar(100) NOT NULL,
  `SessKey` varchar(255) CHARACTER SET latin1 COLLATE latin1_bin NOT NULL,
  PRIMARY KEY (`SessionID`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;',

'DROP TABLE IF EXISTS `Users`;',

'CREATE TABLE `Users` (
  `username` varchar(150) NOT NULL,
  `pass` blob NOT NULL,
  `Name` varchar(255) NOT NULL,
  `membergroup` longtext,
  `Usergroup` int(11) NOT NULL DEFAULT \'0\',
  PRIMARY KEY (`username`,`Usergroup`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;',

'DROP TABLE IF EXISTS `bannedIPs`;',

'CREATE TABLE `bannedIPs` (
  `IP` varchar(255) NOT NULL,
  `Expiry` datetime NOT NULL,
  PRIMARY KEY (`IP`),
  KEY `idx_ban_expiry` (`Expiry`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;',

'DROP TABLE IF EXISTS `FailedLogins`;',

'CREATE TABLE `FailedLogins` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(150) NOT NULL,
  `FailedAttempts` int(11) NOT NULL,
  `LastAttempt` datetime NOT NULL,
  `FailedIP` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_failed_user_ip` (`username`,`FailedIP`),
  KEY `idx_failedips` (`FailedIP`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;'

);








$db = new BTDB;
foreach ($sqls as $sql){


$db->setQuery($sql);
    if (!$db->runQuery()){
	echo "<div class='alert alert-error'>Database Creation Failed. Please try again</div>";
	return;

     }
}
echo "<div class='alert alert-success'>Database Tables Created</div>";
credlocker_install_stage_4();



}


/** Stage 2 - Set up the Config File including MySQL connection
*
*/
function credlocker_install_stage_2(){

?>
<form method="POST">
<input type="hidden" name="stage" value="3">
<input type="hidden" name="template" value="EstDeus">


<table>
<tr>
<td colspan="2"><h2>Application Configuration</h2></td>
</tr>
<tr>
<td>Application Name</td><td title="The application name you want displayed in the Interface"><input type="text" name="ProgName" value="PHPCredLocker"></td>
</tr>

<tr>
<td>&nbsp;</td><td>&nbsp;</td>
</tr>


<tr>
<td colspan="2"><h2>Security Options</h2></td>
</tr>
<tr>
<td >Display Credentials for</td><td title="How long should credentials be displayed for when a user requests them? Should really be set to a low value" ><input type="text" name="CredDisplay" value="30" length="3"> seconds</td>
</tr>
<tr>
<td >Minimum Password Strength</td>
<td title="The weakest element of the system is usually going to be authentication. Any security measures taken in PHPCredLocker are wasted if you allow users to set obvious passwords!"><select name="minpassStrength">
<option value="0-16">Very Weak</option>
<option value="15-25">Weak</option>
<option value="24-35">Mediocre</option>
<option value="34-45" selected>Strong</option>
<option value="45+">Very Strong</option>
</td>
</tr>
<tr>
<td >Expire Sessions after</td><td title="Regardless of activity, sessions will expire after this interval. A lowish setting is recommended"><input type="text" name="sessionexpiry" value="15"> minutes</td>
</tr>
<tr>
<td >Internal Audit Logging Enabled</td><td title="All actions are usually logged. If you don't want this to happen, turn this off"><input type="checkbox" checked="checked" name="loggingenabled" value="true"></td>
</tr>
<tr>
<td >Force SSL</td><td title="Redirect http connections to https ones, generally a good idea to have switched on - do you really want to store your passwords securely and then send over the Internet in cleartext?"><input type="checkbox" name="forceSSL" checked="checked" value="true"></td>
</tr>
<tr>
<td >SSL URL</td><td title="The URL to use for https connections. Would normally be https://yourdomain but may be different if you're on shared hosting"><input type="text" name="SSLURL" value="<?php

$str = "https://".$_SERVER['SERVER_NAME'];


$req = explode("/",$_SERVER['REQUEST_URI']);
$len = count($req) - 1;
unset($req[$len]);   
unset($req[$len -1]);

$str .= implode("/",$req);
echo $str;
?>"></td>

<tr>
<td >Cron Password:</td><td title="A password used simply to prevent unauthorised users from running the cronjobs. Default is randomly generated, but you can change if you want"><input type="text" name="cronPass" value="<?php echo base_convert(rand(10e16, 10e20),10,36).mt_rand(0,500);?>"></td>
</tr>

</tr>

<tr>
<td>&nbsp;</td><td>&nbsp;</td>
</tr>


<tr>
<td colspan="2"><h2>Database Options</h2></td>
</tr>

<tr>
<td >Database Host</td><td title="The host name or IP address of the server hosting your MySQL Database"><input type="text" name="dbhost" value="localhost"></td>
</tr>

<tr>
<td >Database Name</td><td title="The name of the database that PHPCredLocker will be using. Must already exist"><input type="text" name="dbname" value="PHPCredLocker_<?php echo mt_rand(0,90000); ?>"></td>
</tr>

<tr>
<td >Database User</td><td title="The database username. Good practice dictates one user per database"><input type="text" name="dbuser" value=""></td>
</tr>

<tr>
<td >Database Password</td><td title="The database password"><input type="text" name="dbpass" value=""></td>
</tr>

<tr>
<td >Display SQL Errors</td><td title="Display any SQL errors that are encountered. Should usually be off!"><input type="checkbox" name="showDBErrors" value="true"></td>
</tr>

</table>

<input type="submit" class="btn btn-primary" value="Save Configuration">
</form>
<?php
return;
}











/** Very first stage of install
*
*/
function first_install_stage(){
$fail = 0;
?>
Welcome to the PHPCredLocker Installation script. In a few simple steps we'll create the database, set up encryption keys and create a Superadministrator. <br/><br />

<h2>System Tests</h2>
<table class="table table-hover">
<tr>
<th >Crypto Functions</th>
<td title="Cryptographic libraries are required so that stored credentials can be encrypted. Storing in cleartext is a <b>really</b> bad idea and is entirely unsupported" class="test<?php

if (function_exists("openssl_encrypt")){
echo 'Pass">Pass - You have OpenSSL';

}elseif(function_exists("mcrypt_encrypt")){
echo 'Pass">Pass - You have MCrypt';

}else{
echo 'Fail">Fail - Neither the OpenSSL or the MCrypt libraries appear to be available';
$fail = 1;
}
?>
</td>
</tr>


<tr>
<th >Configuration Writable</th>
<td title='PHPCredLocker needs to be able to write to your configuration files, especially for the install!' class='test<?php


// is_writable doesn't seem to want to play nice here, writing a file instead
$path = dirname(__FILE__)."/../conf/test.txt";

$rm = file_exists($path);
    $f = @fopen($path, 'a');
    if ($f===false){
	echo "Fail'>Fail - Please check directory permissions";
	$fail = 1;
    }else{
    fclose($f);
    if (!$rm){
        unlink($path);
	echo "Pass'>Pass";
	}
}








?>
</td>
</tr>

<tr>
<th>Configuration Protection</th>
<td title='Your configuration directory should only be readable by Owner and group - Other users should be denied access (e.g. 660 or 650 permissions)' class='test<?php

$perms = sprintf('%o', fileperms(dirname(__FILE__)."/../conf/"));

$allperms = substr($perms,strlen($perms) - 1,1);

if ( $allperms != 0 ){
echo "Fail'>Fail - All users have read permissions on configuration directory. This could compromise your encryption keys.";
$fail = 1;

}else{
echo "Pass'>Pass";
}


?>



</tr>

<tr>
<th>Database Libraries</th>
<td title="To interact with the database, you'll need the relevant PHP libraries installed" class='test<?php

if (function_exists('mysql_query')){

echo "Pass'>Pass";
}else{
echo "Fail'>Fail - Could not find MySQL Libraries";
$fail = 1;
}?>
</td></tr>
</table>


<?php if ($fail == 0):?>
<form method="POST">
<input type="hidden" name="stage" value="2">
<input type="submit" class="btn btn-primary" value="Proceed with Install">
</form>
<?php else: ?>

Please address the issues listed above before continuing

<?php
endif;
}


/** Write the specified Crypto Key to the crypto config
*
* @arg key - The KeyName
* @arg text - The text to display to the user
*
*
*/
function writeCryptoKey($keyname,$text){

$submitted=1;
require 'lib/includes/gatherEntropy.php';


if (!$fh = fopen(dirname(__FILE__)."/../conf/crypto.php",'a')){
echo "<div class='alert alert-error'>Unable to append to Cryptographic config file, please check permissions and try again</div>";
return;

}

$str = "\$crypt->$keyname = '$newkey';\n";
fwrite($fh,$str);

fclose($fh);

echo "<div class='alert alert-success'>Created Cryptographic key for $text</div>";


}


?> 
<html>
<head>
<title>Install PHPCredLocker</title>

<link rel="stylesheet" type="text/css" href="../templates/EstDeus/css/bootstrap/css/bootstrap-responsive.css" />
<link rel="stylesheet" type="text/css" href="../templates/EstDeus/css/bootstrap/css/bootstrap.css" />
<link rel="stylesheet" type="text/css" href="../templates/EstDeus/css/EstDeus.css" />
<link rel="stylesheet" type="text/css" href="../Resources/jquery.tooltip.css" />
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
<script type="text/javascript" src="../Resources/jquery.tooltip.min.js"></script>
<script type="text/javascript" src="../templates/EstDeus/css/bootstrap/js/bootstrap.js"></script>



<Style type="text/css">
.testPass {color: green;}
.testFail {color: red; font-weight: bold;}
li.stage<?php echo $_POST['stage'];?> {font-weight: bold;}
#BreadCrumbs {width: 90% !important; text-align: center;}
</style>
</head>
<body>
<!-- Main PageWrap begins -->
<div id='all' class='PageWrap pgbackground'>

<!-- Navbar Begins -->
<div id='Nav' class='navbar'>
    <div class='navbar-inner'>
	<a class="brand" href="index.php">PHPCredLocker</a>
	  <ul class="nav">
	    <li class="divider-vertical"></li>
	    <li class="nav"><a id="Inst" href="#">Install</a></li>
	  </ul>
    </div>
</div>
<div id='FFContainer'>
<div class='contentArea pgbackground' id='contentArea'>

<div class='BreadCrumbs' id='BreadCrumbs'>

  <ul class="breadcrumb">

    <li class="stage">Introduction</li>
    <li class="stage2"><span class="divider">/</span>Configuration</li>
    <li class="stage3"><span class="divider">/</span>CryptoGraphic Settings</li>
    <li class="stage5"><span class="divider">/</span>Authentication Key</li>
    <li class="stage7"><span class="divider">/</span>Credential Type Key</li>
    <li class="stage9"><span class="divider">/</span>Customer Key</li>
    <li class="stage11"><span class="divider">/</span>Group Key</li>
    <li class="stage13"><span class="divider">/</span>Administrator Account</li>
    <li class="stage15"><span class="divider">/</span>Remove Install Directory</li>
    <li class="stage17"><span class="divider">/</span>Complete!</li>

  </ul>

</div>


<div class='content' id='ContentWrap'>
<?php


if (!isset($_POST['stage'])){
first_install_stage();
}else{




// Change working dir otherwise our includes will break
chdir(dirname(__FILE__)."/../");
require_once 'lib/Framework/main.php';

if (((($_POST['stage'] >= '5') && ($_POST['stage'] <= '13')) || ($_POST['stage'] == 15))) {
require_once 'lib/crypto.php';

}


$fn = "credlocker_install_stage_".$_POST['stage'];
$fn();
}










?>
</div>
</div>

</div>


</div>
<script type="text/javascript">
$('#ContentWrap *').tooltip({track: true, fade: 250});
</script>
</body>
</html>