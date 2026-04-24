<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - MOTIVA WebGIS</title>
    
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    
    <style>
        body {
            background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%);
            font-family: 'Inter', sans-serif;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
        }

        .login-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2);
            overflow: hidden;
            width: 100%;
            max-width: 420px;
        }

        .login-header {
            background: linear-gradient(to right, #1e3a8a, #b8860b);
            color: white;
            padding: 2.5rem 1.5rem;
            text-align: center;
        }

        #motiva-brand {
            font-family: 'Playfair Display', serif;
            font-size: 2.8rem;
            font-weight: 700;
            letter-spacing: 8px;
            color: white;
            text-shadow: 0 3px 10px rgba(0,0,0,0.3);
            transition: all 0.4s ease;
        }

        .login-body {
            padding: 2.5rem 2rem;
        }

        .form-control {
            border-radius: 10px;
            padding: 12px 15px;
            border: 1px solid #ddd;
        }

        .form-control:focus {
            border-color: #1e3a8a;
            box-shadow: 0 0 0 0.2rem rgba(30, 58, 138, 0.25);
        }

        .btn-login {
            background: linear-gradient(to right, #1e3a8a, #3b82f6);
            border: none;
            border-radius: 10px;
            padding: 12px;
            font-weight: 600;
            font-size: 1.1rem;
            transition: all 0.3s ease;
        }

        .btn-login:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 20px rgba(30, 58, 138, 0.3);
        }

        .footer-text {
            text-align: center;
            margin-top: 1.5rem;
            color: #666;
            font-size: 0.95rem;
        }
    </style>
</head>
<body>

    <div class="login-card">
        
        <!-- Header -->
        <div class="login-header">
            <h1 id="motiva-brand">M O T I V A</h1>
            <p class="mb-0 mt-2">WebGIS Cagar Budaya Mempawah</p>
        </div>

        <!-- Body -->
        <div class="login-body">
            <h4 class="text-center mb-4 text-dark">Selamat Datang</h4>
            
            <form method="post" action="proses_login.php">
                
                <div class="mb-3">
                    <label class="form-label fw-medium">Username</label>
                    <input type="text" 
                           name="username" 
                           class="form-control" 
                           placeholder="Masukkan username" 
                           required>
                </div>

                <div class="mb-4">
                    <label class="form-label fw-medium">Password</label>
                    <input type="password" 
                           name="password" 
                           class="form-control" 
                           placeholder="Masukkan password" 
                           required>
                </div>

                <button type="submit" class="btn btn-primary w-100 btn-login">
                    LOGIN
                </button>
            </form>

            <div class="footer-text">
                <small>© 2026 MOTIVA - Sistem Informasi Geografis Cagar Budaya</small>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
