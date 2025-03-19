<!-- resources/views/barcodeScanner.blade.php -->
<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Shop</title>
    <link rel="icon" type="image/x-icon" href="{{ secure_asset('public/assets/img/myshop.png')}}">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Kanit:wght@300;400;600&display=swap">
    <script src="https://pirate-town.manga208.com/public/assets/js/jquery.js"></script>
    <script src="https://pirate-town.manga208.com/public/assets/js/barcode.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.1.2/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.0/dist/JsBarcode.all.min.js"></script>
    {{-- <link rel="stylesheet" href="https://pirate-town.manga208.com/public/assets/css/scaner.css'"> --}}

    <style>
        body {
            font-family: 'Kanit', sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .container {
            background: #fff;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            padding: 20px;
            text-align: center;
            width: 90%;
            max-width: 500px;
            position: relative;
        }

        .header {
            background: #ff447c;
            color: #fff;
            font-size: 18px;
            padding: 15px;
            text-align: center;
            border-radius: 12px 12px 0 0;
            position: relative;
        }

        .back-btn {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            background: #fff;
            color: #007bff;
            border: none;
            padding: 5px 10px;
            font-size: 14px;
            border-radius: 8px;
            cursor: pointer;
        }

        .back-btn:hover {
            background: #f0f0f0;
        }

        h1 {
            margin: 10px 0;
            font-size: 22px;
            color: #333;
        }

        #barcode {
            position: relative;
            background: #000;
            border-radius: 10px;
            overflow: hidden;
        }

        video {
            width: 100%;
            display: block;
        }

        #scan-line {
            position: absolute;
            top: 50%;
            left: 15px;
            right: 15px;
            height: 2px;
            background: red;
            animation: scan 2s infinite linear;
        }

        /* @keyframes scan {
 0% { top: 15%; }
 50% { top: 85%; }
 100% { top: 15%; }
} */

        #result {
            margin-top: 15px;
            font-size: 20px;
            font-weight: bold;
            color: #28a745;
            min-height: 30px;
        }

        /* ซ่อน canvas ประมวลผล */
        #barcodecanvas,
        #barcodecanvasg {
            display: none;
        }

        .modal {
            display: none;
            /* เริ่มซ่อน */
            position: fixed;
            z-index: 1000;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            display: flex;
            /* ใช้ flex เพื่อให้จัดกลาง */
            justify-content: center;
            /* จัดกึ่งกลางแนวนอน */
            align-items: center;
            /* จัดกึ่งกลางแนวตั้ง */
            font-family: 'Kanit', sans-serif;
            overflow: hidden;
        }

        .modal-content {
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            width: 90%;
            max-width: 400px;
            text-align: center;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
            position: relative;
            animation: fadeIn 0.3s ease-in-out;
            /* ใส่อนิเมชันให้สวยขึ้น */
        }

        .close-btn {
            position: absolute;
            top: 10px;
            right: 15px;
            cursor: pointer;
            font-size: 20px;
        }

        /* อนิเมชัน fade in */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: scale(0.9);
            }

            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        .modal-content label {
            display: block;
            text-align: left;
            margin-top: 10px;
            font-weight: 600;
        }



        .modal-content button {
            background: #28a745;
            color: white;
            padding: 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 10px;
            width: 100%;
        }

        .modal-content button:hover {
            background: #218838;
        }

        #productImagePreview {
            width: 100px;
            height: 100px;
            margin-top: 10px;
            object-fit: cover;
            border: 1px solid #ccc;
            display: block;
        }

        a {
            text-decoration: none;
        }

        #Mybarcode {
            width: 100%;
            /* ทำให้บาร์โค้ดขยายตามขนาดของ container */
            /* height: 80px; */
            /* ปรับความสูงของบาร์โค้ด */
        }
    </style>
</head>

