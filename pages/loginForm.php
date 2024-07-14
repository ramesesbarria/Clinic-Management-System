<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
    <title>Medical Clinic - Login</title>
    <link rel="icon" type="image/x-icon" href="img/logo.png">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Merriweather:wght@400;700&display=swap" rel="stylesheet" >
    <style>
        body {
            font-family: "Merriweather", serif;
            background-image: url('../img/background.png');
            background-size: cover;
            background-repeat: no-repeat;
            background-attachment: fixed;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 0;
            padding: 0;
        }
        .container {
            background-color: #fff;
            padding: 30px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
            border-radius: 8px;
            max-width: 100%;
            overflow: hidden;
            margin: 40px;
        }
        .lg-form h4, .register-form h2, .forgot-password-form h2 { margin: 20px auto; }
        .register-form { margin: 0; }
        .error {
            color: red;
            font-size: 0.8em;
            margin-bottom: 15px;
        }
        .btn-primary {
            background-color: #12229D;
            border-color: #12229D;
        }
        .btn-primary:hover {
            background-color: #2f40c2;
            border-color: #2f40c2;
        }
        .btn-block {
            width: 100%;
            margin-bottom: 10px;
        }
        .text-center {
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <div id="Login">
            <h4 class="mb-3">Login</h4>
            <?php
            session_start();
            if (isset($_SESSION['login_error'])) {
                echo '<p class="error">' . $_SESSION['login_error'] . '</p>';
                unset($_SESSION['login_error']);
            }
            ?>
            <form action="../Models/login.php" method="post">
                <div class="mb-3">
                    <input type="email" class="form-control" id="email" name="email" placeholder="Email" required>
                </div>
                <div class="mb-3">
                    <input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
                </div>
                <div class="row align-items-center">
                    <div class="col">
                        <button type="submit" class="btn btn-primary btn-block">Sign in</button>
                    </div>
                </div>
                <p class="text-center">Don't have an account? <a href="javascript:void(0);" onclick="showRegister()" class="registerlink">Register</a></p>
            </form>
        </div>
    </div>

    <script>
        function showRegister() {
            window.location.href = 'registrationForm.html'; // Redirect to the registration form
        }
    </script>
</body>
</html>
