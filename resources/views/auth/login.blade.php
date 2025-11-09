<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login — Restaurant SaaS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary: #4f46e5;
            --primary-dark: #4338ca;
            --secondary: #7c3aed;
            --bg-light: #f8fafc;
            --border: #e2e8f0;
            --text-primary: #0f172a;
            --text-secondary: #64748b;
            --success: #10b981;
            --error: #ef4444;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', system-ui, -apple-system, sans-serif;

            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .login-container {
            width: 100%;
            max-width: 480px;
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

        .login-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            overflow: hidden;
        }

        .login-header {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: white;
            padding: 32px 32px 24px;
            text-align: center;
        }

        .login-header h1 {
            font-size: 28px;
            font-weight: 800;
            margin-bottom: 8px;
        }

        .login-header p {
            opacity: 0.95;
            font-size: 14px;
        }

        .login-body {
            padding: 32px;
        }

        .role-toggle {
            display: flex;
            gap: 8px;
            margin-bottom: 28px;
            background: var(--bg-light);
            padding: 6px;
            border-radius: 12px;
        }

        .role-btn {
            flex: 1;
            padding: 12px 16px;
            border: none;
            background: transparent;
            border-radius: 8px;
            font-weight: 600;
            font-size: 13px;
            color: var(--text-secondary);
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 4px;
        }

        .role-btn i {
            font-size: 18px;
        }

        .role-btn.active {
            background: white;
            color: var(--primary);
            box-shadow: 0 2px 8px rgba(79, 70, 229, 0.15);
        }

        .role-btn:hover:not(.active) {
            color: var(--text-primary);
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-label {
            display: block;
            font-weight: 600;
            font-size: 14px;
            color: var(--text-primary);
            margin-bottom: 8px;
        }

        .form-control {
            width: 100%;
            padding: 12px 16px;
            border: 2px solid var(--border);
            border-radius: 10px;
            font-size: 15px;
            transition: all 0.3s ease;
            font-family: 'Inter', sans-serif;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.1);
        }

        .input-icon {
            position: relative;
        }

        .input-icon i {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-secondary);
            font-size: 16px;
        }

        .input-icon .form-control {
            padding-left: 46px;
            /* leave space on the right for the password visibility toggle */
            padding-right: 46px;
        }

        .password-toggle {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            background: transparent;
            border: none;
            color: var(--text-secondary);
            cursor: pointer;
            font-size: 16px;
            padding: 6px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            z-index: 3;
        }

        .btn-login {
            width: 100%;
            padding: 14px;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: white;
            border: none;
            border-radius: 10px;
            font-weight: 700;
            font-size: 15px;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 8px;
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(79, 70, 229, 0.4);
        }

        .btn-login:active {
            transform: translateY(0);
        }

        .remember-forgot {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            font-size: 14px;
        }

        .remember-me {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .remember-me input[type="checkbox"] {
            width: 18px;
            height: 18px;
            cursor: pointer;
        }

        .forgot-link {
            color: var(--primary);
            text-decoration: none;
            font-weight: 600;
        }

        .forgot-link:hover {
            text-decoration: underline;
        }

        .divider {
            display: flex;
            align-items: center;
            text-align: center;
            margin: 24px 0;
            color: var(--text-secondary);
            font-size: 13px;
        }

        .divider::before,
        .divider::after {
            content: '';
            flex: 1;
            border-bottom: 1px solid var(--border);
        }

        .divider span {
            padding: 0 12px;
        }

        .signup-link {
            text-align: center;
            font-size: 14px;
            color: var(--text-secondary);
        }

        .signup-link a {
            color: var(--primary);
            font-weight: 700;
            text-decoration: none;
        }

        .signup-link a:hover {
            text-decoration: underline;
        }

        .alert {
            padding: 12px 16px;
            border-radius: 10px;
            margin-bottom: 20px;
            font-size: 14px;
            display: none;
        }

        .alert.show {
            display: block;
            animation: slideDown 0.3s ease;
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .alert-danger {
            background: #fee2e2;
            color: #991b1b;
            border: 1px solid #fecaca;
        }

        .alert-success {
            background: #d1fae5;
            color: #065f46;
            border: 1px solid #a7f3d0;
        }

        .back-home {
            position: absolute;
            top: 20px;
            left: 20px;
            color: blueviolet;
            text-decoration: none;
            font-weight: 600;
            font-size: 14px;
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 8px 16px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 8px;
            backdrop-filter: blur(10px);
            transition: all 0.3s ease;
        }

        .back-home:hover {
            background: rgba(255, 255, 255, 0.3);
            color: white;
        }

        @media (max-width: 576px) {
            .login-header h1 {
                font-size: 24px;
            }

            .login-body {
                padding: 24px 20px;
            }

            .role-btn {
                padding: 10px 12px;
                font-size: 12px;
            }

            .role-btn i {
                font-size: 16px;
            }

            .back-home {
                position: static;
                margin-bottom: 20px;
                display: inline-flex;
            }
        }
    </style>
</head>

<body>
    <a href="{{ url('/') }}" class="back-home">
        <i class="fas fa-arrow-left"></i> Back to Home
    </a>

    <div class="login-container">
        <div class="login-card">
            <div class="login-header">
                <h1><i class="fas fa-utensils"></i> Welcome Back</h1>
                <p>Sign in to your Restaurant SaaS account</p>
            </div>

            <div class="login-body">
                <!-- Role Toggle -->
                <div class="role-toggle">
                    <button type="button" class="role-btn active" data-role="admin" onclick="switchRole('admin')">
                        <i class="fas fa-user-shield"></i>
                        <span>Admin</span>
                    </button>
                    <button type="button" class="role-btn" data-role="staff" onclick="switchRole('staff')">
                        <i class="fas fa-users"></i>
                        <span>Staff</span>
                    </button>
                    <button type="button" class="role-btn" data-role="delivery" onclick="switchRole('delivery')">
                        <i class="fas fa-truck"></i>
                        <span>Delivery</span>
                    </button>
                </div>

                <!-- Alert Messages -->
                <div id="alertBox" class="alert"></div>

                <!-- Login Form -->
                <form id="loginForm" method="POST" action="{{ route('admin.verify') }}">
                    @csrf
                    <input type="hidden" name="role" id="roleInput" value="admin">

                    <div class="form-group">
                        <label class="form-label" for="email">Email Address</label>
                        <div class="input-icon">
                            <i class="fas fa-envelope"></i>
                            <input type="email" class="form-control" id="email" name="email"
                                placeholder="you@example.com" value="{{ old('email') }}" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="password">Password</label>
                        <div class="input-icon">
                            <i class="fas fa-lock"></i>
                            <input type="password" class="form-control" id="password" name="password"
                                placeholder="••••••••" required>
                            <button type="button" class="password-toggle" onclick="togglePassword()">
                                <i class="fas fa-eye" id="toggleIcon"></i>
                            </button>
                        </div>
                    </div>

                    <div class="remember-forgot">
                        <label class="remember-me">
                            <input type="checkbox" name="remember">
                            <span>Remember me</span>
                        </label>
                        <a href="#" class="forgot-link">Forgot password?</a>
                    </div>

                    <button type="submit" class="btn-login">
                        <i class="fas fa-sign-in-alt"></i> Sign In
                    </button>
                </form>

                <div class="divider">
                    <span>OR</span>
                </div>

                <div class="signup-link">
                    Don't have a restaurant account?
                    <a href="{{ url('/signup') }}">Sign up now</a>
                </div>
            </div>
        </div>
    </div>

    <script>
        const roleRoutes = {
            admin: "{{ route('admin.verify') }}",
            staff: "{{ route('delivery.verify') }}", // Staff uses same auth as delivery
            delivery: "{{ route('delivery.verify') }}"
        };

        let currentRole = 'admin';

        function switchRole(role) {
            currentRole = role;

            // Update active button
            document.querySelectorAll('.role-btn').forEach(btn => {
                btn.classList.remove('active');
            });
            document.querySelector(`[data-role="${role}"]`).classList.add('active');

            // Update form action
            document.getElementById('loginForm').action = roleRoutes[role];
            document.getElementById('roleInput').value = role;

            // Clear any alerts
            hideAlert();
        }

        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const toggleIcon = document.getElementById('toggleIcon');

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleIcon.classList.remove('fa-eye');
                toggleIcon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                toggleIcon.classList.remove('fa-eye-slash');
                toggleIcon.classList.add('fa-eye');
            }
        }

        function showAlert(message, type = 'danger') {
            const alertBox = document.getElementById('alertBox');
            alertBox.className = `alert alert-${type} show`;
            alertBox.textContent = message;
        }

        function hideAlert() {
            const alertBox = document.getElementById('alertBox');
            alertBox.classList.remove('show');
        }

        // Show Laravel validation errors
        @if ($errors->any())
            showAlert("{{ $errors->first() }}", 'danger');
        @endif

        // Show success messages
        @if (session('success'))
            showAlert("{{ session('success') }}", 'success');
        @endif

        @if (session('error'))
            showAlert("{{ session('error') }}", 'danger');
        @endif

        // Form submission animation
        document.getElementById('loginForm').addEventListener('submit', function(e) {
            const btn = this.querySelector('.btn-login');
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Signing in...';
            btn.disabled = true;
        });
    </script>
</body>

</html>
