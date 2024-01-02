<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Membership Form</title>
    <!-- Bootstrap CSS Link -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        header {
        background-color: #161a30;
        padding: 15px 0;
        margin-bottom: 30px; /* Add margin-bottom to create space */
        }
        .container-box {
            margin-top: 30px;
            max-width: 600px;
            margin: auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);

        }

        h1 {
            color: #161a30;
        }

        p {
            color: #161a30;
        }

        form {
            margin-top: 20px;
        }

        label {
            color: #161a30;
            font-weight: bold;
            margin-top: 10px;
            display: block;
        }

        select, input {
            width: 100%;
            padding: 8px;
            margin-top: 5px;
            margin-bottom: 10px;
            box-sizing: border-box;
        }

        button {
            background-color: #161a30;
            color: #fff;
            padding: 10px 15px;
            border: none;
            cursor: pointer;
        }

        button:hover {
            background-color: #3498db;
        }
    </style>
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
                        <li><a href="new_home_screen.php">Home</a></li>
                        <?php
                        $selectToConfirm = "SELECT Count(user_info.username) AS Count FROM user_info INNER JOIN membership ON user_info.userID = membership.userID WHERE username='$username'";
                        $resultToConfirm = mysqli_query($conn, $selectToConfirm);
                        if ($resultToConfirm) {
                            if (mysqli_num_rows($resultToConfirm) > 0) {
                                $row = mysqli_fetch_assoc($resultToConfirm);
                                    if($row['Count'] < 0) {
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
    $selectToConfirm = "SELECT Count(user_info.username) AS Count FROM user_info INNER JOIN membership ON user_info.userID = membership.userID WHERE username='$username'";
    $resultToConfirm = mysqli_query($conn, $selectToConfirm);
        if ($resultToConfirm) {
            if (mysqli_num_rows($resultToConfirm) > 0) {
                $row = mysqli_fetch_assoc($resultToConfirm);
            if($row['Count'] > 0) {

                $select_member_info = "SELECT user_info.fname, user_info.lname, membership.membership_type, membership.start_date, membership.end_date, membership.membership_fee FROM membership INNER JOIN user_info ON membership.userID=user_info.userID WHERE user_info.username='$username'";
                $result_member_info = mysqli_query($conn, $select_member_info);
                if ($result_member_info) {
                    if (mysqli_num_rows($result_member_info) > 0) {
                        $row = mysqli_fetch_assoc($result_member_info);

                        $fname = $row['fname'];
                        $lname = $row['lname'];
                        $end_date = $row['end_date'];

                        $date = strtotime($end_date);
                        $remaining = $date - time();
                        $days_remaining = floor($remaining / 86400);

                        // Format the date without the time
                        $formatted_date = date("F j, Y", $date);

                        
                    }
                }
            ?>
                <p>You are a member <?php echo $fname . " " . $lname?></p>
                <p><?php echo "There are $days_remaining days left until $formatted_date";?></p>
            <?php
                }else {
            ?>
                
            <div class="container-box">
                <h1>Be Member</h1>
                <p>Become a member with options for semi-monthly at 150, monthly at 300, or annually at 3000. Experience the advantages of membership, including access to training guides and the ability to schedule workouts.</p>
                <form action="" method="POST">
                    <label for="membershipType">Membership Type:</label>
                    <select id="membershipType" name="membershipType" class="form-control" required>
                        <option value="semi-monthly">Semi-monthly</option>
                        <option value="monthly">Monthly</option>
                        <option value="annual">Annual</option>
                    </select>
                    
                    <!-- <label for="start_date">Starting Date:</label>
                    <input type="date" name="start_date" class="form-control">
                    
                    <label for="end_date">Ending Date:</label>
                    <input type="date" name="end_date" class="form-control"> -->
                    
                    <button name="btnMembership" class="btn btn-primary">Join</button>
                </form>
            </div>
        <?php
                }
            }
        }
        ?>
    <?php

    if (isset($_POST['btnMembership'])) {
        $membership_type = $_POST['membershipType'];
        // $start_date = $_POST['start_date'];
        // $end_date = $_POST['end_date'];

        if (empty($membership_type)) {
            echo "<script>alert('Please fill in all fields');</script>";
            header("refresh:0 URL=membership_form.php");
            exit();
        }

        $start_date = new DateTime();

        $select_userID = "SELECT userID FROM user_info WHERE username='$username'";
        $resultUserID = mysqli_query($conn, $select_userID);
        if ($resultUserID && mysqli_num_rows($resultUserID) > 0) {
            $row = mysqli_fetch_assoc($resultUserID);
            $userID = $row['userID'];

            if ($membership_type == 'semi-monthly') {
                $membership_fee = 150;
                $start_date->add(new DateInterval('P15D'));
                $end_date = $start_date->format('Y-m-d');
            } elseif ($membership_type == 'monthly') {
                $membership_fee = 300;
                $start_date->add(new DateInterval('P31D'));
                $end_date = $start_date->format('Y-m-d');
            } elseif ($membership_type == 'annual') {
                $membership_fee = 3000;
                $start_date->add(new DateInterval('P1Y'));
                $end_date = $start_date->format('Y-m-d');
            }

        $insertMembership = "INSERT INTO membership (userID, start_date, end_date, membership_type, membership_fee) VALUES ($userID, NOW(), '$end_date', '$membership_type', '$membership_fee')";

        if (mysqli_query($conn, $insertMembership)) {
            echo "<script>alert('You are now a member');</script>";
            header("refresh:0 URL=new_home_screen.php");
        } else {
            echo "<script>alert('Error in selecting');</script>";
            header("refresh:0 URL=membership_form.php");
        }
    } else {
        echo "<script>alert('Error in selecting');</script>";
        header("refresh:0 URL=membership_form.php");
    }
}


    ?>

    <!-- Bootstrap JS and Popper.js (optional but needed for some Bootstrap features) -->
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
