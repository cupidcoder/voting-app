/*
    First check if the inputted email address is valid,
    if valid, proceed with AJAX call to the pwreset.php
    if not valid, output "please enter a valid email address"
 */

// Function for AJAX call
function sendEmail(emailInput) {
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            document.getElementById('forgot-pass-info').innerHTML = this.responseText;
        }
    };
    xhttp.open("GET", "../admin/pwreset.php?email=" + emailInput, true);
    xhttp.send();
}

document.getElementById('email-reset-btn').addEventListener('click', function() {
   var emailInput = document.getElementById('email').value;
    // Check if the email address inputted is valid
    if (emailInput.indexOf('@') > 0){
        // Proceed with AJAX call
        sendEmail(emailInput);
    } else {
        document.getElementById('forgot-pass-info').innerHTML = "Please enter a valid email address";
    }
    // Clear input field
    document.getElementById('email').value = "";
});