<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Schedule Workout</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .btn{
            background-color: #161A30 !important;
            color: white;
        }
    </style>
</head>
<body>
<?php
    require "db_conn.php";
    session_start();
    

    if (isset($_SESSION['username'])) {
        $username = $_SESSION['username'];


        $selectToConfirm = "SELECT Count(user_info.username) AS Count FROM user_info INNER JOIN membership ON user_info.userID = membership.userID WHERE username='$username'";
        $resultToConfirm = mysqli_query($conn, $selectToConfirm);
        if ($resultToConfirm) {
            if (mysqli_num_rows($resultToConfirm) > 0) {
                $row = mysqli_fetch_assoc($resultToConfirm);
                    if($row['Count'] > 0) {
                        if (isset($_POST['btnSchedule'])) {
                            $workout = $_POST['workoutCategories'];
                            $dateOfWorkout = $_POST['dateOfWorkout'];
                            $start_time = $_POST['start_time'];
                            $end_time = $_POST['end_time'];
                    
                            // Validate if any of the fields is empty
                            if (empty($workout) || empty($dateOfWorkout) || empty($start_time) || empty($end_time)) {
                                echo "<script>alert('Please fill in all required fields.');</script>";
                                header("refresh:0 URL=schedule_workout.php");
                                exit(); // Stop further execution
                            }
                    
                            $select_userID_schedule = "SELECT userID FROM user_info WHERE username='$username'";
                            $resultUserID = mysqli_query($conn, $select_userID_schedule);
                    
                            if ($resultUserID) {
                                if (mysqli_num_rows($resultUserID) > 0) {
                                    $row = mysqli_fetch_assoc($resultUserID);
                                    $userID = $row['userID'];
                                }
                            }
                    
                            $insertSchedule = "INSERT INTO schedule (userID, workout, date_of_workout, start_time, end_time, date_of_creation) VALUES ($userID, '$workout', '$dateOfWorkout', '$start_time', '$end_time', NOW())";
                    
                            if (mysqli_query($conn, $insertSchedule)) {
                                echo "<script>alert('Your schedule has been recorded');</script>";
                                header("Location: schedule_history.php");
                            } else {
                                echo "<script>alert('Error in adding schedule');</script>";
                                header("refresh:0 URL=schedule_workout.php");
                            }
                        }
                    ?>
                    
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
                    
                        <div class="container mt-5">
                    
                            <h1>Schedule Workout</h1>
                    
                            <form action="" method="post">
                                <div class="form-group">
                                    <label for="workoutCategories">Select Workout Category:</label>
                                    <select class="form-control" id="workoutCategories" name="workoutCategories">
                                        <option value="chest">Chest</option>
                                        <option value="back">Back</option>
                                        <option value="legs">Legs</option>
                                        <option value="core">Core</option>
                                        <option value="biceps">Biceps</option>
                                        <option value="triceps">Triceps</option>
                                    </select>
                                </div>
                    
                                <div class="form-group">
                                    <label for="dateOfWorkout">Date of Workout: </label>
                                    <input type="date" class="form-control" name="dateOfWorkout" id="dateOfWorkout">
                                </div>
                    
                                <div class="form-group">
                                    <label for="start_time">Start time of workout: </label>
                                    <input type="time" class="form-control" name="start_time" id="start_time">
                                </div>
                    
                                <div class="form-group">
                                    <label for="end_time">End time of workout:</label>
                                    <input type="time" class="form-control" name="end_time" id="end_time">
                                </div>
                    
                                <button type="submit" class="btn" name="btnSchedule">Save Schedule</button>
                            </form>
                    
                        </div>
        <?php
                    }else {
                        header("Location: membership_form.php");
                    }
                }
            }

        ?>
    

    <!-- Include Bootstrap JS and Popper.js (optional but needed for some Bootstrap features) -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<?php
    }else {
         echo "You need to log in";
        header('Refresh:2; URL=index.html');
    }

?>
</body>
</html>
