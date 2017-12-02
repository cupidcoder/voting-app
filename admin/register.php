<?php 
include("../templates/admin/header.php"); 
include("../includes/db.inc.php");		
include("../templates/admin/inside_header.php");
include("../functions/fun.inc.php");
include("../functions/db_fun.inc.php");
	 
	// Check if admin is logged in
	is_logged_in('admin_user');
?>

<?php // Implementing registeration
	if (isset($_FILES['image']) && isset($_POST['lastname']) && isset($_POST['firstname']) && isset($_POST['dob']) && isset($_POST['email']) && isset($_POST['street_address']) && isset($_POST['city']))	{
		
		// initialise empty array for errors/message
		$info = array();

		// sanitize inputs
		$lastname = ucfirst(strtolower(filter_var($_POST['lastname'], FILTER_SANITIZE_STRING)));
		$firstname = ucfirst(strtolower(filter_var($_POST['firstname'], FILTER_SANITIZE_STRING)));
		$dob = filter_var($_POST['dob'], FILTER_SANITIZE_STRING);
		$email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
		$street_address = filter_var($_POST['street_address'], FILTER_SANITIZE_STRING);
		$city = filter_var($_POST['city'], FILTER_SANITIZE_STRING);


		// Allowed extensions 
		$extensions = array("jpeg", "jpg", "png");
		
		// Getting the image
		$photo_name = $_FILES['image']['name'];
		$photo_size = $_FILES['image']['size'];
		$photo_temp = $_FILES['image']['tmp_name'];
		$photo_type = $_FILES['image']['type'];

		// Get the file extension and make it a lowercase
		$photo_ext = strtolower(end((explode(".", $photo_name))));

		// Validate the extension of the provided file
		if (!(in_array($photo_ext, $extensions))) {
			// add to the $info array
			$info[] = "Extension not allowed, please upload a JPEG, JPG or PNG file format";
		}

		// Validate size of file
		if ($photo_size > 320000) {
			$info[] = "Photo is too large, maximum size allowed is 320kb";
		}

		if (empty($info)) {
			$info[] = "Registration successful, check your inbox for further instructions";

			// move the photo the media/images directory
			move_uploaded_file($photo_temp, "../media/images/" .$photo_name);

			// Insert data into the database
			register_voter($photo_name,$lastname, $firstname, $dob, $email, $street_address, $city);

			// Send new voter email with a link to generate the username and password automatically
			// code goes here
		}
	}	
?>
	
	<!-- Content section -->
	<section id="content">
		<!-- div for error messages or success messages concerning registering -->
		<div id="register_info"> <h2> <?php if (isset($info)) { 
			foreach ($info as $index) {
				echo "{$index} <br>";
			}
		}
		?></h2></div>
		<div class="container">
			<div class="row">
				<div class="col-md-12">
					<div id="register_form">
						<form class="form-horizontal" action="register.php" method="post" enctype="multipart/form-data">
							<div class="form-group">
								<label for="image" class="col-sm-2 control-label">Photo (Max: 320kb) </label>
								<div class="col-sm-4"><input class="form-control" type="file" name="image" id="image">
								</div>
							</div><div class="form-group">
								<label for="lastname" class="col-sm-2 control-label">Lastname </label>
								<div class="col-sm-10"><input class="form-control" type="text" name="lastname" id="lastname" placeholder="enter lastname">
								</div>
							</div>
							<div class="form-group">
								<label for="firstname" class="col-sm-2 control-label">Firstname </label>
								<div class="col-sm-10"><input class="form-control" type="text" name="firstname" id="firstname" placeholder="enter firstname">
								</div>
							</div>
							<div class="form-group">
								<label for="dob" class="col-sm-2 control-label">Date of birth </label>
								<div class="col-sm-10"><input class="form-control" type="text" name="dob" id="dob" placeholder="yyyy/mm/dd">
								</div>
							</div>
							<div class="form-group">
								<label for="email" class="col-sm-2 control-label">Email </label>
								<div class="col-sm-10"><input class="form-control" type="email" name="email" id="email" placeholder="enter email">
								</div>
							</div>
							<div class="form-group">
								<label for="street_address" class="col-sm-2 control-label">Street Address </label>
								<div class="col-sm-10"><input class="form-control" type="text" name="street_address" id="street_address" placeholder="enter address">
								</div>
							</div>
							<div class="form-group">
								<label for="city" class="col-sm-2 control-label">City </label>
								<div class="col-sm-10"><input class="form-control" type="text" name="city" id="city" placeholder="enter city">
								</div>
							</div>							
							<div class="form-group">
							   <div class="col-sm-offset-11 col-sm-10">
							       <button type="submit" class="btn btn-default">register</button>
							    </div>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</section>

<?php include("../templates/admin/footer.php"); ?>