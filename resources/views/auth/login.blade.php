<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>เข้าสู่ระบบร้านค้า</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-r from-gray-900 via-gray-800 to-gray-900 text-white flex items-center justify-center min-h-screen">
    <div class="w-full max-w-md bg-gray-800 p-8 rounded-2xl shadow-2xl transition-transform transform hover:scale-105">
        <h2 class="text-2xl font-bold text-center mb-6 text-indigo-400">{{ __('เข้าสู่ระบบร้านค้า') }}</h2>

        @if (session('status'))
            <div class="mb-4 p-3 text-sm bg-green-500 text-white rounded-lg">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="mb-4">
                <label for="email" class="block text-sm font-medium text-gray-300">{{ __('Email') }}</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" 
                       class="mt-1 block w-full rounded-lg bg-gray-700 border border-gray-600 text-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 p-3 transition" 
                       required autofocus autocomplete="username">
                @error('email')
                    <span class="text-sm text-red-500 mt-1">{{ $message }}</span>
                @enderror
            </div>

            <div class="mb-4">
                <label for="password" class="block text-sm font-medium text-gray-300">{{ __('Password') }}</label>
                <input id="password" type="password" name="password" 
                       class="mt-1 block w-full rounded-lg bg-gray-700 border border-gray-600 text-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 p-3 transition" 
                       required autocomplete="current-password">
                @error('password')
                    <span class="text-sm text-red-500 mt-1">{{ $message }}</span>
                @enderror
            </div>

            <div class="flex items-center justify-between mt-4">
                <a href="{{ route('register') }}" class="text-indigo-400 hover:text-indigo-300 transition">ลงทะเบียน</a>
            </div>

            <button type="submit" 
                    class="w-full mt-4 bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-4 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition transform hover:scale-105">
                {{ __('เข้าสู่ระบบ') }}
            </button>
        </form>
    </div>
</body>
</html>