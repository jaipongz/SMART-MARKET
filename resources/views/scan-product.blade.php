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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

	<script>
		var sound = new Audio("https://pirate-town.manga208.com/public/assets/js/barcode.wav");
		var isScanning = true;

		$(document).ready(function () {
			initBarcodeScanner();

			function initBarcodeScanner() {
				barcode.config.start = 0.1;
				barcode.config.end = 0.9;
				barcode.config.video = '#barcodevideo';
				barcode.config.canvas = '#barcodecanvas';
				barcode.config.canvasg = '#barcodecanvasg';

				barcode.setHandler(function (barcode) {
					if (!isScanning) return;

					$('#result').html('📦 บาร์โค้ด: ' + barcode);
					playSound();
					isScanning = false;
					showProductDetail(barcode);
					// เปิดการสแกนใหม่หลังจาก 1.5 วินาที
					setTimeout(function () {
						isScanning = true;
					}, 1500);
				});

				barcode.init();
			}

			function playSound() {
				sound.play().catch(function (e) {
					console.warn('Autoplay prevented:', e);
				});
			}
		});

		function goBack() {
			window.location.href = '../index.html';  // หรือใช้ history.back(); ถ้าอยากให้ย้อนหน้าก่อนหน้า
		}
		function showProductDetail(barcode) {
			// ตัวอย่างข้อมูลสินค้า (จริงๆควรดึงจากระบบหรือ API ตามบาร์โค้ดที่ได้มา)
			const mockProduct = {
				image: 'https://3auntiesthaimarket.com/cdn/shop/products/Oishi-500-ml-Original_530x@2x.png?v=1646084127',  // เอารูปจริงมาใส่ทีหลังได้
				name: 'สินค้า ' + barcode,
				price: 25
			};

			$('#productImage').attr('src', mockProduct.image);
			$('#productName').text(mockProduct.name);
			$('#productPrice').text(`ราคา: ฿${mockProduct.price.toFixed(2)}`);
			$('#productQty').val(1);  // เริ่มต้น 1 ชิ้น

			$('#productModal').fadeIn();
		}

		function closeModal() {
			$('#productModal').fadeOut();
		}

		function changeQty(amount) {
			let currentQty = parseInt($('#productQty').val()) || 1;
			currentQty += amount;
			if (currentQty < 1) currentQty = 1;
			$('#productQty').val(currentQty);
		}

		function addToCart() {
			const product = {
				name: $('#productName').text(),
				price: parseFloat($('#productPrice').text().replace('ราคา: ฿', '')),
				qty: parseInt($('#productQty').val())
			};

			console.log('📦 เพิ่มลงตะกร้า:', product);
			alert(`${product.name} จำนวน ${product.qty} ชิ้น ถูกเพิ่มลงตะกร้าแล้ว!`);

			closeModal();
		}
		
	</script>
</head>

<body>

	<div class="container">
		<div class="header bg-green-600">
			<button class="back-btn" onclick="goBack()">⬅️</button>
			ซื้อสินค้า
		</div>

		<h1 id="storeName">{{ $merchantName }}</h1>

		<div id="barcode">
			<video id="barcodevideo" autoplay playsinline></video>
			<div id="scan-line"></div>
		</div>

		<div id="result">
            <button id="generateBarcode"
                class="bg-green-600 text-white py-2 px-4 rounded hover:bg-green-700 ml-4">ตะกร้าสินค้า</button>
        </div>

		<!-- canvas ซ่อนประมวลผล -->
		<canvas id="barcodecanvas"></canvas>
		<canvas id="barcodecanvasg"></canvas>
	</div>
	<div id="productModal"  style="display:none;" class="modal">
		<div class="modal-content">
			<span class="close-btn" onclick="closeModal()">✖</span>
			<img id="productImage" src="" alt="Product Image">
			<h3 id="productName">ชื่อสินค้า</h3>
			<p id="productPrice">ราคา: ฿0.00</p>
			<div class="qty">
				<button onclick="changeQty(-1)">➖</button>
				<input type="number" id="productQty" value="1" min="1">
				<button onclick="changeQty(1)">➕</button>
			</div>
			<div class="modal-footer">
				<button onclick="addToCart()">🛒 เพิ่มลงตะกร้า</button>
			</div>
		</div>
	</div>
</body>
<style>
	.modal-icon i {
        font-size: 120px;
        color: rgb(255, 70, 70)
            /* ปรับขนาดได้ตามต้องการ */
    }

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
        color: #28a745;
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