<?php
/**
 * Database connection configurations
 * @author: Tu Nguyen
 * @version: 1.0
 */

	/**
	 * Opens a connection to the database
	 * @return PDO object
	 */
	function openDB()
	{
        /*
		$server="localhost";
		$user="ape_user";
		$pass="X5mlOhMZyTKheq1Q";
		$database="ape_database";
         */
		$server="localhost";
		$user="ape_user";
		$pass="password";
		$database="ape_database";

		$conn = null;
		
		try
		{
			$conn = new PDO("mysql:host=$server;dbname=$database", $user, $pass);
			$conn -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			return $conn;
		}
		catch(PDOException $e)
		{
			echo "Connection failed: " . $e->getMessage();
		}
	}
?>
