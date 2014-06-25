<?php
/**
* Dashboard
* This file contains the welcome message for the logged in user and the list of directories which the user has access to
* If the user is an administrator, the file provides them with a header menu as well
*
* @author Georgi Zhivankin <georgijivankin@gmail.com>
* @version 0.1
* @since 0.1
* @access public
* @copyright 2012, Georgi Zhivankin . All rights reserved.
*/
// require the configuration file
require_once('config.php');
// include the restrict file
require_once('restrict.inc.php');
if (isset($_SESSION['username']))
{
$userID = $_SESSION['userID'];
$username = $_SESSION['username'];
$homeDir = $_SESSION['homeDir'];
$level = $_SESSION['level'];
	$sql = $db->query("SELECT DISTINCT smartdir_directories.dirName as dirName, smartdir_directories.dirPath FROM smartdir_directories, smartdir_users, smartdir_users2directories WHERE smartdir_directories.dirPath = smartdir_users2directories.dirPath and smartdir_users.userID = smartdir_users2directories.userID and smartdir_users2directories.userID = $userID");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo siteTitle; ?> - <?php echo siteDescription; ?></title>
</head>

<body>
<div id="container">
<div id="header"><?php include_once('header.php'); ?>
<p>Welcome, <?php echo ucfirst($username) . "."; ?></p>
</div>
<div id="content">
<h1><?php echo siteTitle; ?> - <?php echo siteDescription; ?></h1>
<p>
<ul>
<?php if (isset($homeDir)) { echo "Your home directory is: " ?><li><a href='list.php?dir=<?php echo $homeDir; ?>'><?php echo basename($homeDir); ?></a></li><?php } ?>
<?php if ($sql)
{
foreach ($sql as $row)
	{
?>
<li><a href="list.php?dir=<?php echo $row['dirPath']; ?>"><?php echo $row['dirName']; ?></a></li>
<?php } } else { echo "You do not have permissions to access more directories on this server."; } } else {
header("Location: login.php");
exit;
} ?>
</ul>
</div>
<div id="footer"><?php include_once('footer.php'); ?></div>

</body>
</html>
