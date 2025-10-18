<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Super Admin Login</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
</head>

<body>
    <!-- Login Form -->
    <div class="auth-container active" id="loginContainer">
        <div class="auth-card">
            <div class="auth-header">
                <div class="auth-logo">
                    <i class="fas fa-utensils"></i>
                </div>
                <h2>Super Admin</h2>
                <p>Sign in to the admin dashboard</p>
            </div>

            <div class="auth-body">
                <div class="alert-custom alert-danger" id="loginError">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    <span id="loginErrorText"></span>
                </div>

                <form id="loginForm" method="POST" action="{{ route('superadmin.login') }}">
                    @csrf
                    <div class="form-group">
                        <label class="form-label">Email Address <span class="required">*</span></label>
                        <div class="input-group-custom">
                            <i class="fas fa-envelope"></i>
                            <input type="email" name="email" class="form-control-custom" id="loginEmail"
                                placeholder="Enter your email" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Password <span class="required">*</span></label>
                        <div class="input-group-custom">
                            <i class="fas fa-lock"></i>
                            <input type="password" class="form-control-custom" name="password" id="loginPassword"
                                placeholder="Enter your password" required>
                            <button type="button" class="password-toggle"
                                onclick="togglePassword('loginPassword', this)">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                    </div>

                    <div class="mb-3"></div>

                    <button type="submit" class="btn-primary-custom">
                        <i class="fas fa-sign-in-alt me-2"></i>Sign In
                    </button>
                </form>
            </div>

            <div class="auth-footer">
                <!-- Admin login only -->
            </div>
        </div>
    </div>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Toggle password visibility
        function togglePassword(inputId, button) {
            const input = document.getElementById(inputId);
            const icon = button.querySelector('i');

            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }

        // Clear all forms and alerts
        function clearForms() {
            $('form').trigger('reset');
            $('.alert-custom').removeClass('show');
            $('.form-control-custom').removeClass('is-invalid');
            $('#subdomainPreview').hide();
        }

        // Show error message
        function showError(containerId, message) {
            $(`#${containerId}`).addClass('show');
            $(`#${containerId}Text`).text(message);

            setTimeout(() => {
                $(`#${containerId}`).removeClass('show');
            }, 5000);
        }

        // Validate email format
        function isValidEmail(email) {
            return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
        }


        // Login form submission
        $('#loginForm').on('submit', function(e) {
            e.preventDefault();

            const email = $('#loginEmail').val().trim();
            const password = $('#loginPassword').val();

            // Client-side validation
            if (!isValidEmail(email)) {
                $('#loginEmail').addClass('is-invalid');
                showError('loginError', 'Please enter a valid email address');
                return;
            }

            if (password.length < 6) {
                $('#loginPassword').addClass('is-invalid');
                showError('loginError', 'Password must be at least 6 characters');
                return;
            }

            // Clear validation errors
            $('.form-control-custom').removeClass('is-invalid');

            const loginData = {
                email: email,
                password: password
            };

            const submitBtn = $(this).find('button[type="submit"]');
            const originalText = submitBtn.html();
            submitBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-2"></i>Signing in...');

            $.ajax({
                url: '{{ route('superadmin.login') }}',
                method: 'POST',
                data: loginData,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(res) {
                    submitBtn.prop('disabled', false).html(originalText);
                    if (res && res.redirect) {
                        window.location.href = res.redirect;
                    } else {
                        // fallback
                        window.location.href = '{{ route('super_admin.admins.index') }}';
                    }
                },
                error: function(xhr) {
                    submitBtn.prop('disabled', false).html(originalText);
                    if (xhr.status === 401) {
                        showError('loginError', (xhr.responseJSON && xhr.responseJSON.message) ? xhr
                            .responseJSON.message : 'Invalid credentials');
                    } else if (xhr.status === 422 && xhr.responseJSON && xhr.responseJSON.errors) {
                        // show first validation error
                        const errs = xhr.responseJSON.errors;
                        const firstField = Object.keys(errs)[0];
                        const msg = errs[firstField][0];
                        showError('loginError', msg);
                    } else {
                        showError('loginError', (xhr.responseJSON && xhr.responseJSON.message) ? xhr
                            .responseJSON.message : 'Unexpected login error');
                    }
                }
            });
        });

        // Remove validation error on input
        $('.form-control-custom').on('input', function() {
            $(this).removeClass('is-invalid');
        });

        // Initialize
        $(function() {
            // Super Admin login initialized
        });
    </script>
</body>

</html>
