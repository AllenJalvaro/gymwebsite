<?php
    require "db_conn.php";
    session_start();
    

    if (isset($_SESSION['username'])) {
        $username = $_SESSION['username'];
        echo "WELCOME " . $username;
?>
<?php
//////MAIN OR BODY DISPLAY
    $selectMemberInfo = "SELECT user_info.username, membership.membership_type, membership.membership_fee FROM user_info INNER JOIN membership ON user_info.userID = membership.userID WHERE username='$username';";

    $resultMemberInfo = mysqli_query($conn, $selectMemberInfo);
    if ($resultMemberInfo) {
        if (mysqli_num_rows($resultMemberInfo) > 0) {
                $row = mysqli_fetch_assoc($resultMemberInfo);
                $type = $row['membership_type'];
                $fee = $row['membership_fee'];
                echo "You are a member $username <br>";
                echo "Your membership type is $type <br>";
                echo "Your membership fee is $fee <br>";
        }
    }

    $selectSchedule = "SELECT * FROM schedule INNER JOIN user_info ON schedule.userID=user_info.userID WHERE username = '$username'";
    $resultSchedule = mysqli_query($conn, $selectSchedule);
    if ($resultSchedule) {
        if (mysqli_num_rows($resultSchedule) > 0) {
            echo "<h2>Work out schedule</h2>";
            while ($row = mysqli_fetch_assoc($resultSchedule)) {
                echo "Your workout is " . $row['workout'] . ". On " . $row['date_of_workout'] . ". From " . $row['start_time'] . " to " . $row['end_time'] . "<br>";
            }
            
            
        }
    }

////Sched

?>

<?php
//////MEMBERSHIP FORM
    if (isset($_POST['btnMembership'])) {
            
        $membership_type = $_POST['membershipType'];
        $start_date = $_POST['start_date'];
        $end_date = $_POST['end_date'];

        $selectToConfirm = "SELECT Count(user_info.username) AS Count FROM user_info INNER JOIN membership ON user_info.userID = membership.userID WHERE username='$username'";
        $resultToConfirm = mysqli_query($conn, $selectToConfirm);
        if ($resultToConfirm) {
            if (mysqli_num_rows($resultToConfirm) > 0) {
                $row = mysqli_fetch_assoc($resultToConfirm);
                    if($row['Count'] > 0) {
                        echo "You are a member!";
                    }else {
                        $select_userID = "SELECT userID FROM user_info WHERE username='$username'";
                        $resultUserID = mysqli_query($conn, $select_userID);
                        if ($resultUserID) {
                            if (mysqli_num_rows($resultUserID) > 0) {
                                $row = mysqli_fetch_assoc($resultUserID);
                                $userID = $row['userID'];
                            }
                        }
                        ////membershipfee
                        if ($membership_type == 'semi-monthly') {
                            $membership_fee = 150;
                        } elseif ($membership_type == 'monthly') {
                            $membership_fee = 300;
                        }elseif ($membership_type == 'annual') {
                            $membership_fee = 3000;
                        }

                        $insertMembership = "INSERT INTO membership (userID, start_date, end_date, membership_type, membership_fee) VALUES ($userID, '$start_date', '$end_date', '$membership_type', '$membership_fee')";

                        if (mysqli_query($conn, $insertMembership)) {
                            echo "<script>alert('You are now a member');</script>";
                            header("refresh:0 URL=home_screen.php");
                        }else {
                            echo "<script>alert('Error in selecting');</script>";
                            header("refresh:0 URL=membership_form.php");
                        }
                    }
                }
            }
        }

        
?>

<?php
///SCHEDULE
    if (isset($_POST['btnSchedule'])) {
        $workout = $_POST['workoutCategories'];
        $dateOfWorkout = $_POST['dateOfWorkout'];
        $start_time = $_POST['start_time'];
        $end_time = $_POST['end_time'];

        $select_userID_schedule = "SELECT userID FROM user_info WHERE username='$username'";
        $resultUserID = mysqli_query($conn, $select_userID_schedule);
        if ($resultUserID) {
            if (mysqli_num_rows($resultUserID) > 0) {
                $row = mysqli_fetch_assoc($resultUserID);
                $userID = $row['userID'];
            }
        }

        $insertSchedule = "INSERT INTO schedule (userID, workout, date_of_workout, start_time, end_time) VALUES ($userID, '$workout', '$dateOfWorkout', '$start_time', '$end_time')";

        if (mysqli_query($conn, $insertSchedule)) {
            echo "<script>alert('Your schedule has been recorded');</script>";
            header("refresh:0 URL=home_screen.php");
        }else {
            echo "<script>alert('Error in adding schedule');</script>";
            header("refresh:0 URL=schedule_workout.php");
        }
    }

?>
<?php
    
    }else {
        echo "You need to log in";
        header('Refresh:2; URL=index.html');
    }
?>