<?php
/**
* login file
* This file contains the logic needed for logging in users within the e-commerce website
* The logic is responsible both for user and admin login
*
* @author Georgi Zhivankin <georgijivankin@gmail.com>
* @version 1.0
* @since 0.1
*/
// load the configuration file
include_once('config.php');

if (!isset($_SESSION['loggedIn']) == 1)
{

if (array_key_exists('login', $_POST))
{
	// Take the username and the password from the script and try to login
$username = trim($_POST['username']);
$password = trim($_POST['password']);
// as the password stored in the database is hashed using a SALT and a SHA256 encryption, make a hashed version of the supplied database
// try to login
$hashed_Password = hash('sha256', $password.$salt);
$sql = "SELECT * FROM {$db_Prefix}users WHERE username = '$username' and password = '$hashed_Password'";
$res = query("$sql");
// check if there are returned rows
if ($res)
{
// Set some session variables for global use
$_SESSION['loggedIn'] = 1;
$_SESSION['userID'] = $res[0]['userID'];
$_SESSION['userName'] = $res[0]['userName'];
$_SESSION['level'] = $res[0]['level'];
// Redirect the user to the main page.
$refered = $_SERVER['HTTP_REFERER'];
if(strstr($refered, 'login') ===false)
{
header("location: $refered");
}
else
{
header("location: index.php");
}
}
else
{
$message[] = "Login attempt failed. Please try again.";
}
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo SITE_TITLE; ?> - Login</title>
<link href="css/style.css" rel="stylesheet" type="text/css" />
<style type="text/css"></style>
</head>

<body>
<div id="container">
<div id="header"><?php include_once('header.php'); ?>
</div>
<div id="nav">
<?php include_once('nav.php'); ?>
<div class="clear"></div>
</div>
<div id="content">
<?php include_once('includes/message.inc.php'); ?>
<p>If you have an account, you can log in to make an order or to contact us. <br/>Otherwise, you can <a href="register.php">register one from here</a> and then login from here.
<p>
<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" />
<label for='username'>Username:</label>
<input type="text" name="username" value="<?php if (isset($username)) { echo $username; } ?>" />
<br/>
<br/>
<label for='password'>Password:</label>
<input type="password" name="password" value="<?php if (isset($password)) { echo $password; } ?>" />
<br/>
<br/>
<input type="submit" name="login" value="Log in" />
</form>
</p>
</div>
<div id="footer">
<?php include_once('footer.php'); ?>
<div class="clear"></div>
</div>

</div>
</body>
</html>

<?php }
else
{
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo SITE_TITLE; ?> - Login</title>
<link href="css/style.css" rel="stylesheet" type="text/css" />
</head>

<body>
<div id="container">
<div id="header">
<?php include_once('header.php'); ?>
<div id="basket">
<h1>This is the basket!</h1>
</div>
<div class="clear"></div>
</div>
<div id="nav">
<?php include_once('nav.php'); ?>
<div class="clear"></div>
</div>
<div id="content" class="home">
<h1><?php echo SITE_TITLE; ?> - Login</h1>
<p>You are already logged in.</p>
</div>
<div id="footer">
<?php include_once('footer.php'); ?>
</div>
<div class="clear"></div>

</div>
</body>
</html>

<?php } ?>