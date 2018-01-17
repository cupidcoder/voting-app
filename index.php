<?php 

// Include header
include("templates/header.php"); 

// Include function library
include("functions/fun.inc.php");

// Check if voter is logged in, if yes redirect to dashboard.php
is_voter_logged_in();

// ..if no, redirect to login.php
redirect_url("login.php");