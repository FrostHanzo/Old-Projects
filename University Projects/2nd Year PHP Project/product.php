<?php
/**
* An individual product file which displays all product's details
*
* @author Georgi Zhivankin <georgijivankin@gmail.com>
* @version 1.0
* @since 0.1
*/
// Load the configuration file
// Include the main configuration file
include_once('config.php');
include_once('includes/restrict.inc.php');
// Initialise an error array
$message = array();
// First, check if the productID parameter has been passed through the $_REQUEST superglobal array
if (!$_REQUEST['productID'])
{
$message[] = "Invalid product ID. Please go back and try again";
}
if (!$message)
{
$productID = $_REQUEST['productID'];

// Now get the product's details from the database
$sql = "SELECT * FROM {$db_Prefix}products WHERE productID = $productID";
// Execute the query
$res = query($sql);
if (!$res)
{
$message[] = "Sorry. An error has occured. Please contact us for more help.";
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo SITE_TITLE; ?> - Product Details</title>
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
<h1><?php echo SITE_TITLE; ?> - <?php echo SITE_DESCRIPTION; ?> | Welcome</h1>
<?php include_once('includes/message.inc.php'); ?>
<p>
<?php
foreach ($res as $product)
{
?>
<h2><?php echo $product['productName']; ?></h2>
<p>Product Details</p>
<?php
// Now, get the product images for the selected product
$sql = "SELECT * from {$db_Prefix}products2images WHERE productID = {$product['productID']}";
// Execute the query
$res = query($sql);
if ($res)
{
// Display the products details
foreach ($res as $image)
{
// Get the name of the image
$imageName = $image['imageName'];
	// Construct the images URL and display them
	$imageURL = UPLOAD_DIR.$image['imageName'];
// Show the images
?>
<div id="productImages">
<img src="https://lamp2010.soi.city.ac.uk/~abdr910/productImages/<?php echo $image['imageName']; ?>" alt="<?php echo $image['imageName']; ?>" width="250" height="250" />
</div>
<br/>
<?php } } ?>
<p><?php echo $product['productDescription']; ?></p>
<br/>
<p><a href="cart.php?action=add&productID=<?php echo $product['productID']; ?>&quantity=1">Add to cart (&pound;<?php echo $product['productPrice']; ?>)</a></p>
<?php } } ?>
</div>
</p>
<div id="footer">
<?php include_once('footer.php'); ?>
<div class="clear"></div>
</div>

</div>
</body>
</html>
