<?php
/**
* An individual order file which displays all products within an order
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

// First, check if the orderNumber parameter has been passed through the $_REQUEST superglobal array
if (!$_REQUEST['orderNo'])
{
$message[] = "Invalid order number. Please go back and try again";
}
if (!$message)
{
$orderNo = $_REQUEST['orderNo'];

// Now get the product IDs corresponding to a given order
$sql = "SELECT * FROM {$db_Prefix}products2orders WHERE orderNumber = $orderNo";
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

<?php include_once('includes/message.inc.php'); ?>
<p>Details for order number: <a href="myOrders.php"><?php echo $orderNo; ?></a></p>
<table>
<tr>
<th>Product Name:</th>
<th>Quantity:</th>
<th>Price:</th>
</tr>
<?php
// Now, get all products for the selected order
foreach ($res as $product)
{
// Get the product details from the products table
$sql = "SELECT * from {$db_Prefix}products WHERE productID = {$product['productID']}";
// Execute the query
$res = query($sql);
if ($res)
{
// display the products npw
foreach ($res as $res)
{
?>
<tr>
<td><a href="product.php?productID=<?php echo $product['productID']; ?>"><?php echo $res['productName']; ?></td>
<td><?php echo $product['productQuantity']; ?></td>
<td>&pound;<?php echo $res['productPrice']; ?></td>
</tr>
<?php } } }
} ?>
</table>
</div>
<div id="footer">
<?php include_once('footer.php'); ?>
<div class="clear"></div>
</div>

</div>
</body>
</html>
