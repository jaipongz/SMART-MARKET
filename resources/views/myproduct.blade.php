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
            <div class="mb-6 flex items-center space-x-4">
                <!-- รูปโปรไฟล์ร้านค้า -->
                @if($merchant->profile_pic)
                    <img src="data:image/jpeg;base64,{{ $merchant->profile_pic }}" alt="Merchant Profile"
                        class="w-16 h-16 object-cover rounded-full shadow">
                @else
                    <div class="w-16 h-16 bg-gray-300 rounded-full flex items-center justify-center text-gray-600">
                        📷
                    </div>
                @endif
            
                <!-- ข้อความต้อนรับ -->
                <div>
                    <h2 class="font-semibold text-3xl text-gray-800">สินค้าของคุณ</h2>
                    <p class="mt-2 text-gray-600">ยินดีต้อนรับ {{ $merchant->name }}</p>
                </div>
            </div>
            <!-- ปุ่มเพิ่มสินค้า -->
            <div class="mb-6">
                <a href="{{ route('merchantScan', [Auth::user()->id]) }}"
                    class="bg-blue-600 text-white py-2 px-4 rounded hover:bg-blue-700">
                    เพิ่มสินค้า
                </a>
            </div>

            <!-- ตารางสินค้า -->
            <div class="overflow-x-auto">
                <table class="min-w-full bg-white border border-gray-200 shadow-md rounded-lg">
                    <thead>
                        <tr class="bg-gray-100 text-gray-600">
                            <th class="py-3 px-6 text-left"></th>
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
                                <td >
                                    @if ($item->product_pic)
                                        <img  src="data:image/jpeg;base64,{{ $item->product_pic }}" alt="Product Image"
                                        class="w-32 h-32 object-cover rounded m-2">
                                    @else
                                        <span>No Image</span>
                                    @endif
                                </td>
                                <td class="py-3 px-6 text-left">{{ $item->product_name }}</td>
                                <td class="py-3 px-6 text-left">{{ $item->amount }}</td>
                                <td class="py-3 px-6 text-left">{{ $item->price }}</td>
                                <td class="py-3 px-6 text-left">{{ $item->created_at }}</td>
                                <td class="py-3 px-6 text-left">
                                    <button
                                        onclick="editProduct('{{ $item->id }}', '{{ $item->product_name }}', '{{ $item->amount }}', '{{ $item->price }}', '{{ $item->product_pic }}')"
                                        class="text-yellow-500 hover:text-yellow-700">
                                        แก้ไข
                                    </button>
                                    <form action="{{ route('product.destroy', ['id' => $item->id]) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this product?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-500 hover:text-red-700 ml-4">ลบ</button>
                                    </form>
                                    
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Logout Button -->
            <div class="mt-6">
                <form method="POST" action="{{route('logout')}}">
                    @csrf
                    <button type="submit"
                        class="bg-red-600 text-white py-2 px-4 rounded hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-opacity-50">
                        ออกจากระบบ
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal for Editing Product -->
    <div id="editModal" class="fixed inset-0 flex justify-center items-center bg-gray-800 bg-opacity-50 hidden">
        <div class="bg-white p-6 rounded-lg w-96">
            <h3 class="text-2xl font-semibold text-gray-800 mb-4">แก้ไขสินค้า</h3>
            <form id="editForm" method="POST" action="{{ route('product.update') }} "enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <input type="hidden" id="productId" name="product_id">
                <div class="mb-4 text-center">
                    <!-- รูปภาพสินค้า -->
                    <label for="productImageInput">
                        <img id="productImage" src="" alt="Product Image"
                            class="w-32 h-32 object-cover rounded mx-auto cursor-pointer hidden">
                    </label>
                    <input type="file" name="product_pic" id="productImageInput" accept="image/*" class="hidden"
                        onchange="previewImage(event)">
                </div>
                <div class="mb-4">
                    <label for="productName" class="block text-gray-700">ชื่อสินค้า</label>
                    <input type="text" name="product_name" id="productName"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg" required>
                </div>
                <div class="mb-4">
                    <label for="productQuantity" class="block text-gray-700">จำนวน</label>
                    <input type="number" name="product_amount" id="productQuantity"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg" required>
                </div>
                <div class="mb-4">
                    <label for="productPrice" class="block text-gray-700">ราคา</label>
                    <input type="number" name="product_price" id="productPrice"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg" required>
                </div>
                <div class="flex justify-end">
                    <span onclick="closeModal()"
                        class="bg-gray-500 text-white py-2 px-4 rounded hover:bg-gray-600">ยกเลิก</span>
                    <button type="submit"
                        class="bg-blue-600 text-white py-2 px-4 rounded hover:bg-blue-700 ml-4">บันทึกการเปลี่ยนแปลง</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // เปิด Modal
        function openModal() {
            document.getElementById('editModal').classList.remove('hidden');
        }

        // ปิด Modal
        function closeModal() {
            document.getElementById('editModal').classList.add('hidden');
        }

        // ฟังก์ชัน Edit Product (เติมค่าลงใน Modal)
        function editProduct(id, name, quantity, price, image) {
            document.getElementById('productId').value = id;
            document.getElementById('productName').value = name;
            document.getElementById('productQuantity').value = quantity;
            document.getElementById('productPrice').value = price;

            let imgElement = document.getElementById('productImage');

            if (image) {
                imgElement.src = "data:image/jpeg;base64," + image;
                imgElement.classList.remove('hidden');
            } else {
                imgElement.classList.add('hidden');
            }

            openModal();
        }

        // ฟังก์ชันแสดงตัวอย่างภาพหลังจากอัปโหลด
        function previewImage(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('productImage').src = e.target.result;
                    document.getElementById('productImage').classList.remove('hidden');
                };
                reader.readAsDataURL(file);
            }
        }

        // ฟังก์ชัน Delete Product
        function deleteProduct(id) {
            if (confirm("คุณแน่ใจหรือไม่ที่จะลบสินค้านี้?")) {
                alert("ลบสินค้า " + id);
            }
        }
    </script>


</body>

</html>
