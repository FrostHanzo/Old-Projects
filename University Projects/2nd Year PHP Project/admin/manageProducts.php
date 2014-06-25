<?php
/**
* ManageProducts file
*
* This file contains all functions related to managing products within the e-commerce website
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
* Code for action uploadImage
*/
case 'uploadImage':

// Initialise error array that holds error messages
$message = array();
// Check if the product ID is being specified
$productID = $_REQUEST['productID'];
if (isset($productID))
{
// The whole code below is executed only if this variable has been set

/**
* Delete Images Code
*/
// process the form if a deleted link has been clicked
if ($_REQUEST['delete'])
{
// Get the image name with the directory name where the image is contained
$imageName = $_REQUEST['imageName'];
// get the name of the file that should be deleted by stripping off the directory path of the previous variable
$uploadDir = dirname($_REQUEST['delete']);
$file = basename($_REQUEST['delete']);
// open the user's folder and delete the file
$delete = $uploadDir.'/'.$file;
// find the file and delete it
if (file_exists($delete))
{
// delete it
$deleted = unlink($delete);
// Update the database
$sql = "DELETE FROM {$db_Prefix}products2images WHERE productID = $productID and imageName = '$imageName'";
// Execute the query
$update = vQuery($sql);
// display a message
if ($deleted = true)
{
$message[] = 'The picture you chose to remove was deleted successfully';
}
else
{
	$message[] = 'There was a problem deleting this picture';
}
}
}

/**
* Code for Uploading Images
*/
// Check if the uploads folder contains a subfolder with the current date as a name
$date = date('Y-m-d');
if (file_exists(UPLOAD_DIR.$date))
{
// set the path of the uploads directory to the existing directory
$uploadsDir = UPLOAD_DIR.$date;
}
else
{
	// try to create the directory with 0775 as permissions CHMOD (not valid on Windows)
$uploadsDir = mkdir(UPLOAD_DIR.$date, 0775);
}
// check if the image form has been submitted
if (array_key_exists('upload', $_POST))
{
// Check also if the images directory variable contains a path, just in case.
	if ($uploadsDir)
{
/**
* make a few security checks for the uploaded image
* Check if the file is equal or less than the permitted file size
* Check if the type of the file is indeed one of the predefined image types
* sanitize the name of the file by removing the spaces off the filename
*/
  $file = str_replace(' ', '_', $_FILES['image']['name']);
  // convert the maximum size to kilobites
  $max = number_format(MAX_FILE_SIZE/1024, 1).'KB';
// Create an array of permitted MIME types. The permitted mime types are JPG, JPEG, PJPEG, GIF, PNG
$permittedMimeTypes = array('image/gif', 'image/jpeg', 'image/pjpeg', 'image/png', 'image/x-png');
// Begin by assuming that the file is unacceptable
$acceptableSize = false;
$acceptableType = false;
// Check that the file is within the permitted size
if ($_FILES['image']['size'] > 0 && $_FILES['image']['size'] <= MAX_FILE_SIZE)
{
// Set the variable acceptableSize to true as the size is verified
$acceptableSize = true;
	}
// Check that the file is of a permitted image type.
// Loop through each of the types within the array and check the file against the mime type listed within the array.
foreach ($permittedMimeTypes as $type)
{
if ($type == $_FILES['image']['type'])
{
// If the type matches, set the acceptableType variable to true
$acceptableType = true;
// End the loop.
break;
	  }
	}
// If both the acceptable size and the acceptable type are true, continue with uploading the image.
if ($acceptableSize && $acceptableType)
{
/**
* The below code is a complex switch type which checks the error type of the returned $_FILES array, against the switch and performs an action based on the error code of the returned error.
*/
switch($_FILES['image']['error'])
{
case 0:
// Check if a file of the same name has been already uploaded.
if (!file_exists($uploadsDir.'/'.$file))
{
// move the file to the upload folder and rename it
$success = move_uploaded_file($_FILES['image']['tmp_name'], $uploadsDir.'/'.$file);
// see which is the biggest ID in the table and set the AUTO_INCREMENT accordingly. This is done to prevent future products from being indexed badly if in the meantime old images were being removed off the database
$largestID = vQuery("ALTER TABLE {$db_Prefix}products2images AUTO_INCREMENT = 1");
// Write the information about the uploaded file within the database
$sql = sprintf("INSERT INTO {$db_Prefix}products2images VALUES ('%d', '%s');",
$productID, $date.'/'.$file);
// Execute the query
$insert = vQuery($sql);
}
else
{
// Move the new file into the directory, but append the current date and time to its name to differentiate the files.
$success = move_uploaded_file($_FILES['image']['tmp_name'], $uploadsDir.'/'.$date.$file);
// Write the information about the uploaded file within the database
$sql = sprintf("INSERT INTO {$db_Prefix}products2images VALUES ('%d', '%s');",
$productID, $date.'/'.$date.$file);
// Execute the query
$insert = vQuery($sql);
		  }
// Check if the upload succeeded and display a success message.
if ($success)
{
$message[] = "The image: $file uploaded successfully";
	      }
		else {
$message[] = "Error uploading image: $file. Please try again.";
		  }
	    break;
	  case 3:
$message[] = "Error uploading image: $file. Please try again.";
	  default:
$message[] = "A technical error has occured while uploading image: $file. Contact the webmaster for assistance.";
	  }
    }
  elseif ($_FILES['image']['error'] == 4) {
$message[] = "No file selected to upload. Please choose a file from your local hard drive to continue.";
	}
  else {
$message[] = "The image: $file cannot be uploaded. The permitted maximum size of the images is: $max. The acceptable image types to upload are: GIF, JPG and PNG. Please make sure that the image you are trying to upload, conforms to these requirements and try again.";
	}
  }
	}
}
else
{
die('Invalid product ID. Please go back and try again of if this problem persists, contact the administrator of this website and report the error.');
exit;
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo SITE_TITLE; ?> - Upload a Product Image</title>
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

<?php include_once('../includes/message.inc.php'); ?>
<p>Here you can upload images that you can subsequently use to display on your product pages.</p>
<div id="showImage">
<p>
<?php
// Fetch the data of the images from the database
$sql = "SELECT * FROM {$db_Prefix}products2images WHERE productID = $productID";
// Execute the query
$select = query($sql);
// Check if anything is being retrieved
if ($select)
{
// Loop through the array of images
foreach ($select as $image)
{
// Get the name of the image
$imageName = $image['imageName'];
	// Construct the images URL and display them
	$imageURL = UPLOAD_DIR.$image['imageName'];
// Show the images
?>
<img src="https://lamp2010.soi.city.ac.uk/~abdr910/productImages/<?php echo $image['imageName']; ?>" alt="Product Picture" width="250" height="250" />
<a href="<?php echo $_SERVER['PHP_SELF']; ?>?action=uploadImage&productID=<?php echo $productID; ?>&delete=<?php echo $imageURL; ?>&imageName=<?php echo $imageName; ?>">Delete</a>
<?php
}
}
?>
</p></div>
<p>
<form action="<?php echo $_SERVER['PHP_SELF']; ?>?action=uploadImage&productID=<?php echo $productID; ?>" method="post" enctype="multipart/form-data" name="uploadImage" id="uploadImage">
    <p>
		<label for="image">Upload a Product Image:</label>
		<input type="hidden" name="MAX_FILE_SIZE" value="<?php echo MAX_FILE_SIZE; ?>" />
        <input type="file" name="image" id="image" /> 
    </p>
    <p>
        <input type="submit" name="upload" id="upload" value="Upload" />
    </p>
</form>
</p>
</div>
<div id="footer">
<?php include_once('../footer.php'); ?>
</div>

</div>

</body>
</html>

<?php break;

/**
* Code for action add
*/
case 'add':
// check if the add button has been clicked and if so, process the form
if (array_key_exists('add', $_POST))
{
$db_Prefix = $GLOBALS['db_Prefix'];
// get the product's name, product's description, quantity, etc, from the input sent by the user
$productName = trim($_POST['productName']);
$productDescription = trim($_POST['productDescription']);
$productPrice = trim($_POST['productPrice']);
$productQuantity = trim($_POST['productQuantity']);
$productCategory = trim($_POST['category']);
// Initialise error array that holds error messages
$message = array();
// if the message array is empty, go and process the form
	if (!$message)
{
// otherwise, it's ok to insert the details in the database
// see which is the biggest ID in the table and set the AUTO_INCREMENT accordingly. This is done to prevent future products from being indexed badly if in the meantime old products were being removed off the database
$largestID = vQuery("ALTER TABLE {$db_Prefix}products AUTO_INCREMENT = 1");
// insert the product's details in the database
$sql = sprintf("INSERT INTO {$db_Prefix}products (productName, productDescription, productPrice, productQuantity) VALUES ('%s', '%s', '%s', '%s');",
mysql_real_escape_string(trim($productName)),
mysql_real_escape_string(trim($productDescription)),
trim($productPrice),
trim($productQuantity));
$insert = iQuery($sql);
// Show a message if the insert query failed for some reason.
if (!($insert))
{
// generate a failure message
$message[] = "There was a problem adding the product $productName into the system. Please try again.";
} else
{
// generate a success message
$message[] = "The product $productName was added successfully to the system.";
// Get the lastInsertID from the session
$lastInsertID = $_SESSION['lastInsertID'];
if ($lastInsertID)
{
	// Insert the data about this product into the products2categories database
$sql = "INSERT INTO {$db_Prefix}products2categories (productID, categoryID) VALUES ($lastInsertID, $productCategory)";
// Execute the query
$insert2 = vQuery($sql);
}
}
}
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo SITE_TITLE; ?> - Add a New Product</title>
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
<h1><?php echo SITE_TITLE; ?> - Add a New Product</h1>
<?php include_once('../includes/message.inc.php'); ?>
<p>Here you can add a product into the catalogue.</p>
<p>
<form action="<?php echo $_SERVER['PHP_SELF']; ?>?action=add" method="post" name="form1">
<label>Product Name:</label>
<input name="productName" type="text" value="<?php if (isset($productName)) { echo $productName; } ?>" />
<br/>
<label>Product Description:</label>
<textarea name="productDescription" rows="5" cols="40"><?php if (isset($productDescription)) { echo $productDescription; } ?></textarea>
<br/>
<label>Product Price:</label>
<input type="text" name="productPrice" value="<?php if (isset($productPrice)) { echo $productPrice; } ?>" />
<br/>
<label>Product Quantity:</label>
<input name="productQuantity" type="text" value="<?php if (isset($productQuantity)) { echo $productQuantity; } ?>" />
<br/>
<label name="category">Product category:</label>
<?php
// Display all categories from the database
$sql = "SELECT * FROM {$db_Prefix}categories";
// Execute the query
$res = query($sql);
?>
<select name="category">
<option name="category" value="0">---</option>
<?php
foreach ($res as $category)
{
?>
<option name="category" value="<?php echo $category['categoryID']; ?>"><?php echo $category['categoryName']; ?></option>
<?php } ?>
</select>
<br/>
<?php if ($lastInsertID)
{
?>
<a href="<?php echo $_SERVER['PHP_SELF']; ?>?action=uploadImage&productID=<?php echo $lastInsertID; ?>" target="_BLANK">Upload Images</a>
<?php } ?>
<br/>
<input name="add" type="submit" value="Add this product" />
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
* Code for Modify product case
*/
// Initialise error array that holds error messages
$message = array();
// get the product ID of the product that the script needs to modify from the URL
$productID = $_REQUEST['productID'];
// check if the $productID is set
if (!isset($productID))
{
// Display a message that the product ID is invalid.
$message[] = 'Invalid product ID. Please go back and try again.';
// Terminate the subsequent code altogether and exit the case.
exit;
}
// check if the modify button has been clicked and if so, process the form
// get the global variable containing the database table prefix
$db_Prefix = $GLOBALS['db_Prefix'];
// get the product's details from the database
$sql = sprintf("SELECT {$db_Prefix}products.productID as productID, productName, productDescription, productPrice, productQuantity, categoryID as productCategory FROM {$db_Prefix}products, {$db_Prefix}products2categories WHERE {$db_Prefix}products.productID = {$db_Prefix}products2categories.productID AND {$db_Prefix}products.productID = %s;",
$productID);
$select = query($sql);
// check if anything is being found
if ($select)
{
foreach ($select as $res)
{
// assign the values into variables
$productID = $res['productID'];
$productName = $res['productName'];
$productDescription = $res['productDescription'];
$productPrice = $res['productPrice'];
$productQuantity = $res['productQuantity'];
$productCategory = $res['productCategory'];
}
}
// now check if the Modify Product button has been clicked and if so, go and modify the product
if (array_key_exists('modify', $_POST))
{
// get the new product name, product description and product quantity fields from the input sent by the user
$newProductName = trim($_POST['productName']);
$newProductDescription = trim($_POST['productDescription']);
$newProductCategory = trim($_POST['category']);
$newProductPrice = trim($_POST['productPrice']);
$newProductQuantity = trim($_POST['productQuantity']);
// Check if product name is provided. It shouldn't be empty.
if (!isset($newProductName))
{
$message[] = 'The product name should not be empty.';
}
// Check if product description is provided. It shouldn't be empty.
if (!isset($newProductDescription))
{
$message[] = 'The product description should not be empty.';
}
// Validate the product quantity using a regular expression. It should be an intiger.
$regexpQuantity = "/^([0-9\-._])+$/";
if (!preg_match($regexpQuantity, $newProductQuantity))
{
	$message[] = "The quantity you entered is not valid, please provide quantity in digits only.";
}
// check for duplicate product name different than the old one
if ($newProductName != $productName)
{
$checkDuplicateProductName = query("SELECT * FROM {$db_Prefix}products WHERE productName = '$newProductName'");
// if the array returns a result
if ($checkDuplicateProductName)
{
// display a message 
$message[] = "The product name: $productName, you have chosen is already registered in the system. Please choose another one";
}
}
// If the message array is empty, go and process the form.
	if (!$message)
{
// Otherwise, it's ok to insert the details in the database.
// See which is the biggest ID in the table and set the AUTO_INCREMENT accordingly.
$largestID = vQuery("ALTER TABLE {$db_Prefix}products AUTO_INCREMENT = 1");
// Insert the modified product details in the database
$sql = sprintf("UPDATE {$db_Prefix}products SET productName = '%s', productDescription  = '%s', productPrice = '%s', productQuantity = '%s' WHERE productID = %d;",
mysql_real_escape_string($newProductName),
mysql_real_escape_string($newProductDescription),
mysql_real_escape_string($newProductPrice),
mysql_real_escape_string($newProductQuantity),
mysql_real_escape_string($productID));
$update = vQuery($sql);
if (!empty($update))
{
// generate a failure message
$message[] = "There was a problem modifying the product: $newProductName. Please try again";
} else
{
// generate a success message
$message[] = "The product information for product: $newProductName was modified successfully.";
	// Insert the data about this product into the products2categories database
$sql = "UPDATE {$db_Prefix}products2categories SET categoryID = $newProductCategory WHERE productID = $productID";
// Execute the query
$update2 = query($sql);
// Redirect the user back to the management screen
header("Refresh: 3; url=manageProducts.php");
}
}
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo SITE_TITLE; ?> - Modify a Product</title>
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
<h1><?php echo SITE_TITLE; ?> - Modify a Product</h1>
<?php include_once('../includes/message.inc.php'); ?>
<p>Modifying a product is easy. Just change the information that you would like to update and press on modify.</p>
<p>
<form action="<?php echo $_SERVER['PHP_SELF']; ?>?action=modify&productID=<?php echo $productID; ?>" method="post" name="modify">
<label>Product Name:</label>
<input name="productName" type="text" value = "<?php if (isset($productName)) { echo $productName; } ?>" />
<br/>
<label>Product Description:</label>
<textarea name="productDescription" rows="5" cols="40"><?php if (isset($productDescription)) { echo $productDescription; } ?></textarea>
<br/>
<label>Product Price:</label>
<input name="productPrice" type="text" value="<?php if (isset($productPrice)) { echo $productPrice; } ?>" />
<br/>
<label>Product Quantity:</label>
<input name="productQuantity" type="text" value="<?php if (isset($productQuantity)) { echo $productQuantity; } ?>" />
<br/>
<label name="category">Product category:</label>
<?php
// Display all categories from the database
$sql = "SELECT * FROM {$db_Prefix}categories";
// Execute the query
$res = query($sql);
?>
<select name="category">
<option name="category" value="0">---</option>
<?php
foreach ($res as $category)
{
?>
<option name="category" <?php if ($productCategory == $category['categoryID']) { echo "selected='selected'"; } ?> value="<?php echo $category['categoryID']; ?>"><?php echo $category['categoryName']; ?></option>
<?php } ?>
</select>
<br/>
<a href="<?php echo $_SERVER['PHP_SELF']; ?>?action=uploadImage&productID=<?php echo $productID; ?>">Upload Images</a>
<br/>
<input name="modify" type="submit" value="modify product information" />
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
* Code for delete product case
*/
// Initialise error array that holds error messages
$message = array();
// get the global database prefix table variable
$db_Prefix = $GLOBALS['db_Prefix'];
// get the product ID of the product the script needs to modify from the URL
$productID = $_GET['productID'];
// check if the $productID is set
if (!isset($productID))
{
$message[] = 'Invalid product ID. Please go back and try again';
exit;
}
// check if the product exists in the database prior to deleting it
// Prepare the query.
$select = sprintf("SELECT productName FROM {$db_Prefix}products where productID = %d;",
$productID);
// Execute the Query
$sql = query($select);
foreach ($sql as $product)
{
// Get the name of the product if found.
$productName = $product['productName'];
}
if (!$productName)
{
	$message[] = "The product you are trying to remove does not exist or has been deleted already.";
}
// Check if the Delete Product button has been clicked and if so, go and modify the user
if (array_key_exists('delete', $_POST))
{
// if the message array is empty, go and process the form
	if (!$message)
{
// see which is the biggest ID in the table and set the AUTO_INCREMENT accordingly
$largestID = vQuery("ALTER TABLE {$db_Prefix}products AUTO_INCREMENT = 1");
// Delete the details from the database
$delete = sprintf("DELETE FROM {$db_Prefix}products WHERE productID = %d;",
$productID);
// Execute the query.
$sql = vQuery($delete);
// Delete the info about the corresponding images as well
$largestID2 = vQuery("ALTER TABLE {$db_Prefix}products2images AUTO_INCREMENT = 1");
$delete2 = sprintf("DELETE FROM {$db_Prefix}products2images WHERE productID = %d;",
$productID);
// Execute the query.
$sql2 = vQuery($delete2);
if (!empty($sql))
{
// generate a failure message
$message[] = "There was a problem deleting the product: $productName. Please try again";
} else
{
// generate a success message
$message[] = "The product: $productName was deleted successfully.";
}
// Unset the product name
unset($productName);
}
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo SITE_TITLE; ?> - Delete a Product</title>
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
<h1><?php echo SITE_TITLE; ?> - Delete a Product</h1>
<?php include_once('../includes/message.inc.php'); ?>
<p>
<?php if (isset($productName))
{
	printf("<p>Please press the 'Delete' button to remove: %s from the database completely.</p>",
$productName);
}
?>
<p>
<form action="<?php echo $_SERVER['PHP_SELF']; ?>?action=delete&productID=<?php echo $productID; ?>" method="post">
<?php if (isset($productName)) { ?>
<input name="delete" type="submit" value="Delete this product" />
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
// browse through the products table looking for products
$sql = query("SELECT * FROM {$db_Prefix}products");
// check if the array contains any products.
if (!$sql)
{
// create an error message that will be shown to the user
$message[] = 'No products were found in the database';
}
// count the number of retrieved rows
$productCount = count($sql);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo SITE_TITLE; ?> - Product Management</title>
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
<h1><?php echo SITE_TITLE; ?> - Product Management</h1>
<?php include_once('../includes/message.inc.php'); ?>
<p>This is the product management section where you can add, modify and delete products off the system as well as view details for every product.</p>
<p><a href="manageProducts.php?action=add">Add a new Product</a></p>
<p>
<table>
<tr>
<th>Product Name:</th>
<th>Description:</th>
<th>Quantity:</th>
</tr>
<?php foreach ($sql as $row) { ?>
<tr>
<th><?php echo $row['productName']; ?></th>
<th><?php echo $row['productDescription']; ?></th>
<th><?php echo $row['productQuantity']; ?></th>
<th><a href="<?php echo $_SERVER['PHP_SELF']; ?>?action=modify&amp;productID=<?php echo $row['productID']; ?>">Modify Details</a></th>
<th><a href="<?php echo $_SERVER['PHP_SELF']; ?>?action=delete&amp;productID=<?php echo $row['productID']; ?>">Delete</a></th>
</tr>
<?php } ?>
</table>
<p>There are a total of <?php echo $productCount; ?> products in the catalogue.</p>
</div>
<div id="footer">
<?php include_once('../footer.php'); ?>
</div>

</div>
</body>
</html>

<?php break; } ?>