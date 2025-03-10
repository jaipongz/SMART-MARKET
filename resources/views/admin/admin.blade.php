<!DOCTYPE html>
<html lang="en">

@include('admin.layouts.head')


<body class="bg-gray-900 text-white font-sans">
    <div id="app" class="flex">
        @include('admin.layouts.aside')

        <div class="flex-1 p-6">
            <div class="flex justify-between items-center mb-4">
                <h1 class="text-2xl font-bold">Admin management</h1>
                <button id="toggleSidebarBtn"
                    class="bg-blue-600 hover:bg-blue-500 text-white px-4 py-2 rounded-md md:hidden">
                    ☰
                </button>
            </div>

            <div class="flex items-center bg-gray-800 p-2 rounded-md mb-6">
                <input type="text" placeholder="ค้นหาผู้ใช้งาน ID/หมายเลขโทรศัพท์"
                    class="bg-gray-900 w-full text-white p-2 rounded-md focus:outline-none">
                <button class="text-white ml-2">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                        class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19a8 8 0 100-16 8 8 0 000 16zm5.93-6.93l4.24 4.24" />
                    </svg>
                </button>
            </div>

            <!-- Table -->
            <div class="bg-gray-800 p-4 rounded-md">
                <table class="w-full text-left">
                    <thead>
                        <tr class="text-gray-400">
                            <th class="py-2">รายชื่อ</th>
                            <th class="py-2">E-mail</th>
                            <th class="py-2">Created Date</th>
                        </tr>
                    </thead>
                    <tbody class="text-white">
                        @foreach ($user as $users)
                            <tr class="border-b border-gray-700">
                                <td class="py-2 flex items-center space-x-2">
                                    <span>{{$users->name}}</span>
                                </td>
                                <td class="py-2">{{$users->email}}</td>
                                <td class="py-2">{{$users->created_at}}</td>
                                
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @include('admin.layouts.script')

</body>

</html>
