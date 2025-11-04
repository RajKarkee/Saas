<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Logged Out - Restaurant Management System</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .logout-container {
            text-align: center;
            animation: fadeIn 0.8s ease-in;
        }

        .logout-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            padding: 3rem 2rem;
            max-width: 500px;
            margin: 0 auto
        }

        .success-icon {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%)
        }

        .success-icon i {
            font-size: 2.5rem;
            color: #fff
        }

        .logout-title {
            font-size: 2rem;
            font-weight: 700;
            color: #2d3748;
            margin-bottom: 1rem
        }

        .logout-message {
            color: #4a5568;
            font-size: 1.1rem;
            margin-bottom: 2rem;
            line-height: 1.6
        }

        .message-alert {
            background: #f0f7ff;
            border-left: 4px solid #667eea;
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 2rem;
            text-align: left
        }

        .message-alert i {
            color: #667eea;
            margin-right: 0.5rem
        }

        .message-alert p {
            margin: 0;
            color: #2d3748;
            font-size: 0.95rem
        }

        .btn-login {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: #fff;
            border: none;
            padding: .75rem 2.5rem;
            border-radius: 50px;
            font-weight: 600
        }

        .info-text {
            margin-top: 2rem;
            color: #718096;
            font-size: .9rem
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px)
            }

            to {
                opacity: 1;
                transform: translateY(0)
            }
        }

        @keyframes scaleIn {
            from {
                transform: scale(0);
                opacity: 0
            }

            to {
                transform: scale(1);
                opacity: 1
            }
        }

        .redirect-timer {
            display: inline-block;
            font-weight: 700;
            color: #667eea
        }
    </style>
</head>

<body>
    <div class="logout-container">
        <div class="logout-card">
            <div class="success-icon"><i class="fas fa-check"></i></div>
            <h1 class="logout-title">Successfully Logged Out</h1>
            <p class="logout-message">Thank you for using our Restaurant Management System. Your session has been
                securely terminated.</p>

            {{-- Server-side message (if controller passed one) --}}
            @if (!empty($message))
                <div class="message-alert" id="dynamicMessage">
                    <i class="fas fa-info-circle"></i>
                    <p id="messageText">{{ $message }}</p>
                </div>
            @else
                <div class="message-alert" id="dynamicMessage" style="display:none;">
                    <i class="fas fa-info-circle"></i>
                    <p id="messageText"></p>
                </div>
            @endif

            <a href="/" class="btn btn-login"><i class="fas fa-sign-in-alt me-2"></i>Login Again</a>

            <div class="info-text">
                <p><i class="fas fa-shield-alt me-1"></i>Your data is safe and secure.<br>Redirecting to homepage in
                    <span class="redirect-timer" id="countdown">10</span> seconds...</p>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // If no server message, try URL param or sessionStorage
        const serverHasMessage = {{ !empty($message) ? 'true' : 'false' }};
        if (!serverHasMessage) {
            const urlParams = new URLSearchParams(window.location.search);
            const message = urlParams.get('message');
            if (message) {
                const block = document.getElementById('dynamicMessage');
                block.style.display = 'block';
                document.getElementById('messageText').textContent = decodeURIComponent(message);
            }

            const storedMessage = sessionStorage.getItem('logout_message');
            if (storedMessage) {
                const block = document.getElementById('dynamicMessage');
                block.style.display = 'block';
                document.getElementById('messageText').textContent = storedMessage;
                sessionStorage.removeItem('logout_message');
            }
        }

        // Countdown
        let countdown = 10;
        const countdownElement = document.getElementById('countdown');
        const timer = setInterval(() => {
            countdown--;
            countdownElement.textContent = countdown;
            if (countdown <= 0) {
                clearInterval(timer);
                window.location.href = '/';
            }
        }, 1000);

        document.querySelector('.btn-login').addEventListener('click', () => {
            clearInterval(timer);
        });
    </script>
</body>

</html>