<body>

    <div class="container">
        <div class="header">
            <a class="back-btn" href="{{ route('merchant.welcome') }}">⬅️</a>
            สแกนออเดอร์
        </div>

        <h1>สแกนคำสั่งซื้อของลูกค้า</h1>

        <div id="barcode">
            <video id="barcodevideo" autoplay playsinline></video>
            <div id="scan-line"></div>
        </div>

        <div id="result">
            <button id="generateBarcode"
                class="bg-blue-600 text-white py-2 px-4 rounded hover:bg-blue-700 ml-4">โค้ดร้านค้าของคุณ</button>
        </div>
        {{-- {{Auth::user()->id}} --}}
        <canvas id="barcodecanvas"></canvas>
        <canvas id="barcodecanvasg"></canvas>
    </div>

    <!-- Modal สำหรับเพิ่มสินค้า -->
    <div id="orderModal" style="display: none" class="modal">
        <div class="modal-content">
            <span class="close-btn" onclick="closeModal()">✖</span>
            <h3>รายการคำสั่งซื้อ</h3>

            <form method="POST" action="">
                <button type="submit">💾 บันทึกสินค้า</button>
            </form>
        </div>
    </div>

    <div id="barcodeModal" style="display: none" class="modal">
        <div class="modal-content">
            <h2>Barcode ของร้านค้าของคุณ</h2>
            <svg id="Mybarcode"></svg>
            <div class="mt-4">
                <button id="closeBarModal"
                    class="bg-gray-600 text-white py-2 px-4 rounded hover:bg-gray-700">ปิด</button>
            </div>
        </div>
    </div>

</body>

