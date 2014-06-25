<?php
// Start a new session
session_start();
// Use output buffering
ob_start();
/**
* A config file that holds configuration variables for the SmartDir script
* These settings will define the main operational parameters that the script uses to write to the file system, to write and read .htaccess files on * the local system and to create default user accounts that correspond with the .htaccess files.
*
* @author Georgi Zhivankin <georgijivankin@gmail.com>
* @version 0.1
* @since 0.1
* @access public
* @copyright 2012, Georgi Zhivankin . All rights reserved.
*/

/**
* BASIC SITE SETTINGS
*
* These settings define some basic site properties such as a site title, site description, copyright message and admin email address
* You can edit any of these settings
*/

define('siteTitle', 'Ilyan.com Private Area');
define('siteDescription', 'Welcome!');
define("copyright", "Copyright &copy; 2012, <a href='http://www.ilyan.com'>Ilyan.com</a>. All rights reserved.");
define('siteMail', 'info@example.com');

// set a default timezone
date_default_timezone_set('Europe/Sofia');

/**
* STOP EDITING HERE.
*/

/**
* GENERAL SETTINGS
*
* These settings define the main application configuration parameters
* Please change them only when you are absolutely sure what you are doing
*/

/**
* the path of the directory where this script resides
*
* @var constants
* @access public
* @since 0.1
* @see setGeneralParameters()
*/
define('rootDir', 'C:/xampp/htdocs/smartdir');

/**
* Directory seperator that separates subfolders from one another
* I am using the unix style separator, I.E. slash instead of back slash as it is much more flexible and is the industry standard
* if you are using this script on Windows, change this to back slash
*
* @var constants
* @access public
* @since 0.1
* @see setGeneralParameters()
*/
define('ds', '/');

/**
* Default directory of the customer's files
* The default directory which contains the different client's subfolders
* note that for this script to function properly, you need to have a new folder for each customer you wish to grant access to
* @var constants
* @access public
* @since 0.1
* @see setGeneralParameters()
*/
define('dir', 'clients');

/**
* Default includes directory
* The default directory which contains the include files that the script uses
*
* @var constants
* @access public
* @since 0.1
* @see setGeneralParameters()
*/
define('includes', 'includes');

/**
* FUNCTIONS for setting up the above parameters
*/
function setGeneralParameters($rootDir, $ds, $dir, $protectedDir)
{
	$rootDir = 'rootDir';
	$ds = 'ds';
$dir = 'dir';
$protectedDir = 'protectedDir';
}

/**
* Load include files and set database parameters
*/
// load the functions file from the includes folder
require_once('includes/dirfuncs.inc.php');
// load the database connector from the includes folder as well
require_once('includes/mysql.inc.php');
// Instantiate a MySQL object
$db = new Mysql();
// Set the database encoding to UTF8
$db->setEncoding('utf8');
?>