<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <title>Document</title>
    <link rel="stylesheet" href="home.css">
</head>
<body>
<?php
    require "db_conn.php";
    session_start();
    

    if (isset($_SESSION['username'])) {
        $username = $_SESSION['username'];

    $user_info = "SELECT * FROM user_info WHERE username='$username'";
    $result_user_info = mysqli_query($conn, $user_info);
    if ($result_user_info) {
        if (mysqli_num_rows($result_user_info) > 0) {
            $row = mysqli_fetch_assoc($result_user_info);

            $fname = $row['fname'];
            $lname = $row['lname'];
        }
    }

?>
<header class="header-area header-sticky">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <nav class="main-nav">
                    <h2>Welcome <?php echo $username?></h2>
                    <ul class="nav">
                        <li><a href="indexshop.php">Shop</a></li>

                        <?php
                        $selectToConfirm = "SELECT Count(user_info.username) AS Count FROM user_info INNER JOIN membership ON user_info.userID = membership.userID WHERE username='$username'";
                        $resultToConfirm = mysqli_query($conn, $selectToConfirm);
                        if ($resultToConfirm) {
                            if (mysqli_num_rows($resultToConfirm) > 0) {
                                $row = mysqli_fetch_assoc($resultToConfirm);
                                    if($row['Count'] > 0) {
                                        ?>
                                        <li><a href="track_progress.php">Track Progress</a></li>
                                        <li><a href="schedule_workout.php">Schedule Workout</a></li>

                                        <?php
                                    }else {
                                        ?>
                                        <li><a href="membership_form.php">Join membership</a></li>
                                        <?php
                                    }
                                }
                            }
                        ?>
                    </ul>
                <a href="profile_view.php">
                    <div class="profile-avatar">
                        <span><?php echo strtoupper(substr($fname, 0, 1) . substr($lname, 0, 1)) ?></span>
                    </div>
                </a>
                </nav>
            </div>
        </div>
    </div>
</header>

<?php
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
                            header("refresh:0 URL=new_home_screen.php");
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

    }else {
         echo "You need to log in";
        header('Refresh:2; URL=index.html');
    }

?>

</body>
</html>