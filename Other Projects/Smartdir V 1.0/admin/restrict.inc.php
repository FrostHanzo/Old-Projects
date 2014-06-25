<?php
/**
* Restrict Access File
* This file restricts unauthorised users from accessing certain resources
* The script restricts the users by checking if they are loggeed in and whether they have a level of a user or a level of an administrator
*
* @author Georgi Zhivankin <georgijivankin@gmail.com>
* @version 0.1
* @since 0.1
* @access public
* @copyright 2012, Georgi Zhivankin . All rights reserved.
*/
// where to redirect if rejected
$redirect = "../login.php";
// if session variable not set, redirect to login page
if (!isset($_SESSION['loggedIn']))
{
header("Location: $redirect");
exit;
}