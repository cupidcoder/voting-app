<?php
include("../templates/admin/header.php"); 
include("../includes/db.inc.php");		
include("../templates/admin/inside_header.php");
include("../functions/fun.inc.php");
include("../functions/db_fun.inc.php");
// Check if user is logged in
is_logged_in('admin_user');
?>

<!-- Implementing the create vote feature -->
<?php 
	if (isset($_POST['submit'])) {
		
		// Get the inputs
		$category = ucfirst(strtolower(filter_var($_POST['category'], FILTER_SANITIZE_STRING)));
		$party = strtoupper(filter_var($_POST['party'], FILTER_SANITIZE_STRING));
		$candidate_name = ucfirst(strtolower(filter_var($_POST['candidate_name'], FILTER_SANITIZE_STRING)));
		$propaganda = ucfirst(strtolower(filter_var($_POST['propaganda'], FILTER_SANITIZE_STRING)));
		$year = filter_var($_POST['year'], FILTER_SANITIZE_NUMBER_INT);

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

		// Check if inputs were filled
		if ( empty($_POST['category']) || empty($_POST['party']) || empty($_POST['candidate_name']) || empty($_POST['propaganda']) || empty($_POST['year']) ) {
			$info[] = "Some fields were not filled, please fill allfields";
		}

		if (empty($info)) {
			$info[] = "Vote created successfully";

			// move the photo the media/images directory
			move_uploaded_file($photo_temp, "../media/images/candidates/" .$photo_name);

			// Insert data into the database
			create_vote($category, $party, $photo_name, $candidate_name, $propaganda, $year);
		}
	}
	
?>
	
	<!-- Content section -->
	<section id="content">
		<!-- div for error messages or success messages concerning creating a poll -->
		<div id="register_info"> <h3> <?php if (isset($info)) { 
			foreach ($info as $index) {
				echo "{$index} <br>";
			}
		}
		?></h3></div>
		<div class="container">
					<div id="register_form">
						<form class="form-horizontal" action="create.php" method="post" enctype="multipart/form-data">
							<div class="form-group">
								<label for="image" class="col-sm-2 control-label">Photo (Max: 320kb) </label>
								<div class="col-sm-4"><input class="form-control" type="file" name="image" id="image">
								</div>
							</div><div class="form-group">
								<label for="category" class="col-sm-2 control-label">Category </label>
								<div class="col-sm-10">
									<select class="form-control" name="category" id="category">
										<option value="Chairman" selected>Chairman</option>
										<option value="Secretary">Secretary</option>
										<option value="Councillor">Councillor</option>
										<option value="Treasurer">Treasurer</option>
									</select>
<!--									<input class="form-control" type="text" name="category" id="category" placeholder="enter poll category">-->
								</div>
							</div>
							<div class="form-group">
								<label for="party" class="col-sm-2 control-label">Party </label>
								<div class="col-sm-10"><input class="form-control" type="text" name="party" id="party" placeholder="enter name of political party">
								</div>
							</div>
							<div class="form-group">
								<label for="candidate" class="col-sm-2 control-label">Candidate name </label>
								<div class="col-sm-10"><input class="form-control" type="text" name="candidate_name" id="candidate" placeholder="enter candidate name">
								</div>
							</div>
							<div class="form-group">
								<label for="propaganda" class="col-sm-2 control-label">Propaganda </label>
								<div class="col-sm-10"><input class="form-control" type="text" name="propaganda" id="propaganda" placeholder="candidate's propaganda">
								</div>
							</div>
							<div class="form-group">
								<label for="year" class="col-sm-2 control-label">Year </label>
								<div class="col-sm-10"><input class="form-control" type="text" name="year" id="year" placeholder="polling year">
								</div>
							</div>	
							<div class="form-group">
							   <div class="col-sm-offset-11 col-sm-10">
							       <input type="submit" name = "submit" class="btn btn-default" value="create">
							   </div>
							</div>
						</form>
					</div>
			<!-- Section for created votes -->
				<hr>
				<h2 class="text-center">Created Votes</h2>
				<?php // Process retrieval of created votes
				    // Does admin want the votes archived?
				    if (isset($_GET['archive'])) {
						archive_all_votes();
					}// Then check if there are any votes
					if (empty(retrieve_votes())) {
						echo "<p class='text-center'>No currently active poll</p>";
					} else { ?>
						<div class="row">
							<div class="col-xs-12">
								<p class="text-center"><a href="?archive" class="btn btn-default">Archive all</a></p>
							</div>
						</div>
				<?php		$votes = retrieve_votes();
						for ($i=0; $i < count($votes); $i++) {
					
				?>	
							<div class="row">

								<div class="col-xs-2">
									<img src="../media/images/candidates/<?php echo $votes[$i]['photo_name']; ?>" class="img-responsive img-circle">
								</div>

								<div class="col-xs-2">
									<?php echo $votes[$i]['category']; ?>
								</div>
								<div class="col-xs-2">
									<?php echo $votes[$i]['party']; ?>
								</div>
								<div class="col-xs-2">
									<?php echo $votes[$i]['candidate_name']; ?>
								</div>
								<div class="col-xs-2">
									<?php echo $votes[$i]['propaganda']; ?>
								</div>
							</div>
							<br>
				<?php				
					}
				}
				?>
		</div>	<!-- End of Container class-->
		<!-- End of placeholder for retrieving created votes from the database -->
	</section>