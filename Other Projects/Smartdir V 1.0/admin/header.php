<nav>
<ul>
<li><a href="<?php echo '../index.php'; ?>">Home</a></li>
<?php if (isset($_SESSION['level']) && $_SESSION['level'] == 'admin') { ?><li><a href=<?php echo 'users.php'; ?>>User Management and Permissions</a></li><?php } ?>
<li><a href="<?php echo '../list.php'; ?>">Go to your home directory</a></li>
<?php if (isset($_SESSION['loggedIn'])) { ?><li><a href="<?php echo '../includes/logout.inc.php'; ?>">Log Out</a><?php } ?>
</ul>
</nav>