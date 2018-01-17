<?php 
	// Database query functions
	include_once("fun.inc.php");

	// 1. Login query
	function login_checker($table_name, $user, $pass, $column='username') {
		global $db;
		try
		{
			$query = "SELECT COUNT($column), COUNT(password) ";
			$query .= "FROM $table_name WHERE ";
			$query .= "$column = '$user' AND password = '$pass'";
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
			$db->exec($query);
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
			$db->exec($query);
		}
		catch (PDOException $e) {
			$msg = "There was an error inserting value into verification table with error: " .$e->getMessage();
			echo $msg;
			exit();
		}
		
		/*
		 *	Populate the vote_casted_status with the unique voter_id and a value of "0" for voting_status (the voter is yet to cast a vote)
		 *	the value for voting_status would be changed to 1 once a voter has casted a vote and changed back to 0 when an admin archives all concluded polls
		*/
		
		try {
			$query = "INSERT INTO vote_casted_status(voter_id, voting_status) ";
			$query .= "VALUES('$id', 0)";
			$db->exec($query);
		} 
		
		catch (PDOException $e) {
			$msg = "There was an error: " . $e->getMessage() . ",populating vote_casted_status";
			echo $msg;
			exit();
		}
		
		// Send new voter email with a link to generate the username and password automatically
		send_new_mail($id, $email_address, $firstname, $lastname);
	}

 	
 	// 3a. Creating of votes
	function create_vote($category, $party, $photo_name, $candidate_name, $propaganda, $year) {
		
		global $db;
	
		/*
		THERE'S A SERIOUS BUG HERE
		identical category names would have unique IDs
		To resolve this, first check if the particular category is present in the vote table; if it's not perform creation query, if it is, do something else
		
		***UPDATE*** This bug has been fixed
		
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
		$id = $db->lastInsertId(); // This would be used to populate the polls_count table
	}
	catch (PDOException $e) {
		$msg = "There was an error with this polls query: " .$e->getMessage();
		echo $msg;
		exit();
	}
	
	// Populate polls_count table with primary id from polls table
	try {
		$query = "INSERT INTO polls_count(poll_id, count) ";
		$query .= "VALUES('$id', 0)";
		$db->exec($query);
	} 
	catch (PDOException $e) {
		$msg = "Error: " . $e->getMessage() . ", populating polls_count with poll id";
		echo $msg;
		exit();
	}
}

    // 3b. Archiving all votes
    function archive_all_votes() {
		// first change polling status of votes to 1 (indicating concluded)
		global $db;
		try {
			$query = "UPDATE vote ";
			$query .= "SET polling_status='1' WHERE true";
			$db->exec($query);
		} catch (PDOException $e) {
			$msg = "Error: " . $e->getMessage() . ", while archiving votes";
			echo $msg;
			exit();
		}

		// Then change vote_casted_status of all voters to 0 (indicating fresh polling campaign
		try {
			$query = "UPDATE vote_casted_status ";
			$query .= "SET voting_status='0' WHERE true";
			$db->exec($query);
		} catch (PDOException $e) {
			$msg = "Error: " . $e->getMessage() . ", while resetting voting status of registered voters";
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
				$query .= "WHERE category_id=vote.id AND polling_status='0'";
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

	// 5. Retrieving Voter's for dashboard display
	function retrieve_voters($username) {
		global $db;
		try {
			$query = "SELECT photo_name, firstname ";
			$query .= "FROM biodata INNER JOIN voters ";
			$query .= "WHERE biodata.voter_id=voters.id ";
			$query .= "AND voters.identification_number='$username'";
			$result = $db->query($query);
		} catch (PDOException $e) {
			$msg = "There was an error: " . $e->getMessage() . ", with retrieving voter information";
			echo $msg;
			exit();
		}
		return $result->fetch(PDO::FETCH_ASSOC);
	}
	// 6a. Retrieving unique voter id from database
	function retrieve_voter_id() {
		$username = $_SESSION['voter'];
		global $db;
		// Retrieve id from voters table
		try {
			$query = "SELECT id from voters ";
			$query .= "WHERE identification_number='$username'";
			$result = $db->query($query);
		}
		catch (PDOException $e) {
			$msg = "There an error, " .$e->getMessage() . "retrieving id from voters";
			echo  $msg;
			exit();
		}
		$id = $result->fetch(PDO::FETCH_ASSOC);
		return $id = $id['id'];
	}
	
    // 6b. Inserting feedback issues to the database
   function insert_issue($feedback) {
	global $db;
	$id = retrieve_voter_id();
   	// Populate feedback table with voter_id and issue
   	try {
   		$query = "INSERT INTO feedback";
   		$query .= "(voter_id, issues) ";
   		$query .= "VALUES('$id', ':feedback')";
   		$s = $db->prepare($query);
   		$s->bindValue(':feedback', $feedback);
   		$s->execute();
   	}
   	catch(PDOException $e) {
   		$msg = "There was an error, " .$e->getMessage() . "inserting issue into database";
   		echo $msg;
   		exit();
   	}
   }
   
   // 7a. Retrieving "votes categories" for voter's dashbaord
   
   function retrieve_vote_categories() {
   	global $db;
   	try {
   		$query = "SELECT * FROM vote ";
		$query .= "WHERE polling_status='0'";
   		$result = $db->query($query);
   	} 
   
   	catch (PDOException $e) {
   		$msg = "There was an error:" .$e->getMessage() . ", retrieving vote categories";
   		echo $msg;
   		exit();
   	}
   	return $result->fetchAll(PDO::FETCH_ASSOC);
   }
   
   // 7b. Per every category, retrieve all polls pertaining to that category
   function retrieve_polls($id) {
   	global $db;
   	try {
   		$query = "SELECT id, party, photo_name, candidate_name, propaganda ";
   		$query .= "FROM polls WHERE category_id='$id'";
   		$result = $db->query($query);
   	}
   	
   	catch(PDOException $e) {
   		$msg = "There was an error: " .$e->getMessage() ." retrieving polls";
   		echo $msg;
   		exit();
   	}
   	return $result->fetchAll(PDO::FETCH_ASSOC);
   }
   
//    // 8. Poll checker: to know if polls are available in the app --- not necessarily needed
//    function poll_checker() {
//    	global $db;
//    	try {
//    		$query = "SELECT COUNT(*) FROM polls";
//    		$result = $db->query($query);
//    	} 
//    	catch (PDOException $e) {
//    		$msg = "Error: " .$e->getMessage() . ", checking polls table";
//    		echo $msg;
//    		exit();
//    	}
//    	$count = $result->fetch(PDO::FETCH_ASSOC);
//    	if ($count['COUNT(*)'] === '0') return false;
//    	else {return true;}
//    }
   
   // 9. Vote Casting status checker
   function vote_casted_status() {
   	global $db;
   	$id = retrieve_voter_id();
   	try {
   		$query = "SELECT voting_status FROM vote_casted_status ";
   		$query .= "WHERE voter_id='$id'";
   		$result = $db->query($query);
   	} catch (PDOException $e) {
   		$msg = "Error: " .$e->getMessage() .", checking vote casted status";
   		echo $msg;
   		exit();
   	}
   	$status = $result->fetch(PDO::FETCH_ASSOC);
   	if ($status['voting_status'] === '0') return false;
   	else {return true;}
   }

   // 10. Vote Casting status changer
   function set_vote_casted() {
	   global $db;
	   $id = retrieve_voter_id();
	   try{
		   $query = "UPDATE vote_casted_status ";
		   $query .= "SET voting_status='1' ";
		   $query .= "WHERE voter_id='$id'";
		   $db->exec($query);
	   } catch (PDOException $e) {
		   $msg = "Error: " .$e->getMessage() . ", setting vote casted status";
		   echo $msg;
		   exit();
	   }
   }
   // 11. Vote Counter
   function vote_counter($poll_id) {
	   global $db;
	   try {
		   $query = "UPDATE polls_count ";
		   $query .= "SET count=count+1 ";
		   $query .= "WHERE poll_id='$poll_id'";
		   $result = $db->exec($query);
	   }
	   catch (PDOException $e) {
		   $msg = "Error: " .$e->getMessage() . ", while performing vote count";
		   echo $msg;
		   exit();
	   }
   }
?>