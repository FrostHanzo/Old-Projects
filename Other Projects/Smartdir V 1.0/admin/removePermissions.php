<?php
/**
* removePermissions
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
// go and download the list of the already provided directories for the given user
$dirs = $db->Query("SELECT smartdir_directories.dirName as dirName, smartdir_directories.dirPath as dirPath FROM smartdir_directories, smartdir_users2directories WHERE smartdir_directories.dirPath = smartdir_users2directories.dirPath AND smartdir_users2directories.userID = $userID");
// check if the remove permission button has been clicked and if so, process the form
if (array_key_exists('removePermissions', $_POST))
{
// get the directory fields from the input sent by the user
$checkedDirectories = $_POST['directories'];
// if the message array is empty, go and process the form
	if (!$message)
{
// Check if the $_POST permissions is an array and if so, iterrate through the array and insert the permissions into the database
if (isset($checkedDirectories))
{
// loop through the array of values and assign those values into a variable as they are all directory locations
foreach ($checkedDirectories as $checkedDirPath)
	{
$res = $db->query("DELETE FROM smartdir_users2directories WHERE smartdir_users2directories.dirPath = '$checkedDirPath' AND smartdir_users2directories.userID = $userID");
// see which is the biggest ID in the table and set the AUTO_INCREMENT accordingly
$largestID = $db->vQuery("ALTER TABLE smartdir_directories AUTO_INCREMENT = 1");
// check if there are any returned rows
if ($res)
{
$message[] = 'Error. There was a problem removing these permissions. Please go back and try again';
	}
else
{
    $message[] = 'The permissions were removed successfully';
   }
    }
    }
}
}
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
<h1>Remove Permissions</h1>
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
* create a table with checkboxes containing all directories by running through the SQL query specified above and list them inside that table
*/
foreach ($dirs as $dir)
{
?>
<tr>
<td><input type="checkbox" name="directories[]" value="<?php echo $dir['dirPath']; ?>" /></td>
<td><?php echo $dir['dirName']; ?></td>
<td><?php echo $dir['dirPath']; ?></td>
</tr>

<?php } ?>
</fieldset>
</table>
<br/>
<input name="removePermissions" type="submit" value="Remove Permissions" />
</form>

</p>
</div>
<div id="footer"><?php include_once('footer.php'); ?></div>

</body>
</html>
