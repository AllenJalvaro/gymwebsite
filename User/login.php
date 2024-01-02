<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-image: url('img/gym_home.jpg');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            height: 100vh;
            margin: 0;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .card {
            background: rgba(255, 255, 255, 0.9);
            border-radius: 30px;
            padding: 30px;;
        }

        .form-label {
            font-size: 20px;
        }

        .center-button {
            display: flex;
            justify-content: center;
            margin-top: 20px;
            
        }
        .btn{
            background-color: #161A30 !important;
            color: white;
            margin-top: 20px;
            padding: 5px 210px;
        }
        .btn:hover{
            color: gray;
        }
        .form-control {
            background-color: rgba(255, 255, 255, 0.3);
            border: 1px solid grey;   
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="row justify-content-center align-items-center min-vh-100">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h1 class="text-center mb-4">Flexin Login</h1>

                        <form action="login_backend.php" method="POST">
                            <div class="mb-3">
                                <label for="username" class="form-label">Username:</label>
                                <input type="text" name="username" class="form-control" placeholder="Enter username">
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label">Password:</label>
                                <input type="password" name="password" class="form-control" placeholder="Enter password">
                            </div>

                            <div class="center-button">
                                <button type="submit" name="btnLogin" class="btn">Login</button>
                            </div>
                        </form>

                        <p class="mt-3 text-center">
                            Already have an account? <a href="user_registration.php" style="color: black">Sign up</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
