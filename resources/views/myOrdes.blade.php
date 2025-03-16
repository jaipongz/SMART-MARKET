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
        <!-- Sidebar -->


        <!-- Main Content -->
        <div class="w-full sm:w-5/6 bg-white p-6 ml-0 sm:ml-[16.67%] transition-all duration-300">
            <!-- Header -->
            <div
                class="mb-6 flex flex-col sm:flex-row items-center sm:items-start space-y-4 sm:space-y-0 sm:space-x-4 text-center sm:text-left">
                <!-- รูปโปรไฟล์ร้านค้า (คลิกเพื่ออัปโหลด) -->
                <label for="profilePicInput" class="cursor-pointer">
                    @if ($merchant->profile_pic)
                        <img id="profilePicPreview" src="data:image/jpeg;base64,{{ $merchant->profile_pic }}"
                            alt="Merchant Profile" class="w-24 h-24 sm:w-16 sm:h-16 object-cover rounded-full shadow">
                    @else
                        <div id="profilePicPlaceholder"
                            class="w-24 h-24 sm:w-16 sm:h-16 bg-gray-300 rounded-full flex items-center justify-center text-gray-600">
                            📷
                        </div>
                    @endif
                </label>

                <!-- Input สำหรับอัปโหลดรูป (ซ่อนไว้) -->
                <input type="file" id="profilePicInput" class="hidden" accept="image/*">

                <!-- ข้อความต้อนรับ -->
                <div>
                    <h2 class="font-semibold text-2xl sm:text-3xl text-gray-800 mt-2 sm:mt-0">ออเดอร์ของคุณ</h2>
                    <p class="mt-1 text-gray-600">ร้าน {{ $merchant->name }}</p>
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
                <table class="hidden sm:table min-w-full bg-white border border-gray-200 shadow-md rounded-lg">
                    <thead>
                        <tr class="bg-gray-100 text-gray-600">
                            {{-- <th class="py-3 px-4 text-left"></th> --}}
                            <th class="py-3 px-4 text-left whitespace-nowrap">หมายเลขออเดอร์</th>
                            <th class="py-3 px-4 text-left whitespace-nowrap">ราคา</th>
                            <th class="py-3 px-4 text-left whitespace-nowrap">สถานะ</th>
                            <th class="py-3 px-4 text-left whitespace-nowrap">วันที่</th>
                            <th class="py-3 px-4 text-left whitespace-nowrap">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($orders as $order)
                            <tr class="border-b">
                                <td class="py-2 px-4 text-left">{{ $order->order_id }}</td>
                                <td class="py-2 px-4 text-left">{{ $order->total_price }}</td>
                                <td class="py-2 px-4 text-left ">
                                    @if ($order->order_status == 'completed')
                                        <span
                                            class="bg-green-200 text-green-800 px-2 py-1 rounded-full ">เสร็จสิ้น</span>
                                    @elseif ($order->order_status == 'pending')
                                        <span
                                            class="bg-yellow-200 text-yellow-800 px-2 py-1 rounded-full">กำลังดำเนินการ</span>
                                    @elseif ($order->order_status == 'canceled')
                                        <span class="bg-gray-200 text-gray-800 px-2 py-1 rounded-full">ยกเลิก</span>
                                    @else
                                        <span
                                            class="bg-gray-200 text-gray-800 px-2 py-1 rounded-full">ไม่ทราบสถานะ</span>
                                    @endif
                                </td>
                                <td class="py-2 px-4 text-left">{{ $order->created_at }}</td>
                                <td class="py-2 px-4 text-left">
                                    {{-- <button
                                        onclick="editProduct('{{ $item->id }}', '{{ $item->product_name }}', '{{ $item->amount }}', '{{ $item->price }}', '{{ $item->product_pic }}')"
                                        class="text-yellow-500 hover:text-yellow-700">
                                        แก้ไข
                                    </button> --}}
                                    @if ($order->order_status == 'pending')
                                        {{-- <form action="" method="POST"  --}}
                                        <form action="{{ route('order.cancel', ['id' => $order->order_id]) }}" method="POST"
                                            onsubmit="return confirm('Are you sure you want to delete this product?')">
                                            @csrf
                                            @method('PUT')
                                            <button type="submit"
                                                class="text-red-500 hover:text-red-700 ml-4">ยกเลิก</button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <!-- มือถือ: Card -->
                <div class="sm:hidden space-y-4">
                    @foreach ($orders as $order)
                        <div class="bg-white border border-gray-200 shadow-md rounded-lg p-4 flex flex-col">
                            <div class="flex items-center space-x-4">
                                <div class="flex-1">
                                    <h3 class="font-semibold text-lg text-gray-800">{{ $order->order_id }}</h3>
                                    <p class="text-gray-600">ราคา: {{ $order->total_price }} บาท</p>
                                    <p class="text-gray-600">สถานะ: @if ($order->order_status == 'completed')
                                            <span
                                                class="bg-green-200 text-green-800 px-2 py-1 rounded-full ">เสร็จสิ้น</span>
                                        @elseif ($order->order_status == 'pending')
                                            <span
                                                class="bg-yellow-200 text-yellow-800 px-2 py-1 rounded-full">กำลังดำเนินการ</span>
                                        @elseif ($order->order_status == 'canceled')
                                            <span class="bg-gray-200 text-gray-800 px-2 py-1 rounded-full">ยกเลิก</span>
                                        @else
                                            <span
                                                class="bg-gray-200 text-gray-800 px-2 py-1 rounded-full">ไม่ทราบสถานะ</span>
                                        @endif
                                    </p>
                                    <p class="text-gray-500 text-sm">วันที่: {{ $order->created_at }}</p>
                                </div>
                            </div>
                            <!-- ปุ่มแก้ไข & ลบ -->
                            <div class="mt-4 flex justify-end space-x-4">
                                {{-- <button
                                    onclick="editProduct('{{ $item->id }}', '{{ $item->product_name }}', '{{ $item->amount }}', '{{ $item->price }}', '{{ $item->product_pic }}')"
                                    class="text-yellow-500 hover:text-yellow-700">
                                    แก้ไข
                                </button> --}}
                                <form action="" method="POST"
                                    onsubmit="return confirm('Are you sure you want to delete this product?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:text-red-700">ยกเลิก</button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>


            <!-- Logout Button -->
            <div class="mt-6">
                <form method="POST" action="{{ route('logout') }}">
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

        const menuToggle = document.getElementById('menuToggle');
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('overlay');

        menuToggle.addEventListener('click', function() {
            sidebar.classList.toggle('-translate-x-full'); // เปิด-ปิด sidebar
            overlay.classList.toggle('hidden'); // เปิด-ปิด overlay
        });

        overlay.addEventListener('click', function() {
            sidebar.classList.add('-translate-x-full'); // ปิด sidebar
            overlay.classList.add('hidden'); // ปิด overlay
        });

        document.getElementById('profilePicInput').addEventListener('change', function(event) {
            previewProfilePic(event);
            uploadProfilePic(event);
        });

        function previewProfilePic(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const imgElement = document.getElementById('profilePicPreview');
                    const placeholder = document.getElementById('profilePicPlaceholder');

                    if (imgElement) {
                        imgElement.src = e.target.result;
                    } else {
                        // ถ้าไม่มีรูปเดิม ให้สร้าง <img> มาแทน Placeholder
                        const newImg = document.createElement('img');
                        newImg.id = 'profilePicPreview';
                        newImg.src = e.target.result;
                        newImg.className = "w-24 h-24 sm:w-16 sm:h-16 object-cover rounded-full shadow";
                        placeholder.replaceWith(newImg);
                    }
                };
                reader.readAsDataURL(file);
            }
        }

        function uploadProfilePic(event) {
            const file = event.target.files[0];
            if (!file) return;

            const formData = new FormData();
            formData.append('profile_pic', file);
            formData.append('merchant_id', {{ Auth::user()->id }});

            fetch('/upload-merchant-profile', {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    console.log('Profile picture uploaded:', data);
                    // if (data.profile_pic) {
                    //     document.getElementById('profilePicPreview').src = data.profile_pic;
                    // }
                })
                .catch(error => console.error('Error uploading:', error));
        }
    </script>


</body>

</html>
