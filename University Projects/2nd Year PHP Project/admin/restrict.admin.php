<?php
if (isset($_SESSION) && ($_SESSION['level'] != 'administrator'))
{
?>
<p>Access not allowed. Please <a href="../login.php">login</a> as an administrator to get access to this page.</p>
<?php
exit;
}
?>