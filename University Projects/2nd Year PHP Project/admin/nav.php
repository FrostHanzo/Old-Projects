<?php
/**
* Navigation file for the user view
*
* @author Georgi Zhivankin <abdr910@city.ac.uk>
* @access public
* @version 1.0
* @since 0.1
*/
?>
<ul>
<li><a href="../index.php">Home</a></li>
<?php
if (isset($_SESSION) && ($_SESSION['level'] == 'administrator'))
{
?>
<ul>
<li><a href="manageCategories.php">Category Management</a></li>
<li><a href="manageProducts.php">Product Management</a></li>
<li><a href="manageUsers.php">User Management</a></li>
</ul>
<?php }
if (isset($_SESSION) && ($_SESSION['loggedIn']))
{
?>
<ul>
<li><a href="../myAccount.php">My Account</a></li>
<li><a href="../myOrders.php">My Previous Orders</a></li>
</ul>
<?php } ?>
<li><a href="../about.php">Company Overview</a></li>
<li><a href="../categories.php">Products Catalogue</a></li>
<li><a href="../search.php">Search</a></li>
<li><a href="../services.php">Services</a></li>
<li><a href="../contact.php">Support and Contact</a></li>
<?php if (isset($_SESSION['loggedIn']))
{
	?>
<li><a href="../logout.php">Log out</a></li>
<?php } else { ?>
<li><a href="../login.php">Login</a></li>
<?php } ?>
</ul>