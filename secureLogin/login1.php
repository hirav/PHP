<?php include_once("header.php"); ?>

<section class="parent">
	
	<div class="child">
		
		<?php 

			if (func::checkLoginState($dbh))
			{
				header("location:index.php");
			}

			if (isset($_POST['username']) && isset($_POST['password']))
			{
				$query = "SELECT * FROM tbl_admin WHERE admin_email = :username AND admin_password = :password";
				
				$username = $_POST['username'];
				$password = $_POST['password'];

				$stmt = $dbh->prepare($query);
				$stmt->execute(array(':username' => $username,
									 ':password' => $password));

				$row = $stmt->fetch(PDO::FETCH_ASSOC);

				if ($row['admin_id'] > 0)
				{
					func::createRecord($dbh, $row['admin_email'], $row['admin_id']);
					header("location:index.php");
				}
			}
			else
			{
				echo '
					<form action="login.php" method="post">
						<label>Username</label><br />
						<input type="text" name="username" /><br />
						<label>Password</label><br />
						<input type="password" name="password" /><br />
						<input type="submit" value="login" />
					</form>
				';
			}


		 ?>

	</div>
</section>

<?php include_once('footer.php'); ?>