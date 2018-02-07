<?php include_once('header.php'); ?>

<div class="container"></div>
<footer>
<?php 
echo '
<a href="index.php">Index</a> | ';
if (Utility::checkLoginState()) 
	{
		echo '<a href="logout.php">Logout</a> | ';
		if(($_SESSION['adminroleid'] == 2))
		echo '<a href="admin.php">Admin</a>';
	}
else 
	echo '<a href="login.php">Login</a>';
 ?>
</footer>
</div>
</body>
</html>
