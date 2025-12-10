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
            --primary-color: #4f46e5;
            --secondary-color: #6366f1;
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
            background: linear-gradient(135deg, var(--primary-color) 0%, #3730a3 100%);
            font-family: 'Segoe UI', system-ui, sans-serif;
            padding: 1rem;
        }
        .login-container {
            width: 100%;
            max-width: 420px;
        }
        .login-card {
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 25px 50px -12px rgba(0,0,0,0.25);
            overflow: hidden;
        }
        .login-header {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: #fff;
            padding: 2rem 1.5rem;
            text-align: center;
        }
        .login-header .icon-wrapper {
            width: 70px;
            height: 70px;
            background: rgba(255,255,255,0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem;
        }
        .login-header .icon-wrapper i {
            font-size: 2rem;
        }
        .login-header h4 {
            font-weight: 700;
            margin-bottom: 0.25rem;
        }
        .login-header small {
            opacity: 0.9;
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
            box-shadow: 0 0 0 3px rgba(79,70,229,0.1);
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
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            border: none;
            padding: 0.875rem;
            border-radius: 8px;
            font-weight: 600;
            font-size: 1rem;
            transition: all 0.2s;
        }
        .btn-login:hover {
            background: linear-gradient(135deg, #4338ca, var(--primary-color));
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(79,70,229,0.4);
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
            .login-header .icon-wrapper {
                width: 60px;
                height: 60px;
            }
            .login-header .icon-wrapper i {
                font-size: 1.75rem;
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
                <div class="icon-wrapper">
                    <i class="bi bi-box-seam"></i>
                </div>
                <h4>e-Sarpras</h4>
                <small>Sistem Sarana & Prasarana</small>
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
