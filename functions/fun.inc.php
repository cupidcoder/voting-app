<?php 
// General functions for the admin portal
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require($_SERVER['DOCUMENT_ROOT'] . '/voting-app/includes/mailer/Exception.php');
require($_SERVER['DOCUMENT_ROOT'] . '/voting-app/includes/mailer/PHPMailer.php');
require($_SERVER['DOCUMENT_ROOT'] . '/voting-app/includes/mailer/SMTP.php');

// 1. Function for redirecting
function redirect_url($url){
	header("Location: $url");
}

// 2. Function for checking if admin is logged in
function is_admin_logged_in(){
	if (isset($_SESSION['admin_user'])){
		$username = $_SESSION['admin_user'];
		redirect_url("dashboard.php");
	}
}

// 3. Function for checking if voter is logged in
function is_voter_logged_in(){
	if (isset($_SESSION['voter'])){
		$username = $_SESSION['voter'];
		redirect_url("dashboard.php");
	}
}

// 4. General function for checking if user is logged in
function is_logged_in($sess_name){
	if (isset($_SESSION[$sess_name])){
		$username = $_SESSION[$sess_name];
	} else {redirect_url("login.php");}
}

// 5. Function for sending new user verification email
function send_new_mail($id,$email_address, $firstname, $lastname) {
	$mail = new PHPMailer(true);
		try {
		//Server settings
		//$mail->SMTPDebug = 2;                                 // Enable verbose debug output
		$mail->isSMTP();                                    // Set mailer to use SMTP
		$mail->Host = 'chukume.name.ng';  // Specify main and backup SMTP servers
		$mail->SMTPAuth = true;                               // Enable SMTP authentication
		$mail->Username = 'no-reply@voting-app.chukume.name.ng';                 // SMTP username
		$mail->Password = '#(tV{Uu{)W=&';                           // SMTP password
		$mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
		$mail->Port = 587;                                    // TCP port to connect to /465, 25
		
		//Recipients
		$mail->setFrom('no-reply@voting-app.chukume.name.ng', 'E-Voting App');
		$mail->addAddress($email_address, $firstname, $lastname);     // Add a recipient
		//$mail->addAddress('ellen@example.com');               // Name is optional
		//$mail->addReplyTo('info@example.com', 'Information');
		//$mail->addCC('cc@example.com');
		//$mail->addBCC('bcc@example.com');
		
		//Attachments
		//$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
		//$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name
		
		$link = "http://voting-app.chukume.name.ng/admin/verify.php?id=$id&fname=$firstname&lname=$lastname";

		//Content
		$mail->isHTML(true);                                  // Set email format to HTML
		$mail->Subject = 'Verify Your Account';
		$mail->Body    = "Hello $firstname, kindly visit the link below to complete your registration.<br>";
		$mail->Body   .= "<strong>Please open link in a browser</strong><br><br>";
		$mail->Body   .= "<a href='$link'>Verify your account</a><br><br>";
		$mail->Body   .= "<i>Ensure you secure your Voter Identification Number and Password at all times</i>";
		//$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
		
		$mail->send();
		//echo 'Message has been sent';
	} 
	
	catch (Exception $e) {
		echo 'Message could not be sent.';
		echo 'Mailer Error: ' . $mail->ErrorInfo;
	}
}

// 6. Generating password for Users
function pass_generator($length = 32) {
	$randstr = "";
	srand((double) microtime(TRUE) * 1000000);
	//our array add all letters and numbers if you wish
	$chars = array(
			'a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'p',
			'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z', '1', '2', '3', '4', '5',
			'6', '7', '8', '9', 'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K',
			'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z');
	
	for ($rand = 0; $rand <= $length; $rand++) {
		$random = rand(0, count($chars) - 1);
		$randstr .= $chars[$random];
	}
	return $randstr;
}

?>