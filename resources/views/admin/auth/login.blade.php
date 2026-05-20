<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('Admin Login') }} | EC-NEXT</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: radial-gradient(circle at top left, #1e293b, #0f172a);
            color: #f8fafc;
        }
        .glass {
            background: rgba(30, 41, 59, 0.7);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
        input:focus {
            box-shadow: 0 0 15px rgba(99, 102, 241, 0.3);
            border-color: rgba(99, 102, 241, 0.5) !important;
        }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center p-6">

    <div class="w-full max-w-md">
        <div class="flex flex-col items-center mb-10">
            <div class="w-12 h-12 bg-indigo-500 rounded-xl flex items-center justify-center shadow-2xl shadow-indigo-500/50 mb-4">
                <span class="text-white font-bold text-xl">N</span>
            </div>
            <h1 class="text-3xl font-bold tracking-tighter">EC-NEXT <span class="text-indigo-400 text-sm font-medium uppercase ml-1">{{ __('Admin') }}</span></h1>
            <p class="text-slate-500 text-sm mt-2 font-medium">{{ __('Please sign in with your credentials') }}</p>
        </div>

        <div class="glass p-10 rounded-[2.5rem] shadow-2xl">
            <form method="POST" action="{{ route('admin.login') }}" class="space-y-6">
                @csrf

                <!-- Email Address -->
                <div class="space-y-2">
                    <label for="email" class="text-xs font-bold uppercase tracking-widest text-slate-400 ml-1">{{ __('Email Address') }}</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus placeholder="admin@example.com"
                        class="w-full bg-slate-900/50 border border-white/10 rounded-2xl px-5 py-4 text-white placeholder-slate-600 transition-all outline-none">
                    @error('email')
                        <p class="text-rose-400 text-xs mt-1 ml-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password -->
                <div class="space-y-2">
                    <div class="flex justify-between items-center px-1">
                        <label for="password" class="text-xs font-bold uppercase tracking-widest text-slate-400">{{ __('Password') }}</label>
                        <a href="#" class="text-[10px] font-bold text-indigo-400 hover:text-indigo-300 transition-colors uppercase tracking-wider">{{ __('Forgot?') }}</a>
                    </div>
                    <input id="password" type="password" name="password" required placeholder="••••••••"
                        class="w-full bg-slate-900/50 border border-white/10 rounded-2xl px-5 py-4 text-white placeholder-slate-600 transition-all outline-none">
                    @error('password')
                        <p class="text-rose-400 text-xs mt-1 ml-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Remember Me -->
                <div class="flex items-center gap-3 px-1">
                    <input type="checkbox" id="remember_me" name="remember" class="w-4 h-4 rounded border-white/10 bg-slate-900 text-indigo-500 focus:ring-indigo-500 focus:ring-offset-slate-900">
                    <label for="remember_me" class="text-xs font-medium text-slate-400">{{ __('Stay signed in for 30 days') }}</label>
                </div>

                <div class="pt-4">
                    <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-500 text-white font-bold py-4 rounded-2xl shadow-xl shadow-indigo-600/20 transition-all active:scale-[0.98]">
                        {{ __('Sign In to Console') }}
                    </button>
                </div>
            </form>
        </div>

        <p class="text-center mt-10 text-xs text-slate-600 font-medium tracking-wide">
            Powered by &copy; {{ date('Y') }} EC-NEXT Platform
        </p>
    </div>

</body>
</html>
