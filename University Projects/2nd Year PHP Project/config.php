<?php
session_start();
ob_start();

/**
* Configuration file
* This configuration file contains all settings for the IN3008 Electronic Commerce Coursework
*
* @author Georgi Zhivankin <abdr910@city.ac.uk>
* @access public
* @version 1.0
* @since 0.1
*/

/**
* General Settings
* These settings define the main parameters of the site such as a site title, default site description, copyright message, administrator's email address, etc
* All settings are defined as constants as they will stay unchanged throughout the script
*/

define('SITE_TITLE', 'Radio Automation Systems');
define('SITE_DESCRIPTION', 'Radio Automation, Voice Tracking and Playlist Creation Software');
define('COPYRIGHT', 'Copyright &copy; '.date("Y").' Georgi Zhivankin and City University London. All rights reserved.');
// Set the default timezone to GMT / UTC. Requires at least PHP 5.1 or newer.
date_default_timezone_set('UTC');
// An older statement that does the same as the newer one - sets the ini_set parameter of the PHP.ini file to the local timezone to GMT.
//ini_set('date.timezone', 'Europe/London');

/**
* Server and Directory Settings
*/
// define the default publically available and accessible directory on the server
define('SERVER_DOC_ROOT', '/');
// define the directory where this script resides
define('DIR', '');
// define the root directory for the whole script by combining the values from the previous two constants into one
define('ROOT', SERVER_DOC_ROOT.DIR);
// define a constant that holds the path to the includes directory
define('INCLUDES', ROOT.'/includes');
// define the directory that holds the product images uploaded via the add products page
define('UPLOAD_DIR', ROOT.'/productImages/');
// define the maximum upload size of a file (in bites)

/**
* DATABASE Settings
*
* This section defines the settings needed for the script to connect to its database
* Please change the relevant details as needed
* All settings are constants so they are available within the global scope of the script
*/

define('DB_HOST', 'dbserverhost');
define('DB_NAME', 'dbname');
define('DB_USER', 'dbuser');
define('DB_PASS', 'dbpass');
define('DB_PREFIX', 'in3008_');
$db_Prefix = DB_PREFIX;
// Take the DB_PREFIX constant and assign it into a variable with global scope for convenience.
global $db_Prefix;
// Create a password salt
$password_Salt = "P3j7QyZ2Ec9V7tkdu";
// Make the password salt hash a variable with global scope.
global $password_Salt;
// Load the MySQL abstraction class
include_once(INCLUDES.'/mysql.inc.php');

/**
* Upload Picture Settings
*
These settings define some basic parameters for our custom picture uploads
*/
// The maximum size of the uploaded image file (in bites)
define('MAX_FILE_SIZE', 2097152);

/**
* Shopping Cart Functions
*/

/**
* A function to display how many items are in the cart
*/
function displayNumberOfItems()
{
// Initialise a session that would hold the shopping cart. Cookies could have been used, but storing the cart into a session is better as even // the customer's browser does not have cookies enabled, PHP will still track them accurately.
$cart;
if(isSet($_SESSION['cart'])) {
	$cart = $_SESSION['cart'];
	// Checks if the cart is empty and displays the appropriate message
	if (!$cart)
	{
	return '<p>Your shopping cart is empty.</p>';
	}
	else
	{
	// Count how many items are in the cart
	$countItems = $_SESSION['items'];
	// Display the plurals correctly, I.E. 1 product instead of 1 products
	// Check if the items are more than one and display the letter s, otherwise, don't display anything
	$plural = '';
	if ($countItems > 1)
	{
	 $plural = 's';
	}
	// Return the message and a link to the cart page
	return "<p>You have $countItems product$plural in your shopping cart.</p>";
	}
} else {
	return '<p>Your shopping cart is empty.</p>';
}
}
?>