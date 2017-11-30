<?php 
// General functions for the admin portal

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
	} else {redirect_url("login.php");}
}

// 4. General function for checking if user is logged in
function is_logged_in($sess_name){
	if (isset($_SESSION[$sess_name])){
		$username = $_SESSION[$sess_name];
	} else {redirect_url("login.php");}
}
?>