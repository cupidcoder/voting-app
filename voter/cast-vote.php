<?php 
include("../templates/admin/header.php");
include("../includes/db.inc.php");
include("../templates/inside_header.php");
include("../functions/fun.inc.php");
include("../functions/db_fun.inc.php");

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
				</div>
		<?php
			}
		?>
		
		<?php 
		if (!empty(retrieve_vote_categories()) && vote_casted_status()) { ?>
			<!-- When there are votes but voter has already casted vote -->
		 		<div id="welcome_text">
					<h3>Your vote has been received, please check back during next polling period</h3>
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
                    <form action="cast-vote.php" method="post">
					<div class="row">
						<div class="col-md-12">
							<table class="table table-hover">
							<caption class="text-center"><strong><?php echo $poll_category['category'];?></strong>(<?php echo $poll_category['year'];?>)</caption>
								<thead>
									<tr>
		  								<th><strong>Photo</strong></th>
		  								<th><strong>Party</strong></th>
		  								<th><strong>Candidate Name</strong></th>
		  								<th><strong>Propaganda</strong></th>
		  								<th><strong>Your choice</strong></th>
		  							</tr>
		  						</thead>
					<?php 
						$polls = retrieve_polls($poll_category['id']);
						foreach ($polls as $index_inner => $poll) { ?>
								<tbody>
									<tr>
										<!--<td><input type="hidden" name="poll_id" value="<?php /*echo $poll['id'];*/?>"></td>-->
										<td>
											<img height="150px" width="150px" class="img-responsive img-circle" alt="candidate photo" src="media/images/candidates/<?php echo $poll['photo_name'];?>">
										</td>
										<td><?php echo $poll['party'];?></td>
										<td><?php echo $poll['candidate_name'];?></td>
										<td><?php echo $poll['propaganda'];?></td>
										<td>
											<input type="radio" name="<?php echo $poll_category['category'];?>" value="<?php echo $poll['id'];?>">
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
                        <input type="submit" value="Finish">
                    </form>
                    <div class="info-text">
                        <small><i>Please ensure you make all your selections before clicking 'finish'. Once submitted, your selections cannot be modified</i></small>
                    </div>
				</div> <!-- End of Container -->
		<?php 
			}
		?>
	</section>

    <!-- Vote counter is Handled here -->
<?php
if (isset($_POST)) {
    // Go through all items in the array and record each selection
    foreach($_POST as $category => $poll_id) {
        vote_counter($poll_id);
    }

    // Then change vote_casted_status for voter to 1
    set_vote_casted();
}
?>
<?php include("../templates/footer.php"); ?>