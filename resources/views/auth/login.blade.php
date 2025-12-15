<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - e-Sarpras</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #78C841;
            --secondary-color: #5fb030;
            --primary-dark: #4a9928;
        }
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        html, body {
            height: 100%;
        }
        body {
            min-height: 100vh;
            min-height: 100dvh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: url('{{ asset('images/bg-login.jpg') }}') no-repeat center center fixed;
            background-size: cover;
            font-family: 'Segoe UI', system-ui, sans-serif;
            padding: 1rem;
            position: relative;
        }
        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(45, 80, 22, 0.7);
            z-index: 0;
        }
        .login-container {
            position: relative;
            z-index: 1;
            width: 100%;
            max-width: 420px;
        }
        .login-logo {
            width: 80px;
            height: 80px;
            object-fit: contain;
            margin: 0 auto 1rem;
            display: block;
        }
        .login-card {
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 25px 50px -12px rgba(0,0,0,0.25);
            overflow: hidden;
        }
        .login-header {
            background: #fff;
            color: #333;
            padding: 2rem 1.5rem;
            text-align: center;
            border-bottom: 1px solid #e2e8f0;
        }
        .login-header h4 {
            font-weight: 700;
            margin-bottom: 0.25rem;
            color: #78C841;
        }
        .login-header small {
            color: #64748b;
        }
        .login-body {
            padding: 2rem 1.5rem;
        }
        .form-label {
            font-weight: 500;
            color: #374151;
            margin-bottom: 0.5rem;
        }
        .input-group-text {
            background: #f8fafc;
            border-color: #e2e8f0;
            color: #64748b;
        }
        .form-control {
            border-radius: 8px;
            padding: 0.75rem 1rem;
            border-color: #e2e8f0;
            font-size: 1rem;
        }
        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(120,200,65,0.25);
        }
        .input-group .form-control {
            border-top-left-radius: 0;
            border-bottom-left-radius: 0;
        }
        .input-group .input-group-text {
            border-top-left-radius: 8px;
            border-bottom-left-radius: 8px;
        }
        .btn-login {
            background: #78C841;
            border: none;
            padding: 0.875rem;
            border-radius: 8px;
            font-weight: 600;
            font-size: 1rem;
            transition: all 0.2s;
        }
        .btn-login:hover {
            background: #4a9928;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(120,200,65,0.4);
        }
        .btn-login:active {
            transform: translateY(0);
        }
        .alert {
            border-radius: 8px;
            font-size: 0.875rem;
        }
        .password-toggle {
            cursor: pointer;
            background: #f8fafc;
            border-color: #e2e8f0;
            border-left: none;
        }
        .password-toggle:hover {
            background: #f1f5f9;
        }
        .login-footer {
            text-align: center;
            padding: 1rem 1.5rem 1.5rem;
            color: #64748b;
            font-size: 0.875rem;
        }
        @media (max-width: 480px) {
            body {
                padding: 0.75rem;
                align-items: flex-start;
                padding-top: 2rem;
            }
            .login-header {
                padding: 1.5rem 1rem;
            }
            .login-logo {
                width: 60px;
                height: 60px;
            }
            .login-body {
                padding: 1.5rem 1rem;
            }
            .form-control {
                font-size: 16px; /* Prevents zoom on iOS */
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-card">
            <div class="login-header">
                <img src="{{ asset('mq.png') }}" alt="Logo" class="login-logo">
                <h4>e-Sarpras</h4>
                <small>Sistem Sarana & Prasarana Pesantren</small>
            </div>
            <div class="login-body">
                @if($errors->any())
                <div class="alert alert-danger py-2 mb-3">
                    <i class="bi bi-exclamation-circle me-1"></i>{{ $errors->first() }}
                </div>
                @endif
                @if(session('success'))
                <div class="alert alert-success py-2 mb-3">
                    <i class="bi bi-check-circle me-1"></i>{{ session('success') }}
                </div>
                @endif
                <form method="POST" action="{{ route('login.post') }}">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                            <input type="email" name="email" class="form-control" placeholder="Masukkan email" value="{{ old('email') }}" required autofocus>
                        </div>
                    </div>
                    <div class="mb-4">
                        <label class="form-label">Password</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-lock"></i></span>
                            <input type="password" name="password" id="password" class="form-control" placeholder="Masukkan password" required>
                            <span class="input-group-text password-toggle" onclick="togglePassword()">
                                <i class="bi bi-eye" id="toggleIcon"></i>
                            </span>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary btn-login w-100">
                        <i class="bi bi-box-arrow-in-right me-2"></i>Masuk
                    </button>
                </form>
            </div>
            <div class="login-footer">
                &copy; {{ date('Y') }} e-Sarpras
            </div>
        </div>
    </div>

    <script>
        function togglePassword() {
            const password = document.getElementById('password');
            const icon = document.getElementById('toggleIcon');
            if (password.type === 'password') {
                password.type = 'text';
                icon.classList.remove('bi-eye');
                icon.classList.add('bi-eye-slash');
            } else {
                password.type = 'password';
                icon.classList.remove('bi-eye-slash');
                icon.classList.add('bi-eye');
            }
        }
    </script>
</body>
</html>
