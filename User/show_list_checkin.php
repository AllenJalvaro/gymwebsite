<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.css">
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.js"></script>
    <title>See List</title>
    <style>
    #example {
        width: calc(100% - 20px);
        border-collapse: collapse; /* Collapse the borders to create a more squared look */
        margin: 10px; /* Add margin for spacing outside the table */
    }

    #example th, #example td {
        padding: 10px; /* Add padding to cells for spacing inside the table */
        text-align: left; /* Adjust text alignment if needed */
        border: 1px solid #ddd; /* Add a border to cells for a more defined look */
    }
</style>

<table id="example" class="display">
    <!-- Your table content here -->
</table>

</head>
<body>
    <script>
        $(document).ready(function() {
            $('#example').DataTable({
                searching: false,
                lengthChange: false,
                info: false,
                paging: false,
            });
        });
    </script>

    <?php
        require 'db_conn.php';

    ?>

<table id="example" class="display">
    <thead>
        <tr>
            <th>Checkin ID</th>
            <th>Name</th>
            <th>Username</th>
            <th>Checkin Date and Time</th>
        </tr>
    </thead>
    <tbody>
    <?php
    $select_checkins = mysqli_query($conn, "SELECT checkins.checkinID, user_info.fname, user_info.lname, user_info.username, DATE_FORMAT(checkins.date_checkin, '%m-%d-%Y %h:%i %p') AS formatted_date FROM checkins INNER JOIN user_info ON checkins.userID=user_info.userID ORDER BY checkins.checkinID DESC;");

    if (mysqli_num_rows($select_checkins) > 0) {
        while ($row = mysqli_fetch_assoc($select_checkins)) {
?>
            <tr>
                <th><?php echo $row['checkinID'] ?></th>
                <th><?php echo $row['fname'] . " " . $row['lname']?></th>
                <th><?php echo $row['username'] ?></th>
                <th><?php echo $row['formatted_date'] ?></th>
            </tr>
<?php
        }
    }
?>


    </tbody>
</table>

</body>
</html>