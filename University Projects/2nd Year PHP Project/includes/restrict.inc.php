<?php
/**
* Restrict file
* This file contains the code snippet that restricts showing of certain items only to logged in users
*
* @author Georgi Zhivankin <georgijivankin@gmail.com>
* @version 0.1
* @since 0.1
*/

// Check if the session variable is set
if (!$_SESSION['loggedIn'] == 1)
header("Location: login.php");
?>