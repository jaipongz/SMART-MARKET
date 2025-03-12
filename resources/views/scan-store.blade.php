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
    <!-- FontAwesome CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    {{-- <link rel="stylesheet" href="style.css"> --}}
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
                    // $('#result').html('📦 บาร์โค้ด: ' + barcodeWithoutLastDigit);
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

                        // Add the profile image URL (Assuming response.profile_image contains the image URL)
                        $('#merchantProfileImg').attr('src', 'data:image/jpeg;base64,' + response
                            .profile_pic);

                        // Show the modal
                        $('#merchantModal').fadeIn();
                    },
                    error: function(error) {
                        console.error('เกิดข้อผิดพลาดในการดึงข้อมูลสินค้า:', error);
                        showErrorModal('เกิดข้อผิดพลาด ไม่สามารถดึงข้อมูลร้านค้าได้');
                    }
                });
            }

            function showErrorModal(message) {
                $('#errorModalMessage').text(message);
                $('#errorModal').fadeIn();
            }
        });

        function goBack() {
            window.location.href = '../index.html'; // หรือใช้ history.back(); ถ้าอยากให้ย้อนหน้าก่อนหน้า
        }


        function closeModal() {
            $('#merchantModal').fadeOut();
        }

        function closeErrorModal() {
            $('#errorModal').fadeOut();
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
            <div class="profile-img-container"
                style="display: flex; justify-content: center; align-items: center; margin-bottom: 20px;">
                <img id="merchantProfileImg" src="" alt="รูปโปรไฟล์ร้านค้า"
                    style="max-width: 120px; border-radius: 50%; border: 4px solid #fff; box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1); object-fit: cover;">
            </div>
            <h3 id="merchantName"
                style="font-size: 24px; font-weight: bold; color: #333; text-align: center; margin-bottom: 10px;">
                ชื่อร้านค้า</h3>
            <p id="merchantEmail" style="font-size: 16px; color: #666; text-align: center; margin-bottom: 5px;">อีเมล์:
                <span>email@example.com</span>
            </p>
            <p id="merchantCreatedAt" style="font-size: 16px; color: #666; text-align: center;">วันที่สร้าง:
                <span>2023-01-01</span>
            </p>

            <div class="modal-footer" style="text-align: center; margin-top: 20px;">
                <button onclick="buyNow()"
                    class="bg-blue-500 text-white py-2 px-6 rounded-lg hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    ซื้อเลย
                </button>
            </div>
        </div>
    </div>

    <div id="errorModal" class="modal">
        <div class="modal-content">
            <div class="modal-icon">
                <i class="fas fa-exclamation-circle"></i> <!-- ใช้ FontAwesome -->
            </div>
            <p id="errorModalMessage"></p>
            <button class="close-btn" onclick="closeErrorModal()">ปิด</button>
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
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        display: flex;
        justify-content: center;
        align-items: center;
        z-index: 9999;
        transition: opacity 0.3s ease-in-out;
    }

    .modal-content {
        background-color: #fff;
        padding: 30px;
        border-radius: 10px;
        width: 400px;
        max-width: 100%;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        position: relative;
        text-align: center;
    }

    .close-btn {
        position: absolute;
        top: 10px;
        right: 10px;
        font-size: 24px;
        font-weight: bold;
        color: #333;
        cursor: pointer;
        background: transparent;
        border: none;
    }

    .modal-footer button {
        font-size: 16px;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    .modal-footer button:hover {
        background-color: #3b82f6;
    }
</style>

</html>
