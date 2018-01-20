<?php 
include("../templates/header.php");
include("../includes/db.inc.php");
include("../functions/fun.inc.php");
include("../functions/db_fun.inc.php");

// check if admin is logged in
is_voter_logged_in();
?>

<?php // Implementing login
if (isset($_POST['vin']) && isset($_POST['pass'])) {

	// sanitize the inputs
	$vin = filter_var($_POST['vin'], FILTER_SANITIZE_STRING);
	$pass = filter_var($_POST['pass'], FILTER_SANITIZE_STRING);
	
	// hashing
	$salt1 = "xoxo";
	$salt2 = "apha";
	$hashed_password = hash( 'ripemd128', $salt1 . $pass . $salt2 );
	
	// check if username and password exist in database

	$login = login_checker("voters", $vin, $hashed_password, 'identification_number');
	if ($login['COUNT(identification_number)'] == 1 && $login['COUNT(password)'] == 1)
	{
		// Store admin to the session
		$_SESSION['voter'] = $vin;

		// redirect to dashboard
		redirect_url("dashboard.php");
	}
	else {
		// Keep the person on a long thing
	}
}
?>
	<!--	Landing page area	-->
	<section id="home">
		<div id="home-content" class="text-center">
			<h2>Access Voting Portal Below</h2>
			<div id="home-content-icon">
				<a href="#login-area"><i class="fa fa-arrow-circle-down fa-3x"></i></a>
			</div>
		</div>
	</section>
	<section id="login-area">
		<!--	Login Form	-->
		<div id="login-access"> 
			<form id="login-form" method="post">
				<div>
					<div> <i class="fa fa-lock fa-4x"></i></div>
					<div class="form-element"> <label for="identification-number"><i class="fa fa-user-circle fa-3x"></i></label> <input type="text" name="vin" id="identification-number" placeholder="Voter Identification number"> </div>
					<div class="form-element"> <label for="pass"><i class="fa fa-key fa-3x"></i></label> <input type="password" name="pass" id="pass" placeholder="Password">  </div>
					<div id="login-btn"> <button class="btn btn-default" type="submit" value="Login">Login <i class="fa fa-arrow-circle-right"></i> </button></div>
				</div>
<!--					<div id="login-form-hint"> <p> <sup>*</sup> Yet to register on the Portal?</p> </div>-->
			</form>
		</div>
	</section>
<?php include("../templates/footer.php"); ?>