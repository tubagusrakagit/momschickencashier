<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Mom\'s Chicken POS')</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    
    <script src="https://unpkg.com/lucide@latest"></script>
    
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f3f4f6; /* Abu-abu terang */
        }
        /* Definisi warna custom Mom's Chicken untuk Tailwind */
        .bg-brand-red { background-color: #D93630; }
        .text-brand-red { color: #D93630; }
        .border-brand-red { border-color: #D93630; }
        
        .bg-brand-gold { background-color: #FFC300; }
        .text-brand-gold { color: #FFC300; }
        .border-brand-gold { border-color: #FFC300; }
    </style>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'brand-red': '#D93630',
                        'brand-gold': '#FFC300',
                    }
                }
            }
        }
    </script>
</head>
<body class="antialiased text-gray-800">

    <div class="flex min-h-screen">
        
        <aside class="w-64 bg-white border-r border-gray-200 hidden md:flex flex-col fixed h-full z-10">
            <div class="h-16 flex items-center px-6 border-b border-gray-100">
                <span class="text-2xl font-black text-brand-red">Mom's <span class="text-brand-gold">Chicken</span></span>
            </div>

            <nav class="flex-1 px-4 py-6 space-y-2 overflow-y-auto">
                
                <a href="{{ route('dashboard') }}" class="flex items-center px-4 py-3 rounded-xl transition-colors {{ Request::routeIs('dashboard') ? 'bg-brand-red/10 text-brand-red font-semibold' : 'text-gray-600 hover:bg-gray-50' }}">
                    <i data-lucide="layout-dashboard" class="w-5 h-5 mr-3"></i>
                    <span>Dashboard</span>
                </a>

                <a href="{{ route('kasir') }}" class="flex items-center px-4 py-3 rounded-xl transition-colors {{ Request::routeIs('kasir') ? 'bg-brand-red/10 text-brand-red font-semibold' : 'text-gray-600 hover:bg-gray-50' }}">
                    <i data-lucide="calculator" class="w-5 h-5 mr-3"></i>
                    <span>Kasir / Transaksi</span>
                </a>

                <a href="{{ route('produk.index') }}" class="flex items-center px-4 py-3 rounded-xl transition-colors {{ Request::routeIs('produk.index') ? 'bg-brand-red/10 text-brand-red font-semibold' : 'text-gray-600 hover:bg-gray-50' }}">
                    <i data-lucide="package" class="w-5 h-5 mr-3"></i>
                    <span>Master Produk</span>
                </a>

                <a href="#" class="flex items-center px-4 py-3 text-gray-600 rounded-xl hover:bg-gray-50 transition-colors">
                    <i data-lucide="bar-chart-3" class="w-5 h-5 mr-3"></i>
                    <span class="font-medium">Laporan</span>
                </a>
                
                <form action="{{ route('logout') }}" method="POST" class="mt-4">
                    @csrf
                    <button type="submit" class="w-full flex items-center px-4 py-3 text-gray-600 rounded-xl hover:bg-gray-50 transition-colors">
                        <i data-lucide="log-out" class="w-5 h-5 mr-3"></i>
                        <span class="font-medium">Logout</span>
                    </button>
                </form>

            </nav>

            <div class="p-4 border-t border-gray-100">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-brand-gold flex items-center justify-center text-white font-bold">
                        {{ strtoupper(substr(Auth::user()->name ?? 'CS', 0, 2)) }}
                    </div>
                    <div>
                        <p class="text-sm font-bold text-gray-700">{{ Auth::user()->name ?? 'Kasir' }}</p>
                        <p class="text-xs text-green-500">● {{ ucfirst(Auth::user()->role ?? 'Guest') }}</p>
                    </div>
                </div>
            </div>
        </aside>

        <main class="flex-1 md:ml-64 p-8 bg-[#f8f9fa]">
            @yield('content')
        </main>

    </div>

    <script>
        lucide.createIcons();
    </script>
</body>
</html>