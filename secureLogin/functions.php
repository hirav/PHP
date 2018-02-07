<?php 

class func 
{
	public static function checkLoginState($dbh)
	{
		if (!isset($_SESSION))
		{
			session_start();
		}
		if (isset($_COOKIE['userid']) && isset($_COOKIE['token']) && isset($_COOKIE['series']))
		{
			$query = "SELECT * FROM tbl_sessions WHERE sessions_admin_id = :userid AND sessions_token = :token AND sessions_series_identifier = :series;";

			$userid = $_COOKIE['userid'];
			$token = $_COOKIE['token'];
			$series = $_COOKIE['series'];

			$stmt = $dbh->prepare($query);
			$stmt->execute(array(':userid' => $userid, 
								 ':token' => $token, 
								 ':series' => $series));

			$row = $stmt->fetch(PDO::FETCH_ASSOC);
   
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
						func::createSession($_COOKIE['username'], $_COOKIE['userid'], $_COOKIE['token'], $_COOKIE['series']);
						return true;
					}
				}
			}
		}
	}

	public static function createRecord($dbh, $user_username, $user_id)
	{

		$query = "INSERT INTO tbl_sessions (sessions_admin_id, sessions_token, sessions_series_identifier) VALUES (:user_id, :token, :series);";
		
		$dbh->prepare("DELETE FROM tbl_sessions WHERE sessions_admin_id= :sessions_admin_id;")->execute(array(':sessions_admin_id' => $user_id));

		$token = func::createString(30);
		$series = func::createString(30);

		echo '<script language="javascript">';
					echo 'alert("created record")';
					echo '</script>';

		func::createCookie($user_username, $user_id, $token, $series);
		func::createSession($user_username, $user_id,  $token, $series);
echo '<script language="javascript">';
					echo 'alert("set cookie nd session")';
					echo '</script>';

		$stmt = $dbh->prepare($query);
		$stmt->execute(array(':user_id' => $user_id,
							 ':token' => $token,
							 ':series' => $series));
	}

	public static function createCookie($user_username, $user_id, $token, $series)
	{
		setcookie('userid', $user_id, time() + (86400) * 30, "/");
		setcookie('username', $user_username, time() + (86400) * 30, "/");
		setcookie('token', $token, time() + (86400) * 30, "/");
		setcookie('series', $series, time() + (86400) * 30, "/");
	}

	public static function deleteCookie()
	{
		setcookie('userid', '', time() - 1, "/");
		setcookie('username', '', time()  - 1, "/");
		setcookie('token', '', time()  - 1, "/");
		setcookie('series', '', time()  - 1, "/");
		session_destroy();
	}

	public static function createSession($user_username, $user_id, $token, $series)
	{
		if (!isset($_SESSION))
		{
			session_start();
		}
		$_SESSION['userid'] = $user_id;
		$_SESSION['token'] = $token;
		$_SESSION['series'] = $series;
		$_SESSION['username'] = $user_username;
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