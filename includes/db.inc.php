<?php // Database connection file

// Database constants
define("DB_USER", "voter_admin");
define("DB_PASS", "Voter_adminpass");

try {
	$db = new PDO ("mysql:host=localhost;dbname=voting_app", DB_USER, DB_PASS);
	$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$db->exec("SET NAMES 'utf8'");

} catch (PDOException $e) {
	$msg = "There was an error connecting to mysql database with error: " .$e->getMessage();
	echo $msg; // Place holder for error message page
	exit();
}