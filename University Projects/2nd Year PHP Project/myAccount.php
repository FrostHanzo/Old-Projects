<?php
/**
* This is the my account file that shows all details registered in the database for the urrent customer
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

// Get the user's details from the database
$userID = $_SESSION['userID'];
// Query the database and get all the details
$sql = "SELECT * from {$db_Prefix}users WHERE userID = '$userID'";
// Execute the query
$res = query($sql);
// If the details are found
if (!$res)
{
$message[] = "An error has occured while trying to display your details. Please contact us for help.";
}
else
{
// Extract all found details into variables
extract ($res[0]);
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
<p>The my account section contains all of your registered details that we hold for you. Due to security reasons, if you wish to amend any of them, or to delete your account altogether, please contact us and we will be more than happy to do so.</p>
<p>
<table>
<tr>
<th>Username:</th>
<th>Gender:</th>
<th>Email:</th>
<th>Phone:</th>
<th>Level:</th>
</tr>
<tbody>
<tr>
<td><?php echo $userName; ?></td>
<td><?php if ($gender == 'm') { echo "male"; } elseif ($gender == 'f') { echo "female"; } else { echo ""; } ?></td>
<td><a href="mailto: <?php echo $email; ?>"><?php echo $email; ?></td>
<td><?php echo $phone; ?></td>
<td><?php echo ucfirst($level); ?></td>
</tr>
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
