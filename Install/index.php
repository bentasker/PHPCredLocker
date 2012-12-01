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

}



/** Generate a key for authentication
*
*/
function credlocker_install_stage_6(){
$notifications = new notifications;

?>

<h1>Create Authentication Key</h1>

Now we're going to create an ecryption key which will be used to secure all user accounts. To do so, we need to generate random data, so please move your mouse and click randomly in the box below until it turns green
<br/><br /><form method="POST">
<input type="hidden" name="stage" value="7">
<?php

require 'lib/includes/gatherEntropy.php';





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
<td>
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
<td><input type="text" name="keyLength" value="2048"></td>
</tr>



	<?php if ($ssl):?>
	  <tr>
		<td>&nbsp;</td><td>&nbsp;</td>
	  </tr>
	  <tr>
	    <td colspan="2"><h2>OpenSSL Specific Settings</h2></td>
	  </tr>

	  <tr>
	    <td>Algorithm</td><td><select name="openssl[cipher]">
		<?php foreach (openssl_get_cipher_methods() as $cipher) {?>
		      <option value='<?php echo $cipher; ?>'><?php echo $cipher; ?></option>
		<?php }?>
	    </select></td>
	  </tr>
	<?php endif; ?>

	<?php if ($ssl):?>
	  <tr>
		<td>&nbsp;</td><td>&nbsp;</td>
	  </tr>

	  <tr>
	    <td colspan="2"><h2>MCrypt Specific Settings</h2></td>
	  </tr>

	  <tr>
	    <td>Algorithm</td><td><select name="MCrypt[Encryption]">
		<?php foreach (mcrypt_list_algorithms() as $cipher) {?>
		      <option value='<?php echo $cipher; ?>'><?php echo $cipher; ?></option>
		<?php }?>
	    </select></td>
	  </tr>
	  <tr>
	    <td>Algorithm</td><td><select name="MCrypt[mode]">
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
) ENGINE=MyISAM DEFAULT CHARSET=latin1;'

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
<td>Application Name</td><td><input type="text" name="ProgName" value="PHPCredLocker"></td>
</tr>

<tr>
<td>&nbsp;</td><td>&nbsp;</td>
</tr>


<tr>
<td colspan="2"><h2>Security Options</h2></td>
</tr>
<tr>
<td>Display Credentials for</td><td><input type="text" name="CredDisplay" value="30" length="3"> seconds</td>
</tr>
<tr>
<td>Expire Sessions after</td><td><input type="text" name="sessionexpiry" value="15"> minutes</td>
</tr>
<tr>
<td>Internal Audit Logging Enabled</td><td><input type="checkbox" checked="checked" name="loggingenabled" value="true"></td>
</tr>
<tr>
<td>Force SSL</td><td><input type="checkbox" name="forceSSL" checked="checked" value="true"></td>
</tr>
<tr>
<td>SSL URL</td><td><input type="text" name="SSLURL" value="<?php

$str = "https://".$_SERVER['SERVER_NAME'];


$req = explode("/",$_SERVER['REQUEST_URI']);
$len = count($req) - 1;
unset($req[$len]);   
unset($req[$len -1]);

$str .= implode("/",$req);
echo $str;
?>"></td>
</tr>

<tr>
<td>&nbsp;</td><td>&nbsp;</td>
</tr>


<tr>
<td colspan="2"><h2>Database Options</h2></td>
</tr>

<tr>
<td>Database Host</td><td><input type="text" name="dbhost" value="localhost"></td>
</tr>

<tr>
<td>Database Name</td><td><input type="text" name="dbname" value="PHPCredLocker_<?php echo mt_rand(0,90000); ?>"></td>
</tr>

<tr>
<td>Database User</td><td><input type="text" name="dbuser" value=""></td>
</tr>

<tr>
<td>Database Password</td><td><input type="text" name="dbpass" value=""></td>
</tr>

<tr>
<td>Display SQL Errors</td><td><input type="checkbox" name="showDBErrors" value="true"></td>
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
<th>Crypto Functions</th><td class="test<?php

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
<th>Configuration Writable</th>
<td class='test<?php


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
<td class='test<?php

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
<td class='test<?php

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





?> 
<html>
<head>
<title>Install</title>

<link rel="stylesheet" type="text/css" href="../templates/EstDeus/css/bootstrap/css/bootstrap-responsive.css" />
<link rel="stylesheet" type="text/css" href="../templates/EstDeus/css/bootstrap/css/bootstrap.css" />

<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>

<script type="text/javascript" src="../templates/EstDeus/css/bootstrap/js/bootstrap.js"></script>



<Style type="text/css">
.testPass {color: green;}
.testFail {color: red; font-weight: bold;}
.EntropyDiv {width: 100%; height: 400px; border-radius: 5px; border: 1px black solid; background: red;}
.EntropyGenerated {border: 1px green solid; background: green;}
</style>
</head>
<body>

<?php


if (!isset($_POST['stage'])){
first_install_stage();
}else{




// Change working dir otherwise our includes will break
chdir(dirname(__FILE__)."/../");
require_once 'lib/Framework/main.php';

if ($_POST['stage'] == '5'){
require_once 'lib/crypto.php';

}


$fn = "credlocker_install_stage_".$_POST['stage'];
$fn();
}










?>
</body>
</html>