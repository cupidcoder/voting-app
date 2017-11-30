<?php 
// Database query functions

// 1. Login query
function login_checker($table_name, $user, $pass) {
	global $db;
	try
	{
		$query = "SELECT COUNT(username), COUNT(password) ";
		$query .= "FROM $table_name WHERE ";
		$query .= "username = '$user' AND password = '$pass'";
		$result = $db->query($query);
	}
	catch (PDOException $e)
	{
		$msg = "There was an error performing db query with error: " .$e->getMessage();
		echo $msg;
		exit();
	}
	return $result->fetch(PDO::FETCH_ASSOC); 
}

// 2. Registeration of voters
function register_voter($photo_name,$lastname, $firstname, $dob, $email_address, $street_address, $city) {
	global $db;

	// First populate the voters table to retrieve the unique id
	try
	{
		$query = "INSERT INTO voters(id) ";
		$query .= "VALUES(null)";
		$result = $db->exec($query);
		$id = $db->lastInsertId();
	}
	catch (PDOException $e) {
		$msg = "There was an error with this voters query: " .$e->getMessage();
		echo $msg;
		exit();
	}

	// Now use voters.id to populate biodata.voters_id and other details
	try
	{
		$query = "INSERT INTO biodata";
		$query .= "(photo_name, voter_id, lastname, firstname, dob, email_address, street_address, city) ";
		$query .= "VALUES(:photo_name, '$id', :lastname, :firstname, :dob, :email_address, :street_address, :city)";
		$s = $db->prepare($query);
		$s->bindValue(':photo_name', $photo_name);
		$s->bindValue(':lastname', $lastname);
		$s->bindValue(':firstname', $firstname);
		$s->bindValue(':dob', $dob);
		$s->bindValue(':email_address', $email_address);
		$s->bindValue(':street_address', $street_address);
		$s->bindValue(':city', $city);
		$s->execute();
	}
	catch (PDOException $e) {
		$msg = "There was an error with this query: " .$e->getMessage();
		echo $msg;
		exit();
	}
}

?>