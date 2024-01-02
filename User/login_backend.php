<?php
    require "db_conn.php";
    session_start();

    $username = $_POST['username'];
    $password = $_POST['password'];


    if (!empty($username) && !empty($password) ){

    $select = "SELECT username, password FROM user_info WHERE username='$username'";
    $result = mysqli_query($conn, $select);

    $adminQuery = "SELECT * FROM admin WHERE admin_username='$username'";
    $adminresult = mysqli_query($conn, $adminQuery);

if($adminresult){
    if(mysqli_num_rows($adminresult)>0){
        $adminrow = mysqli_fetch_assoc($adminresult);
        if ($username == $adminrow['admin_username'] && $password == $adminrow['admin_password']) {
                    
            $_SESSION['username'] = $username;
            header('Location: admin.php');
        }
    }
}
//shop manager
$smQuery = "SELECT * FROM shopmanager WHERE sm_username='$username'";
$smresult = mysqli_query($conn, $smQuery);

if($smresult){
    if(mysqli_num_rows($smresult)>0){
        $smrow = mysqli_fetch_assoc($smresult);
        if ($username == $smrow['sm_username'] && $password == $smrow['sm_password']) {
                    
            $_SESSION['username'] = $username;
            header('Location: shopmanager.php');
        }
    }
}


    if ($result) {
        if (mysqli_num_rows($result) > 0) {
                $row = mysqli_fetch_assoc($result);

                if ($username == $row['username'] && $password == $row['password']) {
                    
                    $_SESSION['username'] = $username;
                    header('Location: new_home_screen.php');
                }else {
                    echo "<script>alert('Invalid credential');</script>";
                    header("refresh:0 URL=login.php");
                }
        }else {
            echo "<script>alert('You are not registered');</script>";
            header("refresh:0 URL=login.php");
        }
    }else {
        echo "<script>alert('Error in query');</script>";
        header("refresh:0 URL=login.php");
    }
    }else {
        echo "<script>alert('fill all fields');</script>";
        header("refresh:0 URL=login.php");
    }
    


?>