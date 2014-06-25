<?php
/**
* deleteUser
* This file contains the functions needed for the script to modify an existing user by writing into a .passwd file their username and password and by adding their directory path into the /permissions/.usrs. file
* Ideally, the user details should have been added into a database, but as the script is needed quite quickly, the implementation of this feature would be left out for now
* @author Georgi Zhivankin <georgijivankin@gmail.com>
* @copyright Copyright 2012, Georgi Zhivankin. All rights reserved.
* @version 0.1
* @since 0.1
*/
// require the configuration file to initialise the required settings
require_once('../config.php');
// include the restrict file
require_once('restrict.inc.php');
// Initialise error array that holds error messages
$message = array();
// get the user ID of the user the script needs to modify from the URL
$userID = $_GET['userID'];
// check if the $userID is set
if (!isset($userID))
{
$message[] = 'Invalid user ID. Please go back and try again';
exit;
}
// check if the user exists in the database prior to deleting it
$sql = $db->query("SELECT username FROM smartdir_users where userID = '$userID'");
foreach ($sql as $user)
{
$username = $user['username'];
}
if (!$username)
{
	$message[] = "The user you are trying to remove, does not exist or has been deleted already.";
}
// Check if the Delete User button has been clicked and if so, go and modify the user
if (array_key_exists('deleteUser', $_POST))
{
// if the message array is empty, go and process the form
	if (!$message)
{
// see which is the biggest ID in the table and set the AUTO_INCREMENT accordingly
$largestID = $db->vQuery("ALTER TABLE users AUTO_INCREMENT = 1");
// Delete the details from the database
$delete = $db->query("DELETE FROM smartdir_users WHERE userID = $userID");
if (!empty($delete))
{
// generate a failure message
$message[] = "There was a problem deleting the user $username. Please try again";
} else
{
// generate a success message
$message[] = "The user $username was deleted successfully.";
}
}
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo siteTitle; ?> - Delete a User</title>
</head>

<body>
<div id="container">
<div id="header"><?php include_once('header.php'); ?></div>
<div id="content">
<h1><?php echo siteTitle; ?> - Delete User</h1>
<p>
<a href='users.php'>User Management</a>
</p>
<p>
<?php if (isset($message))
{
?>
<ul>
<?php 	foreach ($message as $error)
{
?>
<li><?php echo $error; ?></li>
<?php } ?>
</ul>
<?php } ?>
</p>
<p>
<form action="deleteUser.php?userID=<?php echo $userID; ?>" method="post">
<input name="deleteUser" type="submit" value="Delete this user" />
<br/>
</form>

</p>
</div>
<div id="footer"><?php include_once('footer.php'); ?></div>

</body>
</html>
