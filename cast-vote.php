<?php 
include("templates/admin/header.php"); 
include("includes/db.inc.php");		
include("templates/inside_header.php");
include("functions/fun.inc.php");

// Check if user is logged in
is_logged_in('voter');
?>
	
	<!-- Content section -->
	<section id="content">
		
	</section>

<?php include("templates/footer.php"); ?>