<?php 
include("templates/admin/header.php"); 
include("includes/db.inc.php");		
include("templates/inside_header.php");
include("functions/fun.inc.php");
include("functions/db_fun.inc.php");

// Check if user is logged in
is_logged_in('voter');
?>
	
	<!-- Content section -->
	<section id="content">
		<div class="container">
			<!--First retrieve all active votes-->
			<?php
			// If there are any active polls
			if (retrieve_vote_categories()) {
				$vote_categories = retrieve_vote_categories();
				foreach ($vote_categories as $index => $vote) {
					?>
					<!--Now use each vote category to retrieve results for the category-->
					<table class="table table-striped">
						<caption class="text-center"><?php echo $vote['category']; ?>(<?php echo $vote['year']; ?>)
						</caption>
						<thead>
						<tr>
							<th>Party</th>
							<th>Photo</th>
							<th>Candidate Name</th>
							<th>Votes</th>
						</tr>
						</thead>
						<?php
						$results = retrieve_results_per_category($vote['id']);
						foreach ($results as $index => $result) { ?>
							<tbody>
							<tr>
								<td><?php echo $result['party']; ?></td>
								<td>
									<img height="150px" width="150px" class="img-responsive img-circle"
										 alt="candidate photo"
										 src="media/images/candidates/<?php echo $result['photo_name']; ?>">
								</td>
								<td><?php echo $result['candidate_name']; ?></td>
								<td><?php echo $result['count']; ?></td>
							</tr>
							</tbody>
						<?php }
						?>
					</table>
				<?php }
			}
			else {?>
				<!-- When there are no current active polls -->
				<div id="welcome_text">
					<h3>No Polling activity currently available</h3>
				</div>
			<?php }
			?>
		</div>
	</section>

<?php include("templates/footer.php"); ?>