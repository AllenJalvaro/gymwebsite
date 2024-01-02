<?php
require "phpqrcode/qrlib.php";
require "db_conn.php";
session_start();

if (isset($_SESSION['username'])) {
    $username = $_SESSION['username'];

    if (isset($_GET['image'])) {
        $file = $_GET['image'];
        if (file_exists($file)) {
            // Send the file as a download
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="' . basename($file) . '"');
            header('Content-length: ' . filesize($file));
            
            // Output the file content
            readfile($file);
            
            // Redirect after download
            echo '<script type="text/javascript">setTimeout(function(){ window.location.href = "profile_view.php"; }, 2000);</script>';

            exit;
        }
    }

} else {
    // If not logged in, show an error message
    echo "<p class='alert alert-danger'>You need to log in</p>";
    header('Refresh:2; URL=index.html');
}
?>
