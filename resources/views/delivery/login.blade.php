<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delivery Login</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .login-container {
            max-width: 450px;
            width: 100%;
        }

        .login-card {
            background: #ffffff;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            overflow: hidden;
            animation: slideUp 0.6s ease;
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .login-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 40px 30px;
            text-align: center;
            color: white;
        }

        .login-header .logo {
            width: 80px;
            height: 80px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            font-size: 36px;
            backdrop-filter: blur(10px);
        }

        .login-header h2 {
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 8px;
        }

        .login-header p {
            font-size: 14px;
            opacity: 0.9;
            margin: 0;
        }

        .login-body {
            padding: 40px 30px;
        }

        .form-group {
            margin-bottom: 25px;
        }

        .form-label {
            font-weight: 600;
            color: #1e293b;
            margin-bottom: 8px;
            font-size: 14px;
        }

        .input-group-custom {
            position: relative;
        }

        .input-group-custom i {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
            z-index: 10;
        }

        .form-control {
            height: 50px;
            padding-left: 45px;
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            font-size: 15px;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
        }

        .password-toggle {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #94a3b8;
            z-index: 10;
            transition: color 0.3s ease;
        }

        .password-toggle:hover {
            color: #667eea;
        }

        .form-options {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
        }

        .form-check {
            display: flex;
            align-items: center;
        }

        .form-check-input {
            width: 18px;
            height: 18px;
            margin-right: 8px;
            cursor: pointer;
        }

        .form-check-label {
            font-size: 14px;
            color: #64748b;
            cursor: pointer;
            margin: 0;
        }

        .forgot-password {
            font-size: 14px;
            color: #667eea;
            text-decoration: none;
            font-weight: 600;
            transition: color 0.3s ease;
        }

        .forgot-password:hover {
            color: #764ba2;
        }

        .btn-login {
            width: 100%;
            height: 50px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            border-radius: 12px;
            color: white;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.6);
        }

        .btn-login:active {
            transform: translateY(0);
        }

        .divider {
            display: flex;
            align-items: center;
            margin: 30px 0;
            color: #94a3b8;
            font-size: 14px;
        }

        .divider::before,
        .divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: #e2e8f0;
        }

        .divider span {
            padding: 0 15px;
        }

        .social-login {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
        }

        .btn-social {
            height: 50px;
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            background: white;
            color: #64748b;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .btn-social:hover {
            border-color: #667eea;
            color: #667eea;
            transform: translateY(-2px);
        }

        .btn-social i {
            font-size: 18px;
        }

        .signup-link {
            text-align: center;
            margin-top: 25px;
            font-size: 14px;
            color: #64748b;
        }

        .signup-link a {
            color: #667eea;
            text-decoration: none;
            font-weight: 600;
            transition: color 0.3s ease;
        }

        .signup-link a:hover {
            color: #764ba2;
        }

        .error-message {
            background: #fee2e2;
            color: #dc2626;
            padding: 12px 15px;
            border-radius: 10px;
            font-size: 14px;
            margin-bottom: 20px;
            display: none;
            animation: shake 0.5s ease;
        }

        @keyframes shake {

            0%,
            100% {
                transform: translateX(0);
            }

            25% {
                transform: translateX(-10px);
            }

            75% {
                transform: translateX(10px);
            }
        }

        .success-message {
            background: #d1fae5;
            color: #059669;
            padding: 12px 15px;
            border-radius: 10px;
            font-size: 14px;
            margin-bottom: 20px;
            display: none;
        }

        /* Loading spinner */
        .spinner {
            display: none;
            width: 20px;
            height: 20px;
            border: 3px solid rgba(255, 255, 255, 0.3);
            border-top-color: white;
            border-radius: 50%;
            animation: spin 0.8s linear infinite;
            margin-left: 10px;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        /* Responsive */
        @media (max-width: 480px) {
            .login-body {
                padding: 30px 20px;
            }

            .login-header {
                padding: 30px 20px;
            }

            .login-header h2 {
                font-size: 24px;
            }

            .social-login {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>

<body>
    <div class="login-container">
        <div class="login-card">
            <div class="login-header">
                <div class="logo">
                    <i class="fas fa-shipping-fast"></i>
                </div>
                <h2>Welcome Back</h2>
                <p>Sign in to access your delivery dashboard</p>
            </div>

            <div class="login-body">
                <div class="error-message" id="errorMessage">
                    <i class="fas fa-exclamation-circle"></i> Invalid email or password
                </div>

                <div class="success-message" id="successMessage">
                    <i class="fas fa-check-circle"></i> Login successful! Redirecting...
                </div>

                <form id="loginForm" action="{{ route('delivery.verify') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label class="form-label">Email Address</label>
                        <div class="input-group-custom">
                            <i class="fas fa-envelope"></i>
                            <input type="email" name="email" class="form-control" id="email"
                                placeholder="Enter your email" required>
                        </div>
                        <div class="invalid-feedback" id="emailError" style="display:none"></div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Password</label>
                        <div class="input-group-custom">
                            <i class="fas fa-lock"></i>
                            <input type="password" name="password" class="form-control" id="password"
                                placeholder="Enter your password" required>
                            <i class="fas fa-eye password-toggle" id="togglePassword"></i>
                        </div>
                        <div class="invalid-feedback" id="passwordError" style="display:none"></div>
                    </div>

                    <div class="form-options">
                        <div class="form-check">
                            <input type="checkbox" name="remember" class="form-check-input" id="rememberMe">
                            <label class="form-check-label" for="rememberMe">Remember me</label>
                        </div>
                        <a href="#" class="forgot-password">Forgot Password?</a>
                    </div>

                    <button type="submit" class="btn-login">
                        <span id="loginText">Sign In</span>
                        <span class="spinner" id="loginSpinner"></span>
                    </button>
                </form>

                <div class="divider">
                    <span>OR CONTINUE WITH</span>
                </div>

                <div class="social-login">
                    <button class="btn-social" onclick="socialLogin('google')">
                        <i class="fab fa-google"></i> Google
                    </button>
                    <button class="btn-social" onclick="socialLogin('facebook')">
                        <i class="fab fa-facebook-f"></i> Facebook
                    </button>
                </div>

                <div class="signup-link">
                    Don't have an account? <a href="#">Sign up now</a>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script>
        $(document).ready(function() {

            $('#togglePassword').click(function() {
                const passwordInput = $('#password');
                const icon = $(this);

                if (passwordInput.attr('type') === 'password') {
                    passwordInput.attr('type', 'text');
                    icon.removeClass('fa-eye').addClass('fa-eye-slash');
                } else {
                    passwordInput.attr('type', 'password');
                    icon.removeClass('fa-eye-slash').addClass('fa-eye');
                }
            });


            function clearErrors() {
                $('.invalid-feedback').hide().text('');
                $('.form-control').removeClass('is-invalid');
                $('#errorMessage').hide().text('Invalid email or password');
            }

            // Form submission via AJAX
            $('#loginForm').submit(function(e) {
                e.preventDefault();
                clearErrors();

                const $form = $(this);
                const url = $form.attr('action');
                const method = $form.attr('method') || 'POST';

                // Show loading
                $('#loginText').text('Signing in...');
                $('#loginSpinner').show();
                $('.btn-login').prop('disabled', true);

                $.ajax({
                    url: url,
                    method: method,
                    data: $form.serialize(),
                    dataType: 'json'
                }).done(function(response) {
                    // If server returns redirect path, go there
                    if (response.redirect) {
                        window.location.href = response.redirect;
                        return;
                    }

                    // Success response handling
                    if (response.success) {
                        $('#successMessage').show();
                        // Optionally store remembered user
                        if ($('#rememberMe').is(':checked')) {
                            localStorage.setItem('deliveryUser', JSON.stringify({
                                email: $('#email').val()
                            }));
                        } else {
                            localStorage.removeItem('deliveryUser');
                        }

                        setTimeout(function() {
                            // default redirect to dashboard if not provided
                            window.location.href = response.redirect ||
                                '/delivery/dashboard';
                        }, 900);
                    } else {
                        // Generic error fallback
                        $('#errorMessage').text(response.message || 'Invalid credentials').show();
                    }
                }).fail(function(xhr) {
                    if (xhr.status === 422 && xhr.responseJSON && xhr.responseJSON.errors) {
                        const errors = xhr.responseJSON.errors;
                        // Show first message in top error box
                        const firstField = Object.keys(errors)[0];
                        $('#errorMessage').text(errors[firstField][0]).show();

                        // Per-field errors
                        for (const field in errors) {
                            const msg = errors[field][0];
                            const fieldEl = $('#' + field);
                            if (fieldEl.length) {
                                fieldEl.addClass('is-invalid');
                                $('#' + field + 'Error').text(msg).show();
                            }
                        }
                        // Focus first invalid field
                        $('#' + firstField).focus();
                    } else if (xhr.status === 401) {
                        const msg = (xhr.responseJSON && xhr.responseJSON.message) ? xhr
                            .responseJSON.message : 'Unauthorized';
                        $('#errorMessage').text(msg).show();
                    } else {
                        $('#errorMessage').text('Server error. Please try again.').show();
                    }
                }).always(function() {
                    // Restore button state
                    $('#loginText').text('Sign In');
                    $('#loginSpinner').hide();
                    $('.btn-login').prop('disabled', false);
                });
            });

            // Auto-fill if remembered
            const savedUser = localStorage.getItem('deliveryUser');
            if (savedUser) {
                const user = JSON.parse(savedUser);
                $('#email').val(user.email);
                $('#rememberMe').prop('checked', true);
            }
        });

        function socialLogin(provider) {
            alert('Social login with ' + provider + ' would be implemented here');
        }

        // Add input focus effects
        $('.form-control').focus(function() {
            $(this).parent().find('i').first().css('color', '#667eea');
        }).blur(function() {
            $(this).parent().find('i').first().css('color', '#94a3b8');
        });
    </script>
</body>

</html>
