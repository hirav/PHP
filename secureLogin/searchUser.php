<?php include_once("header.php"); include_once("user.php"); 

if (!Utility::checkLoginState() || ($_SESSION['adminroleid'] != 2))
{
	header("location:login.php");
	exit();
}
if (isset($_GET['statusChange']) && $_GET['statusChange'] == true) {
    $user = new User();
	$flag = $user->toggleUserStatus($_GET['email']);
	if($flag = true){
		$flag = false;
		Utility::alert("Status toggled successfully");
		header("Refresh:0; url=searchUser.php");
	}
}

if (isset($_GET['deleteUser']) && $_GET['deleteUser'] == true) {
    $user = new User();
	$flag = $user->deleteUser($_GET['id']);
	if($flag = true){
		$flag = false;
		Utility::alert("User Deleted successfully");
		header("Refresh:0; url=searchUser.php");
	}
}

echo '
		<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.0.0-beta1/jquery.min.js"></script>
		<script type="text/javascript" src="crud.js"></script>
		<section class="parent">
			<div class="child">
			<form method="post">
			<table>
				<tr>
					<td> Search users by</td>
				</tr>
				<tr>
					<td width = "180%"><input type="text" name="name" placeholder="first name or last name" /></td>
				</tr>
				<tr>
					<td><input type="email" name="email" placeholder="or Email id" /></td>
				</tr>
				<tr>
					<td align="center"><button type="submit" name="search_btn">Search</button></td>
				</tr>
			</table>
			</form>
			</div>
		</section>
		
';

if(isset($_POST['search_btn']) && (isset($_POST['email']) || isset($_POST['name']))) 
{
	$name = $_POST['name'];
	$email = $_POST['email'];

	$user = new User();
	$rows = $user->searchUser($name,$email);
	if($rows)
	{?>
	<h1 align="center"> User Details (Some columns are editable) </h1>
	<div id="status"> </div>
 	<div id="loading"></div>
	<table border="0"  class="sortable table zebra-style">
		<thead>
			<tr>
			    <th>First Name</th>
			    <th>Last Name</th>
			    <th>Email</th>
			    <th>Role</th>
			    <th>Favorite Color</th>
			    <th>User creation date</th>
			    <th>Status</th>
			    <th>Delete</th>
	  		</tr>
  		</thead>
  	<tbody  class="list">
	<?php foreach ($rows as $row): ?>
		<tr>
			<td contenteditable="true" id="admin_fname:<?php echo $row["admin_id"]; ?>"><?php echo $row["admin_fname"]; ?></td>
		    <td contenteditable="true" id="admin_lname:<?php echo $row["admin_id"]; ?>"><?php echo $row["admin_lname"]; ?></td>
		    <td contenteditable="true" id="admin_email:<?php echo $row["admin_id"]; ?>"><?php echo $row["admin_email"]; ?></td>
		    <td contenteditable="true" id="admin_role_id:<?php echo $row["admin_id"]; ?>"><?php echo $row["role_label"]; ?></td>
		    <td contenteditable="true" id="admin_fav_color:<?php echo $row["admin_id"]; ?>"><?php echo  $row["admin_fav_color"]; ?></td>
		    <td contenteditable="false" id="admin_date_created:<?php echo $row["admin_date_created"]; ?>"><?php echo $row["admin_date_created"]; ?></td>
		    <td><?php echo'<a href="searchUser.php?email='. $row['admin_email'] .'&statusChange=true">' . $row['admin_active'].'</a>';?></td>
		    <td><?php echo'<a href="searchUser.php?id='. $row['admin_id'] .'&deleteUser=true"> Delete </a>';?></td>
		</tr>
	<?php endforeach; ?>
	</tbody>
	</table>
	</body>
	<?php
	}
	else
	{
		Utility::alert("No record found. Try using another emailid and/or firstname/lastname");
	}
}

include_once('footer.php');