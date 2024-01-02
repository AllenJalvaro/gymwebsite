<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QR code</title>
    <!-- Add Bootstrap CSS Link -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">

    <style>
        /* Custom styles for square container and box shadow */
        .square-container {
            width: 400px; /* Set the width to your desired size */
            padding: 20px; /* Adjust padding as needed */
            margin: auto;
            background-color: #fff; /* Set background color */
            border-radius: 10px; /* Set border radius for rounded corners */
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* Add box shadow for elevation */
        }
    </style>
</head>
<body>
    <div class="square-container mt-5 text-center">
        <?php
            require "phpqrcode/qrlib.php";
            require "db_conn.php";
            session_start();
            
            if (isset($_SESSION['username'])) {
                $username = $_SESSION['username'];

                $result = mysqli_query($conn, $select_user_id = "SELECT userID from user_info WHERE username='$username'");
                $row = mysqli_fetch_assoc($result);
                $userID = $row['userID'];

                $path = "img/";
                $qrcode = $path.time().".png";
                $qrimage = time().".png";

                if (isset($_POST['btnQrcode'])) {
                    $qrtext = $_POST['username'];
                    $insert_qrcode = mysqli_query($conn, "INSERT INTO qrcode SET userID=$userID, qr_username='$username', qr_image='$qrimage'");
                    if ($insert_qrcode) {
                    ?>
                        <div class="alert alert-success" role="alert">
                            QR code generated successfully!
                        </div>
                    <?php
                    }

                    QRcode::png($qrtext, $qrcode, 'H', 4, 4);

                }
        ?>
                <a href="profile_view.php">Back to profile</a>
                <h1>Get your qr code</h1>
                
                <form action="" method="post" class="mb-4">
                    <div class="form-group">
                        <label for="username"></label>
                        <input type="text" class="form-control" name="username" value="<?php echo $username?>">
                    </div>
                    <button type="submit" name="btnQrcode" class="btn btn-primary">Save changes</button>
                </form>

                <?php
                    if (isset($_POST['btnQrcode'])) {
                        ?>
                        
                        <img src="<?php echo $qrcode ?>"  class='img-fluid' width="200px" height="200px">
                        <br>
                        <a href='download_qrcode.php?image=<?php echo $qrcode?>'><i class='fas fa-download fa-lg'></i></a>

                        <?php
                    }
                ?>
        <?php
            } else {
                echo "<p class='alert alert-danger'>You need to log in</p>";
                header('Refresh:2; URL=index.html');
            }
        ?>
    </div>

    <!-- Add Bootstrap JS and Popper.js Scripts -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
