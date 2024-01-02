<?php
require "db_conn.php";
if (isset($_POST['savechanges'])) {
    // Check if the form is submitted

    // Sanitize input data to prevent SQL injection
    $firstN = mysqli_real_escape_string($conn, $_POST['firstN']);
    $lastN = mysqli_real_escape_string($conn, $_POST['lastN']);
    $sex = mysqli_real_escape_string($conn, $_POST['sex']);
    $birthdate = mysqli_real_escape_string($conn, $_POST['bdate']);
    $phoneNumber = mysqli_real_escape_string($conn, $_POST['phone']);

    // Update the user_info table with the new values
    $update_query = "UPDATE user_info SET fname='$firstN', lname='$lastN', sex='$sex', birthdate='$birthdate', phoneNumber='$phoneNumber' WHERE username='$username'";
    
    if (mysqli_query($conn, $update_query)) {
        header("Refresh: 1 url=profile_view.php");
    } else {
        echo "Error updating record: " . mysqli_error($conn);
    }
}
?>