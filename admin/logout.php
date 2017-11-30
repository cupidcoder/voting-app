<?php 
include("../templates/admin/header.php");
include("../functions/fun.inc.php");
// Check if user is logged in
is_logged_in('admin_user');
?>
<?php 
// Kill the session
session_destroy();
?>
	
	<!-- Content section -->
	<section id="content">
		<div id="welcome_text">
			<h3>You have successfully logged out. <a href="login.php">Login?</a></h3>
		</div>
	</section>

<?php include("../templates/admin/footer.php"); ?>