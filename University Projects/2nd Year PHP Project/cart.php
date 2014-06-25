<?php
/**
* This is the cart file that contains the functions needed to add and remove products from the cart and to show the cart itself
*
* @author Georgi Zhivankin <georgijivankin@gmail.com>
* @version 1.0
* @since 0.1
*/
// Load the configuration file
// Include the main configuration file
include_once('config.php');
include_once('includes/restrict.inc.php');
// Start a new session
session_start();
// Get the product ID of the product from the cart's URL
$productID = $_REQUEST['productID'];
// Get the action that the cart would need to perform
$action = $_REQUEST['action'];
// If the cart is not initialised, initialise it
if (!isset($_SESSION['cart']))
{
	$_SESSION['cart'] = array();
	$_SESSION['items'] = 0;
	$_SESSION['totalPrice'] = 0.00;
	}

/**
* Start a switch type loop by switching the action and deciding what to do based on the specified action
*/
switch ($action)
{
case 'add':
// Add a product to the cart
// Check if the product ID is already in the cart and increase it's quantity
if (isset($_SESSION['cart'][$productID]))
{
	$_SESSION['cart'][$productID]++;
}
else
{
// Add it as the first item
$_SESSION['cart'][$productID] = 1;
}
break;
case 'update':
// Go through all items and update each quantity accordingly
foreach ($_REQUEST[items] as $item)
{
$itemID = $item['productID'];
$itemQuantity = $item['quantity'];
$_SESSION[cart][$itemID] = $itemQuantity;
}
// $_SESSION[cart][$productID] = $_REQUEST['quantity'];
break;
case 'remove':
// Remove a product from the cart
$_SESSION['cart'][$productID]--;
// If the cart does not have more products, destroy it
if ($_SESSION['cart'][$productID] == 0)
{
unset($_SESSION['cart'][$productID]);
}
break;
case 'empty':
// Destroy the cart, disregarding what products it contains
unset($_SESSION['cart']);
    break;
	}
// Count all items
$_SESSION['items'] = count($_SESSION[cart]);

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo SITE_TITLE; ?> - Shopping Cart</title>
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
<h1><?php echo SITE_TITLE; ?> - Shopping Cart</h1>
<?php include_once('includes/message.inc.php'); ?>
<p>This is your shopping cart. Here you can check what products you have added, update their quantities, delete them and check your selected products out.</p>
<p>
<?php
/**
* Show the contents of the cart
* Iterrate through the items within the $_SESSION['prodductID'] array and obtain eatch product's ID, quantity and the details for the corresponding product from the database
*/
// Check if the cart is not empty
if ($_SESSION['cart'])
{
?>

<form name="form1" action="cart.php?action=update" method="POST">
<table>
<tr>
<td>Product Name:</td>
<td>QTY:</td>
<td>Price:</td>
<td>Total Price:</td>
</tr>
<?php
// Show all items by iterrating through the array of items where $productID is the key and the quantity of each item is the value of the array
foreach($_SESSION['cart'] as $productID => $quantity)
{
// Get all details from the database
$sql = "SELECT productName, productPrice from in3008_products WHERE productID = $productID";
// Execute the query
$res = query($sql);
// Check if the product details were retrieved
if ($res)
{
// Go through all products
foreach ($res as $item)
{
// Extract the details from the array as simple variables in order to be easy to refer to them later
extract ($item);
// Calculate the total cost of the item
$totalItemCost = ($quantity * $productPrice);
// Add to the total cost of all items in the cart
$totalCost = $totalCost + $totalItemCost;
// Add the total cost into the session variable
$_SESSION['totalPrice'] = $totalCost;
?>
<tr>
<input type="hidden" name="items[<?php echo $productID; ?>][productID]" value="<?php echo $productID; ?>" />
<td><?php echo $productName; ?></td>
<td><input type="text" size="5" name="items[<?php echo $productID; ?>][quantity]" value="<?php echo $quantity; ?>" /></td>
<td>&pound;<?php echo $productPrice; ?></td>
<td>&pound;<?php echo $totalItemCost; ?></td>
<td><a href="cart.php?action=remove&productID=<?php echo $productID; ?>">Remove this product</a></td>
</tr>
<?php } } } ?>
<tr>
<td>Total cost for all items:</td>
<td>&pound;<?php echo $totalCost; ?></td>
</tr>
</table>
<input type="submit" value="Update Quantity" />
<br/>
<a href="cart.php?action=empty">Empty Cart</a>
<br/>
<a href="checkout.php">Check Out</a>
</form>
<?php
} else
{
?>
<p>Your shopping cart is empty.
<br/>Please go back to the products catalogue and add items to your shopping cart.</p>
<?php
}
?>
</p>
</div>
<div id="footer">
<?php include_once('footer.php'); ?>
<div class="clear"></div>
</div>

</div>
</body>
</html>
