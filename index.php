<?php include("templates/header.php"); ?>
	<!--	Landing page area	-->
	<section id="home">
		<div id="home-content" class="text-center">
			<h2>Access Voting Portal Below</h2>
			<div id="home-content-icon">
				<a href="#login-area"><i class="fa fa-arrow-circle-down fa-3x"></i></a>
			</div>
		</div>
	</section>
	<section id="login-area">
		<!--	Login Form	-->
		<div id="login-access"> 
			<form id="login-form">
				<div>
					<div> <i class="fa fa-lock fa-4x"></i></div>
					<div class="form-element"> <label for="identification-number"><i class="fa fa-user-circle fa-3x"></i></label> <input type="number" id="identification-number" placeholder="Voter Identification number"> </div>
					<div class="form-element"> <label for="pass"><i class="fa fa-key fa-3x"></i></label> <input type="password" id="pass" placeholder="Password">  </div>
					<div id="login-btn"> <button class="btn btn-default" type="button" value="Login">Login <i class="fa fa-arrow-circle-right"></i> </button></div>
				</div>
<!--					<div id="login-form-hint"> <p> <sup>*</sup> Yet to register on the Portal?</p> </div>-->
			</form>
		</div>
	</section>
<?php include("templates/footer.php"); ?>