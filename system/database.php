<?PHP

	try{
		$db = new PDO('mysql:host=' . $db_config["sqlHost"].';
					   dbname=' . $db_config["sqlDatabaseName"], 
					   $db_config["sqlUser"] ,
					   $db_config["sqlPass"] ,
					   array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
	}
	catch (PDOException $e)
	{
		echo 'INFO:' . $e->getMessage();
	}

include_once( __DIR__ . '/../classes/database.php' );
$query = new Query;

?>