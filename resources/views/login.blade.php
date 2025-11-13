<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - SIMTAK | Sistem Manajemen Informasi Majelis Taklim</title>
    <link rel="icon" href="{{ asset('voler/assets/images/logo-kemenag.png') }}">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome untuk ikon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Amiri:wght@400;700&display=swap" rel="stylesheet">
    
    <style>
        @font-face {
            font-family: 'Ketupat Ramadhan';
            src: url('../fonts/Ketupat-Ramadhan') format('truetype');
        }
        :root {
            --hijau-utama: #31a831ff;
            --hijau-muda: #4caf50;
            --hijau-paling-muda: #e8f5e9;
            --hijau-gelap: #1b5e20;
            --emas: #d4af37;
            --putih: #ffffff;
            --abu-abu: #f5f5f5;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, var(--hijau-paling-muda) 0%, var(--hijau-muda) 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        
        .login-container {
            width: 100%;
            max-width: 1000px;
            background-color: var(--putih);
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
            display: flex;
            flex-wrap: wrap;
        }
        
        .login-info {
            flex: 1;
            min-width: 300px;
            background: linear-gradient(135deg, var(--hijau-utama) 0%, var(--hijau-gelap) 100%);
            color: var(--putih);
            padding: 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }
        
        .login-info::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: url('https://picsum.photos/seed/islamic-pattern/800/800.jpg');
            background-size: cover;
            opacity: 0.1;
            z-index: 0;
        }
        
        .login-info-content {
            position: relative;
            z-index: 1;
        }
        
        .login-info h1 {
            font-family: 'Ketupat Ramadhan', sans-serif;
            font-size: 2.5rem;
            margin-bottom: 20px;
            color: var(--emas);
        }
        
        .login-info p {
            font-size: 1.1rem;
            margin-bottom: 30px;
            line-height: 1.6;
        }
        
        .islamic-pattern {
            margin: 30px 0;
            text-align: center;
        }
        
        .islamic-pattern i {
            font-size: 2rem;
            color: var(--emas);
            margin: 0 10px;
        }
        
        .login-form {
            flex: 1;
            min-width: 300px;
            padding: 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        
        .login-form h2 {
            font-family: 'Amiri', serif;
            font-size: 2rem;
            color: var(--hijau-utama);
            margin-bottom: 10px;
            text-align: center;
        }
        
        .login-form p {
            color: #666;
            text-align: center;
            margin-bottom: 30px;
        }
        
        .form-group {
            margin-bottom: 20px;
            position: relative;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: var(--hijau-gelap);
            font-weight: 500;
        }
        
        .input-group {
            position: relative;
            display: flex;
            align-items: stretch;
            width: 100%;
        }
        
        .input-group-text {
            display: flex;
            align-items: center;
            padding: 0.375rem 0.75rem;
            font-size: 1rem;
            font-weight: 400;
            line-height: 1.5;
            color: var(--hijau-utama);
            text-align: center;
            white-space: nowrap;
            background-color: #e9ecef;
            border: 1px solid #ced4da;
            border-radius: 0.375rem 0 0 0.375rem;
            border-right: none;
        }
        
        .form-control {
            display: block;
            width: 100%;
            padding: 0.375rem 0.75rem;
            font-size: 1rem;
            font-weight: 400;
            line-height: 1.5;
            color: #212529;
            background-color: #fff;
            background-clip: padding-box;
            border: 1px solid #ced4da;
            -webkit-appearance: none;
            -moz-appearance: none;
            appearance: none;
            border-radius: 0 0.375rem 0.375rem 0;
            transition: border-color .15s ease-in-out,box-shadow .15s ease-in-out;
        }
        
        .form-control:focus {
            color: #212529;
            background-color: #fff;
            border-color: var(--hijau-muda);
            outline: 0;
            box-shadow: 0 0 0 0.25rem rgba(76, 175, 80, 0.25);
        }
        
        .form-options {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
        }
        
        .form-options .remember-me {
            display: flex;
            align-items: center;
        }
        
        .form-options .remember-me input {
            margin-right: 8px;
        }
        
        .form-options a {
            color: var(--hijau-utama);
            text-decoration: none;
            font-weight: 500;
            transition: color 0.3s;
        }
        
        .form-options a:hover {
            color: var(--hijau-gelap);
            text-decoration: underline;
        }
        
        .btn-login {
            background: linear-gradient(135deg, var(--hijau-muda) 0%, var(--hijau-utama) 100%);
            color: var(--putih);
            border: none;
            padding: 12px;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            margin-bottom: 20px;
        }
        
        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(45, 122, 45, 0.3);
        }
        
        .divider {
            text-align: center;
            margin: 20px 0;
            position: relative;
        }
        
        .divider::before {
            content: "";
            position: absolute;
            top: 50%;
            left: 0;
            right: 0;
            height: 1px;
            background-color: #ddd;
        }
        
        .divider span {
            background-color: var(--putih);
            padding: 0 15px;
            color: #999;
            position: relative;
        }
        
        .social-login {
            display: flex;
            justify-content: center;
            gap: 15px;
            margin-bottom: 20px;
        }
        
        .social-btn {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 1px solid #ddd;
            background-color: var(--putih);
            color: #333;
            transition: all 0.3s;
        }
        
        .social-btn:hover {
            background-color: var(--abu-abu);
            transform: translateY(-3px);
        }
        
        .register-link {
            text-align: center;
            margin-top: 20px;
            color: #666;
        }
        
        .register-link a {
            color: var(--hijau-utama);
            text-decoration: none;
            font-weight: 500;
        }
        
        .register-link a:hover {
            text-decoration: underline;
        }
        
        .islamic-quote {
            font-style: italic;
            margin-top: 30px;
            padding: 15px;
            background-color: rgba(255, 255, 255, 0.1);
            border-radius: 8px;
            border-left: 3px solid var(--emas);
        }
        
        .islamic-quote p {
            font-size: 0.9rem;
            line-height: 1.6;
        }
        
        .islamic-quote .quote-source {
            text-align: right;
            margin-top: 10px;
            font-size: 0.8rem;
            color: var(--emas);
        }
        
        @media (max-width: 768px) {
            .login-container {
                flex-direction: column;
            }

            .login-info .islamic-pattern,
            .login-info .islamic-quote {
                display: none !important;
            }
            
            .login-info {
                padding: 30px;
            }
            
            .login-info h1 {
                font-size: 2rem;
            }
            
            .login-form {
                padding: 30px;
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-info">
            <div class="login-info-content">
                <div class="islamic-pattern">
                    <img src="{{ asset('voler/assets/images/logo-bimas.svg') }}" class="img-fluid" alt="" srcset="">
                </div>
                <h1 class="d-flex justify-content-center text-center">S I M T A K</h1>
                <h3 class="d-flex justify-content-center text-center">Sistem Informasi Manajemen Majelis Taklim</h3>
                
                
                <div class="islamic-quote">
                    <p>"Dan hendaklah ada di antara kamu segolongan umat yang menyeru kepada kebaikan, menyuruh kepada yang ma'ruf, dan mencegah dari yang mungkar; dan mereka itulah orang-orang yang beruntung."</p>
                    <div class="quote-source">(QS. Ali 'Imran: 104)</div>
                </div>
            </div>
        </div>
        
        <div class="login-form">
            <h2>Masuk ke Akun Anda</h2>
            <p>Silakan masuk untuk mengakses dashboard SIMTAK</p>
            
            <form action="{{ route('login.authenticate') }}" method="POST">
                @csrf
                @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                <div class="form-group">
                    <label for="email">Email</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="fas fa-envelope"></i>
                        </span>
                        <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" placeholder="Masukkan email" required>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="password">Password</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="fas fa-lock"></i>
                        </span>
                        <input type="password" class="form-control" id="password" name="password" placeholder="Masukkan password" required>
                    </div>
                </div>
                
                <div class="form-options">
                    <div class="remember-me">
                        <input type="checkbox" id="remember" name="remember">
                        <label for="remember">Ingat saya</label>
                    </div>
                    <a href="auth-forgot-password.html">Lupa password?</a>
                </div>
                
                <button type="submit" class="btn-login">Masuk</button>
            </form>
        </div>
    </div>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script src="{{ asset('voler/assets/js/feather-icons/feather.min.js') }}"></script>
    <script src="{{ asset('voler/assets/js/app.js') }}"></script>
    <script src="{{ asset('voler/assets/js/main.js') }}"></script>
</body>
</html>