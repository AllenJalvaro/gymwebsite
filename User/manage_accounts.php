<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage User Accounts</title>
    <!-- Include jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <!-- Include DataTables CSS and JS -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.css">
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.js"></script>
    <!-- Include your custom CSS file -->
    <link rel="stylesheet" type="text/css" href="ADMINstyles.css">
</head>

<body>

    <div class="container">
        <h2>Manage User Accounts</h2>
        <table id="userTable">
            <thead>
                <tr>
                    <th>User ID</th>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Sex</th>
                    <th>Birthdate</th>
                    <th>Phone Number</th>
                    <th>Username</th>
                    <th>Password</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                include('db_conn.php');

                $sql = "SELECT * FROM user_info";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $row['userID'] . "</td>";
                        echo "<td>" . $row['fname'] . "</td>";
                        echo "<td>" . $row['lname'] . "</td>";
                        echo "<td>" . $row['sex'] . "</td>";
                        echo "<td>" . $row['birthdate'] . "</td>";
                        echo "<td>" . $row['phoneNumber'] . "</td>";
                        echo "<td>" . $row['username'] . "</td>";
                        echo "<td>" . $row['password'] . "</td>";
                        echo "<td>";
                        echo "<button class='edit-btn' data-id='" . $row['userID'] . "'>Edit</button>";
                        echo "<button class='delete-btn' data-id='" . $row['userID'] . "'>Delete</button>";
                        echo "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='10'>No records found</td></tr>";
                }

                $conn->close();
                ?>
            </tbody>
        </table>
        <br>

        <!-- Back button to go back to the dashboard -->
        <a href="admin.php">Back to Dashboard</a>
    </div>

    <!-- Include your custom JavaScript file -->
    <script type="text/javascript" src="script.js"></script>

</body>

</html>
