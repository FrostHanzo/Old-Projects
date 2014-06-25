<?php
/** Database Abstraction Layer
* This file provides an abstract database class with functions for working with a MySQL database
* The class provides basic functions for connecting, disconnecting, inserting and retrieving results off and to a MySQL database
*/
class Mysql
{
// initialise a few cridential variables
var $dbName;
var $dbUser;
var $dbPass;
var $dbHost;
var $dbLink;

// create a few functions for our class
// the main MySQL function

function Mysql()
{
$this->dbUser = 'root';
$this->dbPass = '';
$this->dbHost = 'localhost';
$this->dbName = 'smartdir';
}

/** a few functions that change the main login parameters
*/

function changeUser ($user)
{
$this->dbUser = $user;
}

function changePass($pass)
{
$this->dbPass = $pass;
}

function changeHost ($host)
{
$this->dbHost = $host;
}

function changeName ($name)
{
$this->dbName = $name;
}

function changeAll($user, $pass, $host, $name)
{
$this->dbUser = $user;
$this->dbPass = $pass;
$this->dbHost = $host;
$this->dbName = $name;
}

// create a connect function using our parameters
function connect()
{
$this->dbLink = mysql_connect($this->dbHost, $this->dbUser, $this->dbPass) or die ('Cannot connect to MySQL');
mysql_select_db($this->dbName) or die ('Could not open database: ' . $this->dbName);
}

// disconnect function
function disconnect()
{
if (isset($this->dbLink))
{
mysql_close($this->dbLink);
}
else {
mysql_close();
}
}

/** create a query function that does not return any results
* For example such queries are insert, update and delete queries
*/
function vQuery($qry)
{
if(!isset($this->dbLink))
	$this->connect();
$temp = mysql_query($qry, $this->dbLink) or die('Error: ' . mysql_error());
}


/** query function that returns results
* This query function will first check if the connection to MySQL has been established and if so, will perform the requested query and will display the result in an associative array
* the result set could be used with a foreach loop later on
*/



function query($qry)
{
if(!isset($this->dbLink))
$this->connect();
$result = mysql_query($qry, $this->dbLink) or die('Error: ' . mysql_error());
// $numRows = mysql_num_rows($result);
// $totalRows = mysql_query("SELECT FOUND_ROWS()");
// $link = $this->dbLink;
$returnArray = array();
$i=0;
while ($row = mysql_fetch_array($result, MYSQL_ASSOC))
if ($row)
$returnArray[$i++]=$row;
// $returnArray['numRows']=$numRows;
// $returnArray['totalRows']=$totalRows;
// $returnArray['link']=$link;
return $returnArray;
// mysql_free_result($result);
}

function sQuery($qry)
{
	if(!isset($this->dbLink))
	$this->connect();
$temp = mysql_query($qry, $this->dbLink) or die('Error: ' . mysql_error());
$singleRow = mysql_fetch_row($temp);
return $singleRow;
}

function getNumRows()
{
	if(isset($this->dbLink))
$result = mysql_query('SELECT FOUND_ROWS()', $this->dbLink) or die('Error: ' . mysql_error());
$singleRow = mysql_fetch_row($result);
return $singleRow;
mysql_free_result($result);
}

function setEncoding($enc)
{
if(!isset($this->dbLink))
	$this->connect();
$encQuery = 'SET CHARSET ' .$enc;
$temp = mysql_query($encQuery, $this->dbLink) or die('Error: ' . mysql_error());
}
}
?>