
<?php
require "db_conn.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Filtering user input
    $fname = filter_input(INPUT_POST, 'fname', FILTER_SANITIZE_STRING);
    $lname = filter_input(INPUT_POST, 'lname', FILTER_SANITIZE_STRING);
    $gender = filter_input(INPUT_POST, 'gender', FILTER_SANITIZE_STRING);
    $rawBirthdate = $_POST['birthdate'];
    $timestamp = strtotime($rawBirthdate);
    $formattedBirthdate = date('Y-m-d', $timestamp);
    $contactNumber = filter_input(INPUT_POST, 'contactNum', FILTER_SANITIZE_STRING);
    $username = strtolower(filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING));
    $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);
    $passwordConfirm = filter_input(INPUT_POST, 'passwordConfirm', FILTER_SANITIZE_STRING);

    if(!preg_match('/^(09|\+639)\d{9}$/', $contactNumber)){
        echo "<script>alert('Invalid Phone Number');</script>";
    }
    // Check if all required fields are filled
    if (
        !empty($fname) &&
        !empty($lname) &&
        !empty(trim($rawBirthdate)) &&
        !empty($contactNumber) &&
        !empty($username) &&
        !empty($password) &&
        !empty($passwordConfirm)
    ) {
        $birthdate = date('Y-m-d', strtotime($rawBirthdate));

        // Check if the username is already used
        $selectUsername = "SELECT username FROM user_info WHERE username='$username'";
        $resultSelect = mysqli_query($conn, $selectUsername);

        if ($resultSelect) {
            if (mysqli_num_rows($resultSelect) <= 0) {
                // Check if passwords match
                if ($password == $passwordConfirm) {
                    // Insert user record if all checks pass
                    $insertUserRecord = "INSERT INTO user_info (fname, lname, sex, birthdate, phoneNumber, username, password, confirm_password) VALUES ('$fname', '$lname', '$gender', '$birthdate', '$contactNumber', '$username', '$password', '$passwordConfirm')";
                    $result = mysqli_query($conn, $insertUserRecord);

                    if ($result) {
                        echo "<script>alert('Successful in registering');</script>";
                        header("refresh:0 URL=login.php");
                        exit();
                    } else {
                        echo "<script>alert('Error in inserting try again');</script>";
                    }
                } else {
                    echo "<script>alert('Password does not match');</script>";
                }
            } else {
                echo "<script>alert('Username Already Used');</script>";
            }
        } else {
            echo "<script>alert('Error in confirming USERNAME');</script>";
        }
    } else {
        echo "<script>alert('Fill all fields');</script>";
    }

    // If there are any errors, redirect back to the registration page
   
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-image: url('img/gym_home.jpg');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            height: 100vh;
            margin: 0;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .card {
            background: rgba(255, 255, 255, 0.9);
            padding: 30px;
            border-radius: 10px;
            width: 100%;
            max-width: 800px;
            border-radius: 30px;
        }
        .form-label {
            font-size: 20px;
        }

        .center-text {
            text-align: center;
        }

        .center-button {
            display: flex;
            justify-content: center;
            margin-top: 20px;
        }

        .form-control {
            background-color: rgba(255, 255, 255, 0.3);
            border: 1px solid grey;    
        }
        .btn{
            background-color: #161A30 !important;
            color: white;
            padding: 5px 300px;
        }
        .btn:hover{
            color: gray !important;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <h1 class="text-center mb-4">Flexin Registration</h1>

                    <form action="" method="POST">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="fname" class="form-label">First Name:</label>
                                <input type="text" name="fname" value="<?php if(isset($fname)) echo $fname ?>" class="form-control" placeholder="Enter first name" required>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="lname" class="form-label">Last Name:</label>
                                <input type="text" name="lname"  value="<?php if(isset($lname)) echo $lname ?>" class="form-control" placeholder="Enter last name" required>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="gender" class="form-label">Select your gender:</label>
                                <select name="gender" id="gender" class="form-control"> 
                                    <option value="Male"  <?php if(isset($sex) && $sex=='Male'){ echo 'selected';}?>>Male</option>
                                    <option value="Female" <?php if(isset($sex) && $sex=='Female'){ echo 'selected';}?>>Female</option>
                                </select>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="birthdate" class="form-label">Birthday:</label required>
                                <input type="date" name="birthdate" class="form-control" value="<?php echo $formattedBirthdate; ?>" >
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="contactNum" class="form-label">Phone Number:</label>
                                <input type="tel" name="contactNum" value="<?php if(isset($contactNumber)) echo $contactNumber ?>"  class="form-control" placeholder="Enter phone number" required>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="username" class="form-label">Username:</label>
                                <input type="text" value="<?php if(isset($username)) echo $username ?>" name="username" class="form-control"  placeholder="Enter Username" required>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="password" class="form-label">Password:</label>
                                <input type="password" name="password" class="form-control" placeholder="Enter password" required>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="passwordConfirm" class="form-label">Confirm password:</label>
                                <input type="password" name="passwordConfirm" class="form-control" placeholder="Comfirm password" required>
                            </div>
                        </div>

                        <div class="center-button">
                            <button type="submit" name="btnRegister" class="btn">Register</button>
                        </div>
                    </form>

                    <p class="mt-3 center-text">
                        Already have an account? <a href="login.php" style="color: black">Login</a>

                    </p>
                </div>
            </div>
        </div>
    </div>
        
    <!-- Bootstrap JS (optional) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
