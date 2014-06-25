<?php
/**
* Login file
* This file contains the logic for loging in into the script
* @author Georgi Zhivankin <georgijivankin@gmail.com>
* @copyright Copyright 2012, Georgi Zhivankin. All rights reserved.
* @version 0.1
* @since 0.1
*/
// require the configuration file
require_once('config.php');
// process the script only if the login button is being clicked
if (array_key_exists('login', $_POST))
{
// initialise an error array to hold the error messages
	$message = array();
// get the details from the form
$username = trim($_POST['username']);
$password = trim($_POST['password']);
// salt for the password
$key = "thisisthesecretkeythathelpstoregisternewuserstothesystem";
// get the username's details from the database
$sql = $db->query("SELECT * FROM smartdir_users WHERE username = '$username' AND password = 	AES_ENCRYPT('$password', '$key')");
// check if any rows were retrieved
if (!$sql)
{
$message[] = 'Login attempt failed. Please try again.';
}
else
{
foreach ($sql as $user)
{
$id = $user['userID'];
$name = $user['username'];
$level = $user['level'];
$homeDir = $user['homeDir'];
// add some values to the $_SESSION array
$_SESSION['loggedIn'] = 'Y';
$_SESSION['userID'] = $id;
$_SESSION['username'] = $name;
$_SESSION['level'] = $level;
$_SESSION['homeDir'] = $homeDir;
$_SESSION['start'] = time();
// redirect to the requested page depending on the level of the user
if ($_SESSION['level'] == 'admin')
{
header("location: index.php");
exit;
} elseif ($_SESSION['level'] == 'user')
{
header("location: index.php");
exit;
} else {
	// the user was not logged in successfully, so destroy the session
	$_SESSION = array();
session_destroy();
$message[] = 'Login attempt failed. Please try again.';
}
}
}
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo siteTitle; ?> - Login</title>
</head>

<body>
<div id="container">
<div id="header"><?php include_once('header.php'); ?></div>
<div id="content">
<h1><?php echo siteTitle; ?> - Login</h1>
<p>
To access this resource, you need to be logged in. Please enter your username and password in the fields below and click on 'Login' to continue.</p>
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
<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
<label>Username:</label>
<input name="username" type="text" />
<label>Password:</label>
<input name="password" type="password" />
<br/>
<input name="login" type="submit" value="Login" />
<br/>
</form>

</p>
</div>
<div id="footer"><?php include_once('footer.php'); ?></div>

</body>
</html>
