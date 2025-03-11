<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>สมัครสมาชิก</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-r from-gray-900 via-gray-800 to-gray-900 text-white flex items-center justify-center min-h-screen">
    <div class="w-full max-w-md bg-gray-800 p-8 rounded-2xl shadow-2xl transition-transform transform hover:scale-105">
        <h2 class="text-2xl font-bold text-center mb-6 text-indigo-400">สมัครสมาชิก</h2>

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <div class="mb-4">
                <label for="name" class="block text-sm font-medium text-gray-300">ชื่อร้านค้า</label>
                <input id="name" type="text" name="name" class="mt-1 block w-full rounded-lg bg-gray-700 border border-gray-600 text-white focus:ring-2 focus:ring-indigo-500 p-3" required autofocus autocomplete="name">
            </div>

            <div class="mb-4">
                <label for="email" class="block text-sm font-medium text-gray-300">อีเมล</label>
                <input id="email" type="email" name="email" class="mt-1 block w-full rounded-lg bg-gray-700 border border-gray-600 text-white focus:ring-2 focus:ring-indigo-500 p-3" required autocomplete="username">
            </div>

            <div class="mb-4">
                <label for="password" class="block text-sm font-medium text-gray-300">รหัสผ่าน</label>
                <input id="password" type="password" name="password" class="mt-1 block w-full rounded-lg bg-gray-700 border border-gray-600 text-white focus:ring-2 focus:ring-indigo-500 p-3" required autocomplete="new-password">
            </div>

            <div class="mb-4">
                <label for="password_confirmation" class="block text-sm font-medium text-gray-300">ยืนยันรหัสผ่าน</label>
                <input id="password_confirmation" type="password" name="password_confirmation" class="mt-1 block w-full rounded-lg bg-gray-700 border border-gray-600 text-white focus:ring-2 focus:ring-indigo-500 p-3" required autocomplete="new-password">
            </div>

            <div class="flex items-center justify-between mt-4">
                <a href="{{ route('login') }}" class="text-indigo-400 hover:text-indigo-300 transition">มีบัญชีอยู่แล้ว? เข้าสู่ระบบ</a>
                <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-4 rounded-lg focus:ring-2 focus:ring-indigo-500 transition transform hover:scale-105">
                    สมัครสมาชิก
                </button>
            </div>
        </form>
    </div>
</body>
</html>
