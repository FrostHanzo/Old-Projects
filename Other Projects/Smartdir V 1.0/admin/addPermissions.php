<?php
/**
* Permissions
* This file contains the functions needed for the script to give permissions to users to access different ffolders
* @author Georgi Zhivankin <georgijivankin@gmail.com>
* @copyright Copyright 2012, Georgi Zhivankin. All rights reserved.
* @version 1.0
* @since 0.1
*/
// require the configuration file to initialise the required settings
require_once('../config.php');
// include the restrict file
require_once('restrict.inc.php');
// create an error array that contains error messages
$message = array();
// get the userID off the URL
$userID = trim($_GET['userID']);
// if the userID is not past along with the form at runtime, do nothing
if (!$userID)
{
// display a message saying that the ID is not present and stop the script
echo "The userID is not valid. Please go back and try again.";
exit;
}
// get the client's directories which will be used to build the menu with checkboxes
$list = getFileList(rootDir.ds.dir, false);
// check if the update permission button has been clicked and if so, process the form
if (array_key_exists('addPermissions', $_POST))
{
// get the directory fields from the input sent by the user
$checkedDirectories = $_POST['directories'];
// if the message array is empty, go and process the form
	if (!$message)
{
// see which is the biggest ID in the table and set the AUTO_INCREMENT accordingly
$largestID = $db->vQuery("ALTER TABLE smartdir_directories AUTO_INCREMENT = 1");
// Check if the $_POST permissions is an array and if so, iterrate through the array and insert the permissions into the database
if (isset($checkedDirectories))
{
// loop through the array of values and assign those values into a variable as they are all directory locations
foreach ($checkedDirectories as $checkedDirPath)
	{
// assign the obtained value into a name variable by stripping off the location part
$checkedDirName = basename($checkedDirPath);
// check if the directory is not in the database already
$res = $db->query("SELECT * from smartdir_directories, smartdir_users2directories WHERE smartdir_directories.dirName = '$checkedDirName' AND smartdir_directories.dirPath = smartdir_users2directories.dirPath AND smartdir_directories.dirPath = '$checkedDirPath'");
// check if there are any returned rows
if ($res)
{
// do nothing
	}
else
{
// insert it into the database
$insert = $db->query("INSERT INTO smartdir_directories (dirName, dirPath) VALUES ('$checkedDirName', '$checkedDirPath')");
}
// check if the directory is not assigned to the user already
$res2 = $db->query("SELECT * from smartdir_directories, smartdir_users2directories WHERE smartdir_directories.dirPath = smartdir_users2directories.dirPath AND smartdir_directories.dirPath = '$checkedDirPath' AND smartdir_users2directories.userID = '$userID'");
// check if there are any returned rows
if ($res2)
{
// do nothing
	}
else
{
$insert2 = $db->query("INSERT INTO smartdir_users2directories (userID, dirPath) VALUES ($userID, '$checkedDirPath')");
}
	}
}
if (!empty($insert) && !empty($insert2))
{
// generate a failure message
$message[] = "There was a problem setting up the permissions. Please try again.";
} else
{
// generate a success message
$message[] = "The permissions were updated successfully";
}
}
}
// go and download the list of the already provided directories for the given user
$extDirs = $db->Query("SELECT smartdir_directories.dirName as extDirName, smartdir_directories.dirPath as extDirPath FROM smartdir_directories, smartdir_users2directories WHERE smartdir_directories.dirPath = smartdir_users2directories.dirPath AND smartdir_users2directories.userID = $userID");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo siteTitle; ?> - User Permissions</title>
</head>

<body>
<div id="container">
<div id="header"><?php include_once('header.php'); ?></div>
<div id="content">
<h1>User Permissions</h1>
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
</p>
<?php } ?>	
<p>
	<form name="permissionsForm" action="<?php echo $_SERVER['PHP_SELF']; ?>?userID=<?php echo $userID; ?>" method="post">
<label>User Permissions:</label>
<p>
<table>
<tr>
<th>Name:</th>
<th>Location:</th>
</tr>
<fieldset name="directories">
<?php
/**
* create a table with checkboxes containing all directories by running through the root directory specified in config.php recursively and list them inside that table
*/
foreach ($list as $single)
{
// display only directories I.E. files having the filetype 'dir'
if ($single['type'] == 'dir')
{
$singleName = basename($single['name']);
$singlePath = $single['name'];
?>
<tr>
<td><input type="checkbox" name="directories[]" value="<?php /** if (in_array_r($singleName, $extDirs)) { } else { */ echo $singlePath; ?>"
<?php
// Check if the array of existing directories contains the current directory and if so, set a variable to indicate this
$isChecked = in_array_r($singleName, $extDirs);
If ($isChecked)
{
	echo "checked='checked'";
}
?></td>
<td><?php echo $singleName; ?></td>
<td><?php echo $singlePath; ?></td>
</tr>
<?php } } ?>
</fieldset>
</table>
<br/>
<input name="addPermissions" type="submit" value="Add Permissions" />
</form>

</p>
</div>
<div id="footer"><?php include_once('footer.php'); ?></div>

</body>
</html>
