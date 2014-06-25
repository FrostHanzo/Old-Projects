<?php
/**
* Users
* This file contains the logic that retrieves users from the database and displays links for adding, modifying and removing users as well as for adding and removing permissions for these users
*
* @author Georgi Zhivankin <georgijivankin@gmail.com>
* @copyright Copyright 2012, Georgi Zhivankin. All rights reserved.
* @version 0.1
* @since 0.1
*/
// require the configuration file to initialise the required settings
require_once('../config.php');
// include the restrict file
require_once('restrict.inc.php');
// create an array to hold error messages
$messages = array();
// log into the database and get the list of users from there
$res = $db->query("SELECT * FROM smartdir_users");
// if the result is 0, display a message informing the user that the user list is empty
if (!$res)
{
$message[] = "There are no users in the database. Why don't you add one now?";
}
$rows = count($res);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo siteTitle; ?> - <?php echo siteDescription; ?></title>
</head>

<body>
<div id="container">
<div id="header"><?php include_once('header.php'); ?></div>
<div id="content">
<h1>User Management</h1>
<p><a href='addUser.php'>Add a New User</a></p>
<p>
<?php if (isset($message))
{
?>
<ul>
<?php foreach ($message as $error)
{
	?>
    <li><?php echo $error; ?></li>
    <?php } } else { ?>
<p>There are <?php echo $rows; ?> users in the database.</p>
<p>
    <table>
<tr>
<th>Username:</th>
<th>Level:</th>
<th>Home Directory:</th>
</tr>
<?php foreach ($res as $user)
{
?>
<tr>
<td><?php echo $user['username']; ?></td>
<td><?php echo $user['level']; ?></td>
<td><?php echo $user['homeDir']; ?></td>
<td><a href="addPermissions.php?userID=<?php echo $user['userID']; ?>">Add Permissions</a></td>
<td><a href="removePermissions.php?userID=<?php echo $user['userID']; ?>">Remove Permissions</a></td>
<td><a href="modifyUser.php?userID=<?php echo $user['userID']; ?>">Modify</a></td>
<td><a href="deleteUser.php?userID=<?php echo $user['userID']; ?>">Delete</a></td>
</tr>
<?php } ?>
</table>
<?php } ?>
</p>
</div>
<div id="footer"><?php include_once('footer.php'); ?></div>
</body>
</html>

