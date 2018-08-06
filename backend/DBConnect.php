
<?php

			require_once 'Config.php';
		
		try {
		$con = new PDO('mysql:host='.DB_HOST.';dbname='.DB_DATABASE.';charset=utf8', DB_USER, DB_PASSWORD, array(PDO::ATTR_EMULATE_PREPARES => false,PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
		$con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$con->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
		
	}catch(Exception $e) {
    //echo $e->getMessage();
    echo "An error has occurred-". $e->getMessage();
}
	
	function close(){
		mysql_close();
	}
	
		
?>