<?php
/**
* logout file
* This file contains the logic needed for logging users out of the e-commerce website
* The logic is responsible both for user and admin logout
*
* @author Georgi Zhivankin <georgijivankin@gmail.com>
* @version 1.0
* @since 0.1
*/
// load the configuration file
include_once('config.php');

// Log the user out
session_destroy();
unset($_SESSION);
// Redirect back to the home page
header("Refresh: 5; url=index.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo SITE_TITLE; ?> - <?php echo SITE_DESCRIPTION; ?></title>
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
<p>You have now been logged out. Thank you for using our website. We hope to see you back soon!</p>
</div>
<div id="footer">
<?php include_once('footer.php'); ?>
</div>

</div>
</body>
</html>
