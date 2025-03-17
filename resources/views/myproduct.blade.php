<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.0/dist/JsBarcode.all.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.1.2/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@2.8.2/dist/alpine.js" defer></script>
</head>
<style>

</style>

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
                    <h2 class="font-semibold text-2xl sm:text-3xl text-gray-800 mt-2 sm:mt-0">สินค้าของคุณ</h2>
                    <p class="mt-1 text-gray-600">ร้าน {{ $merchant->name }}</p>
                </div>
            </div>

            <!-- ปุ่มเพิ่มสินค้า -->
            <div class="mb-6 flex flex-col gap-2 md:flex-row md:gap-4">
                <a href="{{ route('merchantScan', [Auth::user()->id]) }}"
                    class="bg-green-600 text-white py-2 px-4 rounded hover:bg-green-700">
                    เพิ่มสินค้า
                </a>
                <button id="openModalBtn" onclick="openCreateModal()"
                    class="bg-blue-600 text-white py-2 px-4 rounded hover:bg-blue-700">
                    เพิ่มสินค้าที่ไม่มีบาร์โค้ด
                </button>
            </div>

            <!-- ตารางสินค้า -->
            <div class="overflow-x-auto">
                <table class="hidden sm:table min-w-full bg-white border border-gray-200 shadow-md rounded-lg">
                    <thead>
                        <tr class="bg-gray-100 text-gray-600">
                            <th class="py-3 px-4 text-left"></th>
                            <th class="py-3 px-4 text-left whitespace-nowrap">ชื่อสินค้า</th>
                            <th class="py-3 px-4 text-left whitespace-nowrap">จำนวน</th>
                            <th class="py-3 px-4 text-left whitespace-nowrap">ราคา</th>
                            <th class="py-3 px-4 text-left whitespace-nowrap">วันที่</th>
                            <th class="py-3 px-4 text-left whitespace-nowrap">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($products as $item)
                            <tr class="border-b">
                                <td class="py-2 px-4">
                                    @if ($item->product_pic)
                                        <img src="data:image/jpeg;base64,{{ $item->product_pic }}" alt="Product Image"
                                            class="w-20 h-20 object-cover rounded-lg border border-gray-300 shadow-sm">
                                    @else
                                        <div
                                            class="w-20 h-20 bg-gray-200 flex items-center justify-center rounded-lg border border-gray-300">
                                            <span class="text-gray-500">No Image</span>
                                        </div>
                                    @endif
                                </td>
                                <td class="py-2 px-4 text-left">{{ $item->product_name }}</td>
                                <td class="py-2 px-4 text-left">{{ $item->amount }}</td>
                                <td class="py-2 px-4 text-left">{{ $item->price }}</td>
                                <td class="py-2 px-4 text-left">{{ $item->created_at }}</td>
                                <td class="py-2 px-4 text-left gap-2">
                                    <button class="text-green-500 hover:text-green-700"
                                        onclick="showModal('{{ $item->product_id }}','{{$item->product_name}}')">ดูบาร์โค้ด</button>
                                    <button
                                        onclick="editProduct('{{ $item->id }}', '{{ $item->product_name }}'
                                        , '{{ $item->amount }}' , '{{ $item->price }}' , '{{ $item->product_pic }}'
                                        )"
                                        class="text-yellow-500 hover:text-yellow-700">
                                        แก้ไข
                                    </button>
                                    <form action="{{ route('product.destroy', ['id' => $item->id]) }}" method="POST"
                                        onsubmit="return confirm('Are you sure you want to delete this product?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-500 hover:text-red-700 ml-4">ลบ</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <!-- มือถือ: Card -->
                <div class="sm:hidden space-y-4">
                    @foreach ($products as $item)
                        <div class="bg-white border border-gray-200 shadow-md rounded-lg p-4 flex flex-col">
                            <div class="flex items-center space-x-4">
                                <!-- รูปภาพ -->
                                @if ($item->product_pic)
                                    <img src="data:image/jpeg;base64,{{ $item->product_pic }}" alt="Product Image"
                                        class="w-24 h-24 object-cover rounded-lg border border-gray-300 shadow-sm">
                                @else
                                    <div
                                        class="w-24 h-24 bg-gray-200 flex items-center justify-center rounded-lg border border-gray-300">
                                        <span class="text-gray-500">No Image</span>
                                    </div>
                                @endif
                                <!-- ข้อมูลสินค้า -->
                                <div class="flex-1">
                                    <h3 class="font-semibold text-lg text-gray-800">{{ $item->product_name }}</h3>
                                    <p class="text-gray-600">จำนวน: {{ $item->amount }}</p>
                                    <p class="text-gray-600">ราคา: {{ $item->price }} บาท</p>
                                    <p class="text-gray-500 text-sm">วันที่: {{ $item->created_at }}</p>
                                </div>
                            </div>
                            <!-- ปุ่มแก้ไข & ลบ -->
                            <div class="mt-4 flex justify-end space-x-4">
                                <button class="text-green-500 hover:text-green-700"
                                        onclick="showModal('{{ $item->product_id }}','{{$item->product_name}}')">ดูบาร์โค้ด</button>
                                <button
                                    onclick="editProduct('{{ $item->id }}', '{{ $item->product_name }}', '{{ $item->amount }}', '{{ $item->price }}', '{{ $item->product_pic }}')"
                                    class="text-yellow-500 hover:text-yellow-700">
                                    แก้ไข
                                </button>
                                <form action="{{ route('product.destroy', ['id' => $item->id]) }}" method="POST"
                                    onsubmit="return confirm('Are you sure you want to delete this product?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:text-red-700">ลบ</button>
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
    <div id="productModal" class="fixed inset-0 flex justify-center items-center bg-gray-800 bg-opacity-50 hidden">
        <div class="bg-white p-6 rounded-lg w-96">
            <h3 class="text-2xl font-semibold text-gray-800 mb-4">เพิ่มสินค้า</h3>
            <form method="POST" action="{{ route('product.store') }}" enctype="multipart/form-data">
                @csrf
                <input type="text" style="display: none" id="merchantId" name="merchantId"
                    value="{{ Auth::user()->id }}">

                <!-- รหัสสินค้า -->
                {{-- <div class="mb-4">
                    <label for="productCode" class="block text-gray-700">รหัสสินค้า:</label>
                    <input type="text" id="productCode" name="barcode" readonly
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                </div> --}}

                <!-- ชื่อสินค้า -->
                <div class="mb-4">
                    <label for="productName" class="block text-gray-700">ชื่อสินค้า:</label>
                    <input type="text" id="productName" name="name" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                </div>

                <!-- ราคา -->
                <div class="mb-4">
                    <label for="productPrice" class="block text-gray-700">ราคา (บาท):</label>
                    <input type="number" id="productPrice" name="price" min="0" step="0.01" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                </div>

                <!-- จำนวนในคลัง -->
                <div class="mb-4">
                    <label for="productStock" class="block text-gray-700">จำนวนในคลัง:</label>
                    <input type="number" id="productStock" name="stock" min="0" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                </div>

                <!-- อัปโหลดรูปสินค้า -->
                <div class="mb-4">
                    <label for="productImageInput" class="block text-gray-700">อัปโหลดรูปสินค้า:</label>
                    <input type="file" id="productImageInput" name="image" accept="image/*"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg" onchange="previewImage(event)">
                    <img id="productImagePreview" src=""
                        style="display:none; max-width: 200px; margin-top: 10px;">
                </div>

                <div class="flex justify-end">
                    <span onclick="closeCreateModal()"
                        class="bg-gray-500 text-white py-2 px-4 rounded hover:bg-gray-600 cursor-pointer">ยกเลิก</span>
                    <button type="submit"
                        class="bg-blue-600 text-white py-2 px-4 rounded hover:bg-blue-700 ml-4">บันทึกสินค้า</button>
                </div>
            </form>
        </div>
    </div>
    <!-- Modal for Editing Product -->
    <div id="editModal" class="fixed inset-0 flex justify-center items-center bg-gray-800 bg-opacity-50 hidden">
        <div class="bg-white p-6 rounded-lg w-96">
            <h3 class="text-2xl font-semibold text-gray-800 mb-4">แก้ไขสินค้า</h3>

            <form id="editForm" method="POST"
                action="{{ route('product.update') }} "enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <input type="hidden" id="productEditId" name="product_id">
                <div class="mb-4 text-center">
                    <!-- รูปภาพสินค้า -->
                    <label for="productEditImageInput">
                        <img id="productEditImage" src="" alt="Product Image"
                            class="w-32 h-32 object-cover rounded mx-auto cursor-pointer hidden">
                    </label>
                    <input type="file" name="product_pic" id="productEditImageInput" accept="image/*"
                        class="hidden" onchange="previewImage(event)">
                </div>
                <div class="mb-4">
                    <label for="productEditName" class="block text-gray-700">ชื่อสินค้า</label>
                    <input type="text" name="product_name" id="productEditName"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg" required>
                </div>
                <div class="mb-4">
                    <label for="productEditQuantity" class="block text-gray-700">จำนวน</label>
                    <input type="number" name="product_amount" id="productEditQuantity"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg" required>
                </div>
                <div class="mb-4">
                    <label for="productEditPrice" class="block text-gray-700">ราคา</label>
                    <input type="number" name="product_price" id="productEditPrice"
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
    <div id="barcodeModal" class="fixed inset-0 flex justify-center items-center bg-gray-800 bg-opacity-50 hidden">
        <div class="bg-white p-6 rounded-lg w-96">
            <h3 id="barcodeName" class="text-2xl font-semibold text-gray-800 mb-4">รหัส</h3>

            <svg id="Mybarcode" class="w-full h-32 mb-4"></svg>

            <div class="flex justify-end">
                <span onclick="closeModal()"
                    class="bg-gray-500 text-white py-2 px-4 rounded hover:bg-gray-600 cursor-pointer">ปิด</span>
                <button id="downloadBarcode"
                    class="bg-blue-600 text-white py-2 px-4 rounded hover:bg-blue-700 ml-4">ดาวน์โหลด</button>
            </div>
        </div>
    </div>

    <script>
        function openCreateModal() {
            var modal = document.getElementById('productModal');
            modal.classList.remove('hidden'); // เปิด Modal
        }

        // ฟังก์ชันปิด Modal การสร้างสินค้า
        function closeCreateModal() {
            var modal = document.getElementById('productModal');
            modal.classList.add('hidden'); // ปิด Modal
        }

        // ฟังก์ชันพรีวิวรูปภาพ
        function previewImage(event) {
            var reader = new FileReader();
            reader.onload = function() {
                var output = document.getElementById('productImagePreview');
                output.src = reader.result;
                output.style.classList.remove('hidden');
            };
            reader.readAsDataURL(event.target.files[0]);
        }


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

            document.getElementById('productEditId').value = id;
            document.getElementById('productEditName').value = name;
            document.getElementById('productEditQuantity').value = quantity;
            document.getElementById('productEditPrice').value = price;

            let imgElement = document.getElementById('productEditImage');

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
                    document.getElementById('productEditImage').src = e.target.result;
                    document.getElementById('productEditImage').classList.remove('hidden');
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


        // Show the modal
        function showModal(productId,productName) {
            const modal = document.getElementById('barcodeModal');
            document.getElementById('barcodeName').textContent = `Barcode ${productName}`;
            modal.style.display = 'flex';
            generateBarcode(productId); // Generate the barcode when the modal opens
        }

        // Close the modal
        function closeModal() {
            const modal = document.getElementById('barcodeModal');
            modal.style.display = 'none';
        }

        // Generate the barcode using JsBarcode
        function generateBarcode(productId) {
            JsBarcode("#Mybarcode", productId, {
                format: "EAN13", // Barcode format (you can choose different formats)
                lineColor: "#000000", // Barcode line color
                width: 4, // Width of each barcode line
                height: 100, // Height of the barcode
                displayValue: true, // Show the barcode number under the barcode
                fontSize: 18, // Font size of the displayed value
            });
        }

        // Download the barcode as an image
        document.getElementById('downloadBarcode').addEventListener('click', function() {
            const svg = document.getElementById('Mybarcode');
            const svgData = new XMLSerializer().serializeToString(svg);
            const canvas = document.createElement('canvas');
            const ctx = canvas.getContext('2d');
            const img = new Image();
            const svgBlob = new Blob([svgData], {
                type: 'image/svg+xml;charset=utf-8'
            });
            const url = URL.createObjectURL(svgBlob);

            img.onload = function() {
                canvas.width = img.width;
                canvas.height = img.height;
                ctx.drawImage(img, 0, 0);
                URL.revokeObjectURL(url);

                // Create a download link
                const link = document.createElement('a');
                link.href = canvas.toDataURL('image/png');
                const now = new Date();
                const datetime = now.toISOString().replace(/T/, '_').replace(/\..+/, '');
                link.download = `barcode-${datetime}.png`;
                link.click();
            };

            img.src = url;
        });
    </script>


</body>

</html>
