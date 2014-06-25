<?php
/**
* list.php
* @Version 0.1
* @Author Georgi Zhivankin
* @Description This file lists all files and folders in a given directory
*/
// require the configuration file
require_once('config.php');
// include the restrict file
require_once('restrict.inc.php');
$homeDir = $_SESSION['homeDir'];

if (isset($_GET['dir']))
{
$directory = $_GET['dir'];
	$list = getFileList($directory);
}
elseif (isset($_SESSION['homeDir']))
{
$directory = $homeDir;
	$list = getFileList($directory);
}
else
{
$directory = dir;
	$list = getFileList($directory);
}
if (isset($_GET['file']))
{
$filename = $_GET['file'];
openFile($filename, $mimeType = getMimeType($filename));
exit;
}
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Index of <?php echo basename($directory); ?></title>
</head>

<body>
<div id="container">
<div id="header">
<?php if (isset($_SESSION['loggedIn'])) { ?><a href="dashboard.php">Home</a><?php } ?>
</div>
<div id="content">

<table>
<tr>
<th>File Name:</th>
<th>File Size:</th>
<th>File Type:</th>
<th>Last Modified: </th>
<th>Mime Type:</th>
</tr>
<tr>
<td>
<?php
if($homeDir == $directory || dirname($homeDir) == dirname($directory)) { echo "You are in your home directory"; } else {
?><a href="<?php echo $_SERVER['PHP_SELF'] . '?' . 'dir' . '=' . dirname($directory); ?>">.. Go to the parent directory</a>
<?php } ?>
</td>
</tr>
<?php foreach ($list as $file)
{
?>
<tr>
<td><a href="<?php
if ($file['type'] == 'dir')
{
$dirName = $_SERVER['PHP_SELF'] . '?' . 'dir' . '=' . $file['name'];
echo "$dirName";
}
else
{
$fileName = $_SERVER['PHP_SELF'] . '?' . 'file' . '=' . $file['name'];
echo $fileName;
}
?>"><?php echo basename($file['name']); ?></a></td>
<td><?php echo $file['size']; ?></td>
<td><?php echo $file['type']; ?></td>
<td><?php echo date('r', $file['lastmod']); ?></td>
<td><?php if ($file['type'] != 'dir') { echo getMimeType($file['name']); } ?></td>
</tr>
<?php } ?>
</table>
</div>
<div id="footer"><?php include_once('footer.php'); ?></div>

</body>
</html>
