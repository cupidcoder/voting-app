<?php 
include("../templates/admin/header.php");
include("../includes/db.inc.php");
include("../functions/fun.inc.php");

/* After a voter has clicked on the verification link sent to their email address, 
 * the user is directed here. 
 * This file processes the generation of a VIT (Voter's identification number and password)
 * After the instructions have been followed successfully and VIT and password generated, the verification status is changed to 1 (true)
 * Populate the verification table with the voter id and verification status
 */

if (isset($_GET['id']) && isset($_GET['fname']) && isset($_GET['lname'])) // check whether the variables were sent with the url
{  
	$id = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);
	$firstname = filter_var($_GET['fname'], FILTER_SANITIZE_STRING);
	$lastname = filter_var($_GET['lname'], FILTER_SANITIZE_STRING);
	
	
	
	// Use the variables to generate Voter's identification number using (firstname, lastname & lastname)
	$vin = strtoupper(substr($firstname, 0, 2) . substr($lastname, 0, 2) . date("dmy"));
	
	// Generate 6 digit password 
	$pass = pass_generator(6);
	$salt1 = "xoxo";
	$salt2 = "apha";
	$hashed_password = hash( 'ripemd128', $salt1 . $pass . $salt2 );
	
	// Change the verification status in the DB to 1
	try 
	{
		$query = "UPDATE verification ";
		$query .= "SET verified='1' ";
		$query .= "WHERE voter_id='$id'";
		$db->exec($query);
	} 
	catch (PDOException $e) 
	{
		$msg = "There was an error with verification query with error: " .$e->getMessage();
		echo $msg;
		exit();
	}
	
	// Then populate the DB with VIN and password
	try
	{
		$query = "UPDATE voters ";
		$query .= "SET identification_number='$vin', password='$hashed_password' ";
		$query .= "WHERE id='$id'";
		$db->exec($query);
	}
	catch (PDOException $e)
	{
		$msg = "There was an error with verification query with error: " .$e->getMessage();
		echo $msg;
		exit();
	}
	
	
	// Finally output the VIN and password to the user	
}
?>
	<div class="container">
		<h3 class="text-center">Please find your <strong>login details</strong> below:</h3>
		<div class="row">
			<div class="col-md-12 login_info">
				<p>Voter Identification Number<strong>(V.I.N)</strong>: <span><strong><?php echo $vin;?></strong></span></p>
				<p>Password: <span><strong><?php echo $pass;?></strong></span></p>
			</div>
			<div class="col-md-12">
				<p class="text-center"><strong>Ensure you keep your login details safe at all times</strong></p>
			</div>
		</div>
	</div>




<?php include("../templates/admin/footer.php"); ?>