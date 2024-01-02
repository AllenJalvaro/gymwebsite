<?php
include('db_conn.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $title = $_POST['title'];
    $procedure = $_POST['procedure'];
    $steps = $_POST['steps'];
    $reps = $_POST['reps'];
    $

    $sql = "UPDATE training SET title='$title',`procedure`='$procedure', steps='$steps', reps='$reps' WHERE id=$id";

    if ($conn->query($sql) === TRUE) {
        header("Location: training_guide.php");
        exit();
    } else {
        echo "Error updating record: " . $conn->error;
    }
}

$conn->close();
?>
