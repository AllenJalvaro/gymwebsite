<?php
include('db_conn.php');

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['userID'])) {
    $userID = $_POST['userID'];
    $fname = $_POST['fname'];
    $lname = $_POST['lname'];
    $sex = $_POST['sex'];
    $birthdate = $_POST['birthdate'];
    $phoneNumber = $_POST['phoneNumber'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    
    if ($password !== $confirm_password) {
        
        $error_message = "Passwords do not match. Please re-enter the password.";
        echo json_encode(array("success" => false, "message" => $error_message));
        exit();
    }

    
    $sql = "UPDATE user_info SET 
            fname = '$fname', 
            lname = '$lname', 
            sex = '$sex', 
            birthdate = '$birthdate', 
            phoneNumber = '$phoneNumber', 
            username = '$username', 
            password = '$password', 
            confirm_password = '$confirm_password' 
            WHERE userID = $userID";

    if ($conn->query($sql) === TRUE) {
        
        echo json_encode(array("success" => true, "message" => "User updated successfully"));
    } else {
        
        echo json_encode(array("success" => false, "message" => "Error updating user: " . $conn->error));
    }

    $conn->close();
}
?>
