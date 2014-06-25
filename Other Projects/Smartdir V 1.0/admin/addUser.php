<?php
/**
* addUser
* This file contains the functions needed for the script to add a new user by writing into a .passwd file their username and password and by adding their directory path into the /permissions/.usrs. file
* Ideally, the user details should have been added into a database, but as the script is needed quite quickly, the implementation of this feature would be left out for now
* @author Georgi Zhivankin <georgijivankin@gmail.com>
* @copyright Copyright 2012, Georgi Zhivankin. All rights reserved.
* @version 0.1
* @since 0.1
*/
// require the configuration file to initialise the required settings
require_once('../config.php');
// include the restrict file
// require_once('restrict.inc.php');
// get the client's directories which will be used to build the menu with checkboxes
$list = getFileList(rootDir.ds.dir, true);
// check if the add button has been clicked and if so, process the form
if (array_key_exists('addUser', $_POST))
{
// get the username, password, level and the home directory fields from the input sent by the user
$username = trim($_POST['username']);
$password = trim($_POST['password']);
$level = trim($_POST['level']);
$homeDir = trim($_POST['homeDir']);
// Initialise error array that holds error messages
$message = array();
// Check the length of username, it must be between 3 and 15 characters long
if (strlen($username) < 3 || strlen($username) > 15)
{
$message[] = 'The username should contain between 3 and 15 characters';
}
// validate username
$regexpUser = "/^([a-zA-Z0-9\-._])+$/";
if (!preg_match($regexpUser, $username))
{
	$message[] = "The username you chose is not valid, please provide username that conforms to the following standard: your username may contain one or more letters (a-z), numbers (0-9), -, _, ., is between 3 and 15 characters long and doesn't contain spaces or special characters such as dollar sign";
}
// check password
if(strlen($password) < 6 || preg_match('/\s/', $password))
{
$message[] = 'The password should contain at least 6 characters without spaces';
}
// check that the passwords match
if ($password != $_POST['passwordConfirm'])
{
$message[] = "The passwords you typed do not match, please retype them again and make sure that both are the same";
}
// check that a level is specified
if ($level == 'unspecified')
{
	$message[] = "Please choose the level of the user you are trying to add";
}
// check that a home directory is selected
if ($homeDir == 'unspecified')
{
	$message[] = 'Please choose a default home directory for this user';
}
// check for duplicate username
$checkDuplicateUsername = $db->query("SELECT * FROM smartdir_users WHERE username = '$username'");
// if the array returns a result
if ($checkDuplicateUsername)
{
// display a message 
$message[] = "The username: $username, you have chosen is already registered in the system. Please choose another one";
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
$insert = $db->query("INSERT INTO smartdir_users (username, password, level, homeDir) VALUES ('$username', AES_ENCRYPT('$password', '$key'), '$level', '$homeDir')");
if (!empty($insert))
{
// generate a failure message
$message[] = "There was a problem adding the user $username into the system. Please try again";
} else
{
// generate a success message
$message[] = "The user was added successfully to the system";
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
<h1><?php echo siteTitle; ?> - Add a New User</h1>
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
<form action="addUser.php" method="post">
<label>Username:</label>
<input name="username" type="text" />
<label>Password:</label>
<input name="password" type="password" />
<label>Confirm Password:</label>
<input name="passwordConfirm" type="password" />
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
<input name="addUser" type="submit" value="add this user" />
<br/>
<input type='reset' name='clear' value='Clear' />
</form>

</p>
</div>
<div id="footer"><?php include_once('footer.php'); ?></div>

</body>
</html>
