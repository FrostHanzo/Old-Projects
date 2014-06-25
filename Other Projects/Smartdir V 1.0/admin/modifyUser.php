<?php
/**
* modifyUser
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
// get the client's directories which will be used to build the menu with checkboxes
$list = getFileList(rootDir.ds.dir, true);
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
// check if the modify button has been clicked and if so, process the form
// get the user's details from the database
// create a salt for decoding the password
$key = "thisisthesecretkeythathelpstoregisternewuserstothesystem";
$sql = $db->query("SELECT username, AES_DECRYPT(password, '$key') as password, level FROM smartdir_users WHERE userID = $userID");
// check if anything is being found
if ($sql)
{
foreach ($sql as $res)
{
// assign the values into variables
$username = $res['username'];
$password = $res['password'];
$level = $res['level'];
}
}
// now check if the Modify User button has been clicked and if so, go and modify the user
if (array_key_exists('modifyUser', $_POST))
{
// get the username, password and the directory fields from the input sent by the user
$newUsername = trim($_POST['username']);
$newPassword = trim($_POST['password']);
$newLevel = trim($_POST['level']);
// Check the length of username, it must be between 3 and 15 characters long
if (strlen($newUsername) < 3 || strlen($newUsername) > 15)
{
$message[] = 'The username should contain between 3 and 15 characters';
}
// validate username
$regexpUser = "/^([a-zA-Z0-9\-._])+$/";
if (!preg_match($regexpUser, $newUsername))
{
	$message[] = "The username you chose is not valid, please provide username that conforms to the following standard: your username may contain one or more letters (a-z), numbers (0-9), -, _, ., is between 3 and 15 characters long and doesn't contain spaces or special characters such as dollar sign";
}
// check password
if (isset($newPassword))
{
if(strlen($newPassword) < 6 || preg_match('/\s/', $newPassword))
{
$message[] = 'The password should contain at least 6 characters without spaces';
}
// check that the passwords match
if ($newPassword != $_POST['passwordConfirm'])
{
$message[] = "The passwords you typed do not match, please retype them again and make sure that both are the same";
}
}
// check that a level is specified
if ($newLevel == 'unspecified')
{
	$message[] = "Please choose the level of the user you are trying to add";
}
// check for duplicate username different than the old one
if ($username =! $newUsername)
{
$checkDuplicateUsername = $db->query("SELECT * FROM users WHERE username = '$newUsername'");
// if the array returns a result
if ($checkDuplicateUsername)
{
// display a message 
$message[] = "The username: $username, you have chosen is already registered in the system. Please choose another one";
}
}
// if the message array is empty, go and process the form
	if (!$message)
{
// otherwise, it's ok to insert the details in the database
// see which is the biggest ID in the table and set the AUTO_INCREMENT accordingly
$largestID = $db->vQuery("ALTER TABLE smartdir_users AUTO_INCREMENT = 1");
// create a salt for the password
$key = "thisisthesecretkeythathelpstoregisternewuserstothesystem";
// insert the details in the database
$update = $db->query("UPDATE smartdir_users SET username = '$newUsername', password = AES_ENCRYPT('$newPassword', '$key'), level = '$newLevel' WHERE userID = $userID");
if (!empty($update))
{
// generate a failure message
$message[] = "There was a problem modifying the user $newUsername. Please try again";
} else
{
// generate a success message
$message[] = "The user information was modified successfully.";
}
}
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo siteTitle; ?> - Add a New User</title>
</head>

<body>
<div id="container">
<div id="header"><?php include_once('header.php'); ?></div>
<div id="content">
<h1><?php echo siteTitle; ?> - Modify User</h1>
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
<form action="modifyUser.php?userID=<?php echo $userID; ?>" method="post">
<label>Username:</label>
<input name="username" type="text" value = "<?php if (isset($username)) { echo $username; } ?>" />
<label>Password:</label>
<input name="password" type="password" value = "<?php if (isset($password)) { echo $password; } ?>" />
<label>Confirm Password:</label>
<input name="passwordConfirm" type="password" value="<?php if (isset($password)) { echo $password; } ?>" />
<br/>
<label>User Level:</label>
<select name='level'>
<option name='unspecified' value='unspecified'>Please choose a level:</option>
<option name='user' value='user'>User</option>
<option name='admin' value='admin'>Administrator</option>
</select>
</br>
<label>User's Default Home Directory:</label>
<select name='homeDir'>
<option name='unspecified' value='unspecified'>Please choose a default directory for this user:</option>
<?php
/**
* create a combo box containing all directories and subdirectories by running through the root directory specified in config.php recursively and list them inside that combo box
*/
foreach ($list as $file)
{
if ($file['type'] == 'dir')
{
?>
<option name="<?php echo basename($file['name']); ?>" value="<?php echo $file['name']; ?>"><?php echo basename($file['name']); ?></option>
<?php
}
}
?>
</select>
<br/>
<input name="modifyUser" type="submit" value="modify this user" />
<br/>
<input type='reset' name='clear' value='Clear' />
</form>

</p>
</div>
<div id="footer"><?php include_once('footer.php'); ?></div>

</body>
</html>
