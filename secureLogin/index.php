<?php 
include_once("header.php");
?>

<section class="parent">
	<div class="child">
		
		<?php 

			if (!Utility::checkLoginState())
			{
				header("location:login.php");
				exit();
			}

			echo 'Welcome ' . $_SESSION['username'] . '!';
			
		 ?>
		 
	</div>

</section>

<?php 
include_once("footer.php");
?>