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
                $sex = $row['sex'];
                $birthdate = $row['birthdate'];
                $timestamp = strtotime($birthdate);
                $formattedBirthdate = date('Y-m-d', $timestamp);
                $phone_number = $row['phoneNumber'];

            }
        }

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if the form is submitted

    // Sanitize input data to prevent SQL injection
    $firstN = mysqli_real_escape_string($conn, $_POST['firstN']);
    $lastN = mysqli_real_escape_string($conn, $_POST['lastN']);
    $sex = mysqli_real_escape_string($conn, $_POST['sex']);
    $birthdate = mysqli_real_escape_string($conn, $_POST['bdate']);
    $phoneNumber = mysqli_real_escape_string($conn, $_POST['phone']);

    // Update the user_info table with the new values
    $update_query = "UPDATE user_info SET fname='$firstN', lname='$lastN', sex='$sex', birthdate='$birthdate', phoneNumber='$phoneNumber' WHERE username='$username'";
    
    if (mysqli_query($conn, $update_query)) {
        echo "Changes saved successfully.";
        header("Location: profile_view.php");
    } else {
        echo "Error updating record: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" integrity="sha384-HJXe0R26uOrqzYBn1A+K0qou1yfD5PLO1iRst+q38LQJNN5T3CGX0iQpIf+njrJJ" crossorigin="anonymous">

    <style>
        body {
    margin: 0;
    padding-top: 40px;
    color: #2e323c;
    background: #f5f6fa;
    position: relative;
    height: 100%;
    }
    .account-settings .user-profile {
        margin: 0 0 1rem 0;
        padding-bottom: 1rem;
        text-align: center;
    }
    .account-settings .user-profile .user-avatar {
        margin: 0 0 1rem 0;
    }

    .account-settings .user-profile .user-avatar .avatar-initials {
    display: inline-block;
    width: 90px;
    height: 90px;
    line-height: 90px;
    text-align: center;
    font-size: 24px;
    font-weight: bold;
    background-color: #007ae1;
    color: #ffffff;
    -webkit-border-radius: 50%;
    -moz-border-radius: 50%;
    border-radius: 50%;
}
    .account-settings .user-profile h5.user-name {
        margin: 0 0 0.5rem 0;
    }
    .account-settings .user-profile h6.user-email {
        margin: 0;
        font-size: 0.8rem;
        font-weight: 400;
        color: #9fa8b9;
    }
    .account-settings .about {
        margin: 2rem 0 0 0;
        text-align: center;
    }
    .account-settings .about h5 {
        margin: 0 0 15px 0;
        color: #007ae1;
    }
    .account-settings .about p {
        font-size: 0.825rem;
    }
    .form-control {
        border: 1px solid #cfd1d8;
        -webkit-border-radius: 2px;
        -moz-border-radius: 2px;
        border-radius: 2px;
        font-size: .825rem;
        background: #ffffff;
        color: #2e323c;
    }

    .card {
        background: #ffffff;
        -webkit-border-radius: 5px;
        -moz-border-radius: 5px;
        border-radius: 5px;
        border: 0;
        margin-bottom: 1rem;
    }

    </style>
</head>
<body>

<div class="container">
<div class="row gutters">
<div class="col-xl-3 col-lg-3 col-md-12 col-sm-12 col-12">
<div class="card h-100">
	<div class="card-body">
        <a href="profile_view.php">Back</a>
		<div class="account-settings">
			<div class="user-profile">
				<div class="user-avatar">
                    <span class="avatar-initials"><?php echo strtoupper(substr($fname, 0, 1) . substr($lname, 0, 1)) ?></span>
				</div>
				<h5 class="user-name"><?php echo $username?></h5>
                <?php
                $select_if_member = mysqli_query($conn, "SELECT membership.membershipID FROM membership INNER JOIN user_info ON membership.userID=user_info.userID WHERE user_info.username='$username'");
                if (mysqli_num_rows($select_if_member) > 0) {
                    echo "<h6 class='user-email'>Member</h6>";
                }else {
                    echo "<h6 class='user-email'>Session</h6>";
                }
                ?>
				
			</div>
			<div class="about">
				
                <?php
                    $select_if_have_qrcode = mysqli_query($conn, "SELECT Count(qrcode.qr_username) AS Count FROM qrcode INNER JOIN user_info ON qrcode.userID=user_info.userID WHERE user_info.username='$username';");
                    if (mysqli_num_rows($select_if_have_qrcode) > 0) {
                        $row = mysqli_fetch_assoc($select_if_have_qrcode);
                        if ($row['Count'] > 0) {
                            $select_qrcode_path = mysqli_query($conn, "SELECT qr_image FROM qrcode INNER JOIN user_info ON qrcode.userID=user_info.userID WHERE user_info.username='$username'");
                            if (mysqli_num_rows($select_qrcode_path) > 0) {
                                $row = mysqli_fetch_assoc($select_qrcode_path);
                                // echo "<a href='qr_code_generator.php'><button type='button' class='btn btn-secondary'>Re-generate QR code</button></a>";
                                echo "<img src='img/".$row['qr_image']."' alt='qrcode' width='200px' height='200px'>";
                                echo "<br><a href='download_qrcode.php?image=img/" . urlencode($row['qr_image']) . "'><i class='fas fa-download fa-lg'></i></a>";

                                
                            }
                        }else {
                            echo "<a href='qr_code_generator.php'><button type='button' class='btn btn-primary'>Get QR code</button></a>";
                        }
                    }

                ?>
			</div>
		</div>
	</div>
</div>
</div>
<div class="col-xl-9 col-lg-9 col-md-12 col-sm-12 col-12">
<div class="card h-100">
	<div class="card-body">
		<div class="row gutters">
			<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
            <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" name="savechanges">
				<h6 class="mb-2 text-primary">Personal Details
        <button type="submit" style="margin-left:20px; color: green; background: none; border: none;" class="save-link">
            <i class="fas fa-save"></i> Save Changes
        </button>
    </h6>
			</div>
            
			<div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
            <div class="form-group">
    <div class="row">
        <div class="col-md-6">
            <label for="firstN">First Name</label>
            <input type="text" id="firstN" name="firstN" class="form-control" value="<?php echo $fname;?>" >
        </div>
        <div class="col-md-6">
            <label for="lastN">Last Name</label>
            <input type="text" id="lastN" name="lastN" class="form-control" value="<?php echo $lname;?>" >
        </div>
    </div>
</div>

			</div>
			<div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
				<div class="form-group">
                <label for="bdate">Birthdate</label>
    <input type="date" id="bdate" name="bdate" class="form-control" value="<?php echo $formattedBirthdate; ?>" >

				</div>
			</div>
			<div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
				<div class="form-group">
					<label for="phone">Phone</label>
					<input type="text" id="phone" name="phone" class="form-control" value="<?php echo $phone_number?>" >
				</div>
			</div>
			<div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
				<div class="form-group">
					<label for="sex">Sex</label>
                    <select name="sex" id="sex" class="form-control">
                        <option value="Male" <?php if(isset($sex) && $sex=='Male'){ echo 'selected';}?>>Male</option>
                        <option value="Female"  <?php if(isset($sex) && $sex=='Female'){ echo 'selected';}?>>Female</option>
                    </select>
				
				</div>
			</div>
            </form>
            
		</div>
		<div class="row gutters">
			<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
				<h6 class="mt-3 mb-2 text-primary">Membership status</h6>
			</div>
            <?php
                $select_member_info = mysqli_query($conn, "SELECT membership.membership_type, membership.membership_fee, membership.start_date, membership.end_date FROM membership INNER JOIN user_info ON membership.userID=user_info.userID WHERE user_info.username='$username'");
                if (mysqli_num_rows($select_member_info) > 0) {
                    $row = mysqli_fetch_assoc($select_member_info);

                    $memberType = $row['membership_type'];
                    $memberFee = $row['membership_fee'];
                    $start_date = $row['start_date'];
                    $end_date = $row['end_date'];
                    $start_date_in_words = date('F j, Y', strtotime($start_date));
                    $end_date_in_words = date('F j, Y', strtotime($end_date));

                    ?>

                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                        <div class="form-group">
                            <label for="Street">Membership Type</label>
                            <input type="name" class="form-control" value="<?php echo $memberType?>" readonly>
                        </div>
                    </div>
                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                        <div class="form-group">
                            <label for="ciTy">Fee</label>
                            <input type="name" class="form-control" value="<?php echo "₱" . $memberFee?>" readonly>
                        </div>
                    </div>
                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                        <div class="form-group">
                            <label for="sTate">Start date</label>
                            <input type="text" class="form-control" value="<?php echo $start_date_in_words?>" readonly>
                        </div>
                    </div>
                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                        <div class="form-group">
                            <label for="zIp">Membership Expiration Date</label>
                            <input type="text" class="form-control" value="<?php echo $end_date_in_words?>" readonly>
                        </div>
                    </div>
                </div>
                <div class="row gutters">
                    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                        <div class="text-right">
                            <a href="#"><button type="button" class="btn btn-secondary">Cancel Membership</button></a>
                            <a href="logout.php"><button type="button" class="btn btn-primary">Log out</button></a> 
                        </div>
                    </div>
                </div>
                    <?php
                }else {
                    ?>
                        <h2 style='margin-left: 20px;'>No records, you are not a member</h2>
                        <a href='membership_form.php'><button type='button' class='btn btn-secondary' style='margin-right: 20px; margin-left: 20px; margin-top: 120px;'>Be a member</button></a>
                        <div class="row gutters">
                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                            <div class="text-right">
                                <a href="logout.php"><button type="button" class="btn btn-primary" style='margin-top: 120px'>Log out</button></a>
                            </div>
                        </div>
                    </div>
                    <?php
                }
            ?>
	</div>
</div>
</div>
</div>
</div>

<?php
    }else {
         echo "You need to log in";
        header('Refresh:2; URL=index.html');
    }

?>


</body>
</html>