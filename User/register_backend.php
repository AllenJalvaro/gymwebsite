<?php
require "db_conn.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Filtering user input
    $fname = filter_input(INPUT_POST, 'fname', FILTER_SANITIZE_STRING);
    $lname = filter_input(INPUT_POST, 'lname', FILTER_SANITIZE_STRING);
    $gender = filter_input(INPUT_POST, 'gender', FILTER_SANITIZE_STRING);
    $rawBirthdate = $_POST['birthdate'];
    $contactNumber = filter_input(INPUT_POST, 'contactNum', FILTER_SANITIZE_STRING);
    $username = strtolower(filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING));
    $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);
    $passwordConfirm = filter_input(INPUT_POST, 'passwordConfirm', FILTER_SANITIZE_STRING);

    // Check if all required fields are filled
    if (
        !empty($fname) &&
        !empty($lname) &&
        !empty(trim($rawBirthdate)) &&
        !empty($contactNumber) &&
        !empty($username) &&
        !empty($password) &&
        !empty($passwordConfirm)
    ) {
        $birthdate = date('Y-m-d', strtotime($rawBirthdate));

        // Check if the username is already used
        $selectUsername = "SELECT username FROM user_info WHERE username='$username'";
        $resultSelect = mysqli_query($conn, $selectUsername);

        if ($resultSelect) {
            if (mysqli_num_rows($resultSelect) <= 0) {
                // Check if passwords match
                if ($password == $passwordConfirm) {
                    // Insert user record if all checks pass
                    $insertUserRecord = "INSERT INTO user_info (fname, lname, sex, birthdate, phoneNumber, username, password, confirm_password) VALUES ('$fname', '$lname', '$gender', '$birthdate', '$contactNumber', '$username', '$password', '$passwordConfirm')";
                    $result = mysqli_query($conn, $insertUserRecord);

                    if ($result) {
                        echo "<script>alert('Successful in registering');</script>";
                        header("refresh:0 URL=login.php");
                        exit();
                    } else {
                        echo "<script>alert('Error in inserting try again');</script>";
                    }
                } else {
                    echo "<script>alert('Password does not match');</script>";
                }
            } else {
                echo "<script>alert('Username Already Used');</script>";
            }
        } else {
            echo "<script>alert('Error in confirming USERNAME');</script>";
        }
    } else {
        echo "<script>alert('Fill all fields');</script>";
    }

    // If there are any errors, redirect back to the registration page
    header("refresh:0 URL=user_registration.php");
    exit();
}
?>
