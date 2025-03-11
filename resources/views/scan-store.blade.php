<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Barcode Scanner</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Kanit:wght@300;400;600&display=swap">
    <script src="https://pirate-town.manga208.com/public/assets/js/jquery.js"></script>
    <script src="https://pirate-town.manga208.com/public/assets/js/barcode.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.1.2/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
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

                    var barcodeWithoutLastDigit = barcode.slice(0, -1);
                    $('#result').html('📦 บาร์โค้ด: ' + barcodeWithoutLastDigit);
                    playSound();
                    isScanning = false;
                    showMerchantDetail(barcodeWithoutLastDigit);
                    // เปิดการสแกนใหม่หลังจาก 1.5 วินาที
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

            function showMerchantDetail(barcode) {
                $.ajax({
                    url: '/get-merchant-info',
                    method: 'GET',
                    data: {
                        merchantId: barcode
                    },
                    success: function(response) {
                        console.log(response);

                        // Populate the modal with merchant data
                        $('#merchantName').text(`ร้าน ${response.name}`);
                        $('#merchantEmail').find('span').text(response.email);
                        $('#merchantCreatedAt').find('span').text(response.created_at);

                        // Show the modal
                        $('#merchantModal').fadeIn();
                    },
                    error: function(error) {
                        console.error('เกิดข้อผิดพลาดในการดึงข้อมูลสินค้า:', error);
                    }
                });
            }
        });

        function goBack() {
            window.location.href = '../index.html'; // หรือใช้ history.back(); ถ้าอยากให้ย้อนหน้าก่อนหน้า
        }


        function closeModal() {
            $('#merchantModal').fadeOut();
        }

    </script>

</head>

<body>

    <div class="container">
        <div class="header">
            <button class="back-btn" onclick="goBack()">⬅️</button>
            เลือกร้านค้า
        </div>

        <h1>กรุณาอยู่ในที่ที่มีแสงพอดี</h1>

        <div id="barcode">
            <video id="barcodevideo" autoplay playsinline></video>
            <div id="scan-line"></div>
        </div>


        <!-- canvas ซ่อนประมวลผล -->
        <canvas id="barcodecanvas"></canvas>
        <canvas id="barcodecanvasg"></canvas>
    </div>
    <div id="merchantModal" style="display:none;" class="modal">
        <div class="modal-content">
            <span class="close-btn" onclick="closeModal()">✖</span>
            <h3 id="merchantName">ชื่อร้านค้า</h3>
            <p id="merchantEmail">อีเมล์: <span>email@example.com</span></p>
            <p id="merchantCreatedAt">วันที่สร้าง: <span>2023-01-01</span></p>

            <!-- Optional: You can add more details about the merchant if needed -->
            <div class="modal-footer">
                <button onclick="buyNow()"
                    class="bg-blue-500 text-white py-2 px-4 rounded hover:bg-blue-600">ซื้อเลย</button>
            </div>
        </div>
    </div>

</body>
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
        background: #ffc400;
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
        color: #fbff00;
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
        justify-content: center;
        align-items: center;
        font-family: 'Kanit', sans-serif;
        overflow: hidden;
    }

    .modal-content {
        background-color: #fff;
        padding: 20px;
        border-radius: 12px;
        width: 90%;
        max-width: 400px;
        text-align: center;
        position: relative;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
    }

    .modal img {
        width: 100%;
        max-height: 200px;
        object-fit: cover;
        margin-bottom: 15px;
        border-radius: 8px;
    }

    .modal h3 {
        margin: 0;
        font-size: 20px;
        color: #333;
    }

    .modal p {
        font-size: 16px;
        color: #555;
        margin: 10px 0;
    }

    .qty {
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 15px;
        margin: 15px 0;
    }

    .qty button {
        padding: 5px 15px;
        font-size: 18px;
        cursor: pointer;
        border: none;
        background: #007bff;
        color: white;
        border-radius: 6px;
    }

    .qty button:hover {
        background: #0056b3;
    }

    .qty input {
        width: 50px;
        text-align: center;
        font-size: 18px;
    }

    .modal-footer button {
        background: #28a745;
        color: white;
        border: none;
        padding: 10px 15px;
        border-radius: 8px;
        cursor: pointer;
        font-size: 16px;
    }

    .modal-footer button:hover {
        background: #218838;
    }

    .close-btn {
        position: absolute;
        top: 10px;
        right: 15px;
        cursor: pointer;
        font-size: 18px;
        color: #333;
    }
</style>

</html>
