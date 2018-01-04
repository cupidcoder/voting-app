<?php 
	// Database query functions
	include_once("fun.inc.php");

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

	// 2. Registration of voters
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
	
	// Populate the verification table
	try 
	{
		$query = "INSERT INTO verification";
		$query .= "(voter_id, verified) ";
		$query .= "VALUES('$id', 0)";
		$result = $db->exec($query);
	}
	catch (PDOException $e) {
		$msg = "There was an error inserting value into verification table with error: " .$e->getMessage();
		echo $msg;
		exit();
	}
	
	// Send new voter email with a link to generate the username and password automatically
	send_new_mail($id, $email_address, $firstname, $lastname);
}

 	
 	// 3. Creating of voters
function create_vote($category, $party, $photo_name, $candidate_name, $propaganda, $year) {
	
	global $db;

	/*
	THERE'S A SERIOUS BUG HERE
	identical category names would have unique IDs
	To resolve this, first check if the particular category is present in the vote table; if it's not perform creation query, if it is, do something else

	*/
	
	// Check if category is present in the vote table
	try 
	{
		$query = "SELECT id FROM vote ";
		$query .= "WHERE category='$category'";
		$result = $db->query($query);
	} 
	
	catch (PDOException $e) {
		$msg = "There was an error with category-check query with the error: " .$e->getMessage();
		echo $msg;
		exit();
	}
	
	$id = $result->fetch(PDO::FETCH_ASSOC);
	if ($id) // If an id is present
	{
		$id = $id['id']; // Use the same id to populate the polls table
	}
	
	else 
	{
		// First populate the vote table with category name, id and year
		try
		{
			$query = "INSERT INTO vote(category, year) ";
			$query .= "VALUES(:category, :year)";
			$s = $db->prepare($query);
			$s->bindValue(':category', $category);
			$s->bindValue(':year', $year);
			$s->execute();
			$id = $db->lastInsertId();
		}
		catch (PDOException $e) {
			$msg = "There was an error with this vote query: " .$e->getMessage();
			echo $msg;
			exit();
		}
	}
	
	// Then populate the polls table with the category id, candidate name, propaganda, party name
	
	try
	{
		$query = "INSERT INTO polls";
		$query .= "(category_id, party, photo_name, candidate_name, propaganda) ";
		$query .= "VALUES('$id', :party, :photo_name, :candidate_name, :propaganda)";
		$s = $db->prepare($query);
		$s->bindValue(':party', $party);
		$s->bindValue(':photo_name', $photo_name);
		$s->bindValue(':candidate_name', $candidate_name);
		$s->bindValue(':propaganda', $propaganda);
		$s->execute();
	}
	catch (PDOException $e) {
		$msg = "There was an error with this polls query: " .$e->getMessage();
		echo $msg;
		exit();
	}
}

	// 4. Retrieving created votes
function retrieve_votes() {
		global $db;
		try 
		{
			$query = "SELECT photo_name, category, party, candidate_name, propaganda ";
			$query .= "FROM polls INNER JOIN vote ";
			$query .= "WHERE category_id=vote.id";
			$result = $db->query($query);
		}
		catch (PDOException $e)
		{
			$msg = "There was an error retrieving polls data from database: " .$e->getMessage();
			echo $msg;
			exit();
		}
		return $result->fetchAll(PDO::FETCH_ASSOC);
}

	// 5. Retrieving firstname and second 

?>