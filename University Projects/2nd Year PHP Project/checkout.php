<?php
/**
* Checkout file
* This is a mock mechanism for a virtual check out
*
* @author Georgi Zhivankin <georgijivankin@gmail.com>
* @version 1.0
* @since 0.1
*/
// Load the configuration file
include_once('config.php');
include_once('includes/restrict.inc.php');
/**
* Get the items that the customer is checking out
* The code is almost the same as the cart code
*/
$message = array();
// Check if the cart is not empty
if ($_SESSION['cart'])
{
// Process the form and place an order if the form is submitted
if (array_key_exists('placeOrder', $_POST))
{
// First, make a few checks to verify that all fields in the shipping and payment methods are correct
// Get all fields
$shippingName = $_REQUEST['shippingName'];
$deliveryMethod = $_REQUEST['deliveryMethod'];
$shippingAddress = $_REQUEST['shippingAddress'];
$shippingPostCode = $_REQUEST['shippingPostCode'];
$cardType = $_REQUEST['cardType'];
$cardNumber = $_REQUEST['cardNumber'];
$cardExpiryDate = $_REQUEST['cardExpiryDate'];
$cardExpiryYear = $_REQUEST['cardExpiryYear'];
$cardSecurityCode = $_REQUEST['cardSecurityCode'];
// Make the checks
if (!isset($shippingName))
{
$message[] = "Please enter the name of the person whom the order is addressed to.";
}

if (!preg_match("/[a-zA-Z][a-zA-Z]+/", $shippingName))
{
$message[] = "Your shipping name should contain only letters a-z and no other characters.";
}

if (!isset($deliveryMethod))
{
$message[] = "Please choose a delivery method";
}

if (!isset($shippingAddress))
{
$message[] = "Please enter a shipping address for this order";
}

if (!isset($shippingPostCode))
{
$message[] = "Please enter a shipping post code in the UK format (XXYYZZ).";
}

if (!isset($cardType))
{
$message[] = "Please choose your card type.";
}

if (!isset($cardNumber))
{
$message[] = "Please enter a card number. It should be 16 exactly 16 digits long, without spaces or other charaacters.";
}

if (!preg_match("/[0-9]{16}/", $cardNumber))
{
$message[] = "Please enter a card number. It should be exactly 16 digits long, without spaces or other characters.";
}

if (!isset($cardExpiryDate))
{
$message[] = "Please enter the expiry date of your card in the format MM.";
}

if (!preg_match("/[0-9]{2}/", $cardExpiryDate))
{
$message[] = "Your expiry date is invalid. Please enter the expiry date of your card in the format MM.";
}

if (!isset($cardExpiryYear))
{
$message[] = "Please enter the expiry year of your card in the format YYYY.";
}

if ($cardExpiryYear <= 2012)
{
$message[] = "Your expiry year cannot be in the past. Please enter the expiry year of your card in the format YYYY.";
}

if (!isset($cardSecurityCode))
{
$message[] = "Please enter the 3 digit security code from the back of your card. On American Express, it's a 4 digit code.";
}

if (strlen($cardSecurityCode) >> 4)
{
$message[] = "Please enter the 3 digit security code from the back of your card. On American Express, it's a 4 digit code.";
}

// Get the userID from the database in order to be able to assign the current order to the correct user
$sql = sprintf("SELECT userID FROM {$db_Prefix}users WHERE username = '%s';",
$_SESSION['userName']);
// Execute the query
$res = query($sql);
if ($res)
{
$userID = $res[0]['userID'];
}
// Set the auto_increment before inserting the new order
$setAutoIncrement = vQuery("ALTER TABLE {$db_Prefix}orders AUTO_INCREMENT = 1");
// Insert the order into the database
if (empty($message))
{
$sql = sprintf("INSERT INTO {$db_Prefix}orders (orderDate, orderAmount, userID, shippingMethod, shippingName, shippingAddress, shippingPostCode, orderStatus) VALUES (NOW(), %d, %d, '%s', '%s', '%s', '%s', 'paid');",
$_SESSION['totalPrice'],
$userID,
$deliveryMethod,
mysql_real_escape_string($shippingName),
mysql_real_escape_string($shippingAddress),
mysql_real_escape_string($shippingPostCode));
// Execute the query
$insert = query($sql);
// If something went wrong when inserting the query, create a message
if (!empty($insert))
{
	$message[] = "There was a problem in processing your order. Please contact us for more help. Thank you for your cooperation.";
}
// Insert all products for that order
$lastInsertID = $_SESSION['lastInsertID'];
foreach ($_SESSION[cart] as $item => $quantity)
{
$sql = "INSERT INTO {$db_Prefix}products2orders VALUES ($item, $quantity, $lastInsertID)";
// Execute the query
$insert2 = vQuery($sql);
// Update the quantity in the products table
$sql = "UPDATE {$db_Prefix}products SET productQuantity = productQuantity - $quantity WHERE productID = $item";
// Execute the query
$insert3 = vQuery($sql);
}
}

// If no messages were found after all checks, process the form, update the database and place the order
if (empty($message))
{
$message[] = "Order processed. Thank you for your order!";
// Unset all sessions and redirect the user back to the home page
$_SESSION[cart] = array();
header("Refresh:10; url=index.php");
}
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
<h1><?php echo SITE_TITLE; ?> - Check-out</h1>
<?php include_once('includes/message.inc.php'); ?>
<p>You are about to make a payment for the following products:</p>
<h2>Ordered Products:</h2>
<table>
<tbody>
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
?>
<tr>
<td><?php echo $productName; ?></td>
<td><?php echo $quantity; ?></td>
<td>&pound;<?php echo $productPrice; ?></td>
<td>&pound;<?php echo ($quantity * $productPrice); ?></td>
</tr>
<?php } } } ?>
<tr>
<td>Total cost for all items:</td>
<td>&pound;<?php echo $_SESSION['totalPrice']; ?></td>
</tr>
</table>
<p>
<h2>Shipping Details:</h2>
<p>Please enter your shipping details in order to place your order.</p>
<form name="form1" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
<label for="shippingName">Shipped to (Person Name):</label>
<input type="text" name="shippingName" value="<?php if (isset($shippingName)) { echo $shippingName; } ?>" />
<br/>
<label for="deliveryMethod">Delivery Method:</label>
<br/>Note that for a limited time only, all delivery methods are free.
<br/>
<select name="deliveryMethod">
<option name="deliveryMethod">--- Choose one ---</option>
<option name="deliveryMethod">Royal Mail Standard Delivery</option>
<option name="deliveryMethod">Royal Mail Next Day Delivery</option>
<option name="deliveryMethod">Royal Mail Special Signed For Delivery</option>
</select>
<br/>
<label for="shippingAddress">Shipping Address:</label>
<textarea name="shippingAddress" rows="5" cols="40"><?php if (isset($shippingAddress)) { echo $shippingAddress; } ?></textarea>
<br/>
<label for="shippingPostCode">Post code:</label>
<input type="text" size="6" name="shippingPostCode" value="<?php if (isset($shippingPostCode)) { echo $shippingPostCode; } ?>" />
<br/>
<h3>Payment Method</h3>
<p>Please enter your cart information in the boxes below:</p>
<label for="cardType">Card type:</label>
<input type="radio" name="cardType" value="Visa">Visa Credit/Debit
<input type="radio" name="cardType" value="Mastercard">Master Card
<input type="radio" name="cardType" value="amex">American Express
<input type="radio" name="cardType" value="Eurocard">Eurocard
<br/>
<label for="cardNumber">Card Number:</label>
<input type="text" name="cardNumber" size="16" value="<?php if (isset($cardNumber)) { echo $cardNumber; } ?>" />
<br/>
<label for="cardExpiryDate">Expiry Date:</label>
<input type="text" name="cardExpiryDate" size="2" value="mm" />
<br/>
<label for="cardExpiryYear">Expiry Year:</label>
<input type="text" name="cardExpiryYear" size="4" value="yyyy" />
<br/>
<label for="cardSecurityCode">Card Security Code:</label>
<input type="text" name="cardSecurityCode" size="4" value="<?php if (isset($cardSecurityCode)) { echo $cardSecurityCode; } ?>" />
<br/>
<input type="submit" name="placeOrder" value="Place my order" />
<input type="reset" name="clear" value="Clear" />
<br/>
</form>
</p>
</div>
<div id="footer">
<?php include_once('footer.php'); ?>
</div>

</div>
</body>
</html>

<?php } else
{
echo "Check out cannot be initiated. Cart is empty.";
}
?>