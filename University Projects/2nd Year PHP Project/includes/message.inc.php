<?php
/**
* This file contains the code snippet that displays error messages in the other files
*
* @author Georgi Zhivankin <georgijivankin@gmail.com>
* @version 1.0
* @since 0.1
*/

?>
<?php if (isset($message))
{
?>
<br/>
<div id="warning" style="font-family: Verdana; font-size: 20px; color: #F00; margin-left:20px;">
<ul>
<?php 	foreach ($message as $error)
{
?>
<li><?php echo $error; ?></li>
<?php } ?>
</ul></div><br/>
<?php } ?>