<?php
/**
* category file
*
* This file displays all products within a given category
* The events specified within this file are triggered by a values sppecified as path of the file's URL and are being obtained from the default $_GET array
*
* @author Georgi Zhivankin <georgijivankin@gmail.com>
* @version 1.0
* @since 0.1
*/
// Load the configuration file
require_once('config.php');
// Initialise an error array
$message = array();
// Get the ID of the category
$categoryID = $_REQUEST['categoryID'];
// Display all information for the products contained within the category selected
$sql = "SELECT * FROM {$db_Prefix}products, {$db_Prefix}products2categories, {$db_Prefix}categories WHERE {$db_Prefix}categories.categoryID = {$db_Prefix}products2categories.categoryID AND {$db_Prefix}products.productID = {$db_Prefix}products2categories.productID AND {$db_Prefix}categories.categoryID = $categoryID";
// Execute the query
$res = query($sql);
// Check if something is retrieved
if (!$res)
{
$message[] = "No products were found in the chosen category.";
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo SITE_TITLE; ?> - Product List</title>
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
<br/>
<?php include_once('includes/message.inc.php'); ?>
<?php if ($res) { ?>
<p>Products in category: <a href="categories.php"><?php echo $res[0]['categoryName']; ?></a></p>
<table>
<tbody>
<tr>
<th>Product Name:</th>
<th>Price:</th>
<th>Availability:</th>
</tr>
<?php
foreach ($res as $product)
{
	?>
<tr>
<td><a href="product.php?productID=<?php echo $product['productID']; ?>"><?php echo $product['productName']; ?></a></td>
<td>&pound;<?php echo $product['productPrice']; ?></td>
<td><?php if ($product['productQuantity'] >> 0) { echo "In stock"; } else { echo "Out of stock"; } ?></td>
</tr>
<?php } ?>
</tbody>
</table>
<p>There are <?php echo count($res); ?> products in this category</p>
<?php } ?>
</div>
<div id="footer">
<?php include_once('footer.php'); ?>
</div>

</div>
</body>
</html>
