<?php
/** Base DB Class
* Controls connect, close and safety measures
*
* All other DB functions are in respective classes and models
*
*/

defined('_CREDLOCK') or die;

class BTDB{


var $linkident = false;

/** Get the basic database config
*
* Will adjust to use config file at later date TODO
*
*/
function setconfig(){


$conf = BTMain::getConf();


// DB Connection config
$this->dbname = $conf->dbname;
$this->dbserver = $conf->dbserver;
$this->dbuser = $conf->dbuser;
$this->dbpass = $conf->dbpass;
$this->tblprefix = $conf->tblprefix;
// Session expiry time (minutes)
$this->sessionexpiry = $conf->sessionexpiry;


// Display SQL errors?
$this->showDBErrors = $conf->showDBErrors;


// To leave connections open after each query
// set to 1. May have an impact on server performance
// but will speed up system so long as MySQL server doesn't
// reach the point of having too many concurrent connections
$this->connreuse = 0;
}


/** Replace #__ with the configured tablename prefix
*
* @arg sql string
*
* @return string
*
*/
function setPrefix($sql){

if (!$this->dbname)
$this->setconfig(); 

return str_replace("#__",$this->tblprefix,$sql);
}


/**                              QUERY FUNCTIONS                                       **/



/** Set the query
*
* @arg sql - The MySQL string to run. Data should already be escaped where necessary
*
*/
function setQuery($sql){
$this->query = $this->setPrefix($sql);
}



/** Clear the currently set query
*
* Generally you wouldn't use this 
* as setQuery will overwrite the old, 
* but it's worth having garbage 
* collection available!
*
* @return true/false
*/
function clearQuery(){
unset($this->query);
}



/** Run an insert query and return the Primary Key
*
*/
function insertID(){
if (!$this->linkident)
$this->opendb(); 

$res = mysql_query($this->query);

// Output any errors if configured to do so
$this->checkErrors();


$id = mysql_insert_id();
// if connection re-use has been disabled, close the link
if (!$this->connreuse){ $this->closedb(); }

return $id;


}



/** Run a query and return the raw MySQL result 
*
*/
function runQuery(){

if (!$this->linkident)
$this->opendb(); 

$res = mysql_query($this->query);

// Output any errors if configured to do so
$this->checkErrors();

// if connection re-use has been disabled, close the link
if (!$this->connreuse){ $this->closedb(); }

return $res;


}



/** Run the query and return a single row
*
* @return Result Object
*/
function loadResult(){

if (!$this->linkident)
$this->opendb();

$additional = "";

// Enforce 1 row limit if user hasn't done so in query
if (strpos("LIMIT 1",$this->query) === false)
$additional = " LIMIT 1";

$res = mysql_fetch_object(mysql_query($this->query . $additional));

// Output any errors if configured to do so
$this->checkErrors();

// if connection re-use has been disabled, close the link
if (!$this->connreuse)
$this->closedb(); 

return $res;
}






/** Run the query and return all results
*
* @return Results Object
*/
function loadResults(){
if (!$this->linkident)
$this->opendb();


$result = mysql_query($this->query);

$this->checkErrors();


$X=0;

while ($row = mysql_fetch_object($result)){
$rowname = "row$X";
$results->$rowname = $row;
$X++;
}

// if connection re-use has been disabled, close the link
if (!$this->connreuse){ $this->closedb(); }

return $results;

}






/**                              SAFETY FUNCTIONS                                       **/





/** Escape supplied string for safe use in
* SQL statements
*
* @arg string - string to be escaped
*
*/
function stringEscape($string){
if (!$this->linkident)
$this->opendb();


$string = mysql_real_escape_string($string);

if (!$this->connreuse)
$this->closedb(); 

return $string;

}




/** Escape every value in an array for safe
* use in SQL statements
*
* @arg arr - the array to be escaped
*
*/
function arrayEscape($arr){
if (!$this->linkident)
$this->opendb();

array_walk_recursive($arr,'sql_esc');

if (!$this->connreuse)
$this->closedb(); 

return $arr;
}

/** Change the supplied variable in place
*
*/
function sql_esc(&$var){
$var = stringEscape($var);
}



/** Convert HTML markup to the equivalent markup entity
*
*/
function convHTML($string){

return htmlspecialchars($string);

}



/**                              ERROR FUNCTIONS                                       **/




/** Check for SQL errors. Output them if configured to do so, but record the status anyway
*
*/
function checkErrors(){
// Output errors if configured to do so
$error = mysql_error();
if ($this->showDBErrors){ echo $error; }

if (!empty($error)){
$this->errors = $error;
}
// We could also implement logging at a later date

}





/**                              CONNECT FUNCTIONS                                       **/







/** Open a Database connection
*
*/
function opendb(){

if (!$this->dbname)
$this->setconfig(); 


// Open the Database connection
$this->linkident = mysql_connect($this->dbserver, $this->dbuser, $this->dbpass);

if (!$this->linkident) {
    die('Could not connect: ' . mysql_error());
      }else{

// Connect to the database if one is named
if (!empty($this->dbname)){
$db_selected = mysql_select_db($this->dbname, $this->linkident);
if (!$db_selected) {
    die ('Can\'t use ' . $this->dbname . ': ' . mysql_error());
	  }

      }

    }

}



/** Close the connection
*
*/
function closedb(){

// Only close the link if it's active
if (!$this->linkident){ return; }

mysql_close($this->linkident);
// Unset the link ID
unset($this->linkident);

}


/** Switch connection re-use on
*
*/
function setPersist(){
$this->connreuse = 1;
}


/** Switch connection re-use off
*
*/
function unsetPersist(){
$this->connreuse = 0;
}




    }



?>