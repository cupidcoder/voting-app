<?php 
include("templates/admin/header.php"); 
include("includes/db.inc.php");		
include("templates/inside_header.php");
include("functions/fun.inc.php");
include("functions/db_fun.inc.php");

// Check if user is logged in
is_logged_in('voter');
?>


<?php 
// Retrieve $username/Voter's identification number from Session
$username = $_SESSION['voter'];

// Handle feedback form
if(isset($_POST['submit'])){
	$feedback = filter_var($_POST['feedback'], FILTER_SANITIZE_STRING);
	insert_issue($username, $feedback);
	$msg = "Thank you for your feedback";
}
?>

	<!-- Content section -->
	<section id="content">
		<div class="container">
			<form class="form-horizontal help-form" action="?" method="post">
			  <div class="form-group help-text">
			  	<h4 class="text-center">Please provide us with any issues you are experiencing so we can serve you better</h4>
			  </div>
			  <div class="form-group">
			    <label for="feedback" class="col-sm-2 control-label">Your feedback</label>
			    <div class="col-sm-10">
			      <textarea class="form-control" name="feedback" id="feedback" rows="5"></textarea>
			    </div>
			  </div>
			  <div class="form-group">
			    <div class="col-sm-offset-2 col-sm-10">
			      <button type="submit" name="submit" class="btn btn-default">Submit</button>
			    </div>
			  </div>
			  <?php if (isset($msg)){ ?>
			  	<div class="form-group help-text">
				  	<h6 class="text-center"><?php echo $msg;?></h6>
				 </div>
			  <?php }?>
			</form>
		</div>
	</section>

<?php include("templates/footer.php"); ?>