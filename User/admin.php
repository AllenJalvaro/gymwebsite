<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/html5-qrcode@2.0.8/dist/html5-qrcode.min.js"></script>


    <title>QR Scan Check in</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">

    <!-- Font Awesome CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">

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
                <a href="#" class="list-group-item list-group-item-action">
                    <i class="fa fa-dashboard"></i> Dashboard
                </a>
                <a href="training_guide.php" class="list-group-item list-group-item-action">
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
            <div id="you-qr-result"></div>
    <h1>scan qr html</h1>
    <div style="display: flex;justify-content: center;">
        <div id="my-qr-reader" style="width: 500px;">
        
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

<script src="https://unpkg.com/html5-qrcode"></script>
    <script>
        function domReader(fn){
            if (document.readyState === "complete" || document.readyState === "interactive") {
                setTimeout(fn, 1)
            }else{
                document.addEventListener("DOMContentLoaded", fn)
            }
        }

        domReader(function (){
            var myqr = document.getElementById('you-qr-result')
            var lastResult,countResults = 0;

            function onScanSuccess(decodeText, decodeResult){
                if (decodeText !== lastResult) {
                    ++countResults;
                    lastResult = decodeText;

                    //alert("You Qr is : " + decodeText,decodeResult)
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: decodeText + ' is checking in!',
                        confirmButtonColor: '#3085d6',
                        confirmButtonText: 'OK'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Redirect the user to another page
                            window.location.href = 'process_qr.php?decodedText=' + encodeURIComponent(decodeText);
                        }
                    });

                    myqr.innerHTML = `You scan ${countResults} : ${decodeText}`;

                    var xhr = new XMLHttpRequest();
                    var url = 'process_qr.php';
                    var params = 'decodedText=' + encodeURIComponent(decodeText);

                    xhr.open('POST', url, true);
                    xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');

                    xhr.onreadystatechange = function () {
                        if (xhr.readyState == 4 && xhr.status == 200) {
                            console.log('Server response:', xhr.responseText);
                        }
                    };

                    xhr.send(params);

                }
            }

            var htmlscanner = new Html5QrcodeScanner(
                "my-qr-reader", {fps:10,qrbox:250}
            )
            htmlscanner.render(onScanSuccess)
        })
    </script>

</body>

</html>
