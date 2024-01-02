<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <title>Track Progress</title>
    <style>
    /* Custom class for styling the cards */
    .main-cont {
        margin: 20px;
    }

    .custom-card {
        height: 400px; /* Updated fixed height for the card */
        padding: 20px; /* Adjusted padding to maintain consistency */
        border-radius: 2px;
        overflow: auto;
    }

    /* Add margin between the cards */
    .card-container {
        margin-top: 20px;
    }

    .row-form{
        margin: 20px;
        width: 1275px;
        border-radius: 2px;
        overflow: auto;
    }
    
    .card{
        border-radius: 2px;
    }
    form {
            display: flex;
            flex-direction: column;
        }

        label {
            margin-bottom: 5px;
        }

        input {
            padding: 5px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 3px;
        }

        button {
            padding: 5px;
            background-color: #161A30 !important;
            color: #fff;
            border: none;
            border-radius: 3px;
            cursor: pointer;
        }

        button:hover {
            background-color: #0056b3;
        }

</style>

</head>
<body>
    <?php
    require "db_conn.php";
    session_start();

    if (isset($_SESSION['username'])) {
        $username = $_SESSION['username'];

        $select_userID = mysqli_query($conn, "SELECT userID FROM user_info WHERE username='$username'");
        if (mysqli_num_rows($select_userID) > 0) {
            $row = mysqli_fetch_assoc($select_userID);
            $userID = $row['userID'];
        }

        if (isset($_POST['btnCalculateBmi'])) {
            $weight = isset($_POST["weight"]) ? $_POST["weight"] : 0;
            $height = isset($_POST["height"]) ? $_POST["height"] : 0;

            if (!empty($weight) || !empty($height)) {
            $heightInMeters = $height / 100;
            $bmi = $weight / ($heightInMeters ** 2);

            if ($bmi < 18.5) {
                $bmi_result = 'Underweight';
            } elseif ($bmi >= 18.5 && $bmi < 24.9) {
                $bmi_result = 'Normal weight';
            } elseif ($bmi >= 25 && $bmi < 29.9) {
                $bmi_result = 'Overweight';
            } else {
                $bmi_result = 'Obesity';
            }

            $insert_bmi = mysqli_query($conn, "INSERT INTO tracker (user_ID, weight, height, bmi_classification, bmi, date_of_bmi) VALUES ($userID, $weight, $height, '$bmi_result', $bmi, NOW())");
        }else {
            echo "<script>alert('Fill the fields!');</script>";
        }
    }
    ?>
    <div class="row-form">
    <div class="col-sm-6 mx-auto justify-content-center">
        <div class="card">
        <div class="card-body">
        <a href="new_home_screen.php">Back</a>
        <h2>Calculate BMI</h2>
            <form action="" method="post" style="margin-bottom: 30px;">
                    <label for="weight">Weight: </label>
                    <input type="text" name="weight" placeholder="Enter weight (kg)">

                    <label for="height">Height: </label>
                    <input type="text" name="height" placeholder="Enter height (cm)">

                    <button name="btnCalculateBmi">Calculate</button>
            </form>
        </div>
        </div>
    </div>
</div>
    <div class="main-cont">
        <div class="row card-container">
            <div class="col-sm-6">
                <div class="card custom-card">
                    <div class="card-body">
                        <?php
                        // Assume $conn is the database connection object
                        $select_tracker_info = mysqli_query($conn, "SELECT tracker.bmi_classification, tracker.date_of_bmi FROM tracker INNER JOIN user_info ON tracker.user_ID=user_info.userID WHERE user_info.username='$username'");
                        if (mysqli_num_rows($select_tracker_info) > 0) {
                            $dataPoints1 = array(); // Initialize array for the first chart

                            // Create a mapping of BMI classifications to numerical values
                            $classificationMapping = array(
                                "Underweight" => 1,
                                "Normal weight" => 2,
                                "Overweight" => 3,
                                "Obesity" => 4
                            );

                            while ($row = mysqli_fetch_assoc($select_tracker_info)) {
                                $selClassification = $row['bmi_classification'];
                                $selDateTimestamp = strtotime($row['date_of_bmi']);
                                $selDate = date("F j, Y", $selDateTimestamp);

                                // Map BMI classification to numerical value
                                $numericValue = $classificationMapping[$selClassification];

                                // Add a symbol or annotation to indicate the classification
                                $annotation = $selClassification; // You can customize this based on your preference

                                $dataPoints1[] = array("y" => $numericValue, "label" => $selDate, "indexLabel" => $annotation);
                            }
                            ?>
                            <div id="chartContainer1" style="height: 300px; width: 100%;"></div>
                            <?php
                            }else {
                                echo '<div style="display: flex; justify-content: center; align-items: center; height: 100%;">';
                                echo '<h2>No records</h2>';
                                echo '</div>';
                            }
                        ?>
                    </div>
                </div>
            </div>

            <div class="col-sm-6">
                <div class="card custom-card">
                    <div class="card-body">
                        <?php
                        // Assume $conn is the database connection object
                        $select_tracker_info = mysqli_query($conn, "SELECT tracker.bmi, tracker.date_of_bmi
                        FROM tracker
                        INNER JOIN user_info ON tracker.user_ID = user_info.userID
                        WHERE user_info.username = '$username'
                        ORDER BY tracker.date_of_bmi ASC");
                        
                        if (mysqli_num_rows($select_tracker_info) > 0) {
                            $dataPoints2 = array(); // Initialize array for the second chart

                            while ($row = mysqli_fetch_assoc($select_tracker_info)) {
                                $selBmi = $row['bmi'];
                                $selDateTimestamp = strtotime($row['date_of_bmi']);
                                $selDate = date("F j, Y", $selDateTimestamp);

                                $dataPoints2[] = array("y" => $selBmi, "label" => $selDate);
                            }
                            ?>
                            <div id="chartContainer2" style="height: 300px; width: 100%;"></div>
                            <?php
                            }else {
                                echo '<div style="display: flex; justify-content: center; align-items: center; height: 100%;">';
                                echo '<h2>No records</h2>';
                                echo '</div>';
                            }
                        ?>
                    </div>
                </div>
            </div>
        </div>

            <div class="row card-container">
                <div class="col-sm-6">
                    <div class="card custom-card">
                        <div class="card-body">
                            <?php
                            // Assume $conn is the database connection object
                            $select_tracker_info = mysqli_query($conn, "SELECT tracker.weight, tracker.date_of_bmi FROM tracker INNER JOIN user_info ON tracker.user_ID=user_info.userID WHERE user_info.username='$username'");
                            if (mysqli_num_rows($select_tracker_info) > 0) {
                                $dataPoints3 = array(); // Initialize array for the third chart

                                while ($row = mysqli_fetch_assoc($select_tracker_info)) {
                                    $selWeight = $row['weight'];
                                    $selDateTimestamp = strtotime($row['date_of_bmi']);
                                    $selDate = date("F j, Y", $selDateTimestamp);

                                    $dataPoints3[] = array("y" => $selWeight, "label" => $selDate);
                                }
                                ?>
                                <div id="chartContainer3" style="height: 300px; width: 100%;"></div>
                            <?php
                            }else {
                                echo '<div style="display: flex; justify-content: center; align-items: center; height: 100%;">';
                                echo '<h2>No records</h2>';
                                echo '</div>';
                            }
                            ?>
                        </div>
                    </div>
                </div>

            <div class="col-sm-6">
                <div class="card custom-card">
                    <div class="card-body">
                        <?php
                        // Assume $conn is the database connection object
                        $select_tracker_info = mysqli_query($conn, "SELECT tracker.height, tracker.date_of_bmi FROM tracker INNER JOIN user_info ON tracker.user_ID=user_info.userID WHERE user_info.username='$username'");
                        if (mysqli_num_rows($select_tracker_info) > 0) {
                            $dataPoints4 = array(); // Initialize array for the fourth chart

                            while ($row = mysqli_fetch_assoc($select_tracker_info)) {
                                $selHeight = $row['height'];
                                $selDateTimestamp = strtotime($row['date_of_bmi']);
                                $selDate = date("F j, Y", $selDateTimestamp);

                                $dataPoints4[] = array("y" => $selHeight, "label" => $selDate);
                            }
                            ?>
                            <div id="chartContainer4" style="height: 300px; width: 100%;"></div>
                        <?php
                        }else {
                            echo '<div style="display: flex; justify-content: center; align-items: center; height: 100%;">';
                            echo '<h2>No records</h2>';
                            echo '</div>';
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>

    <script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
    <script>
        window.onload = function () {
            // Chart initialization for the first card
            var chart1 = new CanvasJS.Chart("chartContainer1", {
                animationEnabled: true,
                title: {
                    text: "BMI Classification Progress"
                },
                axisY: {
                    title: "BMI Classification",
                    interval: 1,
                    ticks: [
                        { y: 1, label: "Underweight" },
                        { y: 2, label: "Normal weight" },
                        { y: 3, label: "Overweight" },
                        { y: 4, label: "Obesity" }
                    ]
                },
                axisX: {
                    title: "Date"
                },
                data: [{
                    type: "column",
                    dataPoints: <?php echo json_encode($dataPoints1, JSON_NUMERIC_CHECK); ?>
                }]
            });
            chart1.render();

            // Chart initialization for the second card
            var chart2 = new CanvasJS.Chart("chartContainer2", {
                animationEnabled: true,
                title: {
                    text: "BMI Progress"
                },
                axisY: {
                    title: "BMI"
                },
                axisX: {
                    title: "Date"
                },
                data: [{
                    type: "line",
                    dataPoints: <?php echo json_encode($dataPoints2, JSON_NUMERIC_CHECK); ?>
                }]
            });
            chart2.render();

            var chart3 = new CanvasJS.Chart("chartContainer3", {
            animationEnabled: true,
            title: {
                text: "Weight Progress"
            },
            axisY: {
                title: "Weight"
            },
            axisX: {
                title: "Date"
            },
            data: [{
                type: "line",
                dataPoints: <?php echo json_encode($dataPoints3, JSON_NUMERIC_CHECK); ?>
            }]
        });
        chart3.render();

        var chart4 = new CanvasJS.Chart("chartContainer4", {
            animationEnabled: true,
            title: {
                text: "Height Progress"
            },
            axisY: {
                title: "Height"
            },
            axisX: {
                title: "Date"
            },
            data: [{
                type: "line", // You can change the chart type as needed (line, column, etc.)
                dataPoints: <?php echo json_encode($dataPoints4, JSON_NUMERIC_CHECK); ?>
            }]
        });
        chart4.render();

        }
    </script>


<?php
    } else {
        echo "You need to log in";
        header('Refresh:2; URL=index.html');
    }
?>
</body>
</html>
