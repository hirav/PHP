<?php include_once("header.php"); 

class User 
{
	public $fname;
	public $lname;
	public $emailId;
	public $role;
	public $favColor;
	public $status;

	public function __construct($fname = null, $lname = null, $emailId = null, $role = null, $favColor = null, $status = null)
	{
		$this->fname = $fname;
    	$this->lname = $lname;
    	$this->emailId = $emailId;
    	$this->role = $role;
    	$this->favColor = $favColor;
    	$this->status = $status;
   	}

	public function login()
	{	
		if (Utility::checkLoginState())
		{
			header("location:index.php");
		}

		if(isset($_POST['login']) && !is_null($_POST['login']))
		{
			if (isset($_POST['username']) && isset($_POST['password']) && $_POST['username'] != null && $_POST['password'] != null)
			{
				$email = filter_var($_POST['username'], FILTER_SANITIZE_EMAIL);
				Database::query('SELECT * FROM tbl_admin WHERE admin_email = :username and admin_active=1');
				Database::bind(':username',$email);
				$row = Database::result();

				if ($row['admin_password']!= null)
				{
					if(password_verify($_POST['password'],$row['admin_password']))
					{	
						Utility::createSessionsRecord($row['admin_fname'],$row['admin_id'],$row['admin_role_id']);
						header("location:index.php");
					}
				}
					Utility::alert("Incorrect username or password");
			}
			else
			{
				Utility::alert("Please enter Email id and password");
			}
		}
		return false;
	}

	public function addUser($hashpwd)
	{
		Database::query('INSERT INTO tbl_admin (admin_fname, admin_lname, admin_email, admin_password, admin_role_id, admin_active, admin_fav_color) values (:admin_fname, :admin_lname, :admin_email, :admin_password, :role_id, :admin_active, :admin_fav_color)');
		//Utility::alert($this->fname);
		Database::bind(':admin_fname',$this->fname);
		Database::bind(':admin_lname',$this->lname);
		Database::bind(':admin_email',$this->emailId);
		Database::bind(':admin_password',$hashpwd);
		Database::bind(':role_id',$this->role);
		Database::bind(':admin_active',$this->status);
		Database::bind(':admin_fav_color',$this->favColor);
		
		return Database::execute();      
	}

	public function editUser($admin_id, $field_name, $val)
	{
		if($field_name != 'admin_role_id')
		{
			$query = 'Update tbl_admin set ' . $field_name . ' = :val where admin_id = :admin_id';	
		}
		else
		{
			$query = 'Update tbl_admin set ' . $field_name . ' = (Select role_id from tbl_admin_roles where LCASE(role_label) = LCASE(:val)) where admin_id = :admin_id';
		}
		Database::query($query);
		Database::bind(':val',$val);
		Database::bind(':admin_id',$admin_id);
		$row = Database::execute();
		return $row;
	}

	public function deleteUser($admin_id)
	{
		$admin_email = filter_var($admin_id, FILTER_SANITIZE_NUMBER_INT);
		Database::query('Delete from tbl_admin WHERE admin_id = :admin_id');
		Database::bind(':admin_id',$admin_id);
		$row = Database::execute();
		return $row;
	}

	public function toggleUserStatus($email)
	{	
		$admin_email = filter_var($email, FILTER_SANITIZE_EMAIL);
		Database::query('Update tbl_admin set admin_active = IF(admin_active=1, 0, 1)  WHERE admin_email = :admin_email');
		Database::bind(':admin_email',$admin_email);
		$row = Database::execute();
		return $row;
		//Utility::alert("toggled");
	}

	public function searchUser($name = '',$email = '')
	{
		$admin_email = filter_var($email, FILTER_SANITIZE_EMAIL);
		$fname = filter_var($name, FILTER_SANITIZE_STRING);
		$lname = filter_var($name, FILTER_SANITIZE_STRING);
		Database::query('SELECT admin_id, admin_fname, admin_lname, admin_email, IF(admin_active = 0, "Inactive", "Active") as admin_active, admin_fav_color, admin_date_created, role_label FROM tbl_admin a, tbl_admin_roles r WHERE a.admin_role_id = r.role_id and (admin_email = :admin_email or admin_fname = :admin_fname or admin_lname = :admin_lname)');
		Database::bind(':admin_email',$admin_email);
		Database::bind(':admin_fname',$fname);
		Database::bind(':admin_lname',$lname);
		return Database::resultset(); 
	}



	public function hashPassword($password)
	{
		$hashpwd = password_hash($password,PASSWORD_DEFAULT,['cost' => 12]);
		return $hashpwd;
	}

}
?>