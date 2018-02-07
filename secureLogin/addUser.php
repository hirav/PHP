<?php include_once("header.php"); include_once("user.php"); 

if (!Utility::checkLoginState() || ($_SESSION['adminroleid'] != 2))
{
	header("location:login.php");
	exit();
}

if(isset($_POST['add_btn']))
{
	if($_POST['email']!= null && $_POST['password']!= null && $_POST['fname']!= null && $_POST['lname']!= null && $_POST['role'] != null && $_POST['color'] != null)
	{
		$fname = filter_var($_POST['fname'],FILTER_SANITIZE_STRING);
		$lname = filter_var($_POST['lname'],FILTER_SANITIZE_STRING);
		$role = $_POST['role'];
		$color = filter_var($_POST['color'],FILTER_SANITIZE_STRING);
		$email = filter_var($_POST['email'],FILTER_VALIDATE_EMAIL);;
		$status = $_POST['status'];
		$user = new User($fname,$lname,$email,$role,$color,$status);
		$password = $user->hashPassword($_POST['password']);
		$rows = $user->searchUser(null,$email);
		if(!$rows)
		{
			$result = $user->addUser($password);
			if($result)
			{
				Utility::alert("User added successfully");
				header("Refresh:0; url=addUser.php");
			}
			else
			{
				Utility::alert("Error adding user");
			}
		}
		else
		{
			Utility::alert("There is already an account created with email id: ". $email);
		}
	}
	else
	{
		Utility::alert("Please input value for all the fields");
	}
}


echo '
		<body>
			<div>
			<form action="addUser.php" method="post">
			<center>
		    <table class = "user"  style="width:30%">
				<tr><td> Create New User</td></tr>
				<tr><td height = "50"><input style="font-size:18pt;height:100%px;width:100%;" type="text" name="fname" placeholder="First name" /></td></tr>
				<tr><td><input style="font-size:18pt;height:100%px;width:100%;" type="text" name="lname" placeholder="Last name" /></td></tr>
				<tr><td><input style="font-size:18pt;height:100%px;width:100%;" type="email" name="email" placeholder="Email id" /></td></tr>
				<tr><td><input style="font-size:18pt;height:100%px;width:100%;" type="password" name="password" placeholder="Password" /></td></tr>

				<tr>
					<td class ="select">
						<select style="font-size:18pt;height:100%px;width:100%;" name="role" >        
				            <option value=1>Developer</option>
				            <option value=2>Manager</option>
		    			</select>
					</td>
				</tr>
				<tr><td><input style="font-size:18pt;height:100%px;width:100%;" type="text" name="color" placeholder="Favorite Color" /></td></tr>
				<tr>
					<td class = "select" >
						<select style="font-size:18pt;height:100%px;width:100%;" name="status">        
				            <option value=1>Active</option>
				            <option value=0>Inactive</option>
		    			</select>
					</td>
				</tr> 
				<tr><td align="center"><button style="font-size:18pt;height:100%px;width:100%;" type="submit" name="add_btn">Add User</button></td></tr>
			</table>
			
			</center>
			</form>
			</div>
		
';



include_once('footer.php');