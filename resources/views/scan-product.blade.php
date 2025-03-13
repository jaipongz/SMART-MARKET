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
        let maxQty = 1;
        let merchantId;
        let productId;
        let producPic;
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
                    playSound();
                    isScanning = false;
                    showProductDetail(barcode);
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
            window.location.href = '../index.html'; // หรือใช้ history.back(); ถ้าอยากให้ย้อนหน้าก่อนหน้า
        }
        // function showProductDetail(barcode) {
        // 	// ตัวอย่างข้อมูลสินค้า (จริงๆควรดึงจากระบบหรือ API ตามบาร์โค้ดที่ได้มา)
        // 	const mockProduct = {
        // 		image: 'https://3auntiesthaimarket.com/cdn/shop/products/Oishi-500-ml-Original_530x@2x.png?v=1646084127',  // เอารูปจริงมาใส่ทีหลังได้
        // 		name: 'สินค้า ' + barcode,
        // 		price: 25
        // 	};

        // 	$('#productImage').attr('src', mockProduct.image);
        // 	$('#productName').text(mockProduct.name);
        // 	$('#productPrice').text(`ราคา: ฿${mockProduct.price.toFixed(2)}`);
        // 	$('#productQty').val(1);  // เริ่มต้น 1 ชิ้น

        // 	$('#productModal').fadeIn();
        // }

        function showProductDetail(barcode) {
            // ดึง merchantId จาก query parameter หรือจากตัวแปรที่เก็บไว้
            merchantId = new URLSearchParams(window.location.search).get('id'); // ถ้าค่ามาจาก URL
            if (!merchantId) {
                console.error('ไม่พบ merchantId');
                return;
            }

            // ส่ง request ไปยัง API เพื่อดึงข้อมูลสินค้า
            $.ajax({
                url: '/get-product-details', // API endpoint ที่คุณตั้งไว้
                method: 'GET',
                data: {
                    merchantId: merchantId,
                    barcode: barcode
                },
                success: function(response) {
                    console.log(response);
                    if (response.error) {
                        if (response.error === "sold_out") {
                            showErrorModal('สินค้าในคลังหมด');
                        } else if (response.error === "not_found") {
                            showErrorModal('ไม่พบสินค้า');
                        }
                        return;
                    }
                    const product = response.product; // สมมุติว่า API ส่งข้อมูลสินค้าในรูปแบบนี้
                    maxQty = product.amount;
                    productId = product.product_id;
                    producPic = product.image;
                    $('#productImage').attr('src', 'data:image/jpeg;base64,' + product.image);
                    $('#productName').text(product.name);
                    $('#productPrice').text(`ราคา: ฿${product.price}`);
                    $('#productQty').val(1); // ตั้งค่าเริ่มต้นเป็น 1 ชิ้น

                    // แสดง modal
                    $('#productModal').fadeIn();
                },
                error: function(error) {
                    console.error('REST Error:', error);
                    try {
                        const jsonError = JSON.parse(error.responseText);
                        console.log('Parsed JSON Error:', jsonError);

                        if (jsonError.error === "sold_out") {
                            showErrorModal('สินค้าในคลังหมด');
                        } else if (jsonError.error === "not_found") {
                            showErrorModal('ไม่พบสินค้า');
                        } else {
                            showErrorModal('เกิดข้อผิดพลาดในการดึงข้อมูลสินค้า');
                        }
                    } catch (e) {
                        console.log('JSON Parse Error:', e);
                        showErrorModal('เกิดข้อผิดพลาดในการดึงข้อมูลสินค้า');
                    }
                }
            });
        }

        function showErrorModal(message) {
            $('#errorModalMessage').text(message);
            $('#errorModal').fadeIn();
        }

        function closeErrorModal() {
            $('#errorModal').fadeOut();
        }

        function closeModal() {
            $('#productModal').fadeOut();
        }

        // function changeQty(amount) {
        //     let currentQty = parseInt($('#productQty').val()) || 1;
        //     currentQty += amount;
        //     if (currentQty < 1) currentQty = 1;
        //     $('#productQty').val(currentQty);
        // }

        function changeQty(amount) {
            let qtyInput = document.getElementById("productQty");
            let errorMsg = document.getElementById("errorMsg");

            let currentQty = parseInt(qtyInput.value);

            if (currentQty + amount > maxQty) {
                errorMsg.style.display = "block"; // แสดงข้อความสีแดง
                return;
            } else {
                errorMsg.style.display = "none"; // ซ่อนข้อความถ้าไม่มีปัญหา
            }

            // อัปเดตค่าจำนวน
            let newQty = currentQty + amount;
            if (newQty >= 1) {
                qtyInput.value = newQty;
            }
        }


        function addToCart() {
            const product = {
                product_pic:producPic,
                product_stock:maxQty,
                produc_id: productId,
                merchant_id: merchantId,
                name: $('#productName').text(),
                price: parseFloat($('#productPrice').text().replace('ราคา: ฿', '')),
                qty: parseInt($('#productQty').val())
            };

            console.log('📦 เพิ่มลงตะกร้า:', product);

            // เก็บข้อมูลลง localStorage (ใช้ JSON.stringify เพื่อแปลงเป็น String)
            let cart = JSON.parse(localStorage.getItem('cart')) || []; // ถ้ายังไม่มีตะกร้าให้เริ่มต้นเป็น array เปล่า
            cart.push(product); // เพิ่มสินค้าลงตะกร้า
            localStorage.setItem('cart', JSON.stringify(cart)); // เก็บข้อมูลลง localStorage

            alert(`${product.name} จำนวน ${product.qty} ชิ้น ถูกเพิ่มลงตะกร้าแล้ว!`);
            closeModal();
        }

        function goToCart() {
            window.location.href = '/cart'; // สมมุติว่า URL ของหน้าตะกร้าสินค้าเป็น "/cart"
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
            <button id="generateBarcode" class="bg-green-600 text-white py-2 px-4 rounded hover:bg-green-700 ml-4"
                onclick="goToCart()">ตะกร้าสินค้า</button>
        </div>

        <!-- canvas ซ่อนประมวลผล -->
        <canvas id="barcodecanvas"></canvas>
        <canvas id="barcodecanvasg"></canvas>
    </div>
    <div id="productModal" style="display:none;" class="modal">
        <div class="modal-content">
            <span class="close-btn" onclick="closeModal()">✖</span>
            <img id="productImage" src="" alt="Product Image">
            <h3 id="productName">ชื่อสินค้า</h3>
            <p id="productPrice">ราคา: ฿0.00</p>
            <div class="qty">
                <button onclick="changeQty(-1)">➖</button>
                <input type="number" id="productQty" value="1" min="1" readonly>
                <button onclick="changeQty(1)">➕</button>
            </div>
            <p id="errorMsg" style="color: red; display: none;">ถึงจำนวนสูงสุดในคลังแล้ว</p>
            <div class="modal-footer">
                <button onclick="addToCart()">🛒 เพิ่มลงตะกร้า</button>
            </div>
        </div>
    </div>

    <div id="errorModal" style="display:none;" class="modal">
        <div class="modal-content  mx-4">
            <span class="close-btn" onclick="closeErrorModal()">✖</span>
            <div class="modal-icon">
                <i class="fas fa-exclamation-circle" style=""></i> <!-- ใช้ FontAwesome -->
            </div>
            <p style="margin: 30px 0 20px" id="errorModalMessage">ไม่พบข้อมูลร้านค้า</p>
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
        height: auto;
        aspect-ratio: 1/1;
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
