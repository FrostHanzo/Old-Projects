<?php
/**
* Register File
* This is the file that contains the logic for user registrations. It is identical to the code at admin/manageUsers.php case register', however, some of the options from the form were removed as they are available only for administrators
*
* @author Georgi Zhivankin <abdr910@city.ac.uk>
* @access public
* @version 1.0
* @since 0.1
*/
// load the configuration file
require_once('config.php');

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
// Initialise error array that holds error messages
$message = array();
// Check the length of username, it must be between 3 and 15 characters long
if (strlen($username) < 3 || strlen($username) > 15)
{
$message[] = 'Your username should contain between 3 and 15 characters';
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
$message[] = 'Your password should contain at least 6 characters without spaces';
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
$sql = "INSERT INTO {$db_Prefix}users (username, password, email, gender, phone, level) VALUES ('$username', '$hashed_Password', '$email', '$gender', '$phone', 'customer')";
$insert = iQuery($sql);
if (!$insert)
{
// generate a failure message
$message[] = "There was a problem registering $username into the system. Please try again or contact us for help.";
} else
{
// generate a success message
$message[] = "Thank you for registering an account at Radio Automation Systems.";
}
}
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo SITE_TITLE; ?> - Register</title>
<link href="css/style.css" rel="stylesheet" type="text/css" />
</head>

<body>
<div id="container">
<div id="header">
<?php include_once('header.php'); ?>
</div>
<div id="nav">
<?php include_once('nav.php'); ?>
<div class="clear"></div>
</div>
<div id="content">

<?php include_once('includes/message.inc.php'); ?>
<p>Thank you for your interest in registering an account at <?php echo SITE_TITLE; ?>.
<br/>
To register, please complete the form below and hit the 'register' button when ready.
<br/>
Thank you!</p>
<p>
	<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
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
</br>
<input name="register" type="submit" value="Register" />
<br/>
<input type='reset' name='clear' value='Clear' />
</form>

</p>
</div>
<div id="footer">
<?php include_once('footer.php'); ?>
</div>

</body>
</html>
