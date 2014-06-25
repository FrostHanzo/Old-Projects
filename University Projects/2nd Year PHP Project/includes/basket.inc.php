<?php
/**
* This file contains the shopping basket functions
* The functions define the logic of the shopping cart and are being called by the main shopping cart file
*
* @author Georgi Zhivankin <georgijivankin@gmail.com>
* @version 1.0
* @since 0.1
*/

/**
* A function to display how many items are in the cart
*/
function displayNumberOfItems()
{
// Initialise a session that would hold the shopping cart. Cookies could have been used, but storing the cart into a session is better as even // the customer's browser does not have cookies enabled, PHP will still track them accurately.
$cart = $_SESSION['cart'];
// Checks if the cart is empty and displays the appropriate message
if (!$cart)
{
return '<p>Your shopping cart is empty.</p>';
}
else
{
// Prepare the items and display them by parcing the $cart array using the explode function into different items
$items = explode(',', $cart);
// Display the plurals correctly, I.E. 1 product instead of 1 products
// Count the items from the array
$itemsCount = count($items);
// Check if the items are more than one and display the letter s, otherwise, don't display anything
if ($itemsCount > 1)
{
$plural = 's';
}
else 
{
}
// Return the message and a link to the cart page
return "<p>You have $itemsCount product$plural in your shopping cart.</p>";
}
}

/**
* A function that shows the whole basket
*/
function showCart()
{
// Get the cart's contents into a variable
$cart = $_SESSION['cart'];
// Check if the cart contains any items and explode them into an array
if (isset($cart))
{
$items = explode(',', $cart);
// Prepare an array of all items
$contents = array();
// Loop through eatch item and add it into the $contents array
		foreach ($items as $item)
{
// Check if there is an item and add one more into the contents array, otherwise, add just one item
if ($contents[$item] = (isset($contents[$item])))
{
$contents[$item] + 1;
}
else
{
$contents[$item] = 1;
}
		}
// Output the items onto a page
$output[] = '<form action="cart.php?action=update" method="post" id="cart">';
		$output[] = '<table>';
		foreach ($contents as $productID => $productQuantity)
{
			$sql = "SELECT * FROM in3008_products WHERE productID = $productID";
$res = query($sql);
if ($res)
{
// Extract all information about a product into variables
extract($res[0]);
}
			$output[] = '<tr>';
			$output[] = '<td><a href="cart.php?action=delete&productID='.$productID.'">Remove</a></td>';
			$output[] = '<td>'.$productName.'</td>';
			$output[] = '<td>&pound;'.$productPrice.'</td>';
			$output[] = '<td><input type="text" name="quantity'.$productID.'" value="'.$productQuantity.'" size="3" maxlength="3" /></td>';
			$output[] = '<td>&pound;'.($productPrice * $productQuantity).'</td>';
			$total += $productPrice * $productQuantity;
			$output[] = '</tr>';
		}
		$output[] = '</table>';
		$output[] = '<p>Grand total: <strong>&pound;'.$total.'</strong></p>';
		$output[] = '<div><button type="submit">Update cart</button></div>';
		$output[] = '</form>';
	} else {
		$output[] = '<p>Your cart is empty.</p>';
	}
	return join('',$output);
}
echo showCart();

?>