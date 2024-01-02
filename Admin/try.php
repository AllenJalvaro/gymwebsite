<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <title>Document</title>

    <style>
        /* Custom class for styling the cards */
        .main-cont {
            margin: 20px;
            margin-top: 100px;
        }
        .custom-card {
            height: 400px; /* Fixed height for the card */
            padding: 20px; /* Adjust the padding as needed */
            border-radius: 10px; /* Adjust the border radius for a more rectangular shape */
            overflow: auto; /* Enable overflow for content */
        }

        /* Add margin between the cards */
        .card-container {
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="main-cont">
        <div class="row card-container">
            <div class="col-sm-6">
                <div class="card custom-card">
                    <div class="card-body">
                        <!-- body -->
                    </div>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="card custom-card">
                    <div class="card-body">
                    <?php
                        $dataPoints = array(
                            array("y" => 150, "label" => "December 1, 2023"),
                            array("y" => 152, "label" => "December 5, 2023"),
                            array("y" => 148, "label" => "December 10, 2023"),
                            array("y" => 145, "label" => "December 15, 2023"),
                            array("y" => 147, "label" => "December 20, 2023"),
                            array("y" => 143, "label" => "December 25, 2023"),
                            array("y" => 142, "label" => "December 30, 2023"),
                            array("y" => 200, "label" => "January 1, 2024"),
                        );
                        ?>
                        <script>
                            window.onload = function () {
                                var chart = new CanvasJS.Chart("chartContainer", {
                                    title: {
                                        text: "Weight Progress"
                                    },
                                    axisY: {
                                        title: "Your weights"
                                    },
                                    data: [{
                                        type: "line",
                                        dataPoints: <?php echo json_encode($dataPoints, JSON_NUMERIC_CHECK); ?>
                                    }]
                                });
                                chart.render();
                            }
                        </script>
                        <div id="chartContainer" style="height: 300px; width: 100%;"></div>
                        <script src="https://cdn.canvasjs.com/canvasjs.min.js"></script>
                    </div>
                </div>
            </div>
        </div>

        <div class="row card-container">
            <div class="col-sm-6">
                <div class="card custom-card">
                    <div class="card-body">
                        <!-- body -->
                    </div>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="card custom-card">
                    <div class="card-body">
                        <!-- body -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
