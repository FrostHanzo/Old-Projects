<?php include_once('config.php'); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo SITE_TITLE; ?> - <?php echo SITE_DESCRIPTION; ?></title>
<link href="css/style.css" rel="stylesheet" type="text/css" />
<style type="text/css"></style></head>

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
<?php
if (isset($_SESSION['loggedIn']))
{
?>
<p>Hi, <?php echo ucfirst($_SESSION['userName']); ?>,
<br/>
Here you can view your past orders, you can make a new order or you can contact us for support or if you have any issues with your products.</p>
<?php } ?>
<?php
if (!isset($_SESSION['loggedIn']))
{ ?>
<div style="margin-left:auto; margin-right:auto; text-align:center; font-weight:bold; font-size:24px;">Welcome to the website of Radio Automation Systems.
</div>
<?php } ?>
</div>
<div id="footer">
<?php include_once('footer.php'); ?>
</div>

</div>
</body>
</html>
