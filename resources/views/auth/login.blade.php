<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - e-Sarpras</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body { min-height: 100vh; display: flex; align-items: center; background: linear-gradient(135deg, #4f46e5 0%, #3730a3 100%); }
        .login-card { max-width: 420px; width: 100%; margin: auto; border-radius: 16px; box-shadow: 0 25px 50px -12px rgba(0,0,0,0.25); }
        .login-header { background: linear-gradient(135deg, #4f46e5, #6366f1); color: #fff; padding: 2rem; text-align: center; border-radius: 16px 16px 0 0; }
        .login-header i { font-size: 3rem; margin-bottom: 0.5rem; }
        .login-body { padding: 2rem; }
        .form-control { border-radius: 8px; padding: 0.75rem 1rem; }
        .form-control:focus { border-color: #4f46e5; box-shadow: 0 0 0 3px rgba(79,70,229,0.1); }
        .btn-login { background: linear-gradient(135deg, #4f46e5, #6366f1); border: none; padding: 0.75rem; border-radius: 8px; font-weight: 600; }
        .btn-login:hover { background: linear-gradient(135deg, #4338ca, #4f46e5); }
    </style>
</head>
<body>
    <div class="login-card bg-white">
        <div class="login-header">
            <i class="bi bi-box-seam"></i>
            <h4 class="mb-0">e-Sarpras</h4>
            <small>Sistem Sarana & Prasarana</small>
        </div>
        <div class="login-body">
            @if($errors->any())
            <div class="alert alert-danger py-2">{{ $errors->first() }}</div>
            @endif
            <form method="POST" action="{{ route('login.post') }}">
                @csrf
                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                        <input type="email" name="email" class="form-control" value="{{ old('email') }}" required autofocus>
                    </div>
                </div>
                <div class="mb-4">
                    <label class="form-label">Password</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-lock"></i></span>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary btn-login w-100">
                    <i class="bi bi-box-arrow-in-right me-2"></i>Login
                </button>
            </form>
        </div>
    </div>
</body>
</html>
