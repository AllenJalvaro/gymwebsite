<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User Info</title>
    <!-- Include your custom CSS file -->
    <link rel="stylesheet" type="text/css" href="styles.css">
</head>
<body>

<div class="container">
    <h2>Edit User Info</h2>
    <?php
include('db_conn.php');

    
    $userId = isset($_GET['id']) ? $_GET['id'] : 0;

    
    $sql = "SELECT * FROM user_info WHERE userID = $userId";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        ?>
        <form id="updateForm" action="update_user.php" method="post" onsubmit="return validateForm()">
            <input type="hidden" name="userID" value="<?php echo $row['userID']; ?>">
            <label>First Name:</label>
            <input type="text" name="fname" value="<?php echo $row['fname']; ?>" required><br>
            <label>Last Name:</label>
            <input type="text" name="lname" value="<?php echo $row['lname']; ?>" required><br>
            <label>Sex:</label>
            <input type="text" name="sex" value="<?php echo $row['sex']; ?>" required><br>
            <label>Birthdate:</label>
            <input type="text" name="birthdate" value="<?php echo $row['birthdate']; ?>" required><br>
            <label>Phone Number:</label>
            <input type="text" name="phoneNumber" value="<?php echo $row['phoneNumber']; ?>" required><br>
            <label>Username:</label>
            <input type="text" name="username" value="<?php echo $row['username']; ?>" required><br>
            <label>Password:</label>
            <input type="password" name="password" required><br>
            <label>Confirm Password:</label>
            <input type="password" name="confirm_password" required>
            <span id="error-message" style="color: red;"></span><br>
            <input type="submit" id="submitBtn" value="Update">
        </form>
        <?php
    } else {
        echo "User not found.";
    }

    $conn->close();
    ?>
</div>

<!-- Include your custom JavaScript file -->
<script type="text/javascript" src="script.js"></script>

</body>
</html>
