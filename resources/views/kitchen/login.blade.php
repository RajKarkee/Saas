<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modern Login Form</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <style>
        body {
            background-color: #ffffff;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .login-container {
            background-color: #ffffff;
            border-radius: 10px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            padding: 40px;
            max-width: 400px;
            width: 100%;
            text-align: center;
        }

        .form-control {
            border-radius: 25px;
            border: 1px solid #ddd;
            padding: 15px;
            margin-bottom: 20px;
            font-size: 16px;
        }

        .form-control:focus {
            border-color: #007bff;
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
        }

        .btn-login {
            background-color: #007bff;
            border: none;
            border-radius: 25px;
            padding: 15px;
            width: 100%;
            font-size: 16px;
            color: #ffffff;
            transition: background-color 0.3s;
        }

        .btn-login:hover {
            background-color: #0056b3;
        }

        .login-title {
            margin-bottom: 30px;
            color: #333;
            font-weight: 300;
        }

        .error {
            color: #dc3545;
            font-size: 14px;
            margin-top: -15px;
            margin-bottom: 20px;
        }
    </style>
</head>

<body>
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-4">
                <div class="login-container">
                    <h2 class="login-title">Login</h2>
                    <form id="loginForm" method="POST" action="{{ route('kitchen.login') }}">
                        @csrf
                        <input type="email" class="form-control" id="email" name="email" placeholder="Email"
                            required>
                        <input type="password" class="form-control" id="password" name="password"
                            placeholder="Password" required>
                        <div class="error" id="errorMessage"></div>
                        <button type="submit" class="btn btn-login">Login</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Custom JS -->
    <script>
        $(document).ready(function() {
            $('#loginForm').on('submit', function(e) {
                e.preventDefault();
                var email = $('#email').val().trim();
                var password = $('#password').val().trim();
                var errorMessage = '';

                if (email === '') {
                    errorMessage = 'Email is required.';
                } else if (!isValidEmail(email)) {
                    errorMessage = 'Please enter a valid email.';
                } else if (password === '') {
                    errorMessage = 'Password is required.';
                } else if (password.length < 6) {
                    errorMessage = 'Password must be at least 6 characters.';
                }

                $('#errorMessage').text(errorMessage);
                if (errorMessage === '') {
                    this.submit();
                }
            });

            function isValidEmail(email) {
                var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                return emailRegex.test(email);
            }
        });
    </script>
</body>

</html>
