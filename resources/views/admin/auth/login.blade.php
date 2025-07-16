<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --bg-color: #1e293b;
            --card-color: #2b374d; /* Warna ini sekarang untuk 'topeng' di atas gradien */
            --primary-color: #00e5ff;
            --text-color: #e2e8f0;
            --placeholder-color: #94a3b8;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Nunito', sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background-color: var(--bg-color);
            padding: 1rem;
        }

        .login-wrapper {
            width: 100%;
            max-width: 400px;
        }

        .logo-container {
            text-align: center;
            margin-bottom: 2rem;
        }

        .logo-container img {
            max-width: 150px;
            height: auto;
        }
        
        /* --- KARTU LOGIN DENGAN EFEK RGB --- */
        .login-card {
            /* Position relative diperlukan untuk menampung pseudo-element ::before dan ::after */
            position: relative;
            background: transparent; /* Background asli dibuat transparan */
            padding: 2.5rem;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            overflow: hidden; /* Penting untuk menjaga gradien tetap di dalam border radius */
        }
        
        /* Lapisan Gradien Berputar (Lampu RGB) */
        .login-card::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            z-index: 1;
            /* Membuat gradien berbentuk kerucut dengan warna-warni RGB */
            background: conic-gradient(
                transparent,
                #ff00ff, /* Magenta */
                #00ffff, /* Cyan */
                #00ff00, /* Green */
                #ffff00, /* Yellow */
                #ff0000, /* Red */
                #ff00ff  /* Kembali ke Magenta */
            );
            /* Menerapkan animasi berputar */
            animation: rotate-rgb 4s linear infinite;
        }

        /* Lapisan 'Topeng' untuk menciptakan efek border */
        .login-card::after {
            content: '';
            position: absolute;
            /* Memberi jarak 3px dari setiap sisi, ini adalah tebal border RGB */
            inset: 4px;
            background: var(--card-color); /* Warna asli kartu */
            border-radius: 12px; /* Border radius sedikit lebih kecil dari induknya */
            z-index: 2;
        }

        /* Animasi untuk memutar gradien */
        @keyframes rotate-rgb {
            from {
                transform: rotate(0deg);
            }
            to {
                transform: rotate(360deg);
            }
        }

        /* --- STYLING FORM --- */
        form {
            width: 100%;
            display: flex;
            flex-direction: column;
            /* Form harus berada di lapisan paling atas agar bisa di-klik */
            position: relative;
            z-index: 3;
        }
        
        h2, .input-group, .submit-btn, .error-message {
            /* Pastikan elemen-elemen ini tidak terpengaruh oleh lapisan gradien */
            position: relative;
            z-index: 3;
        }
        
        h2 {
            color: var(--text-color);
            text-align: center;
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 2.5rem;
        }
        
        .input-group {
            margin-bottom: 2rem;
        }

        .input-field {
            width: 100%;
            padding: 10px 0;
            background-color: transparent;
            border: none;
            border-bottom: 2px solid var(--placeholder-color);
            color: var(--text-color);
            font-size: 1rem;
            outline: none;
        }

        .input-label {
            position: absolute;
            top: 10px;
            left: 0;
            color: var(--placeholder-color);
            pointer-events: none;
            transition: all 0.3s ease;
        }

        .input-field:focus {
            border-bottom-color: var(--primary-color);
        }

        .input-field:focus + .input-label,
        .input-field:not(:placeholder-shown) + .input-label {
            top: -20px;
            font-size: 0.8rem;
            color: var(--primary-color);
        }
        
        .submit-btn {
            padding: 1rem;
            margin-top: 1rem;
            background-color: var(--primary-color);
            color: #000;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 1.1rem;
            font-weight: 700;
        }
        
        .error-message {
            color: #ff4757;
            font-size: 0.9rem;
            text-align: center;
            margin-top: -1rem;
            margin-bottom: 1rem;
            min-height: 1.2rem;
        }

        @media (max-width: 480px) {
            .login-card { padding: 2rem; }
            h2 { font-size: 1.8rem; }
        }

    </style>
</head>

<body>

    <div class="login-wrapper">
        <div class="logo-container">
            <img src="{{ asset('images/Icon.png') }}" alt="Logo Perusahaan">
        </div>
        <div class="login-card">
            <form action="{{ route('admin.login') }}" method="POST">
                @csrf
                <h2>Login</h2>
                <div class="error-message">
                    @if($errors->any())
                        {{ $errors->first() }}
                    @endif
                </div>
                <div class="input-group">
                    <input type="email" id="email" name="email" class="input-field" required value="{{ old('email') }}" placeholder=" ">
                    <label for="email" class="input-label">Email</label>
                </div>
                <div class="input-group">
                    <input type="password" id="password" name="password" class="input-field" required placeholder=" ">
                    <label for="password" class="input-label">Password</label>
                </div>
                <button type="submit" class="submit-btn">SUBMIT</button>
            </form>
        </div>
    </div>

</body>
</html>