<?php
include('db_conn.php');

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['userID'])) {
    $userID = $_POST['userID'];

    $sql = "DELETE FROM user_info WHERE userID = $userID";

    if ($conn->query($sql) === TRUE) {
        $response = array("success" => true, "message" => "User deleted successfully");
    } else {
        $response = array("success" => false, "message" => "Error deleting user: " . $conn->error);
    }

    echo json_encode($response);
}

$conn->close();
?>
