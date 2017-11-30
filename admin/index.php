<?php 

// Include header
include("../templates/admin/header.php"); 

// Include function library
include("../functions/fun.inc.php");

// Check if admin is logged, if yes redirect to dashboard.php
is_admin_logged_in();

// ..if no, redirect to login.php
redirect_url("login.php");
 ?>