<script>
    var sound = new Audio("https://pirate-town.manga208.com/public/assets/js/barcode.wav");
    var isScanning = true;

    $(document).ready(function() {
        initBarcodeScanner();

        function initBarcodeScanner() {
            barcode.config.start = 0.1;
            barcode.config.end = 0.9;
            barcode.config.video = '#barcodevideo';
            barcode.config.canvas = '#barcodecanvas';
            barcode.config.canvasg = '#barcodecanvasg';

            barcode.setHandler(function(barcode) {
                if (!isScanning) return;

                // $('#result').html('📦 บาร์โค้ด: ' + barcode);
                playSound();
                isScanning = false;
                showOrderDetail(barcode);

                setTimeout(function() {
                    isScanning = true;
                }, 1500);
            });

            barcode.init();
        }

        function playSound() {
            sound.play().catch(function(e) {
                console.warn('Autoplay prevented:', e);
            });
        }
    });

    function goBack() {
        window.location.href = "{{ url()->previous() }}";
    }

    function showOrderDetail(orderId) {
        $.ajax({
            url: "/get-order-detail/" + orderId, // ส่ง orderId ไปที่ API
            type: "GET",
            success: function(response) {
                if (response.success) {
                    // เรียกฟังก์ชันแสดงผล Modal พร้อมข้อมูลออร์เดอร์
                    updateOrderModal(response.order, response.items);
                } else {
                    alert("ไม่พบคำสั่งซื้อ");
                }
            },
            error: function() {
                alert("เกิดข้อผิดพลาดในการดึงข้อมูลออร์เดอร์");
            }
        });
    }

    function updateOrderModal(order, items) {
        let totalAmount = 0;

        let modalContent = `
        <div class="relative">
            <!-- ปุ่มปิด Modal -->
             <span class="absolute top-2 right-2 bg-gray-200 text-gray-600 hover:bg-gray-300 hover:text-gray-800 rounded-full w-8 h-8 flex items-center justify-center cursor-pointer" onclick="closeModal()">✖</span>
            <p class="text-gray-700"><strong>รหัสออร์เดอร์:</strong> ${order.order_id}</p>
            <p class="text-gray-700"><strong>สถานะ:</strong> ${getOrderStatusText(order.order_status)}</p>
            <hr class="my-3">
            <h4 class="text-lg font-semibold mb-2">สินค้าในออร์เดอร์</h4>
            <div class="space-y-3">
    `;

        items.forEach(item => {
            totalAmount += item.qty * item.price; // คำนวณยอดรวม

            modalContent += `
            <div class="bg-white p-4 shadow-md rounded-lg border border-gray-200 flex items-center space-x-4">
                <div class="w-16 h-16 bg-gray-200 rounded-lg flex items-center justify-center">
                    <span class="text-gray-500">📦</span>
                </div>
                <div class="flex-1">
                    <h5 class="font-medium text-gray-800">${item.name}</h5>
                    <p class="text-gray-600 text-sm">จำนวน: <span class="font-semibold">${item.qty}</span> ชิ้น ราคา: <span class="font-semibold">${totalAmount.toFixed(2)}</span> บาท</p>
                </div>
            </div>
        `;
        });

        modalContent += `
            </div>
            <hr class="my-3">
            <!-- แสดงยอดรวมของบิล -->
            <p class="text-lg font-semibold text-gray-800">💰 ยอดรวม: ${totalAmount.toFixed(2)} บาท</p>

            <!-- ปุ่มกดสำเร็จ -->
            <button class="mt-4 bg-green-600 text-white py-2 px-4 rounded hover:bg-green-700 w-full" onclick="confirmOrder('${order.order_id}')">✅ ยืนยันคำสั่งซื้อ</button>
        </div>
    `;

        $("#orderModal .modal-content").html(modalContent);
        $("#orderModal").show(); // แสดง Modal
    }

    // ฟังก์ชันปิด Modal
    function closeModal() {
        $("#orderModal").hide();
    }

    // ฟังก์ชันเมื่อกดปุ่มสำเร็จ
    function confirmOrder(orderId) {
        $.ajax({
            url: '/api/update-order-status', // เปลี่ยนเป็น URL API ของคุณ
            type: 'POST',
            data: {
                order_id: orderId,
                status: 'completed' // เปลี่ยนสถานะเป็น "เสร็จสิ้น"
            },
            success: function(response) {
                alert("✅ คำสั่งซื้ออัปเดตเรียบร้อย!");
                closeModal();
            },
            error: function(error) {
                alert("❌ มีข้อผิดพลาดในการอัปเดตออร์เดอร์");
                console.error(error);
            }
        });
    }



    function getOrderStatusText(status) {
        switch (status) {
            case 'pending':
                return '⏳ กำลังดำเนินการ';
            case 'success':
                return '✅ เสร็จสิ้น';
            case 'cancel':
                return '❌ ยกเลิก';
            default:
                return '❔ ไม่ทราบสถานะ';
        }
    }

    function closeModal() {
        $("#orderModal").hide(); // ปิด Modal
    }

    function previewImage(event) {
        const input = event.target;
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('productImagePreview').src = e.target.result;
                document.getElementById('productImagePreview').style.display = 'block';
            };
            reader.readAsDataURL(input.files[0]);
        }
    }

    const merchantId = document.getElementById('merchantId').value;

    // Open modal and generate barcode when the button is clicked
    document.getElementById('generateBarcode').addEventListener('click', function() {
        // Show the modal
        document.getElementById('barcodeModal').style.display = 'flex';

        console.log("Merchant ID: ", merchantId); // Make sure the merchantId is correct

        // Generate the barcode using JsBarcode
        JsBarcode("#Mybarcode", merchantId, {
            // format: "CODE128", // Barcode format (you can choose different formats)
            format: "EAN13", // Barcode format (you can choose different formats)
            lineColor: "#000000", // Barcode line color
            width: 4, // Width of each barcode line
            height: 100, // Height of the barcode
            displayValue: true, // Show the barcode number under the barcode
            fontSize: 18 // Font size of the displayed value
        });
    });

    // Close the modal when clicking the close button
    document.getElementById('closeBarModal').addEventListener('click', function() {
        document.getElementById('barcodeModal').style.display = 'none';
    });
</script>

</html>
