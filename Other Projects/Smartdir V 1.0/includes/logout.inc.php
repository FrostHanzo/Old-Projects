<?php
// Empty the $_SESSION array
$_SESSION = array();
// Invalidate the session cookie
if (isset($_COOKIE[session_name()]))
{
setcookie(session_name(), '', time()-86400, '/');
}
// end session and redirect
session_destroy();
header('Location: ../login.php');
exit;
?>