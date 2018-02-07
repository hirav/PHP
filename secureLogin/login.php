<?php include_once("header.php"); include_once("user.php"); 

$user = new User();

			if(!($user->login()))
			{
				echo '
					<section class="parent">
						<div class="child">
							<form action="login.php" method="post">
								<label>Username</label><br />
								<input type="email" name="username" /><br />
								<label>Password</label><br />
								<input type="password" name="password" /><br />
								<input type="submit" name="login" value="login" />
							</form>
						</div>
					</section>
				';
			}

include_once('footer.php');