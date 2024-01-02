<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Schedule Workout</title>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.22/css/jquery.dataTables.css">

    <style>
        #scheduleTable_wrapper {
            max-width: 80%;
            margin: 0 auto;
            margin-top: 20px;
        }

        table {
        border-collapse: collapse;
        width: 100%;
        }

        th, td {
        border: 1px solid #ddd;
        padding: 8px;
        text-align: left;
        }

        th {
        background-color: #161A30;
        color: #F0ECE5;
        }
    </style>
</head>

<body>
<script>
        $(document).ready(function() {
            $('#scheduleTable').DataTable({
                searching: false,
                lengthChange: false,
                info: false,
                paging: false,
            });
            
        });
    </script>
<?php
    require "db_conn.php";
    session_start();
    

    if (isset($_SESSION['username'])) {
        $username = $_SESSION['username'];

        $select_userID_schedule = "SELECT userID FROM user_info WHERE username='$username'";
        $resultUserID = mysqli_query($conn, $select_userID_schedule);
        if ($resultUserID) {
            if (mysqli_num_rows($resultUserID) > 0) {
                $row = mysqli_fetch_assoc($resultUserID);
                $userID = $row['userID'];
            }
        }
    
        $select_user_schedule_history = "SELECT schedule.workout, schedule.date_of_workout, schedule.start_time, schedule.end_time, schedule.status FROM schedule INNER JOIN user_info ON user_info.userID=schedule.userID WHERE user_info.userID=$userID ORDER BY schedule.schedule_id DESC";
        $result_user_schedule_history = mysqli_query($conn, $select_user_schedule_history);
        if ($result_user_schedule_history) {
            if (mysqli_num_rows($result_user_schedule_history) > 0) {
                

        // mysqli_close($conn);
?>
    <script>
        $(document).ready(function() {
            $('#scheduleTable').DataTable();
        });
    </script>

    <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="new_home_screen.php">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="schedule_workout.php">Schedule Workout</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="schedule_history.php">Schedule History</a>
                </li>
                
            </ul>
        </div>
    </nav>

    <table id="scheduleTable" class="display">
            <thead>
                <tr>
                    <th>Workout Category</th>
                    <th>Date of Workout</th>
                    <th>Start Time</th>
                    <th>End Time</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Loop through the fetched data and display it in the table
                while ($row = mysqli_fetch_assoc($result_user_schedule_history)) {
                    echo "<tr>";
                    echo "<td>" . $row['workout'] . "</td>";
                    echo "<td>" . $row['date_of_workout'] . "</td>";
                    echo "<td>" . $row['start_time'] . "</td>";
                    echo "<td>" . $row['end_time'] . "</td>";
                    echo "<td>" . $row['status'] . "</td>";
                    echo "</tr>";
                }
            }
        }
                ?>
            </tbody>
        </table>


    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.22/js/jquery.dataTables.js"></script>
    
<?php
    }else {
         echo "You need to log in";
        header('Refresh:2; URL=index.html');
    }

?>

</body>
</html>
