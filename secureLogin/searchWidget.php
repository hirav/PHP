<?php include_once("header.php"); include_once("widget.php"); 

if (!Utility::checkLoginState() || ($_SESSION['adminroleid'] != 2))
{
	header("location:login.php");
	exit();
}
if (isset($_GET['statusChange']) && $_GET['statusChange'] == true) {
    $widget = new Widget();
	$flag = $widget->toggleWidgetStatus($_GET['id']);
	if($flag = true){
		$flag = false;
		Utility::alert("Status toggled successfully");
		header("Refresh:0; url=searchWidget.php");
	}
}

if (isset($_GET['deleteWidget']) && $_GET['deleteWidget'] == true) {
    $widget = new Widget();
	$flag = $widget->deleteWidget($_GET['id']);
	if($flag = true){
		$flag = false;
		Utility::alert("Widget Deleted successfully");
		header("Refresh:0; url=searchWidget.php");
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
					<td> Search Widget by</td>
				</tr>
				<tr>
					<td width = "180%"><input type="text" name="title" placeholder="Widget Title" /></td>
				</tr>
				<tr>
					<td><input type="text" name="owner" placeholder="or Owner first name" /></td>
				</tr>
				<tr>
					<td align="center"><button type="submit" name="search_btn">Search</button></td>
				</tr>
			</table>
			</form>
			</div>
		</section>
		
';

if(isset($_POST['search_btn']) && (isset($_POST['title']) || isset($_POST['owner']))) 
{
	$owner = $_POST['owner'];
	$title = $_POST['title'];

	$widget = new Widget();
	$rows = $widget->searchWidget($owner,$title);
	if($rows)
	{?>
	<h1 align="center"> Widget Details (Some columns are editable) </h1>
	<div id="status"> </div>
 	<div id="loading"></div>
	<table border="0"  class="sortable table zebra-style">
		<thead>
			<tr>
			    <th>Widget Title</th>
			    <th>Widget Description</th>
			    <th>Option 1</th>
			    <th>Option 2</th>
			    <th>Option 3</th>
			    <th>Widget Owner</th>
			    <th>Last Updated</th>
			    <th>Status</th>
			    <th>Delete</th>
	  		</tr>
  		</thead>
  	<tbody  class="list">
	<?php foreach ($rows as $row): ?>
		<tr>
			<td contenteditable="true" id="widget_title:<?php echo $row["widget_id"]; ?>"><?php echo $row["widget_title"]; ?></td>
		    <td contenteditable="true" id="widget_description:<?php echo $row["widget_id"]; ?>"><?php echo $row["widget_description"]; ?></td>
		    <td contenteditable="true" id="widget_option1:<?php echo $row["widget_id"]; ?>"><?php echo $row["widget_option1"]; ?></td>
		    <td contenteditable="true" id="widget_option2:<?php echo $row["widget_id Id"]; ?>"><?php echo $row["widget_option2"]; ?></td>
		    <td contenteditable="true" id="widget_option3:<?php echo $row["widget_id"]; ?>"><?php echo  $row["widget_option3"]; ?></td>
		    <td contenteditable="true" id="widget_owner_id:<?php echo $row["widget_id"]; ?>"><?php echo  $row["widget_owner"]; ?></td>
		    <td contenteditable="false" id="widget_last_updated:<?php echo $row["widget_id"]; ?>"><?php echo $row["widget_last_updated"]; ?></td>
		    <td><?php echo'<a href="searchWidget.php?id='. $row['widget_id'] .'&statusChange=true">' . $row['widget_active'].'</a>';?></td>
		    <td><?php echo'<a href="searchWidget.php?id='. $row['widget_id'] .'&deleteWidget=true"> Delete </a>';?></td>
		</tr>
	<?php endforeach; ?>
	</tbody>
	</table>
	</body>
	<?php
	}
	else
	{
		Utility::alert("No record found. Try using different widget title and/or differnt owner firstname/lastname");
	}
}

include_once('footer.php');