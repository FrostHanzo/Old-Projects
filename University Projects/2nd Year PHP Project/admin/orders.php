<?php
/**
* This is the my orders file that shows all previous orders for a given customer
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

// Get the user's ID from the database
$username = $_SESSION['userName'];
// Query the database and get the userID needed to query the orders tables
$sql = "SELECT userID from {$db_Prefix}users WHERE username = '$username'";
// Execute the query
$res = query($sql);
// If the details are found
if (!$res)
{
$message[] = "An error has occured while trying to display your details. Please contact us for help.";
}
// Get the userID into a variable
$userID = $res[0]['userID'];
// Now get all orders for that user
$sql = "SELECT * FROM {$db_Prefix}orders";
// Execute the query
$res = query($sql);
if (!$res)
{
$message[] = "Sorry, no previous orders were found in our system.";
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo SITE_TITLE; ?> - View Orders</title>
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
<h1><?php echo SITE_TITLE; ?> - View Orders</h1>
<?php include_once('includes/message.inc.php'); ?>
<p>Here you can view all previous customer's orders.</p>
<table>
<tr>
<th>Order Number:</th>
<th>User Name:</th>
<th>Date and time:</th>
<th>Order amount:</th>
<th>Shipping Method:</th>
<th>Delivered to:</th>
<th>Shipping Address:</th>
<th>Shipping Post code:</th>
<th>Order Status:</th>
</tr>
<tbody>
<?php
foreach ($res as $order)
{
?>
<tr>
<td><a href="../order.php?orderNo=<?php echo $order['orderNumber']; ?>"><?php echo $order['orderNumber']; ?></td>
<td><?php echo $username; ?></td>
<td><?php echo $order['orderDate']; ?></td>
<td><?php echo $order['orderAmount']; ?></td>
<td><?php echo $order['shippingMethod']; ?></td>
<td><?php echo $order['shippingName']; ?></td>
<td><?php echo $order['shippingAddress']; ?></td>
<td><?php echo $order['shippingPostCode']; ?></td>
<td><?php echo $order['orderStatus']; ?></td>
</tr>
<?php } ?>
</tbody>
</table>

</div>
<div id="footer">
<?php include_once('footer.php'); ?>
<div class="clear"></div>
</div>

</div>
</body>
</html>
