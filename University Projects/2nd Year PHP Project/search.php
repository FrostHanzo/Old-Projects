<?php
/**
* Search file that contains the logic for searching through the database for product's details
*
* @author Georgi Zhivankin <georgijivankin@gmail.com>
* @version 1.0
* @since 0.1
*/
// Load the configuration file
// Include the main configuration file
include_once('config.php');
// Initialise an error array
$message = array();
// Perform the search
if (array_key_exists('search', $_POST))
{
// Get the search query from the URL of the file
$searchKeywords = $_REQUEST['searchQuery'];
$sql = "SELECT * FROM {$db_Prefix}products WHERE productName like '%$searchKeywords%'";
// Execute the query
$res = query($sql);
if (!$res)
{
	$message[] = "No results were found.";
}
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo SITE_TITLE; ?> - Search</title>
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
<h1><?php echo SITE_TITLE; ?> - Search</h1>
<?php include_once('includes/message.inc.php'); ?>
<p>
<form name="searchform" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
<label for="searchQuery">Search for:</label>
<input type="text" name="searchQuery" value="<?php if (isset($searchKeywords)) { echo $searchKeywords; } ?>" />
<br/>
<input type="submit" name="search" value="search" />
</form>

<?php
// Check if any results are being found
if (array_key_exists('search', $_POST) && (!$res))
{
?>
<p>No results were found for your search: <?php echo $_REQUEST['searchQuery']; ?>.
<br/>To increase your chances of finding what you are looking for, please do one of the following:
<ul>
<li>refine your keywords</li>
<li>choose alternative words</li>
<li>try with a different query.</li>
</ul>
</p>
<?php } elseif (!isset($res))
{
	?>
<p>Please type in a term to search for.</p>
<?php } else { ?>
<p>The following <?php echo count($res); ?> products were found:</p>
<p>
<table>
<tr>
<th>Product name</th>
<th>Price:</th>
<th>Availability:</th>
</tr>
<?php
foreach ($res as $row)
{
?>
<tbody>
<tr>
<td><a href="product.php?productID=<?php echo $row['productID']; ?>"><?php echo $row['productName']; ?></a></td>
<td>&pound;<?php echo $row['productPrice']; ?></td>
<td><?php echo $row['productQuantity']; ?></td>
</tr>
</tbody>
<?php
}
?>
</table>
</p>
<?php } ?>
</div>
<div id="footer">
<?php include_once('footer.php'); ?>
<div class="clear"></div>
</div>

</div>
</body>
</html>
