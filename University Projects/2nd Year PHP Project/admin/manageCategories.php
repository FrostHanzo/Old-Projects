<?php
/**
* ManageCategories file
*
* This file contains all functions related to managing categories within the e-commerce website
* The events specified within this file are triggered by a values sppecified as path of the file's URL and are being obtained from the default $_GET array
* After the values are obtained, the script check if a matching case is found within the switch loop and runs the corresponding code, if not, the default case is run which triggers an error and a log file with the attempted action is created
*
* @author Georgi Zhivankin <georgijivankin@gmail.com>
* @version 1.0
* @since 0.1
*/
// Load the configuration file
require_once('../config.php');
// Include the restrict file
include_once('../includes/restrict.inc.admin.php');
// Include the restrict file that restricts access to administrators only
include_once('restrict.admin.php');
// Check the $_GET array for an action value and assign it into a variable
if (isset($_GET['action']))
{
	$action = $_GET['action'];
} else {
	$action = 'none';
}
/**
* start the huge switch case loop that would iterrate through the available actions and based on the specified action would perform the task * requested
*/
switch($action)
{

/**
* Code for action add
*/
case 'add':
// check if the add button has been clicked and if so, process the form
if (array_key_exists('add', $_POST))
{
$db_Prefix = $GLOBALS['db_Prefix'];
// get the category's name, description and parent from the input sent by the user
$categoryName = trim($_POST['categoryName']);
$categoryDescription = trim($_POST['categoryDescription']);
$categoryParent = trim($_POST['categoryParent']);
// Initialise error array that holds error messages
$message = array();
// if the message array is empty, go and process the form
	if (!$message)
{
// otherwise, it's ok to insert the details in the database
// see which is the biggest ID in the table and set the AUTO_INCREMENT accordingly. This is done to prevent future categories from being indexed badly if in the meantime old categories were being removed off the database
$largestID = vQuery("ALTER TABLE {$db_Prefix}categories AUTO_INCREMENT = 1");
// insert the category's details in the database
$sql = sprintf("INSERT INTO {$db_Prefix}categories (categoryName, categoryDescription, categoryParent) VALUES ('%s', '%s', '%s');",
trim($categoryName),
trim($categoryDescription),
trim($categoryParent));
$insert = query($sql);
// Show a message if the insert query failed for some reason.
if (!empty($insert))
{
// generate a failure message
$message[] = "There was a problem adding the category $categoryName into the system. Please try again.";
} else
{
// generate a success message
$message[] = "The category $categoryName was added successfully to the system.";
// Redirect back to the main category management page
header("Refresh: 5; url=manageCategories.php");
}
}
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo SITE_TITLE; ?> - Add a New Category</title>
<link href="../css/style.css" rel="stylesheet" type="text/css" />
</head>

<body>
<div id="container">
<div id="header">
<?php include_once('../header.php'); ?>
</div>
<div id="nav">
<?php include_once('nav.php'); ?>
<div class="clear"></div>
</div>
<div id="content">
<h1><?php echo SITE_TITLE; ?> - Add a New category</h1>
<?php include_once('../includes/message.inc.php'); ?>
<p>Add a new category by filling in the form below.</p>
<p>
	<form action="<?php echo $_SERVER['PHP_SELF']; ?>?action=add" method="post" name="form1">
<label for="categoryName">Category Name:</label>
<input name="categoryName" type="text" value="<?php if (isset($categoryName)) { echo $categoryName; } ?>" />
<br/>
<label for="categoryDescription">Category Description:</label>
<input name="categoryDescription" type="text" value="<?php if (isset($categoryDescription)) { echo $categoryDescription; } ?>" />
<br/>
<label for="parentCategory">Parent Category:</label>
<select name="categoryParent">
<option name="categoryParent" value="">---</option>
<?php
// Get all categories from the database in order to populate an existing checkbox which will show which ones could be chosen as a parent categories
$sql = "SELECT * FROM {$db_Prefix}categories";
$res = query($sql);
if ($res)
{
foreach ($res as $category)
{
	extract ($category);
	?>
<option name="categoryParent" VALUE="<?php echo $categoryID; ?>"><?php echo $categoryName; ?></option>
<?php } } ?>
</select>
<br/>
<input name="add" type="submit" value="Add category" />
<br/>
<input type='reset' name='clear' value='Clear' />
</form>

</div>
<div id="footer">
<?php include_once('../footer.php'); ?>
</div>

</div>
</body>
</html>

<?php
break;
case 'modify':
/**
* Code for Modify category case
*/
// Initialise error array that holds error messages
$message = array();
// get the category ID of the category that the script needs to modify from the URL
$categoryID = $_REQUEST['categoryID'];
// check if the $categoryID is set
if (!isset($categoryID))
{
// Display a message that the category ID is invalid.
$message[] = 'Invalid category ID. Please go back and try again.';
// Terminate the subsequent code altogether and exit the case.
exit;
}
// check if the modify button has been clicked and if so, process the form
// get the global variable containing the database table prefix
$db_Prefix = $GLOBALS['db_Prefix'];
// get the category's details from the database
$sql = sprintf("SELECT categoryID, categoryName, categoryDescription, categoryParent FROM {$db_Prefix}categories WHERE categoryID = %s;",
$categoryID);
$select = query($sql);
// check if anything is being found
if ($select)
{
foreach ($select as $res)
{
// assign the values into variables
$categoryID = $res['categoryID'];
$categoryName = $res['categoryName'];
$categoryDescription = $res['categoryDescription'];
$categoryParent = $res['categoryParent'];
}
}
// now check if the Modify category button has been clicked and if so, go and modify the category
if (array_key_exists('modify', $_POST))
{
// get the new category name, category description and category quantity fields from the input sent by the user
$newcategoryName = trim($_POST['categoryName']);
$newcategoryDescription = trim($_POST['categoryDescription']);
$newcategoryParent = trim($_POST['categoryParent']);
// Check if category name is provided. It shouldn't be empty.
if (!isset($newcategoryName))
{
$message[] = 'The category name should not be empty.';
}
// Check if category description is provided. It shouldn't be empty.
if (!isset($newcategoryDescription))
{
$message[] = 'The category description should not be empty.';
}
// check for duplicate category name different than the old one
if ($newcategoryName != $categoryName)
{
$checkDuplicatecategoryName = query("SELECT * FROM {$db_Prefix}categories WHERE categoryName = '$newcategoryName'");
// if the array returns a result
if ($checkDuplicatecategoryName)
{
// display a message 
$message[] = "The category name: $categoryName, you have chosen is already registered in the system. Please choose another one";
}
}
// If the message array is empty, go and process the form.
	if (!$message)
{
// Otherwise, it's ok to insert the details in the database.
// See which is the biggest ID in the table and set the AUTO_INCREMENT accordingly.
$largestID = vQuery("ALTER TABLE {$db_Prefix}categories AUTO_INCREMENT = 1");
// Insert the modified category details in the database
$update = query("UPDATE {$db_Prefix}categories SET categoryName = '$newcategoryName', categoryDescription  = '$newcategoryDescription', categoryParent = '$newcategoryParent' WHERE categoryID = $categoryID");
if (!empty($update))
{
// generate a failure message
$message[] = "There was a problem modifying the category: $newcategoryName. Please try again";
} else
{
// generate a success message
$message[] = "The category information for category: $newcategoryName was modified successfully.";
// Redirect back to the main category management page
header("Refresh: 5; url=manageCategories.php");
}
}
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo SITE_TITLE; ?> - Modify a category</title>
<link href="../css/style.css" rel="stylesheet" type="text/css" />
</head>

<body>
<div id="container">
<div id="header">
<?php include_once('../header.php'); ?>
</div>
<div id="nav">
<?php include_once('nav.php'); ?>
<div class="clear"></div>
</div>
<div id="content">
<h1><?php echo SITE_TITLE; ?> - Modify a category</h1>
<?php include_once('../includes/message.inc.php'); ?>
<p>Modifying an existing category is easy. Change the information that you would like to update below and press on modify.</p>
<p>
<form action="<?php echo $_SERVER['PHP_SELF']; ?>?action=modify&categoryID=<?php echo $categoryID; ?>" method="post" name="modify">
<label>category Name:</label>
<input name="categoryName" type="text" value = "<?php if (isset($categoryName)) { echo $categoryName; } ?>" />
<br/>
<label>category Description:</label>
<input name="categoryDescription" type="text" value = "<?php if (isset($categoryDescription)) { echo $categoryDescription; } ?>" />
<br/>
<label>category Parent:</label>
<select name="categoryParent">
<option name="categoryParent" value="">---</option>
<?php
// Get all categories
$sql = "SELECT * from {$db_Prefix}categories WHERE categoryID != $categoryID";
$res = query($sql);
if ($res)
{
foreach ($res as $row)
{
?>
<option name="categoryParent"
<?php
if ($categoryParent == $row['categoryID'])
{
printf("selected='selected' value='%s'>%s", $categoryParent, $row['categoryName']);
}
else
{
printf("value='%s'>%s", $row['categoryID'], $row['categoryName']);
}
?>
</option>
<?php } } ?>
    </select>
<br/>
<input name="modify" type="submit" value="modify category information" />
<br/>
<input type='reset' name='clear' value='Clear' />
</form>

</p>
</div>
<div id="footer">
<?php include_once('../footer.php'); ?>
</div>

</body>
</html>

<?php
break;
case 'delete':
/**
* Code for delete category case
*/
// Initialise error array that holds error messages
$message = array();
// get the global database prefix table variable
$db_Prefix = $GLOBALS['db_Prefix'];
// get the category ID of the category the script needs to modify from the URL
$categoryID = $_GET['categoryID'];
// check if the $categoryID is set
if (!isset($categoryID))
{
$message[] = 'Invalid category ID. Please go back and try again';
exit;
}
// check if the category exists in the database prior to deleting it
// Prepare the query.
$sql = sprintf("SELECT categoryName FROM {$db_Prefix}categories where categoryID = %d;",
$categoryID);
// Execute the Query
$res = query($sql);
foreach ($res as $category)
{
// Get the name of the category if found.
$categoryName = $category['categoryName'];
}
if (!$categoryName)
{
	$message[] = "The category you are trying to remove does not exist or has been deleted already.";
}
// Check if the Delete category button has been clicked and if so, go and modify the user
if (array_key_exists('delete', $_POST))
{
// if the message array is empty, go and process the form
	if (!$message)
{
// see which is the biggest ID in the table and set the AUTO_INCREMENT accordingly
$largestID = vQuery("ALTER TABLE {$db_Prefix}categories AUTO_INCREMENT = 1");
// Delete the details from the database
$delete = sprintf("DELETE FROM {$db_Prefix}categories WHERE categoryID = %d;",
$categoryID);
// Execute the query.
$sql = query($delete);
if (!empty($sql))
{
// generate a failure message
$message[] = "There was a problem deleting the category: $categoryName. Please try again";
} else
{
// generate a success message
$message[] = "The category: $categoryName was deleted successfully.";
// Redirect the page back to the main category management page
header("Refresh: 5; url=manageCategories.php");
}
// Unset the category name
unset($categoryName);
}
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo SITE_TITLE; ?> - Delete a category</title>
<link href="../css/style.css" rel="stylesheet" type="text/css" />
</head>

<body>
<div id="container">
<div id="header">
<?php include_once('../header.php'); ?>
</div>
<div id="nav">
<?php include_once('nav.php'); ?>
<div class="clear"></div>
</div>
<div id="content">
<h1><?php echo SITE_TITLE; ?> - Delete a category</h1>
<?php include_once('../includes/message.inc.php'); ?>
<p>
<?php if (isset($categoryName))
{
	printf("<p>Please press the 'Delete' button to remove: %s from the database completely.</p>",
$categoryName);
}
?>
<p>
<form action="<?php echo $_SERVER['PHP_SELF']; ?>?action=delete&categoryID=<?php echo $categoryID; ?>" method="post">
<?php if (isset($categoryName)) { ?>
<input name="delete" type="submit" value="Delete this category" />
<?php } ?>
<br/>
</form>

</p>
</div>
<div id="footer">
<?php include_once('../footer.php'); ?>
</div>

</body>
</html>

<?php break;
/**
* Code for the action none, where action is not specified
*/
case 'none':

// initialise an error array holding error messages
$message = array();
// get the global database prefix table
$db_Prefix = DB_PREFIX;
// browse through the categories table looking for categories
$sql = query("SELECT * FROM {$db_Prefix}categories");
// check if the array contains any categories.
if (!$sql)
{
// create an error message that will be shown to the user
$message[] = 'No categories were found in the database';
}
// count the number of retrieved rows
$categoryCount = count($sql);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo SITE_TITLE; ?> - Category Management</title>
<link href="../css/style.css" rel="stylesheet" type="text/css" />
</head>

<body>
<div id="container">
<div id="header">
<?php include_once('../header.php'); ?>
</div>
<div id="nav">
<?php include_once('nav.php'); ?>
<div class="clear"></div>
</div>
<div id="content">
<h1><?php echo SITE_TITLE; ?> - categories Management</h1>
<p>This is the category management section where you can add, modify and delete categories off the system as well as view details for each category.</p>
<?php include_once('../includes/message.inc.php'); ?>
<p><a href="manageCategories.php?action=add">Add a new category</a></p>
<p>
<table>
<tr>
<th>category Name:</th>
<th>Description:</th>
</tr>
<?php foreach ($sql as $row) { ?>
<tr>
<th><?php echo $row['categoryName']; ?></th>
<th><?php echo $row['categoryDescription']; ?></th>
<th><a href="<?php echo $_SERVER['PHP_SELF']; ?>?action=modify&amp;categoryID=<?php echo $row['categoryID']; ?>">Modify Details</a></th>
<th><a href="<?php echo $_SERVER['PHP_SELF']; ?>?action=delete&amp;categoryID=<?php echo $row['categoryID']; ?>">Delete</a></th>
</tr>
<?php } ?>
</table>
<p>There are a total of <?php echo $categoryCount; ?> categories in the catalogue.</p>
</div>
<div id="footer">
<?php include_once('../footer.php'); ?>
</div>

</div>
</body>
</html>

<?php break; } ?>