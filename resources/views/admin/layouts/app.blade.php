<!DOCTYPE html>
<html lang="en">

@include('admin.layouts.head')


<body class="bg-gray-900 text-white font-sans">
    <div id="app" class="flex">
        <!-- Sidebar -->
        @include('admin.layouts.aside')

        <!-- Main Content -->
        <div class="flex-1 p-6">
            <!-- Header -->
            <div class="flex justify-between items-center mb-4">
                <h1 class="text-2xl font-bold">User Management</h1>
                <button id="toggleSidebarBtn" class="bg-blue-600 hover:bg-blue-500 text-white px-4 py-2 rounded-md md:hidden">
                    ☰
                </button>
            </div>

            <!-- Search Bar -->
            <div class="flex items-center bg-gray-800 p-2 rounded-md mb-6">
                <input type="text" placeholder="ค้นหาผู้ใช้งาน ID/หมายเลขโทรศัพท์" class="bg-gray-900 w-full text-white p-2 rounded-md focus:outline-none">
                <button class="text-white ml-2">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="w-6 h-6">
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
                            <th class="py-2">Package</th>
                            <th class="py-2">ยอดที่ใช้</th>
                            <th class="py-2">Start</th>
                            <th class="py-2">End</th>
                            <th class="py-2">สถานะ</th>
                        </tr>
                    </thead>
                    <tbody class="text-white">
                        <tr class="border-b border-gray-700">
                            <td class="py-2 flex items-center space-x-2">
                                <img src="https://placehold.co/32x32" alt="Avatar" class="w-8 h-8 rounded-full">
                                <span>บ้านแพร <span class="text-gray-400 text-sm">xxx-xxx-xxxx</span></span>
                            </td>
                            <td class="py-2">Starter pack</td>
                            <td class="py-2">1,111/2,000</td>
                            <td class="py-2">01/01/2024</td>
                            <td class="py-2">31/01/2024</td>
                            <td class="py-2"><span class="bg-yellow-500 text-black px-2 py-1 rounded-md">กำลังดำเนินการ</span></td>
                        </tr>
                        <!-- Additional Rows -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    
</body>

</html>
