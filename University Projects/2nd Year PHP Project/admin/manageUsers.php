<?php
/**
* ManageUsers file
* This file contains all functions related to managing users within the e-commerce website
* The events specified within this file are triggered by a values sppecified as path of the file's URL and are being obtained from the default $_GET array
*
* @author Georgi Zhivankin <abdr910@city.ac.uk>
* @access public
* @version 1.0
* @since 0.1
*/
// load the configuration file
require_once('../config.php');
// Include the restrict file
include_once('../includes/restrict.inc.admin.php');
// Include the restrict file that restricts access to administrators only
include_once('restrict.admin.php');
// check the $_GET array for an action value and assign it into a variable
if (isset($_GET['action']))
{
	$action = $_GET['action'];
} else {
	$action = 'none';
}
/**
* start the huge if else loop that would iterrate through the available actions and based on the specified action would perform the task * requested
*/
switch($action)
{
/**
* Code for action register
*/
case 'register':
// check if the add button has been clicked and if so, process the form
if (array_key_exists('register', $_POST))
{
$db_Prefix = $GLOBALS['db_Prefix'];
// get the username, password, email, gender etc, from the input sent by the user
$username = trim($_POST['username']);
$password = trim($_POST['password']);
$hashed_Password = hash('sha256', $password.$salt);
$gender = trim($_POST['gender']);
$email = trim($_POST['email']);
$phone = trim($_POST['phone']);
$level = trim($_POST['level']);
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
$hashed_Password = hash('sha256', $password.$salt);
// if a gender is not selected, notify the user
if(!$_POST['gender'])
{
$message[] = "Please select your gender";
}
// if there is no email or it's messed up
// Perform a match using a regular expression on the email address provided
$regexpMail = "/^[^0-9][A-z0-9_]+([.][A-z0-9_]+)*[@][A-z0-9_]+([.][A-z0-9_]+)*[.][A-z]{2,4}$/";
if (!preg_match($regexpMail, $_POST['email']))
{
	$message[] = "The email address you provided is not valid, please provide an email address in the format john.smith@example.com or john@example.com";
}
// check if email is provided at all
if (!$_POST['email'])
{
	$message[] = "An email address is not provided. Please provide one.";
}
// check that a level is specified
if ($level == 'unspecified')
{
	$message[] = "Please choose the level of the user you are trying to add";
}
// check for duplicate username
$checkDuplicateUsername = query("SELECT * FROM {$db_Prefix}users WHERE username = '$username'");
// if the array returns a result
if ($checkDuplicateUsername)
{
// display a message 
$message[] = "The username: $username, you have chosen is already taken. Please choose another one and try again.";
}
// if the message array is empty, go and process the form
	if (!$message)
{
// otherwise, it's ok to insert the details in the database
// see which is the biggest ID in the table and set the AUTO_INCREMENT accordingly
$largestID = vQuery("ALTER TABLE {$db_Prefix}users AUTO_INCREMENT = 1");
// insert the details in the database
$sql = "INSERT INTO {$db_Prefix}users (username, password, email, gender, phone, level) VALUES ('$username', '$hashed_Password', '$email', '$gender', '$phone', '$level')";
$insert = iQuery($sql);
if (!($insert))
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
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo SITE_TITLE; ?> - Add a New User</title>
<link href="../css/style.css" rel="stylesheet" type="text/css" />
</head>

<body>
<div id="container">
<div id="header">
<?php include_once('../header.php'); ?>
</div>
<div id="nav">
<?php include_once('nav.php'); ?>
<div class="clear"></div>
</div>
<div id="content">
<h1><?php echo SITE_TITLE; ?> - Add a New User</h1>
<?php include_once('../includes/message.inc.php'); ?>
<p>Register a new user by filling in the form below.</p>
<p>
<form action="<?php echo $_SERVER['PHP_SELF']; ?>?action=register" method="post">
<label for="username">Username:</label>
<input name="username" type="text" value="<?php if (isset($username)) { echo $username; } ?>" />
<br/>
<label for="password">Password:</label>
<input name="password" type="password" value="<?php if (isset($password)) { echo $password; } ?>" />
<br/>
<label for="passwordConfirm">Confirm Password:</label>
<input name="passwordConfirm" type="password" value="<?php if (isset($passwordConfirm)) { echo $passwordConfirm; } ?>" />
<br/>
<label for="email">email:</label>
<input name="email" type="email" value="<?php if (isset($email)) { echo $email; } ?>" />
<br/>
<label for="gender">Gender:</label>
<select name="gender">
<option name="unspecified" value="unspecified">Please choose your gender:</option>
<option name="m" value="<?php if (isset($gender) && $gender == 'm') { echo $gender; } else { echo "m"; } ?>">Male</option>
<option name="f" value="<?php if (isset($gender) && $gender == 'f') { echo $gender; } else { echo "f"; } ?>">Female</option>
</select>
<br/>
<label for="phone">phone:</label>
<input name="phone" type="text" value="<?php if (isset($phone)) { echo $phone; } ?>" />
<br/>
<label for="level">User Level:</label>
<select name='level'>
<option name='unspecified' value='unspecified'>Please choose a level:</option>
<option name='user' value="<?php if (isset($level) && $level == 'customer') { echo $level; } else { echo "customer"; } ?>">Customer</option>
<option name='admin' value="<?php if (isset($level) && $level == 'administrator') { echo $level; } else { echo "administrator"; } ?>">Administrator</option>
</select>
</br>
<input name="register" type="submit" value="Register" />
<br/>
<input type='reset' name='clear' value='Clear' />
</form>

</p>
</div>
<div id="footer">
<?php include_once('../footer.php'); ?>
</div>

</body>
</html>

<?php
break;
case 'modify':
/**
* Code for ModifyUser case
*/
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
// get the global variable containing the database table prefix
$db_Prefix = $GLOBALS['db_Prefix'];
// get the user's details from the database
$hashed_Password = hash('sha256', $password.$salt);
$sql = query("SELECT username, email, gender, phone, level FROM {$db_Prefix}users WHERE userID = $userID");
// check if anything is being found
if ($sql)
{
foreach ($sql as $res)
{
// assign the values into variables
$username = $res['username'];
$password = $res['password'];
$email = $res['email'];
$gender = $res['gender'];
$phone = $res['phone'];
$level = $res['level'];
}
}
// now check if the Modify User button has been clicked and if so, go and modify the user
if (array_key_exists('modify', $_POST))
{
// get the username, password, gender, phone and level fields from the input sent by the user
$newUsername = trim($_POST['username']);
$newPassword = hash('sha256', $_POST['password'].$salt);
$newEmail = trim($_POST['email']);
$newGender = trim($_POST['gender']);
$newPhone = trim($_POST['phone']);
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
if (isset($_POST['password']))
{
if (!empty($_POST['password']))
{
if(strlen($_POST['password']) < 6 || preg_match('/\s/', $_POST['password']))
{
$message[] = 'The password should contain at least 6 characters without spaces';
}
}
// check that the passwords match
if ($_POST['password'] != $_POST['passwordConfirm'])
{
$message[] = "The passwords you typed do not match, please retype them again and make sure that both are the same";
}
}
// Check if a password was submitted
// if a gender is not selected, notify the user
if(!$newGender)
{
$message[] = "Please select your gender";
}
// if there is no email or it's messed up
// Perform a match using a regular expression on the email address provided
$regexpMail = "/^[^0-9][A-z0-9_]+([.][A-z0-9_]+)*[@][A-z0-9_]+([.][A-z0-9_]+)*[.][A-z]{2,4}$/";
if (!preg_match($regexpMail, $newEmail))
{
	$message[] = "The email address you provided is not valid, please provide an email address in the format john.smith@example.com or john@example.com";
}
// check if email is provided at all
if (!$newEmail)
{
	$message[] = "An email address is not provided. Please provide one.";
}
// check that a level is specified
if ($newLevel == 'unspecified')
{
	$message[] = "Please choose the level of the user you are trying to add";
}
// check for duplicate username different than the old one
if ($newUsername != $username)
{
$checkDuplicateUsername = query("SELECT * FROM {$db_Prefix}users WHERE username = '$newUsername'");
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
$largestID = vQuery("ALTER TABLE {$db_Prefix}users AUTO_INCREMENT = 1");
// insert the details in the database
$update = query("UPDATE {$db_Prefix}users SET username = '$newUsername', ".(($_POST['password'] != "") ? "password = '$newPassword'," : "")." email = '$newEmail',
gender = '$newGender', phone = '$newPhone', level = '$newLevel' WHERE userID = $userID");
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
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo SITE_TITLE; ?> - Modify a User</title>
<link href="../css/style.css" rel="stylesheet" type="text/css" />
</head>

<body>
<div id="container">
<div id="header">
<?php include_once('../header.php'); ?>
</div>
<div id="nav">
<?php include_once('nav.php'); ?>
<div class="clear"></div>
</div>
<div id="content">
<h1><?php echo SITE_TITLE; ?> - Modify User</h1>
<?php include_once('../includes/message.inc.php'); ?>
<p>Modifying user's details is easy. Change the information that you would like to update and press on modify.</p>
<p>
<form action="<?php echo $_SERVER['PHP_SELF']; ?>?action=modify&userID=<?php echo $userID; ?>" method="post">
<label for="username">Username:</label>
<input name="username" type="text" value = "<?php if (isset($username)) { echo $username; } ?>" />
<br/>
<label for="password">Password:</label>
<input name="password" type="password" value = "<?php if (isset($password)) { echo $password; } ?>" />
<br/>
<label for="passwordConfirm">Confirm Password:</label>
<input name="passwordConfirm" type="password" value="<?php if (isset($password)) { echo $password; } ?>" />
<br/>
<label for="email">email:</label>
<input name="email" type="email" value="<?php if (isset($email)) { echo $email; } ?>" />
<br/>
<label for="gender">Gender:</label>
<select name="gender">
<option name="unspecified" value="unspecified">Please choose your gender:</option>
<option name="m" value="<?php if (isset($gender) && $gender == 'm') { echo $gender; } else { echo "m"; } ?>">Male</option>
<option name="f" value="<?php if (isset($gender) && $gender == 'f') { echo $gender; } else { echo "f"; } ?>">Female</option>
</select>
<br/>
<label for="phone">phone:</label>
<input name="phone" type="text" value="<?php if (isset($phone)) { echo $phone; } ?>" />
<br/>
<label for="level">User Level:</label>
<select name='level'>
<option name='unspecified' value='unspecified'>Please choose a level:</option>
<option name='user' value="<?php if (isset($level) && $level == 'customer') { echo $level; } else { echo "customer"; } ?>">Customer</option>
<option name='admin' value="<?php if (isset($level) && $level == 'administrator') { echo $level; } else { echo "administrator"; } ?>">Administrator</option>
</select>
</br>
<input name="modify" type="submit" value="modify this user" />
<br/>
<input type='reset' name='clear' value='Clear' />
</form>

</p>
</div>
<div id="footer">
<?php include_once('../footer.php'); ?>
</div>

</body>
</html>

<?php
break;
case 'delete':
/**
* Code for delete user case
*/
// Initialise error array that holds error messages
$message = array();
// get the global database prefix table variable
$db_Prefix = $GLOBALS['db_Prefix'];
// get the user ID of the user the script needs to modify from the URL
$userID = $_GET['userID'];
// check if the $userID is set
if (!isset($userID))
{
$message[] = 'Invalid user ID. Please go back and try again';
exit;
}
// check if the user exists in the database prior to deleting it
$sql = query("SELECT username FROM {$db_Prefix}users where userID = '$userID'");
foreach ($sql as $user)
{
$username = $user['username'];
}
if (!$username)
{
	$message[] = "The user you are trying to remove does not exist or has been deleted already.";
}
// Check if the Delete User button has been clicked and if so, go and delete the user
if (array_key_exists('delete', $_POST))
{
// if the message array is empty, go and process the form
	if (!$message)
{
// see which is the biggest ID in the table and set the AUTO_INCREMENT accordingly
$largestID = vQuery("ALTER TABLE {$db_Prefix}users AUTO_INCREMENT = 1");
// Delete the details from the database
$delete = query("DELETE FROM {$db_Prefix}users WHERE userID = $userID");
if (!empty($delete))
{
// generate a failure message
$message[] = "There was a problem deleting the user $username. Please try again";
} else
{
// generate a success message
$message[] = "The user $username was deleted successfully.";
}
// Reset the $username variable
unset($username);
}
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo SITE_TITLE; ?> - Delete a User</title>
<link href="../css/style.css" rel="stylesheet" type="text/css" />
</head>

<body>
<div id="container">
<div id="header"><?php include_once('../header.php'); ?></div>
<div id="nav">
<?php include_once('nav.php'); ?>
<div class="clear"></div>
</div>
<div id="content">
<h1><?php echo SITE_TITLE; ?> - Delete User</h1>
<?php include_once('../includes/message.inc.php'); ?>
<p>
<?php if (isset($username))
{
	printf("<p>Please press the 'Delete' button to remove: %s from the database completely.</p>",
$username);
}
?>
</p>
<p>
<form action="<?php echo $_SERVER['PHP_SELF']; ?>?action=delete&userID=<?php echo $userID; ?>" method="post">
<?php if (isset($username))
{
	?>
<input name="delete" type="submit" value="Delete this user" />
<?php } ?>
<br/>
</form>

</p>
</div>
<div id="footer"><?php include_once('../footer.php'); ?></div>

</body>
</html>

<?php break;
/**
* Code for the action none, where action is not specified
*/
case 'none':

// initialise an error array holding error messages
$message = array();
// get the global database prefix table
$db_Prefix = DB_PREFIX;
// browse through the user's table looking for users
$sql = query("SELECT * FROM {$db_Prefix}users");
// check if the array contains any users
if (!$sql)
{
// create an error message that will be shown to the user
$message[] = 'No users were found in the database';
}
// count the number of retrieved rows
$userCount = count($sql);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo SITE_TITLE; ?> - User Management</title>
<link href="../css/style.css" rel="stylesheet" type="text/css" />
</head>

<body>
<div id="container">
<div id="header">
<?php include_once('../header.php'); ?>
</div>
<div id="nav">
<?php include_once('nav.php'); ?>
<div class="clear"></div>
</div>
<div id="content">
<h1><?php echo SITE_TITLE; ?> - User Management</h1>
<?php include_once('../includes/message.inc.php'); ?>
<p>This is the user management section where you can register, modify and delete users off the system as well as view their past orders.</p>
<p><a href="manageUsers.php?action=register">Register a new user</a></p>
<p>
<table>
<tr>
<th>Username:</th>
<th>Gender:</th>
<th>Email:</th>
<th>Phone:</th>
<th>Level:</th>
</tr>
<?php foreach ($sql as $row) { ?>
<tr>
<th><?php echo $row['userName']; ?></th>
<th><?php if ($row['gender'] == 'm') { echo "Male"; } elseif ($row['gender'] == 'f') { echo "Female"; } else { $row['gender']; } ?></th>
<th><?php echo $row['email']; ?></th>
<th><?php echo $row['phone']; ?></th>
<th><?php echo $row['level']; ?></th>
<th><a href="<?php echo $_SERVER['PHP_SELF']; ?>?action=modify&amp;userID=<?php echo $row['userID']; ?>">Modify Details</a></th>
<th><a href="<?php echo $_SERVER['PHP_SELF']; ?>?action=delete&amp;userID=<?php echo $row['userID']; ?>">Delete</a></th>
</tr>
<?php } ?>
</table>
<p>There are a total of <?php echo $userCount; ?> users in the database.</p>
</div>
<div id="footer">
<?php include_once('../footer.php'); ?>
</div>

</div>
</body>
</html>

<?php break; } ?>