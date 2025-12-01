<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Mom's Chicken POS</title>
    <!-- Gunakan CDN Tailwind yang sama dengan layout lain -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;900&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --color-primary-red: #D93630;
            --color-secondary-gold: #FFC300;
        }
        body {
            font-family: 'Inter', sans-serif;
            background-color: #F8F9FA;
            /* Latar belakang*/
            background-image: radial-gradient(circle at 10% 50%, rgba(255, 195, 0, 0.1), transparent 50%),
                              radial-gradient(circle at 90% 80%, rgba(217, 54, 48, 0.1), transparent 50%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .card-shadow {
            box-shadow: 0 15px 45px -10px rgba(0, 0, 0, 0.1), 0 6px 15px -3px rgba(0, 0, 0, 0.08);
        }
        .login-button {
            background: linear-gradient(145deg, var(--color-primary-red), #E04B46);
            transition: all 0.3s ease;
        }
        .login-button:hover {
            box-shadow: 0 10px 30px -5px rgba(217, 54, 48, 0.5);
            transform: translateY(-2px);
        }
        .custom-input:focus {
            border-color: var(--color-secondary-gold);
            box-shadow: 0 0 0 3px rgba(255, 195, 0, 0.3);
        }
    </style>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'brand-red': 'var(--color-primary-red)',
                        'brand-gold': 'var(--color-secondary-gold)',
                    },
                }
            }
        }
    </script>
</head>
<body>

    <div class="w-full max-w-lg p-8 sm:p-12 bg-white rounded-3xl card-shadow mx-4">
        
        <!-- Header Logo -->
        <div class="text-center mb-10">
            <h1 class="text-5xl sm:text-6xl font-black tracking-tighter uppercase text-brand-red">
                MOM'S <span class="text-brand-gold">CHICKEN</span>
            </h1>
            <p class="text-xl font-medium text-gray-500 mt-2">Sistem Kasir</p>
        </div>

        <!-- Tampilkan Pesan Error Global (Misal: Password Salah) -->
        @if(session('error'))
            <div class="bg-red-100 border-l-4 border-brand-red text-red-700 p-4 mb-6 rounded-r" role="alert">
                <p class="font-bold">Gagal Login</p>
                <p>{{ session('error') }}</p>
            </div>
        @endif

        <!-- Form Login -->
        <!-- Action mengarah ke route 'authenticate' yang akan kita buat -->
        <form action="{{ route('login.auth') }}" method="POST" class="space-y-6">
            @csrf <!-- Token Keamanan Wajib Laravel -->
            
            <!-- Username -->
            <div>
                <label for="username" class="block text-sm font-semibold text-gray-700 mb-2">Username</label>
                <input type="text" id="username" name="username" value="{{ old('username') }}" required autofocus
                       class="custom-input w-full px-4 py-3 border @error('username') border-red-500 @else border-gray-300 @enderror rounded-xl text-lg transition duration-200 focus:outline-none placeholder-gray-400"
                       placeholder="Masukkan username">
                
                <!-- Pesan Error Validasi -->
                @error('username')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Password -->
            <div>
                <label for="password" class="block text-sm font-semibold text-gray-700 mb-2">Password</label>
                <input type="password" id="password" name="password" required 
                       class="custom-input w-full px-4 py-3 border @error('password') border-red-500 @else border-gray-300 @enderror rounded-xl text-lg transition duration-200 focus:outline-none placeholder-gray-400"
                       placeholder="Masukkan password">
            </div>

            <!-- Tombol Login -->
            <div class="pt-4">
                <button type="submit" class="login-button w-full py-4 text-xl font-bold rounded-xl text-white uppercase tracking-wider">
                    MASUK
                </button>
            </div>

            <p class="text-center text-sm text-gray-400 pt-4">
                © {{ date('Y') }} Mom's Chicken POS System
            </p>
        </form>

    </div>
    
</body>
</html>