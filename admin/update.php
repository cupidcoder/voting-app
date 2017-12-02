<?php 
include("../templates/admin/header.php"); 
include("../includes/db.inc.php");		
include("../templates/admin/inside_header.php");
include("../functions/fun.inc.php");
// Check if user is logged in
is_logged_in('admin_user');
?>
	
	<!-- Content section -->
	<section id="content">
		<div id="welcome_text">
			<h3>Updating of voter's login credentials coming soon</h3>
		</div>
	</section>

<?php include("../templates/admin/footer.php"); ?>