<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Getting Started • Create Admin & Restaurant</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* Modern multi-step signup redesign */
        :root {
            --primary: #6366f1;
            --primary-rgb: 99, 102, 241;
            --primary-dark: #4f46e5;
            --secondary: #8b5cf6;
            --bg-gradient-start: #0f172a;
            --bg-gradient-end: #1e293b;
            --panel-bg: #ffffff;
            --panel-alt: #f1f5f9;
            --border: #e2e8f0;
            --border-strong: #cbd5e1;
            --text-primary: #0f172a;
            --text-secondary: #64748b;
            --success: #10b981;
            --error: #ef4444;
            --warning: #f59e0b;
            --focus-ring: rgba(99, 102, 241, 0.35);
            --shadow-strong: 0 20px 35px -10px rgba(0, 0, 0, 0.35);
            --shadow-soft: 0 4px 12px rgba(0, 0, 0, 0.08);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
            background: radial-gradient(circle at 20% 20%, rgba(99, 102, 241, 0.18), transparent), linear-gradient(135deg, var(--bg-gradient-start), var(--bg-gradient-end));
            min-height: 100vh;
            padding: 32px clamp(16px, 4vw, 40px);
            color: var(--text-primary);
            -webkit-font-smoothing: antialiased;
        }

        .signup-container {
            max-width: 1020px;
            margin: 0 auto;
            animation: fadeSlide 0.7s cubic-bezier(.16, .84, .44, 1);
            position: relative;
        }

        @keyframes fadeSlide {
            0% {
                opacity: 0;
                transform: translateY(32px) scale(.98)
            }

            100% {
                opacity: 1;
                transform: translateY(0) scale(1)
            }
        }

        .signup-card {
            background: var(--panel-bg);
            border-radius: 28px;
            box-shadow: var(--shadow-strong);
            overflow: hidden;
            position: relative;
            border: 1px solid rgba(255, 255, 255, 0.08);
            backdrop-filter: blur(6px);
        }

        .signup-header {
            background: linear-gradient(120deg, rgba(var(--primary-rgb), 0.95), var(--secondary));
            color: white;
            padding: 28px 32px 20px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .signup-header:before,
        .signup-header:after {
            content: "";
            position: absolute;
            border-radius: 50%;
            filter: blur(60px);
            opacity: .25;
            animation: pulse 6s linear infinite;
        }

        .signup-header:before {
            width: 280px;
            height: 280px;
            background: #fff;
            top: -120px;
            left: -80px;
        }

        .signup-header:after {
            width: 320px;
            height: 320px;
            background: #fff;
            bottom: -160px;
            right: -120px;
            animation-direction: reverse;
        }

        @keyframes pulse {
            0% {
                transform: scale(.9)
            }

            50% {
                transform: scale(1.05)
            }

            100% {
                transform: scale(.9)
            }
        }

        .signup-header h1 {
            font-size: clamp(1.9rem, 4vw, 2.35rem);
            font-weight: 800;
            margin: 0 0 4px;
            letter-spacing: .5px;
        }

        .signup-header p {
            opacity: 0.9;
            font-size: 15px;
            font-weight: 500;
            letter-spacing: .3px;
        }

        .signup-body {
            padding: 32px clamp(24px, 3.5vw, 44px) 34px;
            background: linear-gradient(180deg, var(--panel-bg) 0%, var(--panel-alt) 100%);
        }

        .form-section {
            margin-bottom: 28px;
        }

        .section-title {
            font-size: 14px;
            font-weight: 700;
            text-transform: uppercase;
            color: var(--text-secondary);
            margin-bottom: 18px;
            letter-spacing: .8px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .section-title i {
            color: var(--primary);
            font-size: 16px;
        }

        .form-section {
            margin-bottom: 36px;
        }

        .section-title i {
            color: var(--primary);
        }

        .form-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 22px;
        }

        .form-group {
            margin-bottom: 4px;
            position: relative;
        }

        .form-group.full-width {
            grid-column: 1 / -1;
        }

        .form-label {
            display: block;
            font-weight: 600;
            font-size: 13px;
            color: var(--text-secondary);
            margin-bottom: 8px;
            letter-spacing: .4px;
        }

        .form-label .required {
            color: var(--error);
        }

        .form-control,
        .form-select {
            width: 100%;
            padding: 14px 16px 13px;
            border: 1.5px solid var(--border);
            border-radius: 14px;
            font-size: 15px;
            font-family: 'Inter', sans-serif;
            background: #fff;
            box-shadow: var(--shadow-soft);
            transition: border-color .22s, box-shadow .22s, background .22s;
        }

        .form-control:focus,
        .form-select:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 4px var(--focus-ring);
            background: #fff;
        }

        .form-control::placeholder {
            color: #94a3b8;
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
            padding-right: 48px;
        }

        .password-toggle {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            background: linear-gradient(135deg, var(--panel-alt), #fff);
            border: 1px solid var(--border);
            color: var(--text-secondary);
            cursor: pointer;
            font-size: 15px;
            padding: 6px 8px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 10px;
            box-shadow: var(--shadow-soft);
        }

        .password-toggle:hover {
            color: var(--primary);
        }

        .input-help {
            font-size: 12px;
            color: var(--text-secondary);
            margin-top: 6px;
        }

        .btn-signup {
            width: 100%;
            padding: 15px 20px;
            background: linear-gradient(90deg, var(--primary), var(--secondary));
            color: #fff;
            border: none;
            border-radius: 16px;
            font-weight: 600;
            font-size: 15px;
            cursor: pointer;
            letter-spacing: .5px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            box-shadow: 0 6px 16px -6px rgba(var(--primary-rgb), 0.55);
            transition: transform .25s, box-shadow .25s, filter .25s;
        }

        .btn-signup i {
            font-size: 16px;
        }

        .btn-signup:hover {
            transform: translateY(-3px);
            box-shadow: 0 16px 28px -10px rgba(var(--primary-rgb), 0.55);
            filter: brightness(1.05);
        }

        .btn-signup:active {
            transform: translateY(0);
        }

        .btn-signup:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            transform: none;
        }

        .btn-back {
            width: 100%;
            padding: 14px 20px;
            background: linear-gradient(135deg, #fff, var(--panel-alt));
            color: var(--text-secondary);
            border: 1px solid var(--border);
            border-radius: 14px;
            font-weight: 600;
            font-size: 14px;
            cursor: pointer;
            transition: .25s;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .btn-back:hover {
            background: var(--panel-alt);
            transform: translateY(-2px);
            color: var(--text-primary);
        }

        .btn-back:active {
            transform: translateY(0);
        }

        .step-indicator {
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 18px 0 0;
            gap: 10px;
        }

        .step {
            display: flex;
            flex-direction: column;
            align-items: center;
            opacity: .45;
            transition: all .35s;
        }

        .step.active {
            opacity: 1;
        }

        .step-number {
            width: 44px;
            height: 44px;
            border-radius: 14px;
            background: rgba(255, 255, 255, .2);
            border: 1px solid rgba(255, 255, 255, .55);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            font-size: 15px;
            color: #fff;
            margin-bottom: 8px;
            backdrop-filter: blur(4px);
            box-shadow: inset 0 0 0 1px rgba(255, 255, 255, 0.35);
        }

        .step.active .step-number {
            background: #fff;
            color: var(--primary);
            border-color: #fff;
            box-shadow: 0 6px 18px -4px rgba(0, 0, 0, 0.25);
        }

        .step-label {
            font-size: 12px;
            color: rgba(255, 255, 255, .85);
            font-weight: 600;
            letter-spacing: .6px;
        }

        .step.active .step-label {
            color: #fff;
        }

        .step-divider {
            width: 70px;
            height: 2px;
            background: rgba(255, 255, 255, .3);
            margin-bottom: 30px;
            border-radius: 2px;
            position: relative;
            overflow: hidden;
        }

        .step.active+.step-divider {
            background: linear-gradient(90deg, #fff, rgba(255, 255, 255, .55));
        }

        .step.active~.step .step-divider {
            background: rgba(255, 255, 255, 0.6);
        }

        .terms-check {
            display: flex;
            align-items: flex-start;
            gap: 10px;
            margin-bottom: 20px;
            font-size: 14px;
            color: var(--text-secondary);
        }

        .terms-check input[type="checkbox"] {
            width: 18px;
            height: 18px;
            margin-top: 2px;
            cursor: pointer;
        }

        .terms-check a {
            color: var(--primary);
            text-decoration: none;
            font-weight: 600;
        }

        .terms-check a:hover {
            text-decoration: underline;
        }

        .login-link {
            text-align: center;
            font-size: 14px;
            color: var(--text-secondary);
            margin-top: 20px;
        }

        .login-link a {
            color: var(--primary);
            font-weight: 700;
            text-decoration: none;
        }

        .login-link a:hover {
            text-decoration: underline;
        }

        .alert {
            padding: 14px 18px;
            border-radius: 14px;
            margin-bottom: 26px;
            font-size: 14px;
            display: none;
            font-weight: 500;
            letter-spacing: .3px;
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
            background: #fef2f2;
            color: #b91c1c;
            border: 1px solid #fecaca;
        }

        .alert-success {
            background: #ecfdf5;
            color: #065f46;
            border: 1px solid #a7f3d0;
        }

        .back-home {
            display: inline-flex;
            align-items: center;
            gap: 9px;
            margin-bottom: 26px;
            color: #fff;
            text-decoration: none;
            font-weight: 600;
            font-size: 13px;
            padding: 10px 18px;
            background: linear-gradient(135deg, rgba(255, 255, 255, .18), rgba(255, 255, 255, .08));
            border: 1px solid rgba(255, 255, 255, .25);
            border-radius: 14px;
            backdrop-filter: blur(14px);
            transition: .35s;
            letter-spacing: .5px;
        }

        .back-home:hover {
            background: linear-gradient(135deg, rgba(255, 255, 255, .28), rgba(255, 255, 255, .16));
        }

        .password-strength {
            margin-top: 10px;
            font-size: 11.5px;
            font-weight: 500;
            letter-spacing: .4px;
        }

        .strength-bar {
            height: 6px;
            background: var(--border);
            border-radius: 4px;
            overflow: hidden;
            margin-top: 6px;
            position: relative;
        }

        .strength-fill {
            height: 100%;
            width: 0%;
            transition: width .45s cubic-bezier(.16, .84, .44, 1), background .45s;
            background: var(--error);
        }

        .strength-fill.weak {
            width: 33%;
            background: var(--error);
        }

        .strength-fill.medium {
            width: 66%;
            background: #f59e0b;
        }

        .strength-fill.strong {
            width: 100%;
            background: var(--success);
        }

        /* Inline field feedback */
        .field-feedback {
            margin-top: 6px;
            font-size: 12px;
            font-weight: 500;
            letter-spacing: .3px;
        }

        .field-feedback.feedback-error {
            color: var(--error);
        }

        .field-feedback.feedback-success {
            color: var(--success);
        }

        .field-feedback.feedback-loading {
            color: var(--warning);
        }

        @media (max-width: 760px) {
            body {
                padding: 26px 16px;
            }

            .signup-card {
                border-radius: 24px;
            }

            .signup-header {
                padding: 34px 30px 24px;
            }

            .signup-header h1 {
                font-size: 2rem;
            }

            .signup-body {
                padding: 34px 26px 36px;
            }

            .form-row {
                grid-template-columns: 1fr;
            }

            .btn-back,
            .btn-signup {
                border-radius: 14px;
            }
        }

        /* Logo uploader */
        .logo-uploader {
            border: 1.5px dashed var(--border-strong);
            border-radius: 14px;
            background: #fff;
            padding: 14px;
            display: grid;
            grid-template-columns: 160px 1fr;
            gap: 16px;
            align-items: center;
        }

        .logo-thumb {
            width: 160px;
            height: 160px;
            border-radius: 12px;
            overflow: hidden;
            position: relative;
            background: repeating-conic-gradient(#f1f5f9 0% 25%, transparent 0% 50%) 50% / 16px 16px;
            border: 1px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .logo-thumb canvas,
        .logo-thumb img {
            max-width: 100%;
            max-height: 100%;
            display: block;
        }

        .logo-actions {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }

        .logo-actions .btn {
            padding: 10px 14px;
            border-radius: 10px;
            border: 1px solid var(--border);
            background: #fff;
            color: var(--text-secondary);
            font-weight: 600;
            cursor: pointer;
        }

        .logo-actions .btn:hover {
            background: var(--panel-alt);
            color: var(--text-primary);
        }

        .logo-actions .switch {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-size: 13px;
            color: var(--text-secondary);
        }

        .logo-actions input[type="checkbox"] {
            width: 18px;
            height: 18px;
        }
    </style>
</head>

<body>
    <div class="signup-container">
        <a href="{{ url('/') }}" class="back-home">
            <i class="fas fa-arrow-left"></i> Back to Home
        </a>

        <div class="signup-card">
            <div class="signup-header">
                <h1><i class="fas fa-bolt"></i> Let’s Set Things Up</h1>
                <p>Start with your admin credentials. Then we’ll capture your restaurant details.</p>
                <!-- Step indicator -->
                <div class="step-indicator">
                    <div class="step active" id="step1Indicator">
                        <span class="step-number">1</span>
                        <span class="step-label">Admin</span>
                    </div>
                    <div class="step-divider"></div>
                    <div class="step" id="step2Indicator">
                        <span class="step-number">2</span>
                        <span class="step-label">Restaurant</span>
                    </div>
                </div>
            </div>

            <div class="signup-body">
                <!-- Alert Messages -->
                <div id="alertBox" class="alert"></div>

                <!-- Step 1: Admin Account -->
                <form id="adminForm" method="POST" action="#" style="display: block;">
                    @csrf

                    <div class="form-section">
                        <div class="section-title">
                            <i class="fas fa-user-circle"></i>
                            Admin Information
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label" for="admin_name">
                                    Full Name <span class="required">*</span>
                                </label>
                                <div class="input-icon">
                                    <i class="fas fa-user"></i>
                                    <input type="text" class="form-control" id="admin_name" name="name"
                                        placeholder="John Doe" value="{{ old('name') }}" required>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="form-label" for="admin_email">
                                    Email Address <span class="required">*</span>
                                </label>
                                <div class="input-icon">
                                    <i class="fas fa-envelope"></i>
                                    <input type="email" class="form-control" id="admin_email" name="email"
                                        placeholder="admin@restaurant.com" value="{{ old('email') }}" required>
                                </div>
                                <div id="admin_email_feedback" class="field-feedback" aria-live="polite"></div>
                            </div>


                        </div>
                    </div>

                    <!-- Security -->
                    <div class="form-section">
                        <div class="section-title">
                            <i class="fas fa-lock"></i>
                            Security
                        </div>

                        <div class="form-row">
                            <div class="form-group full-width">
                                <label class="form-label" for="admin_password">
                                    Password <span class="required">*</span>
                                </label>
                                <div class="input-icon">
                                    <i class="fas fa-lock"></i>
                                    <input type="password" class="form-control" id="admin_password" name="password"
                                        placeholder="••••••••" required oninput="checkPasswordStrength()">
                                    <button type="button" class="password-toggle"
                                        onclick="togglePassword('admin_password', 'toggleIcon1')">
                                        <i class="fas fa-eye" id="toggleIcon1"></i>
                                    </button>
                                </div>
                                <div class="password-strength">
                                    <div class="strength-bar">
                                        <div class="strength-fill" id="strengthFill"></div>
                                    </div>
                                    <div id="strengthText" class="input-help">Use at least 8 characters with letters,
                                        numbers & symbols</div>
                                </div>
                            </div>

                            <div class="form-group full-width">
                                <label class="form-label" for="admin_password_confirmation">
                                    Confirm Password <span class="required">*</span>
                                </label>
                                <div class="input-icon">
                                    <i class="fas fa-lock"></i>
                                    <input type="password" class="form-control" id="admin_password_confirmation"
                                        name="password_confirmation" placeholder="••••••••" required>
                                    <button type="button" class="password-toggle"
                                        onclick="togglePassword('admin_password_confirmation', 'toggleIcon2')">
                                        <i class="fas fa-eye" id="toggleIcon2"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="btn-signup">
                        <i class="fas fa-arrow-right"></i> Continue → Restaurant Details
                    </button>
                </form>

                <!-- Step 2: Restaurant Details (Hidden initially) -->
                <form id="restaurantForm" method="POST" action="{{ route('user.admin.create') }}"
                    style="display: none;">
                    @csrf
                    <input type="hidden" name="admin_id" id="admin_id" value="">

                    <div class="form-section">
                        <div class="section-title">
                            <i class="fas fa-utensils"></i>
                            Restaurant Information
                        </div>

                        <div class="form-row">
                            <div class="form-group full-width">
                                <label class="form-label" for="restaurant_name">
                                    Restaurant Name <span class="required">*</span>
                                </label>
                                <div class="input-icon">
                                    <i class="fas fa-store"></i>
                                    <input type="text" class="form-control" id="restaurant_name" name="name"
                                        placeholder="e.g., Bella Italia Restaurant" value="{{ old('name') }}">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="form-label" for="restaurant_email">
                                    Restaurant Email <span class="required">*</span>
                                </label>
                                <div class="input-icon">
                                    <i class="fas fa-envelope"></i>
                                    <input type="email" class="form-control" id="restaurant_email" name="email"
                                        placeholder="contact@restaurant.com" value="{{ old('email') }}">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="form-label" for="domain">
                                    Domain (Optional)
                                </label>
                                <div class="input-icon">
                                    <i class="fas fa-globe"></i>
                                    <input type="text" class="form-control" id="domain" name="domain"
                                        placeholder="restaurant.com" value="{{ old('domain') }}">
                                </div>
                                <div class="input-help">Your primary domain name (optional: can use our subdomain)
                                </div>
                                <div id="domain_feedback" class="field-feedback" aria-live="polite"></div>
                            </div>

                            <div class="form-group>
                                <label class="form-label"
                                for="subdomain">
                                Subdomain (Optional)
                                </label>
                                <div class="input-icon">
                                    <i class="fas fa-link"></i>
                                    <input type="text" class="form-control" id="subdomain" name="subdomain"
                                        placeholder="myrestaurant" value="{{ old('subdomain') }}">
                                </div>
                                <div class="input-help">Full address preview: <strong><span
                                            id="subdomainPreview">myrestaurant</span></strong></div>
                                <input type="hidden" name="full_domain" id="full_domain" value="">
                            </div>
                        </div>
                    </div>

                    <!-- Branding (Logo) -->
                    <div class="form-section">
                        <div class="section-title">
                            <i class="fas fa-image"></i>
                            Branding (Logo)
                        </div>
                        <div class="logo-uploader">
                            <div class="logo-thumb">
                                <canvas id="logoCanvas" width="160" height="160"
                                    aria-label="Logo preview"></canvas>
                            </div>
                            <div>
                                <div class="logo-actions">
                                    <label class="btn" for="logoInput"><i class="fas fa-upload"></i> Upload
                                        Logo</label>
                                    <button type="button" class="btn" id="logoClearBtn"><i
                                            class="fas fa-times"></i> Clear</button>
                                    <label class="switch"><input type="checkbox" id="removeBgToggle"> Remove white
                                        background</label>
                                </div>
                                <div class="input-help">PNG with transparent background recommended. If your logo has a
                                    white background, enable removal to make it transparent.</div>
                                <input type="file" id="logoInput" name="logo_image" accept="image/*"
                                    style="display:none" />
                            </div>
                        </div>
                    </div>

                    <div class="form-row" style="gap: 12px;">
                        <button type="button" class="btn-back" onclick="goBackToStep1()">
                            <i class="fas fa-arrow-left"></i> Back to Admin
                        </button>
                        <button type="submit" class="btn-signup" style="flex: 1;">
                            <i class="fas fa-rocket"></i> Create Account
                        </button>
                    </div>
                </form>

            </div>
            <div class="login-link">
                Already registered?
                <a href="{{ route('landingPage.login') }}">Sign in here</a>
            </div>
        </div>
    </div>
    </div>

    <script>
        // Prevent restaurant submit if domain provided but invalid
        (function ensureDomainValidOnSubmit() {
            const restaurantForm = document.getElementById('restaurantForm');
            if (!restaurantForm) return;
            restaurantForm.addEventListener('submit', function(e) {
                const domainValue = (document.getElementById('domain')?.value || '').trim();
                // domainValid is defined in the domain validation script below; treat as false if unknown
                if (domainValue && (typeof domainValid !== 'undefined') && domainValid === false) {
                    e.preventDefault();
                    try {
                        document.getElementById('domain').focus();
                    } catch (_) {}
                    const domainFeedbackEl = document.getElementById('domain_feedback');
                    if (domainFeedbackEl) {
                        domainFeedbackEl.textContent =
                            'Please provide a valid, available domain or leave it empty.';
                        domainFeedbackEl.className = 'field-feedback feedback-error';
                    }
                    if (typeof showAlert === 'function') {
                        showAlert('Please fix the domain before submitting.', 'danger');
                    }
                }
            }, {
                capture: true
            });
        })();
    </script>

    <script>
        let currentStep = 1;

        // helper: cookies (simple)
        function setCookie(name, value, days = 1) {
            const d = new Date();
            d.setTime(d.getTime() + (days * 24 * 60 * 60 * 1000));
            const expires = 'expires=' + d.toUTCString();
            document.cookie = name + '=' + encodeURIComponent(value) + ';' + expires + ';path=/';
        }

        function getCookie(name) {
            const v = document.cookie.match('(^|;)\\s*' + name + '\\s*=\\s*([^;]+)');
            return v ? decodeURIComponent(v.pop()) : '';
        }

        // Combined domain + subdomain preview logic
        const domainInput = document.getElementById('domain');
        const subdomainInput = document.getElementById('subdomain');
        const previewSpan = document.getElementById('subdomainPreview');
        const fullDomainHidden = document.getElementById('full_domain');

        function updateFullPreview() {
            let d = (domainInput.value || '').trim();
            let s = (subdomainInput.value || '').trim();
            let preview;
            if (d && s) {
                // show subdomain.domain
                preview = s + '.' + d.replace(/^https?:\/\//, '').replace(/\/$/, '');
            } else if (s) {
                // fallback to platform subdomain if no custom domain
                preview = s + '.saas-platform.com';
            } else {
                preview = 'myrestaurant.saas-platform.com';
            }
            previewSpan.textContent = preview;
            fullDomainHidden.value = preview;
        }
        domainInput.addEventListener('input', updateFullPreview);
        subdomainInput.addEventListener('input', updateFullPreview);
        updateFullPreview();

        // Password toggle
        function togglePassword(fieldId, iconId) {
            const passwordInput = document.getElementById(fieldId);
            const toggleIcon = document.getElementById(iconId);

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

        // Password strength checker
        function checkPasswordStrength() {
            const password = document.getElementById('admin_password').value;
            const strengthFill = document.getElementById('strengthFill');
            const strengthText = document.getElementById('strengthText');

            let strength = 0;
            if (password.length >= 8) strength++;
            if (password.match(/[a-z]+/)) strength++;
            if (password.match(/[A-Z]+/)) strength++;
            if (password.match(/[0-9]+/)) strength++;
            if (password.match(/[$@#&!]+/)) strength++;

            strengthFill.className = 'strength-fill';

            if (strength <= 2) {
                strengthFill.classList.add('weak');
                strengthText.textContent = 'Weak password';
                strengthText.style.color = 'var(--error)';
            } else if (strength <= 4) {
                strengthFill.classList.add('medium');
                strengthText.textContent = 'Medium strength';
                strengthText.style.color = '#f59e0b';
            } else {
                strengthFill.classList.add('strong');
                strengthText.textContent = 'Strong password!';
                strengthText.style.color = 'var(--success)';
            }
        }

        function goBackToStep1() {
            currentStep = 1;
            document.getElementById('adminForm').style.display = 'block';
            document.getElementById('restaurantForm').style.display = 'none';
            document.getElementById('step1Indicator').classList.add('active');
            document.getElementById('step2Indicator').classList.remove('active');
            document.querySelector('.signup-header h1').innerHTML = '<i class="fas fa-user-tie"></i> Create Admin Account';
            document.querySelector('.signup-header p').textContent =
                'First, create your admin account to manage your restaurant';
        }

        function showAlert(message, type = 'danger') {
            const alertBox = document.getElementById('alertBox');
            alertBox.className = `alert alert-${type} show`;
            alertBox.textContent = message;
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        }

        function hideAlert() {
            const alertBox = document.getElementById('alertBox');
            alertBox.classList.remove('show');
        }

        // Live email validation & uniqueness check
        const emailInput = document.getElementById('admin_email');
        const emailFeedback = document.getElementById('admin_email_feedback');
        const continueBtn = document.querySelector('#adminForm .btn-signup');
        const checkUrl = '{{ route('check.email') }}';
        let emailValid = false;
        let emailDebounce = null;
        let emailCheckController = null;

        function setEmailFeedback(text, type) {
            emailFeedback.textContent = text;
            emailFeedback.className = 'field-feedback ' + (type ? 'feedback-' + type : '');
        }

        function disableContinueButton() {
            continueBtn.disabled = true;
        }

        function enableContinueButton() {
            continueBtn.disabled = false;
        }

        function validateEmailFormat(value) {
            return /^[^@\s]+@[^@\s]+\.[^@\s]+$/.test(value);
        }

        emailInput.addEventListener('input', () => {
            const value = emailInput.value.trim();
            emailValid = false;
            disableContinueButton();

            if (emailDebounce) clearTimeout(emailDebounce);

            if (!value) {
                setEmailFeedback('', '');
                return;
            }
            if (!validateEmailFormat(value)) {
                setEmailFeedback('Invalid email format', 'error');
                return;
            }

            setEmailFeedback('Checking email...', 'loading');
            emailDebounce = setTimeout(() => {
                // Abort previous request if any
                if (emailCheckController) emailCheckController.abort();
                emailCheckController = new AbortController();

                fetch(checkUrl, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                .getAttribute('content'),
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({
                            email: value
                        }),
                        signal: emailCheckController.signal
                    })
                    .then(r => r.json())
                    .then(data => {
                        if (data.unique === true) {
                            emailValid = true;
                            setEmailFeedback('Email is available', 'success');
                            enableContinueButton();
                        } else {
                            setEmailFeedback(data.message || 'Email is already taken', 'error');
                        }
                    })
                    .catch(err => {
                        if (err.name === 'AbortError') return; // ignore aborted
                        console.warn('Email check failed', err);
                        setEmailFeedback('Unable to verify email right now', 'error');
                    });
            }, 450);
        });


        document.getElementById('adminForm').addEventListener('submit', function(e) {
            e.preventDefault();
            if (!emailValid) {
                setEmailFeedback('Please provide a unique valid email before continuing', 'error');
                emailInput.focus();
                return;
            }
            const adminName = document.getElementById('admin_name').value.trim();
            const adminEmail = emailInput.value.trim();
            const adminPassword = document.getElementById('admin_password').value;
            const adminPasswordConfirmation = document.getElementById('admin_password_confirmation').value;

            setCookie('signup_admin_name', adminName, 1);
            setCookie('signup_admin_email', adminEmail, 1);
            try {
                sessionStorage.setItem('signup_admin_password', adminPassword);
                sessionStorage.setItem('signup_admin_password_confirmation', adminPasswordConfirmation);
            } catch (_) {}

            // Generate temporary admin id for step 2 (will be created server-side on final submit)
            const tempId = 'tmp_' + Date.now();
            document.getElementById('admin_id').value = tempId;
            setCookie('signup_admin_id', tempId, 1);
            proceedToRestaurantForm();
        });

        function proceedToRestaurantForm() {
            currentStep = 2;
            document.getElementById('adminForm').style.display = 'none';
            document.getElementById('restaurantForm').style.display = 'block';
            document.getElementById('step1Indicator').classList.remove('active');
            document.getElementById('step2Indicator').classList.add('active');
            document.querySelector('.signup-header h1').innerHTML = '<i class="fas fa-store"></i> Restaurant Details';
            document.querySelector('.signup-header p').textContent = 'Now, let\'s set up your restaurant';
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        }

        // Restaurant form submission - combine admin data from cookies/sessionStorage and restaurant form
        document.getElementById('restaurantForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const btn = this.querySelector('.btn-signup');
            const originalText = btn.innerHTML;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Creating account...';
            btn.disabled = true;

            const form = this;
            const formData = new FormData(form);

            // attach admin data from cookies
            const adminName = getCookie('signup_admin_name');
            const adminEmail = getCookie('signup_admin_email');
            const adminPhone = getCookie('signup_admin_phone');
            const adminAddress = getCookie('signup_admin_address');
            const adminId = getCookie('signup_admin_id') || document.getElementById('admin_id').value;
            const adminPassword = sessionStorage.getItem('signup_admin_password') || '';
            const adminPasswordConfirmation = sessionStorage.getItem('signup_admin_password_confirmation') || '';

            if (adminName) formData.append('admin_name', adminName);
            if (adminEmail) formData.append('admin_email', adminEmail);
            if (adminId) formData.append('admin_id', adminId);
            // Backend expects 'password' and 'password_confirmation'
            if (adminPassword) formData.append('password', adminPassword);
            if (adminPasswordConfirmation) formData.append('password_confirmation', adminPasswordConfirmation);
            // Ensure combined domain included (optional, for future use)
            if (fullDomainHidden.value) formData.append('combined_domain', fullDomainHidden.value);

            // If we have a processed logo in memory, attach it as a file overriding any existing selection
            try {
                if (window.signupLogoDataURL) {
                    const blob = (function dataURLtoBlob(dataurl) {
                        const arr = dataurl.split(',');
                        const mime = arr[0].match(/:(.*?);/)[1];
                        const bstr = atob(arr[1]);
                        let n = bstr.length;
                        const u8arr = new Uint8Array(n);
                        while (n--) {
                            u8arr[n] = bstr.charCodeAt(n);
                        }
                        return new Blob([u8arr], {
                            type: mime
                        });
                    })(window.signupLogoDataURL);
                    formData.delete('logo_image');
                    formData.append('logo_image', blob, 'logo.png');
                }
            } catch (_) {}

            // Send to the form action if it's set to a real endpoint; otherwise log payload and treat '#' as a spoof
            const action = form.action || '#';
            const csrf = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            if (!action || action === '#' || action.endsWith('#')) {
                // Spoof: no real endpoint configured. Show payload in console and notify user.
                const payload = {};
                for (const pair of formData.entries()) {
                    payload[pair[0]] = pair[1];
                }
                console.log('Signup payload (spoof):', payload);
                showAlert('Collected data ready to be sent (spoof). Check console for payload.', 'success');
                // Clean up sensitive sessionStorage
                try {
                    sessionStorage.removeItem('signup_admin_password');
                } catch (e) {}
                btn.innerHTML = originalText;
                btn.disabled = false;
                return;
            }

            Real submit via fetch
            fetch(action, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrf,
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showAlert(data.message || 'Account created successfully! Redirecting…', 'success');
                        // optionally redirect
                        if (data.redirect) window.location.href = data.redirect;
                    } else {
                        showAlert(data.message || 'Failed to create restaurant', 'danger');
                    }
                })
                .catch(err => {
                    console.error('Error submitting restaurant form:', err);
                    showAlert('Network error while creating restaurant. Please try again.', 'danger');
                })
                .finally(() => {
                    try {
                        sessionStorage.removeItem('signup_admin_password');
                    } catch (e) {}
                    btn.innerHTML = originalText;
                    btn.disabled = false;
                });
        });

        // Show Laravel validation errors
        @if ($errors->any())
            showAlert("{{ $errors->first() }}", 'danger');
        @endif

        @if (session('success'))
            showAlert("{{ session('success') }}", 'success');
        @endif

        @if (session('error'))
            showAlert("{{ session('error') }}", 'danger');
        @endif
    </script>
    <script>
        // Domain live validation
        const domainFeedback = document.getElementById('domain_feedback');
        let domainDebounce = null;
        let domainValid = true; // optional field, so start true
        let domainController = null;
        const domainCheckUrl = '{{ route('check.domain') }}';

        function setDomainFeedback(text, type) {
            domainFeedback.textContent = text;
            domainFeedback.className = 'field-feedback ' + (type ? 'feedback-' + type : '');
        }

        function validateDomainFormat(value) {
            // Allow letters, numbers, hyphens and dots; must contain at least one dot and no spaces
            return /^[A-Za-z0-9-]+(\.[A-Za-z0-9-]+)+$/.test(value);
        }

        domainInput.addEventListener('input', () => {
            const value = domainInput.value.trim();
            domainValid = true; // reset; will mark false if problem
            setDomainFeedback('', '');
            if (domainDebounce) clearTimeout(domainDebounce);

            if (!value) {
                // empty allowed: user might rely on platform subdomain
                domainValid = true;
                updateFullPreview();
                return;
            }
            if (!validateDomainFormat(value)) {
                domainValid = false;
                setDomainFeedback('Invalid domain format', 'error');
                return;
            }
            setDomainFeedback('Checking domain...', 'loading');
            domainDebounce = setTimeout(() => {
                if (domainController) domainController.abort();
                domainController = new AbortController();
                fetch(domainCheckUrl, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                .getAttribute('content'),
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({
                            domain: value
                        }),
                        signal: domainController.signal
                    })
                    .then(r => r.json())
                    .then(data => {
                        if (data.unique === true) {
                            domainValid = true;
                            setDomainFeedback('Domain is available', 'success');
                        } else {
                            domainValid = false;
                            setDomainFeedback(data.message || 'Domain already taken', 'error');
                        }
                        updateFullPreview();
                    })
                    .catch(err => {
                        if (err.name === 'AbortError') return;
                        domainValid = false; // treat as invalid to prevent surprises
                        setDomainFeedback('Could not verify domain now', 'error');
                    });
            }, 500);
            updateFullPreview();
        });
    </script>
    <script>
        // Logo uploader with preview and optional white background removal
        (function initLogoUploader() {
            const logoInput = document.getElementById('logoInput');
            const logoCanvas = document.getElementById('logoCanvas');
            const removeBgToggle = document.getElementById('removeBgToggle');
            const logoClearBtn = document.getElementById('logoClearBtn');
            if (!logoCanvas) return; // markup not present

            const ctx = logoCanvas.getContext('2d');
            let originalImage = null; // Image object for reprocessing
            let latestDataURL = null; // keep most recent render as data URL

            function clearCanvas() {
                ctx.clearRect(0, 0, logoCanvas.width, logoCanvas.height);
                latestDataURL = null;
                window.signupLogoDataURL = null;
            }

            function drawImageToCanvas(img, removeBg) {
                const cw = logoCanvas.width,
                    ch = logoCanvas.height;
                ctx.clearRect(0, 0, cw, ch);
                // Fit image into canvas preserving aspect ratio
                const ratio = Math.min(cw / img.width, ch / img.height);
                const nw = Math.max(1, Math.round(img.width * ratio));
                const nh = Math.max(1, Math.round(img.height * ratio));
                const nx = Math.floor((cw - nw) / 2);
                const ny = Math.floor((ch - nh) / 2);
                ctx.drawImage(img, nx, ny, nw, nh);

                if (removeBg) {
                    try {
                        const imageData = ctx.getImageData(0, 0, cw, ch);
                        const data = imageData.data;
                        const threshold = 245; // treat near-white as background
                        for (let i = 0; i < data.length; i += 4) {
                            const r = data[i],
                                g = data[i + 1],
                                b = data[i + 2];
                            if (r >= threshold && g >= threshold && b >= threshold) {
                                data[i + 3] = 0; // transparent
                            } else if (r > 230 && g > 230 && b > 230) {
                                data[i + 3] = 0;
                            }
                        }
                        ctx.putImageData(imageData, 0, 0);
                    } catch (err) {
                        console.warn('Background removal failed:', err);
                    }
                }

                const dataUrl = logoCanvas.toDataURL('image/png');
                latestDataURL = dataUrl;
                window.signupLogoDataURL = dataUrl;
            }

            function loadFile(file) {
                if (!file) return;
                const reader = new FileReader();
                reader.onload = (ev) => {
                    const img = new Image();
                    img.onload = () => {
                        originalImage = img;
                        drawImageToCanvas(img, removeBgToggle && removeBgToggle.checked);
                    };
                    img.src = ev.target.result;
                };
                reader.readAsDataURL(file);
            }

            logoInput && logoInput.addEventListener('change', (e) => {
                const file = e.target.files && e.target.files[0];
                loadFile(file);
            });

            removeBgToggle && removeBgToggle.addEventListener('change', () => {
                if (originalImage) drawImageToCanvas(originalImage, removeBgToggle.checked);
            });

            logoClearBtn && logoClearBtn.addEventListener('click', () => {
                originalImage = null;
                if (logoInput) logoInput.value = '';
                clearCanvas();
            });

            // Drag & drop support directly on the canvas
            logoCanvas.addEventListener('dragover', (e) => {
                e.preventDefault();
            });
            logoCanvas.addEventListener('drop', (e) => {
                e.preventDefault();
                const file = e.dataTransfer.files && e.dataTransfer.files[0];
                loadFile(file);
            });
        })();
    </script>
</body>

</html>
