<?php include_once("header.php"); 

class Widget 
{
	public $id;
	public $title;
	public $description;
	public $option1;
	public $option2;
	public $option3;
	public $owner;

	public function __construct($id = null, $title = null, $description = null, $option1 = null, $option2 = null, $option3 = null, $owner =null)
	{
		$this->id = $id;
    	$this->title = $title;
    	$this->description = $description;
    	$this->option1 = $option1;
    	$this->option2 = $option2;
    	$this->option3 = $option3;
    	$this->owner = $owner;
   	}

	public function addWidget()
	{
		Database::query('INSERT INTO tbl_widget (widget_title, widget_description, widget_option1, widget_option2, widget_option3, widget_owner_id) values (:widget_title, :widget_description, :widget_option1, :widget_option2, :widget_option3, (Select admin_id from tbl_admin where LCASE(admin_fname) = LCASE(:widget_owner)))');
		
		Database::bind(':widget_title',$this->title);
		Database::bind(':widget_description',$this->description);
		Database::bind(':widget_option1',$this->option1);
		Database::bind(':widget_option2',$this->option2);
		Database::bind(':widget_option3',$this->option3);
		Database::bind(':widget_owner',$this->owner);

		return Database::execute();      
	}

	public function editWidget($widget_id, $field_name, $val)
	{
		if($field_name != 'widget_owner_id')
		{
			$query = 'Update tbl_widget set ' . $field_name . ' = :val, widget_last_updated = now() where widget_id = :widget_id';	
		}
		else
		{
			$query = 'Update tbl_widget set ' . $field_name . ' = (Select admin_id from tbl_admin where LCASE(admin_fname) = LCASE(:val)), widget_last_updated = now() where widget_id = :widget_id';
		}
		Database::query($query);
		Database::bind(':val',$val);
		Database::bind(':widget_id',$widget_id);
		$row = Database::execute();
		return $row;
	}

	public function deleteWidget($widget_id)
	{
		$widget_id = filter_var($widget_id, FILTER_SANITIZE_NUMBER_INT);
		Database::query('Delete from tbl_widget WHERE widget_id = :widget_id');
		Database::bind(':widget_id',$widget_id);
		$row = Database::execute();
		return $row;
	}

	public function toggleWidgetStatus($widget_id)
	{	
		$widget_id = filter_var($widget_id, FILTER_SANITIZE_NUMBER_INT);
		Database::query('Update tbl_widget set widget_active = IF(widget_active=1, 0, 1), widget_last_updated = now()  WHERE widget_id = :widget_id');
		Database::bind(':widget_id',$widget_id);
		$row = Database::execute();
		return $row;
		//Utility::alert("toggled");
	}

	public function searchWidget($owner = '',$title = '')
	{
		$widget_title = filter_var($title, FILTER_SANITIZE_STRING);
		$admin_fname = filter_var($owner, FILTER_SANITIZE_STRING);
		Database::query('SELECT widget_id, widget_title, widget_description, widget_option1, widget_option2, widget_option3, IF(widget_active = 0, "Inactive", "Active") as widget_active, widget_last_updated, admin_fname as widget_owner FROM tbl_widget, tbl_admin WHERE widget_owner_id = admin_id and (widget_title = :widget_title or admin_fname = :admin_fname)');
		Database::bind(':widget_title',$widget_title);
		Database::bind(':admin_fname',$admin_fname);
		return Database::resultset();; 
	}
}
?>