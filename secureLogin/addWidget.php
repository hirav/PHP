<?php include_once("header.php"); include_once("widget.php"); 

if (!Utility::checkLoginState() || ($_SESSION['adminroleid'] != 2))
{
	header("location:login.php");
	exit();
}

if(isset($_POST['add_btn']))
{
	if($_POST['title']!= null && $_POST['description']!= null && $_POST['option1']!= null && $_POST['option2']!= null && $_POST['option3'] != null && $_POST['owner'] != null)
	{
		$title = filter_var($_POST['title'],FILTER_SANITIZE_STRING);
		$description = filter_var($_POST['description'],FILTER_SANITIZE_STRING);
		$option1 = filter_var($_POST['option1'],FILTER_SANITIZE_NUMBER_INT);
		$option2 = filter_var($_POST['option2'],FILTER_SANITIZE_NUMBER_INT);
		$option3 = filter_var($_POST['option3'],FILTER_SANITIZE_NUMBER_INT);
		$owner = filter_var($_POST['owner'],FILTER_SANITIZE_STRING);
		
		$widget = new Widget(null,$title,$description,$option1,$option2,$option3,$owner);
		$rows = $widget->searchWidget(null,$title);
		if(!$rows)
		{
			$result = $widget->addWidget();
			if($result)
			{
				Utility::alert("Widget added successfully");
				header("Refresh:0; url=addWidget.php");
			}
			else
			{
				 Utility::alert("Error adding Widget");
			}
		}
		else
		{
			Utility::alert("There is already a widget created with title: ". $title);
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
			<form action="addWidget.php" method="post">
			<center>
		    <table class = "user"  style="width:30%">
				<tr><td> Create New Widget</td></tr>
				<tr><td height = "50"><input style="font-size:18pt;height:100%px;width:100%;" type="text" name="title" placeholder="Title" /></td></tr>
				<tr><td><input style="font-size:18pt;height:100%px;width:100%;" type="text" name="description" placeholder="Description" /></td></tr>
				<tr><td><input style="font-size:18pt;height:100%px;width:100%;" type="number" name="option1" placeholder="Option 1" /></td></tr>
				<tr><td><input style="font-size:18pt;height:100%px;width:100%;" type="number" name="option2" placeholder="Option 2" /></td></tr>
				<tr><td><input style="font-size:18pt;height:100%px;width:100%;" type="number" name="option3" placeholder="Option 3" /></td></tr>
				<tr><td><input style="font-size:18pt;height:100%px;width:100%;" type="text" name="owner" placeholder="Owner First name" /></td></tr>
				<tr><td align="center"><button style="font-size:18pt;height:100%px;width:100%;" type="submit" name="add_btn">Add Widget</button></td></tr>
			</table>
			</center>
			</form>
			</div>
		
';



include_once('footer.php');