<?php
include("../includes/db.inc.php");
include("../functions/fun.inc.php");

/* Check if the email address is in the database
* if it is, generate new password and forward to the email address.
* if it is not, return false to the AJAX call
*/

// Check if email address is in the biodata table

if (isset($_GET['email'])) {
    $email_address = filter_var($_GET['email'], FILTER_SANITIZE_EMAIL);

    try {
        $query = "SELECT COUNT(email_address), voter_id, firstname ";
        $query .= "FROM biodata WHERE email_address='$email_address'";
        $result = $db->query($query);
    } catch (PDOException $e) {
        $msg = "Error: " .$e->getMessage() . ", attempting to check DB for email address";
        echo $msg;
        exit();
    }
    $info=$result->fetch(PDO::FETCH_ASSOC);

    if ($info['COUNT(email_address)'] == '0') {
        // Return false to the AJAX call
        echo "Email address not found!";
        exit();
    }

    if ($info['COUNT(email_address)'] == '1') {
        // Generate a new password
        $salt1 = "xoxo";
        $salt2 = "apha";
        $pass = pass_generator(6);
        $hashed_password = hash('ripemd128', $salt1 . $pass . $salt2 );

        $id = $info['voter_id'];
        $firstname = $info['firstname'];


        // save the password in the database
        try {
            $query = "UPDATE voters ";
            $query .= "SET password ='$hashed_password' ";
            $query .= "WHERE id='$id'";
            $db->exec($query);
        } catch (PDOException $e) {
            $msg = "Error: " .$e->getMessage() .", while setting new password";
            echo $msg;
            exit();
        }

        // forward new password to the email address
        send_new_password($email_address, $firstname, $pass);

        // Return true to the AJAX call
        echo "Please check your inbox for your new password";
    }
}


