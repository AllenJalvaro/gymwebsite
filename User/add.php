<?php
include('db_conn.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'];
    $procedure = $_POST['procedure'];
    $steps = $_POST['steps'];
    $reps = $_POST['reps'];

    
    if (isset($_FILES["image"]) && $_FILES["image"]["error"] == 0) {
        $targetDir = "training_guides_imgs/";
        $targetFile = $targetDir . basename($_FILES["image"]["name"]);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

        
        $check = getimagesize($_FILES["image"]["tmp_name"]);
        if ($check === false) {
            echo "Error: File is not an image.";
            $uploadOk = 0;
        }

        
        if (file_exists($targetFile)) {
            echo "Error: Sorry, file already exists.";
            $uploadOk = 0;
        }

        
        if ($_FILES["image"]["size"] > 500000) {
            echo "Error: Sorry, your file is too large.";
            $uploadOk = 0;
        }

        
        $allowedFormats = ["jpg", "jpeg", "png", "gif"];
        if (!in_array($imageFileType, $allowedFormats)) {
            echo "Error: Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
            $uploadOk = 0;
        }

        
        if ($uploadOk == 0) {
            echo "Error: File was not uploaded.";
        } else {
            if (move_uploaded_file($_FILES["image"]["tmp_name"], $targetFile)) {
                
                $sql = "INSERT INTO training (title, `procedure`, steps, reps, image_path) VALUES ('$title', '$procedure', '$steps', '$reps', '$targetFile')";

                if ($conn->query($sql) === TRUE) {
                    header("Location: training_guide.php");
                    exit();
                } else {
                    echo "Error: " . $sql . "<br>" . $conn->error;
                }
            } else {
                echo "Error: There was an error uploading your file.";
            }
        }
    } else {
        echo "Error: File not uploaded or invalid file.";
    }
}

$conn->close();
?>
