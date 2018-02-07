<?php include_once("config.php"); include_once("utility.php");

class Database { 
	
	// Setup connection properties 
/*	private $host = '127.0.0.1';
	private $port = '3306';
	private $dbname = 'securelogin';
	private $username = 'root';
	private $password = 'root';
*/
	//Database Handler
	private static $dbha;
	private static $error;
	private static $stmnt;

	// Setup connection automatically when a new instance of Database class is created
	public static function setConnetion()
	{
		//Set Data Source Name (DSN)
		$dsn = 'mysql:host=' . $GLOBALS['host'] . ';port=' . $GLOBALS['port'] . ';dbname=' . $GLOBALS['dbname'];
		//Setup options
		/*$option = array(

			PDO::ATTR_PERSISTENT => true,
			PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
		);*/

		try
		{
			//PDO getting connection
			Database::$dbha = new PDO($dsn, $GLOBALS['username'], $GLOBALS['password'], $GLOBALS['option']);
		}
		catch (PDOException $e)
		{
			Database::$error = $e->getMessage();
		}
	}

	// prepare query
	public static function query($query)
	{
		if(!isset(Database::$dbha))
		{
			Database::setConnetion();
		}
		Database::$stmnt = Database::$dbha->prepare($query);
	}

	// bind variables
	public static function bind($param, $value, $type = null)
	{
		if(is_null($type))
		{
			switch (true) {
				case is_int($value):
					$type = PDO::PARAM_INT;
					break;

				case is_bool($value):
					$type = PDO::PARAM_BOOL;
					break;

				case is_null($value):
					$type = PDO::PARAM_NULL;
					break;
				
				default:
					$type = PDO::PARAM_STR;
					break;
			}
		}

		Database::$stmnt->bindValue($param, $value, $type);
	}

	// Execute query and return o/p if any
	public static function execute()
	{	
		return Database::$stmnt->execute();
	}

	//Execute query and return next row from results in form of Assoc array
	public static function result()
	{
		Database::execute();
		return Database::$stmnt->fetch(PDO::FETCH_ASSOC);
	}

	//Execute query and return all results in form of Assoc array
	public static function resultset()
	{
		Database::execute();
		return Database::$stmnt->fetchAll(PDO::FETCH_ASSOC);
	}


}