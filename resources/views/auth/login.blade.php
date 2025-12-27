<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - e-Sarpras</title>

    <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        :root {
            --primary: #78C841;
            --primary-dark: #4a9928;
        }

body {
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    font-family: 'Poppins', sans-serif;
    background:
        linear-gradient(
            rgba(0,0,0,0.35),
            rgba(0,0,0,0.35)
        ),
        url("/images/bg-login.jpg") center / cover no-repeat fixed;
}


        /* Container */
        .login-container {
            width: 100%;
            max-width: 380px;
        }

        /* Glass Card */
        .login-card {
            background: rgba(255, 255, 255, 0.18);
            backdrop-filter: blur(14px);
            -webkit-backdrop-filter: blur(14px);
            border-radius: 20px;
            border: 1px solid rgba(255,255,255,0.3);
            box-shadow: 0 25px 60px rgba(0,0,0,0.35);
            padding: 2rem 1.75rem;
            color: #fff;
        }

        /* Header */
        .login-header {
            text-align: center;
            margin-bottom: 1.5rem;
        }

        .login-header img {
            width: 56px;
            margin-bottom: 0.5rem;
        }

        .login-header h4 {
            font-weight: 700;
            margin-bottom: 0.25rem;
        }

        .login-header small {
            opacity: 0.85;
        }

        /* Form */
        .form-label {
            font-size: 0.85rem;
            opacity: 0.9;
        }

        .input-group-text {
            background: rgba(255,255,255,0.85);
            border: none;
        }

        .form-control {
            border: none;
            padding: 0.75rem 1rem;
            border-radius: 12px;
            background: rgba(255,255,255,0.9);
        }

        .form-control:focus {
            box-shadow: 0 0 0 4px rgba(6, 197, 207, 0.32);
        }

        .btn-login {
    background: linear-gradient(135deg, #0d9488, #0f766e);
    border: none;
    border-radius: 14px;
    padding: 0.85rem;
    font-weight: 600;
    color: #fff;              /* ⬅️ INI PENTING */
    transition: all 0.25s ease;
}

.btn-login:hover {
    color: #fff;              /* ⬅️ biar hover tetap putih */
    transform: translateY(-2px);
    box-shadow: 0 12px 25px rgba(13,148,136,0.45);
}


        /* Footer */
        .login-footer {
            text-align: center;
            font-size: 0.8rem;
            opacity: 0.8;
            margin-top: 1.25rem;
        }
    </style>
</head>

<body>
<div class="login-container">
    <div class="login-card">
        <div class="login-header">
            <img src="{{ asset('mq.png') }}" alt="Logo">
            <h4>e-Sarpras</h4>
            <small>Sistem Sarana & Prasarana Pesantren</small>
        </div>

        @if($errors->any())
            <div class="alert alert-danger py-2 small">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('login.post') }}">
            @csrf

            <div class="mb-3">
                <label class="form-label">Email</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                    <input type="email" name="email" class="form-control"
                           value="{{ old('email') }}" required autofocus>
                </div>
            </div>

            <div class="mb-4">
                <label class="form-label">Password</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-lock"></i></span>
                    <input type="password" name="password" id="password" class="form-control" required>
                    <span class="input-group-text" onclick="togglePassword()" style="cursor:pointer">
                        <i class="bi bi-eye" id="toggleIcon"></i>
                    </span>
                </div>
            </div>

            <button class="btn btn-login w-100">
                <i class="bi bi-box-arrow-in-right me-2"></i> Masuk
            </button>
        </form>

        <div class="login-footer">
            © {{ date('Y') }} e-Sarpras
        </div>
    </div>
</div>

<script>
    function togglePassword() {
        const input = document.getElementById('password');
        const icon = document.getElementById('toggleIcon');
        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.replace('bi-eye', 'bi-eye-slash');
        } else {
            input.type = 'password';
            icon.classList.replace('bi-eye-slash', 'bi-eye');
        }
    }
</script>
</body>
</html>
