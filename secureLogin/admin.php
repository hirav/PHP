<?php include_once('header.php'); ?>

<?php 
		if (!Utility::checkLoginState() || ($_SESSION['adminroleid'] != 2))
		{
			Utility::alert($_SESSION['adminroleid']);
			header("location:login.php");
				exit();
		} 
	?>

	<section class="parent">
		<div class="child">
			
			<p>Hello <?php echo $_COOKIE['username']; ?> and welcome to your private admin section of this website. Here you can do whatever you like to do, the possibilities are endless.</p>

			<ul>
				<li><a href="searchUser.php">Search/Edit/Delete User</a></li>
				<li><a href="addUser.php">Add User</a></li>
				<li><a href="searchWidget.php">Search/Edit/Delete Widget</a></li>
				<li><a href="addWidget.php">Add Widget</a></li>
			</ul>

		</div>
	</section>

<?php include_once('footer.php'); ?>