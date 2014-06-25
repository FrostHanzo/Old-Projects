<?php
/**
* This file contains the logic needed to display product images on the product pages
*
* @author Georgi Zhivankin <georgijivankin@gmail.com>
* @version 1.0
* @since 0.1
*/

	$uploadDir = UPLOAD_DIR.$userID;
// open the directory for reading
if (file_exists($uploadDir))
{
$files = array_diff(scandir($uploadDir), array('..', '.'));
if (!$files)
{
$message[] = "There are no uploaded pictures. Please upload a new picture now.";
}
// count the number of pictures uploaded
$quantity = count($files);
// loop through the files
foreach($files as $item)
{
// get the original size of the picture first
$size = getimagesize(SITE_ADDRESS.'uploads/'.$_SESSION['ID'].'/'.$item);
// show the picture
?>
<div class="uploaded-image" style="background: url(<?php echo SITE_ADDRESS.'uploads/'.$userID.'/'.$item; ?>) no-repeat center center; display: block; width: 140px; height: 140px;"><a class="delete" href="?delete=<?php echo $uploadDir.'/'.$item; ?>" title="Remove this image">Delete</a></div>
<?php
}
}
?>