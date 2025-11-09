<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Admin & Restaurant Signup</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: white;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .form-container {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            padding: 40px;
            max-width: 500px;
            width: 100%;
            margin: 20px;
        }

        .form-icon {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
        }

        .form-icon i {
            font-size: 35px;
            color: white;
        }

        h2 {
            color: #333;
            font-weight: 700;
            margin-bottom: 10px;
        }

        .subtitle {
            color: #666;
            margin-bottom: 30px;
        }

        .form-label {
            font-weight: 600;
            color: #555;
            margin-bottom: 8px;
        }

        .form-control {
            border-radius: 10px;
            padding: 12px 15px;
            border: 2px solid #e0e0e0;
            transition: all 0.3s;
        }

        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }

        .btn-submit {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            border-radius: 10px;
            padding: 12px;
            font-weight: 600;
            font-size: 16px;
            width: 100%;
            margin-top: 20px;
            transition: transform 0.2s;
        }

        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(102, 126, 234, 0.3);
        }

        .is-invalid {
            border-color: #dc3545;
        }

        small.text-danger {
            font-size: 0.85rem;
        }
    </style>
</head>

<body>
    <!-- Admin Form -->
    <div class="form-container" id="adminForm">
        <div class="form-icon"><i class="fas fa-user-shield"></i></div>
        <h2 class="text-center">Admin Signup</h2>
        <p class="text-center subtitle">Create your admin account</p>

        <form id="adminSignupForm">
            <div class="mb-3">
                <label class="form-label">Username</label>
                <input type="text" class="form-control" id="adminUsername" placeholder="Enter username" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" class="form-control" id="admin_email" placeholder="Enter email" required>
                <small id="admin_email_error" class="text-danger"></small>
            </div>

            <div class="mb-3">
                <label class="form-label">Password</label>
                <input type="password" class="form-control" id="adminPassword" placeholder="Enter password" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Confirm Password</label>
                <input type="password" class="form-control" id="confirmPassword" placeholder="Confirm password"
                    required>
            </div>

            <button type="submit" class="btn btn-primary btn-submit">Continue to Restaurant Form</button>
        </form>
    </div>

    <!-- Restaurant Form -->
    <div class="form-container" id="restaurantForm" style="display:none;">
        <div class="form-icon"><i class="fas fa-utensils"></i></div>
        <h2 class="text-center">Restaurant Details</h2>
        <p class="text-center subtitle">Tell us about your restaurant</p>

        <form id="restaurantSignupForm">
            <div class="mb-3">
                <label class="form-label">Restaurant Name</label>
                <input type="text" class="form-control" id="restaurantName" required>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" class="form-control" id="restaurant_email" required>
                    <small id="restaurant_email_error" class="text-danger"></small>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Phone</label>
                    <input type="tel" class="form-control" id="restaurantPhone" required>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Address</label>
                <input type="text" class="form-control" id="restaurantAddress" required>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Domain</label>
                    <input type="text" class="form-control" id="restaurant_domain" required>
                    <small id="restaurant_domain_error" class="text-danger"></small>
                    <small class="text-muted" id="domain_help" style="display:block;margin-top:4px;">Enter your custom
                        domain </small>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">SubDomain</label>
                    <input type="text" class="form-control" id="restaurantSubDomain" required>
                    <small class="text-muted" id="subdomain_preview" style="display:block;margin-top:4px;">Preview:
                        <span id="subdomainPreviewValue">sub.yourdomain.com(optional)/span></small>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Description</label>
                <textarea class="form-control" id="restaurantDescription" required></textarea>
            </div>

            <button type="submit" class="btn btn-primary btn-submit">Complete Registration</button>
        </form>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script>
        $(document).ready(function() {

            // ✅ Universal uniqueness checker
            function checkUnique(fieldId, type, errorId) {
                let value = $('#' + fieldId).val();
                if (value === '') return;

                $.ajax({
                    url: "{{ route('check.unique') }}", // Laravel route
                    method: 'GET',
                    data: {
                        type: type,
                        value: value
                    },
                    success: function(response) {
                        if (!response.unique) {
                            $('#' + errorId).text('This ' + type.replace('_', ' ') +
                                ' is already taken.');
                            $('#' + fieldId).addClass('is-invalid');
                        } else {
                            $('#' + errorId).text('');
                            $('#' + fieldId).removeClass('is-invalid');
                        }
                    },
                    error: function() {
                        $('#' + errorId).text('Server error. Try again.');
                    }
                });
            }

            // ✅ Attach blur events
            $('#admin_email').on('blur', function() {
                checkUnique('admin_email', 'admin_email', 'admin_email_error');
            });
            $('#restaurant_email').on('blur', function() {
                checkUnique('restaurant_email', 'restaurant_email', 'restaurant_email_error');
            });
            $('#restaurant_domain').on('blur', function() {
                checkUnique('restaurant_domain', 'restaurant_domain', 'restaurant_domain_error');
            });

            // ✅ Admin form submission
            $('#adminSignupForm').on('submit', function(e) {
                e.preventDefault();

                const username = $('#adminUsername').val();
                const email = $('#admin_email').val();
                const password = $('#adminPassword').val();
                const confirmPassword = $('#confirmPassword').val();

                if (password !== confirmPassword) {
                    alert('Passwords do not match!');
                    return;
                }

                // Save to client session
                sessionStorage.setItem('adminUsername', username);
                sessionStorage.setItem('adminEmail', email);
                sessionStorage.setItem('adminPassword', password);
                sessionStorage.setItem('adminPasswordConfirmation', confirmPassword);

                // Move to next step
                $('#adminForm').fadeOut(400, function() {
                    $('#restaurantForm').fadeIn(400);
                });
            });

            // ✅ Restaurant form submission
            $('#restaurantSignupForm').on('submit', function(e) {
                e.preventDefault();

                const data = {
                    admin: {
                        username: sessionStorage.getItem('adminUsername'),
                        email: sessionStorage.getItem('adminEmail'),
                        password: sessionStorage.getItem('adminPassword'),
                        password_confirmation: sessionStorage.getItem('adminPasswordConfirmation')
                    },
                    restaurant: {
                        name: $('#restaurantName').val(),
                        email: $('#restaurant_email').val(),
                        phone: $('#restaurantPhone').val(),
                        address: $('#restaurantAddress').val(),
                        domain: $('#restaurant_domain').val(),
                        subdomain: (function() {
                            const d = $('#restaurant_domain').val().trim();
                            const s = $('#restaurantSubDomain').val().trim();
                            if (d && s) return s + '.' + d.replace(/^https?:\/\//, '').replace(
                                /\/$/, '');
                            return s; // if domain empty, just subdomain
                        })(),
                        description: $('#restaurantDescription').val()
                    }
                };

                // Example: send final data to backend
                $.ajax({
                    url: "{{ route('user.admin.create') }}",
                    method: "POST",
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content'),
                        data: data
                    },
                    success: function(res) {
                        alert('Registration successful!');
                        sessionStorage.clear();
                        $('#restaurantSignupForm')[0].reset();
                        $('#adminSignupForm')[0].reset();
                        $('#restaurantForm').fadeOut(400, function() {
                            $('#adminForm').fadeIn(400);
                        });

                    },
                    error: function(xhr) {
                        alert('Error submitting form');
                    }
                });
            });

        });

        // Live subdomain + domain preview
        function updateSubdomainPreview() {
            const domain = $('#restaurant_domain').val().trim();
            const sub = $('#restaurantSubDomain').val().trim();
            let preview = 'sub.yourdomain.com';
            if (sub && domain) {
                preview = sub + '.' + domain.replace(/^https?:\/\//, '').replace(/\/$/, '');
            } else if (sub) {
                preview = sub + '.yourdomain.com';
            } else if (domain) {
                preview = 'sub.' + domain.replace(/^https?:\/\//, '').replace(/\/$/, '');
            }
            $('#subdomainPreviewValue').text(preview);
        }
        $('#restaurant_domain, #restaurantSubDomain').on('input', updateSubdomainPreview);
        updateSubdomainPreview();
    </script>
</body>

</html>
