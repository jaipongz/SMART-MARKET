<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.1.2/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@2.8.2/dist/alpine.js" defer></script>
</head>

<body class="bg-gray-100">

    <!-- Sidebar + Main Content Wrapper -->
    <div class="flex flex-col sm:flex-row">

        <!-- Sidebar -->
        @include('layouts.aside')

        <!-- Main Content -->
        <div class="w-full sm:w-5/6 bg-white p-6">
            <!-- Header -->
            <div class="mb-6">
                <h2 class="font-semibold text-3xl text-gray-800 leading-tight">สินค้าของคุณ</h2>
                <p class="mt-2 text-gray-600">ยินดีต้อนรับเข้าสู่ระบบ {{$merchant->name}}</p>
            </div>

            <!-- ปุ่มเพิ่มสินค้า -->
            <div class="mb-6">
                <button onclick="openModal()" class="bg-blue-600 text-white py-2 px-4 rounded hover:bg-blue-700">
                    เพิ่มสินค้า
                </button>
            </div>

            <!-- ตารางสินค้า -->
            <div class="overflow-x-auto">
                <table class="min-w-full bg-white border border-gray-200 shadow-md rounded-lg">
                    <thead>
                        <tr class="bg-gray-100 text-gray-600">
                            <th class="py-3 px-6 text-left">#</th>
                            <th class="py-3 px-6 text-left">ชื่อสินค้า</th>
                            <th class="py-3 px-6 text-left">จำนวน</th>
                            <th class="py-3 px-6 text-left">ราคา</th>
                            <th class="py-3 px-6 text-left">วันที่</th>
                            <th class="py-3 px-6 text-left">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($products as $item)
                            <tr class="border-b">
                                <td class="py-3 px-6 text-left">{{ $item->product_id }}</td>
                                <td class="py-3 px-6 text-left">{{ $item->product_name }}</td>
                                <td class="py-3 px-6 text-left">{{ $item->amount }}</td>
                                <td class="py-3 px-6 text-left">{{ $item->price }}</td>
                                <td class="py-3 px-6 text-left">{{ $item->created_at }}</td>
                                <td class="py-3 px-6 text-left">
                                    <button onclick="editProduct(1)"
                                        class="text-yellow-500 hover:text-yellow-700">แก้ไข</button>
                                    <button onclick="deleteProduct(1)"
                                        class="text-red-500 hover:text-red-700 ml-4">ลบ</button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Logout Button -->
            <div class="mt-6">
                <form method="POST" action="/logout">
                    @csrf
                    <button type="submit"
                        class="bg-red-600 text-white py-2 px-4 rounded hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-opacity-50">
                        Logout
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal for Editing Product -->
    <div id="editModal" class="fixed inset-0 flex justify-center items-center bg-gray-800 bg-opacity-50 hidden">
        <div class="bg-white p-6 rounded-lg w-96">
            <h3 class="text-2xl font-semibold text-gray-800 mb-4">แก้ไขสินค้า</h3>
            <form id="editForm">
                <div class="mb-4">
                    <label for="productName" class="block text-gray-700">ชื่อสินค้า</label>
                    <input type="text" id="productName" class="w-full px-4 py-2 border border-gray-300 rounded-lg"
                        value="" required>
                </div>
                <div class="mb-4">
                    <label for="productQuantity" class="block text-gray-700">จำนวน</label>
                    <input type="number" id="productQuantity"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg" value="" required>
                </div>
                <div class="mb-4">
                    <label for="productPrice" class="block text-gray-700">ราคา</label>
                    <input type="number" id="productPrice" class="w-full px-4 py-2 border border-gray-300 rounded-lg"
                        value="" required>
                </div>
                <div class="flex justify-end">
                    <button type="button" onclick="closeModal()"
                        class="bg-gray-500 text-white py-2 px-4 rounded hover:bg-gray-600">ยกเลิก</button>
                    <button type="submit"
                        class="bg-blue-600 text-white py-2 px-4 rounded hover:bg-blue-700 ml-4">บันทึกการเปลี่ยนแปลง</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Open modal
        function openModal() {
            document.getElementById('editModal').classList.remove('hidden');
        }

        // Close modal
        function closeModal() {
            document.getElementById('editModal').classList.add('hidden');
        }

        // Edit Product (this will populate the modal)
        function editProduct(id) {
            // Populate the modal with the existing data (you can fetch data dynamically from a database here)
            document.getElementById('productName').value = "สินค้า " + id; // Example
            document.getElementById('productQuantity').value = id * 10; // Example
            document.getElementById('productPrice').value = id * 100; // Example
            openModal();
        }

        // Delete Product
        function deleteProduct(id) {
            if (confirm("คุณแน่ใจหรือไม่ที่จะลบสินค้านี้?")) {
                // Handle the deletion (send a request to delete the product)
                alert("ลบสินค้า " + id);
            }
        }
    </script>

</body>

</html>
