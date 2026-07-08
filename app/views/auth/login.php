<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sikepal Report</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html, body {
            margin: 0;
            padding: 0;
            height: 100%;
            width: 100%;
        }

        body {
            display: flex;
            align-items: center;
            justify-content: center;
            background: #0F172A;
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            min-height: 100vh;
            overflow: hidden;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
        }

        .auth-container {
            width: 100%;
            max-width: 420px;
            padding: 1.5rem;
            position: relative;
            z-index: 1;
            margin: 0 auto;
        }

        .auth-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            padding: 2.5rem;
            border-radius: 24px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5), 0 0 0 1px rgba(255, 255, 255, 0.1);
            position: relative;
            z-index: 2;
            animation: slideUp 0.6s ease-out;
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(30px) scale(0.95);
            }
            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        .auth-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 60px;
            height: 4px;
            background: linear-gradient(135deg, #EAA023 0%, #C4881E 100%);
            border-radius: 0 0 4px 4px;
        }

        .auth-header {
            text-align: center;
            margin-bottom: 2rem;
        }

        .auth-header .auth-icon {
            font-size: 3.5rem;
            margin-bottom: 0.5rem;
            display: inline-block;
            animation: bounceIcon 2s ease-in-out infinite;
        }

        @keyframes bounceIcon {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-8px); }
        }

        .auth-header h1 {
            font-size: 1.75rem;
            font-weight: 800;
            background: linear-gradient(135deg, #EAA023 0%, #C4881E 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 0.25rem;
            letter-spacing: -0.5px;
        }

        .auth-header p {
            color: #64748B;
            font-size: 0.95rem;
            font-weight: 400;
        }

        .form-group {
            margin-bottom: 1.25rem;
            position: relative;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.4rem;
            font-weight: 600;
            color: #334155;
            font-size: 0.85rem;
            letter-spacing: 0.3px;
        }

        .input-wrapper {
            position: relative;
            width: 100%;
        }

        .input-icon-left {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 1.1rem;
            color: #94A3B8;
            transition: all 0.3s ease;
            pointer-events: none;
            z-index: 2;
            line-height: 1;
        }

        .form-group input {
            width: 100%;
            padding: 0.8rem 1rem;
            padding-left: 44px;
            border: 2px solid #E2E8F0;
            border-radius: 12px;
            font-size: 0.95rem;
            transition: all 0.3s ease;
            font-family: inherit;
            background: #FFFFFF;
            color: #1E293B;
        }

        .form-group input:focus {
            outline: none;
            border-color: #EAA023;
            box-shadow: 0 0 0 4px rgba(234, 160, 35, 0.1);
            transform: translateY(-1px);
        }

        .form-group input:focus ~ .input-icon-left {
            color: #EAA023;
        }

        .form-group input::placeholder {
            color: #94A3B8;
        }

        .forgot-password {
            display: flex;
            justify-content: flex-end;
            margin-bottom: 1.5rem;
            width: 100%;
        }

        .forgot-password a {
            color: #94A3B8;
            text-decoration: none;
            font-size: 0.8rem;
            transition: all 0.3s ease;
            font-weight: 500;
        }

        .forgot-password a:hover {
            color: #EAA023;
            text-decoration: underline;
        }

        .btn-login-wrapper {
            display: flex;
            justify-content: center;
            width: 100%;
        }

        .btn-login {
            width: 100%;
            justify-content: center;
            padding: 0.9rem;
            font-size: 1rem;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            background: linear-gradient(135deg, #EAA023 0%, #C4881E 100%);
            color: #FFFFFF;
            border: none;
            border-radius: 12px;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            letter-spacing: 0.5px;
        }

        .btn-login::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.6s ease;
        }

        .btn-login:hover::before {
            left: 100%;
        }

        .btn-login:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(234, 160, 35, 0.4);
        }

        .btn-login:active {
            transform: scale(0.97);
        }

        .btn-login .btn-icon {
            font-size: 1.2rem;
        }

        .alert {
            border-radius: 12px;
            padding: 0.9rem 1.1rem;
            font-size: 0.9rem;
            margin-bottom: 1.25rem;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .alert-success {
            background: #D1FAE5;
            color: #065F46;
            border: 1px solid #A7F3D0;
        }

        .alert-error {
            background: #FEE2E2;
            color: #991B1B;
            border: 1px solid #FCA5A5;
        }

        /* Decorative circles */
        .auth-container .circle {
            position: absolute;
            border-radius: 50%;
            opacity: 0.05;
            animation: floatCircle 15s ease-in-out infinite;
            pointer-events: none;
        }

        .auth-container .circle:nth-child(1) {
            width: 300px;
            height: 300px;
            background: #EAA023;
            top: -150px;
            left: -150px;
            animation-delay: 0s;
        }

        .auth-container .circle:nth-child(2) {
            width: 400px;
            height: 400px;
            background: #C4881E;
            bottom: -200px;
            right: -200px;
            animation-delay: -5s;
        }

        .auth-container .circle:nth-child(3) {
            width: 200px;
            height: 200px;
            background: #F5C56A;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            animation-delay: -10s;
        }

        @keyframes floatCircle {
            0%, 100% { transform: translate(0, 0) scale(1); }
            33% { transform: translate(20px, -20px) scale(1.2); }
            66% { transform: translate(-20px, 20px) scale(0.8); }
        }

        @media (max-width: 480px) {
            .auth-card {
                padding: 1.75rem;
                border-radius: 20px;
                margin: 0.5rem;
            }

            .auth-header .auth-icon {
                font-size: 2.8rem;
            }

            .auth-header h1 {
                font-size: 1.5rem;
            }

            .form-group input {
                padding: 0.7rem 0.9rem;
                padding-left: 38px;
                font-size: 0.9rem;
            }

            .input-icon-left {
                left: 10px;
                font-size: 0.95rem;
            }

            .btn-login {
                padding: 0.8rem;
                font-size: 0.95rem;
            }

            .auth-container {
                padding: 1rem;
            }
        }

        @media (min-width: 481px) and (max-width: 768px) {
            .auth-card {
                padding: 2rem;
            }
        }
    </style>
</head>
<body>
    <div class="auth-container">
        <div class="circle"></div>
        <div class="circle"></div>
        <div class="circle"></div>
        
        <div class="auth-card">
            <div class="auth-header">
                <div class="auth-icon">🍙</div>
                <h1>Sikepal Report</h1>
                <p>Login untuk melanjutkan</p>
            </div>
            
            <?php if (isset($data['error'])): ?>
                <div class="alert alert-error">❌ <?= $data['error'] ?></div>
            <?php endif; ?>
            
            <?php if (isset($_SESSION['success'])): ?>
                <div class="alert alert-success">✅ <?= $_SESSION['success']; unset($_SESSION['success']); ?></div>
            <?php endif; ?>
            
            <form method="POST" action="/sikepal-report/public/index.php?route=login" id="loginForm">

                <div class="form-group">
                    <label for="email">Email</label>
                    <div class="input-wrapper">
                        <span class="input-icon-left">📧</span>
                        <input 
                            type="email" 
                            id="email" 
                            name="email" 
                            placeholder="Masukkan email" 
                            required 
                            autofocus
                        >
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="password">Password</label>
                    <div class="input-wrapper">
                        <span class="input-icon-left">🔑</span>
                        <input 
                            type="password" 
                            id="password" 
                            name="password" 
                            placeholder="Masukkan password" 
                            required
                        >
                    </div>
                </div>
                
                <div class="forgot-password">
                    <a href="#" onclick="alert('📧 Hubungi admin untuk reset password'); return false;">Lupa password?</a>
                </div>
                
                <div class="btn-login-wrapper">
                    <button type="submit" class="btn-login">
                        <span class="btn-icon">🔑</span> Login
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const loginForm = document.getElementById('loginForm');
            if (loginForm) {
                loginForm.addEventListener('submit', function(e) {
                    const email = document.getElementById('email');
                    const password = document.getElementById('password');
                    
                    if (!email.value.trim() || !password.value.trim()) {
                        e.preventDefault();
                        alert('⚠️ Email dan password harus diisi!');
                        return false;
                    }
                });
            }
        });
    </script>
</body>
</html>