<?php include_once("Database.php");

class Utility 
{
	public static function checkLoginState()
	{
		if (!isset($_SESSION))
		{
			session_start();
		}
		if (isset($_COOKIE['userid']) && isset($_COOKIE['token']) && isset($_COOKIE['series']))
		{
			$admin_id = filter_var($_COOKIE['userid'], FILTER_SANITIZE_NUMBER_INT);
			Database::query('SELECT * FROM tbl_sessions WHERE sessions_admin_id = :userid AND sessions_token = :token AND sessions_series_identifier = :series');
			Database::bind(':userid',$admin_id);
			Database::bind(':token',$_COOKIE['token']);
			Database::bind(':series',$_COOKIE['series']);
			$row = Database::result();
   
			if ($row['sessions_admin_id'] > 0)
			{
				if (
					$row['sessions_admin_id'] == $_COOKIE['userid'] &&
					$row['sessions_token']  == $_COOKIE['token']  &&
					$row['sessions_series_identifier'] == $_COOKIE['series']
					)
				{
					if (
					$row['sessions_admin_id'] == $_SESSION['userid'] &&
					$row['sessions_token']  == $_SESSION['token']  &&
					$row['sessions_series_identifier'] == $_SESSION['series']
						)
					{
						return true;
					}
					else
					{
						Utility::createSession($_COOKIE['username'], $_COOKIE['userid'], $_COOKIE['token'], $_COOKIE['series'], $_COOKIE['adminroleid']);
						return true;
					}
				}
			}
		}
	}

	public static function createSessionsRecord($user_username, $user_id, $admin_role_id)
	{
		$admin_username = filter_var($user_username, FILTER_SANITIZE_EMAIL);
		$admin_id = filter_var($user_id, FILTER_SANITIZE_NUMBER_INT);
		$admin_role_id = filter_var($admin_role_id, FILTER_SANITIZE_NUMBER_INT);

		Database::query("DELETE FROM tbl_sessions WHERE sessions_admin_id= :sessions_admin_id;");
		Database::bind(':sessions_admin_id',$admin_id);
		Database::execute();

		$token = Utility::createString(30);
		$series = Utility::createString(30);

		Database::query('INSERT INTO tbl_sessions (sessions_admin_id, sessions_token, sessions_series_identifier) VALUES (:userid, :token, :series);');
		Database::bind(':userid',$admin_id);
		Database::bind(':token',$token);
		Database::bind(':series',$series);
		$row = Database::execute();

		Utility::createCookie($admin_username, $admin_id, $token, $series, $admin_role_id);
		Utility::createSession($admin_username, $admin_id,  $token, $series, $admin_role_id);
	}

	public static function createCookie($user_username, $user_id, $token, $series, $admin_role_id)
	{
		setcookie('userid', $user_id, time() + (86400) * 30, "/");
		setcookie('username', $user_username, time() + (86400) * 30, "/");
		setcookie('token', $token, time() + (86400) * 30, "/");
		setcookie('series', $series, time() + (86400) * 30, "/");
		setcookie('adminroleid', $admin_role_id, time() + (86400) * 30, "/");
	}

	public static function deleteCookie()
	{
		setcookie('userid', '', time() - 1, "/");
		setcookie('username', '', time()  - 1, "/");
		setcookie('token', '', time()  - 1, "/");
		setcookie('series', '', time()  - 1, "/");
		setcookie('adminroleid', '', time()  - 1, "/");
		session_destroy();
	}

	public static function createSession($user_username, $user_id, $token, $series, $admin_role_id = null)
	{
		if (!isset($_SESSION))
		{
			session_start();
		}
		$_SESSION['userid'] = $user_id;
		$_SESSION['token'] = $token;
		$_SESSION['series'] = $series;
		$_SESSION['username'] = $user_username;
		if(is_null($admin_role_id))
		{
			$admin_role_id = 1;
		}
		$_SESSION['adminroleid'] = $admin_role_id;
		

	}

	public static function createString($len)
	{
		$string = "1qay2wsx3edc4rfv5tgb6zhn7ujm8ik9olpAQWSXEDCVFRTGBNHYZUJMKILOP";
		
		return substr(str_shuffle($string), 0, 30);
	}
	public static function alert($msg)
	{
		echo '<script>alert("' . $msg . '")</script>'; 
	}
}

 ?>