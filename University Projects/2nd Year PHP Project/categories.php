<?php
/**
* categories file
*
* This file displays all categories and all products within eatch category
* The events specified within this file are triggered by a values sppecified as path of the file's URL and are being obtained from the default $_GET array
* After the values are obtained, the script check if a matching case is found within the switch loop and runs the corresponding code, if not, the default case is run which triggers an error and a log file with the attempted action is created
*
* @author Georgi Zhivankin <georgijivankin@gmail.com>
* @version 1.0
* @since 0.1
*/
// Load the configuration file
require_once('config.php');
// Initialise an error array
$message = array();
// Display all categories from the database
$sql = "SELECT * from {$db_Prefix}categories";
// Execute the query
$res = query($sql);
// Check if something is retrieved
if (!$res)
{
$message[] = "No categories were found.";
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo SITE_TITLE; ?> - Product's catalogue></title>
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
<p>Welcome to the <?php echo SITE_TITLE; ?> product's catalogue. To view our products or make an order, please choose one of the categories below.
<br/>
Happy browsing!</p>
<p>
<ul>
<?php
foreach ($res as $categoryID => $category)
{
?>
<li><a href="category.php?categoryID=<?php echo $category['categoryID']; ?>"><?php echo $category['categoryName']; ?></a>
<br/>
<?php echo $category['categoryDescription']; ?>
</li>
<?php } ?>
</ul>
</div>

<div id="footer">
<?php include_once('footer.php'); ?>
</div>

</div>
</body>
</html>
