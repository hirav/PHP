<?php include_once("Database.php");

	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);


	$host = '127.0.0.1';
	$port = '3306';
	$dbname = 'securelogin';
	$username = 'root';
	$password = 'root';
	$option = array(

			PDO::ATTR_PERSISTENT => true,
			PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
		);
	//$dbh = new PDO('mysql:host=127.0.0.1;port=3306;dbname=securelogin', 'root', 'root');
?>
