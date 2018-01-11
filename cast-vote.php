<?php 
include("templates/admin/header.php"); 
include("includes/db.inc.php");		
include("templates/inside_header.php");
include("functions/fun.inc.php");
include("functions/db_fun.inc.php");

// Check if user is logged in
is_logged_in('voter');

/*
 * First check if there are any votes in the database, if there are votes;
 * ... then check vote_casted_status if voter has already casted vote, if so, echo "vote already received"
 * ... else, retrieve votes so voting can be carried out 
 *  Each poll category would be recorded to the DB using AJAX
 */
?>

	<!-- Content section -->
	<section id="content">
		<?php 
		if (empty(retrieve_vote_categories())) { ?>
			<!-- When there are no votes -->
				<div id="welcome_text">
		 			<h3>No Polling activity currently available</h3>
				</div> -->
		<?php
			}
		?>
		
		<?php 
		if (!empty(retrieve_vote_categories()) && vote_casted_status()) { ?>
			<!-- When there are votes but voter has already casted vote -->
		 		<div id="welcome_text">
					<h3>Your vote has already been received, please check back during next polling period</h3>
		 		</div>
		<?php
			}
		?>
		
		<!-- When there are votes and user is yet to cast vote, display votes -->
		<?php 
		if (!empty(retrieve_vote_categories()) && !vote_casted_status()) { ?>
				
				<div class="container">
					<h3 class="text-center">Cast Your Vote Below</h3>
				<?php 
					$vote_categories = retrieve_vote_categories(); // Retrieve vote categories from DB
					forEach($vote_categories as $index => $poll_category) { ?>
					<div class="row">
						<div class="col-md-12">
							<table class="table table-hover">
							<caption><strong><?php echo $poll_category['category'];?></strong></caption>
								<thead>
									<tr>
		  								<th></th>
		  								<th><strong>Photo</strong></th>
		  								<th><strong>Party</strong></th>
		  								<th><strong>Candidate Name</strong></th>
		  								<th><strong>Propaganda</strong></th>
		  								<th><strong>Your choice</strong></th>
		  							</tr>
		  						</thead>
					<?php 
						$polls = retrieve_polls($poll_category['id']);
						foreach ($polls as $index => $poll) { ?>
								<tbody>
									<tr>
										<td><input type="hidden" name="poll_id" value="<?php echo $poll['id'];?>"></td>
										<td>
											<img height="150px" width="150px" class="img-responsive img-circle" alt="candidate photo" src="media/images/candidates/<?php echo $poll['photo_name'];?>">
										</td>
										<td><?php echo $poll['party'];?></td>
										<td><?php echo $poll['candidate_name'];?></td>
										<td><?php echo $poll['propaganda'];?></td>
										<td>
											<input type="radio" name="choice">
										</td>
									</tr>
								</tbody>				
					<?php 
						}
					?>
						</table>
					</div>
				</div>
				<?php		
					}
				?>
				
				</div> <!-- End of Container -->
		<?php 
			}
		?>
	</section>

<?php include("templates/footer.php"); ?>