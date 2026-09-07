<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin - CeritaKu</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="bg-slate-50 min-h-screen flex items-center justify-center p-4">
    <div class="w-full max-w-md bg-white rounded-2xl shadow-xl overflow-hidden border border-slate-100">
        <div class="p-8 text-center bg-slate-900">
            <h1 class="text-3xl font-bold text-white mb-2">Cerita<span class="text-blue-500">Ku</span> Admin</h1>
            <p class="text-slate-400 text-sm">Masuk ke panel manajemen pesanan</p>
        </div>
        
        <div class="p-8">
            @if(session('error'))
                <div class="mb-6 p-3 bg-red-50 border border-red-200 text-red-600 rounded-lg text-sm text-center font-medium">
                    {{ session('error') }}
                </div>
            @endif

            <form action="/admin/login" method="POST" class="space-y-5">
                @csrf
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Alamat Email</label>
                    <input type="email" name="email" required placeholder="admin@ceritaku.com" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:bg-white transition-colors">
                </div>
                
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Password</label>
                    <input type="password" name="password" required placeholder="••••••••" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:bg-white transition-colors">
                </div>

                <div class="pt-2">
                    <button type="submit" class="w-full py-3 bg-blue-600 text-white font-bold rounded-lg hover:bg-blue-700 transition-colors shadow-lg shadow-blue-500/30">
                        Masuk ke Dashboard
                    </button>
                </div>
            </form>
        </div>
        <div class="p-4 bg-slate-50 text-center text-xs text-slate-500 border-t border-slate-100">
            &copy; 2026 CeritaKu Platform
        </div>
    </div>
</body>
</html>
