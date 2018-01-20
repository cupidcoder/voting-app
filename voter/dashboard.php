<?php 
include("../templates/admin/header.php");
include("../includes/db.inc.php");
include("../templates/inside_header.php");
include("../functions/fun.inc.php");
include("../functions/db_fun.inc.php");

// Check if user is logged in
is_logged_in('voter');

// Retrieve photo and name from database
$username = $_SESSION['voter'];
$user_details = retrieve_voters($username);
?>
	
	<!-- Content section -->
	<section id="content">
		<div class="container">
			<div class="row">
			  <div class="col-sm-12">
			    <div class="thumbnail voter-picture-background">
			      <img src="../media/images/voters/<?php echo $user_details['photo_name']; ?>" class="img-responsive img-circle">
			      <div class="caption">
			        <h3 class="text-center">Welcome to your Dashboard, <strong><?php echo $user_details['firstname'];?></strong></h3>
			      </div>
			    </div>
			  </div>
			</div>
		</div>
	</section>

<?php include("../templates/footer.php"); ?>