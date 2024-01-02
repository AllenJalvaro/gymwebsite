<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Training Guide</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>

<?php
include('db_conn.php');

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "DELETE FROM training WHERE id=$id";

    if ($conn->query($sql) === TRUE) {
        echo '<script>
            Swal.fire({
              title: "Are you sure?",
              text: "You won\'t be able to revert this!",
              icon: "warning",
              showCancelButton: true,
              confirmButtonColor: "#3085d6",
              cancelButtonColor: "#d33",
              confirmButtonText: "Yes, delete it!"
            }).then((result) => {
              if (result.isConfirmed) {
                Swal.fire({
                  title: "Deleted!",
                  text: "It has been deleted",
                  icon: "fa fa-sign-out", // Change the icon class
                  confirmButtonColor: "#3085d6",
                  confirmButtonText: "OK"
                }).then(() => {
                  window.location.href = "training_guide.php";
                  header("Refresh: 2, url=training_guide.php");
                });
              }
            });
        </script>';
    } else {
        echo "Error deleting record: " . $conn->error;
    }
} else {
    header("Location: training_guide.php");
    exit();
}

$conn->close();
?>

</body>
</html>
