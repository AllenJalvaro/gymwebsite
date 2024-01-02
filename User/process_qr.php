

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="path/to/sweetalert.css">
    <script src="path/to/sweetalert.min.js"></script>
    <title>QR checkin</title>
</head>
<body>
<?php
    require "db_conn.php";

    $decodedText = $_GET['decodedText'];

    $select_userID = mysqli_query($conn, "SELECT userID FROM user_info WHERE username='$decodedText'");
    if (mysqli_num_rows($select_userID) > 0) {
        $row = mysqli_fetch_assoc($select_userID);
        $userID = $row['userID'];
    }

        $insert_checkin_records = mysqli_query($conn, "INSERT INTO checkins (userID, username, date_checkin) VALUES ($userID, '$decodedText', NOW())");
        if ($insert_checkin_records) {
            ?>
            <script>
                swal("<?php echo $decodedText; ?> has been Checkin!", {
                icon: "success",
                });
            </script>
            <?php
            header("refresh:0; URL='show_list_checkin.php'");
        }
    //echo "Received QR: " . $decodedText . "YEHEEYYYYY";
?>
</body>
</html>
