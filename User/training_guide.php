<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Training Guide</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">

    <!-- Font Awesome CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">

    <link rel="stylesheet" href="style.css">
    <!-- Custom CSS -->
    <style>
        body {
            overflow-x: hidden;
        }

        #wrapper {
            display: flex;
        }

        #sidebar-wrapper {
            position: fixed;
            width: 250px;
            height: 100%;
            background: #000;
            overflow-y: auto;
            transition: all 0.5s ease;
            padding-top: 20px;
        }

        #sidebar-wrapper .sidebar-heading {
            padding: 10px;
            color: white;
            text-align: center;
        }

        #sidebar-wrapper .list-group {
            padding: 20px;
        }

        #sidebar-wrapper .list-group-item {
            border: none;
            background: #000;
            color: #fff;
            border-radius: 0;
            transition: all 0.3s ease;
        }

        #sidebar-wrapper .list-group-item:hover {
            background: rgba(255, 255, 255, 0.2);
            border-left: 4px solid red;
            color: #fff;
        }

        #page-content-wrapper {
            flex: 1;
            padding-left: 250px;
            padding-top: 50px;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
        }

        #wrapper.toggled #page-content-wrapper {
            padding-left: 0;
        }

        .fa {
            margin-right: 10px;
        }

        
        .blog-box {
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 20px;
            margin-top: 20px;
            width: 80%; 
        }
    </style>
</head>

<body>

    <div class="d-flex" id="wrapper">

        <!-- Sidebar -->
        <div class="bg-dark" id="sidebar-wrapper">
            <div class="sidebar-heading">Admin Dashboard</div>
            <div class="list-group">
                <a href="admin.php" class="list-group-item list-group-item-action">
                    <i class="fa fa-dashboard"></i> Dashboard
                </a>
                <a href="#" class="list-group-item list-group-item-action">
                    <i class="fa fa-book"></i> Training Guide
                </a>
                <a href="manage_accounts.php" class="list-group-item list-group-item-action">
                    <i class="fa fa-users"></i> Manage User Accounts
                </a>
                <a href="logout.php" class="list-group-item list-group-item-action">
                    <i class="fa-sign-out"></i> Logout
                </a>
            </div>
        </div>
        <!-- /#sidebar-wrapper -->

        <!-- Page Content -->
        <div id="page-content-wrapper">
            <div class="container-fluid">

            <h1>Training Guides</h1>

<?php
include('db_conn.php');

function displayTrainingData()
{
    global $conn;
    $sql = "SELECT * FROM training";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        echo "<table border='1'>
        <tr>
            <th>Title</th>
            <th>Procedure</th>
            <th>Steps</th>
            <th>Reps</th>
            <th>Image</th>
            <th>Action</th>
        </tr>";

        while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr>
            <td>{$row['title']}</td>
            <td>{$row['procedure']}</td>
            <td>{$row['steps']}</td>
            <td>{$row['reps']}</td>
            <td><img src='".$row['image_path']."' alt='image' height='100px'></td>
            <td>
                <a href='edit.php?id={$row['id']}'>Edit</a> |
                <a href='delete.php?id={$row['id']}'>Delete</a>
            </td>
        </tr>";
        }

        echo "</table>";
    } else {
        echo "<br>No training data available.<br><br>";
    }
}

displayTrainingData();
?>
<br><br>
<h2>Add Training Guide</h2>
<form action="add.php" method="post" enctype="multipart/form-data">
    <label>Title:</label>
    <input type="text" name="title" required><br>
    <label>Procedure:</label>
    <textarea name="procedure" rows="4" cols="50" required></textarea><br>
    <label>Steps:</label>
    <textarea name="steps" rows="4" cols="50" required></textarea><br>
    <label>Reps:</label>
    <input type="number" name="reps" required><br>
    <label>Image:</label>
    <input type="file" name="image" accept="image/*"><br><br>
    <input type="submit" value="Add Training">
</form>
<br>
</div>
    </div>
        </div>
        <!-- /#page-content-wrapper -->

    </div>
    <!-- /#wrapper -->

    <!-- Bootstrap JS and jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.4.min.js" integrity="sha384-ZvpUoO/+PpLXR1lu4jmpXWu80pZlYUAfxl5NsBMWOEPSjUn/6Z/hRTt8+pR6L4N2" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>

    <!-- Your custom JavaScript -->
    <script>
        $("#menu-toggle").click(function (e) {
            e.preventDefault();
            $("#wrapper").toggleClass("toggled");
        });
    </script>

</body>

</html>






































