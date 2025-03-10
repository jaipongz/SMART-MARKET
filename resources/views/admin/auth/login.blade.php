<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>เข้าสู่ระบบร้านค้า</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-900 text-white">
    <div class="flex items-center justify-center min-h-screen">
        <div class="w-full max-w-md bg-gray-800 p-6 rounded-lg shadow-lg">
            <h2 class="text-xl font-semibold text-center mb-6">{{ __('เข้าสู่ระบบร้านค้า') }}</h2>

            <!-- Display Session Status -->
            @if (session('status'))
                <div class="mb-4 p-3 text-sm bg-green-500 text-white rounded-lg">
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <!-- Email Address -->
                <div class="mb-4">
                    <label for="email" class="block text-sm font-medium">{{ __('Email') }}</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" 
                           class="mt-1 block w-full rounded-lg bg-gray-700 border-gray-600 text-white focus:ring-indigo-500 focus:border-indigo-500" 
                           required autofocus autocomplete="username">
                    @error('email')
                        <span class="text-sm text-red-500 mt-1">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Password -->
                <div class="mb-4">
                    <label for="password" class="block text-sm font-medium">{{ __('Password') }}</label>
                    <input id="password" type="password" name="password" 
                           class="mt-1 block w-full rounded-lg bg-gray-700 border-gray-600 text-white focus:ring-indigo-500 focus:border-indigo-500" 
                           required autocomplete="current-password">
                    @error('password')
                        <span class="text-sm text-red-500 mt-1">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Remember Me -->
                <div class="flex items-center mb-4">
                    <input id="remember_me" type="checkbox" name="remember" 
                           class="h-4 w-4 rounded border-gray-600 bg-gray-700 text-indigo-600 focus:ring-indigo-500">
                    <label for="remember_me" class="ms-2 text-sm">{{ __('Remember me') }}</label>
                </div>

                <!-- Forgot Password and Login Button -->
                <div class="flex items-center justify-end" style="gap:  10px">
                    <a class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2" href="{{ route('admin.register') }}">ลงทะเบียน</a>
                    <button type="submit" 
                            class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                        {{ __('เข้าสู่ระบบ') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
