<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ตะกร้าสินค้า</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.1.2/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.0/dist/JsBarcode.all.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <style>
        .modal-icon i {
            font-size: 120px;
            color: rgb(255, 70, 70)
                /* ปรับขนาดได้ตามต้องการ */
        }

        .cart-item {
            display: flex;
            align-items: center;
            background-color: white;
            border-radius: 10px;
            padding: 10px;
            margin-bottom: 20px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .cart-item img {
            width: 100px;
            height: 100px;
            object-fit: cover;
            margin-right: 20px;
        }

        .cart-item h3 {
            font-size: 1.2rem;
            margin-bottom: 5px;
        }

        .cart-item p {
            margin: 5px 0;
        }

        .cart-item button {
            background-color: #ef4444;
            color: white;
            padding: 5px 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .cart-item button:hover {
            background-color: #dc2626;
        }

        .quantity-controls {
            display: flex;
            align-items: center;
            margin-left: 20px;
        }

        .quantity-controls input {
            width: 50px;
            text-align: center;
            margin: 0 10px;
        }

        .quantity-controls button {
            background-color: #4CAF50;
            color: white;
            padding: 5px 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .quantity-controls button:hover {
            background-color: #45a049;
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

        .close-btn {
            position: absolute;
            top: 10px;
            right: 15px;
            cursor: pointer;
            font-size: 18px;
            color: #333;
        }

        #Mybarcode {
            width: 100%;
            /* ทำให้บาร์โค้ดขยายตามขนาดของ container */
            /* height: 80px; */
            /* ปรับความสูงของบาร์โค้ด */
        }
    </style>
</head>

<body class="bg-gray-100">

    <div class="container mx-auto px-4 py-6">
        <div class="header bg-green-600 text-white ">
            <button class="back-btn" onclick="window.history.back()">⬅️</button>
            ตะกร้าสินค้า
        </div>

        <div id="cartItems" class="mt-6">
            <!-- รายการสินค้าจะถูกเพิ่มที่นี่ -->
            <p class="text-center text-gray-500">ไม่มีสินค้าภายในตะกร้า</p>
        </div>

        <div class="mt-6">
            <button onclick="confirmPurchase()"
                class="bg-green-600 text-white py-2 px-4 rounded hover:bg-green-700 w-full">
                ยืนยันการซื้อ
            </button>
        </div>
    </div>
    <div id="barcodeModal" style="display:none;" class="modal">
        <div class="modal-content">
            <h2>Barcode ของร้านค้าของคุณ</h2>
            <svg id="Mybarcode"></svg>
            <div class="mt-4">
                <button id="downloadBarcode"
                    class="bg-blue-600 text-white py-2 px-4 rounded hover:bg-blue-700">ดาวน์โหลด</button>
                <button id="closeBarModal" class="bg-gray-600 text-white py-2 px-4 rounded hover:bg-gray-700"
                    onclick="closeModal()">ปิด</button>
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
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const cart = JSON.parse(localStorage.getItem('cart')) || [];

            const cartItemsContainer = document.getElementById('cartItems');

            if (cart.length === 0) {
                cartItemsContainer.innerHTML = "<p class='text-center text-gray-500'>ไม่มีสินค้าภายในตะกร้า</p>";
            } else {
                let cartHTML = '';
                cart.forEach((product, index) => {
                    cartHTML += `
                <div class="cart-item">
                    <img src="data:image/jpeg;base64,${product.product_pic}" alt="${product.name}" />
                    <div>
                        <h3>${product.name}</h3>
                        <p>ราคา: ฿${product.price}</p>
                        <p>คงเหลือ: ${product.product_stock} ชิ้น</p>
                    </div>
                    <div class="quantity-controls">
                        <button onclick="changeQty(${index}, -1)" ${product.qty <= 1 ? 'disabled' : ''}>➖</button>
                        <input type="number" id="productQty${index}" value="${product.qty}" min="1" max="${product.product_stock}" onchange="updateQty(${index})">
                        <button onclick="changeQty(${index}, 1)" ${product.qty >= product.product_stock ? 'disabled' : ''}>➕</button>
                    </div>
                    <button onclick="removeFromCart(${index})">ลบ</button>
                </div>
            `;
                });
                cartItemsContainer.innerHTML = cartHTML;
            }
        });

        function showErrorModal(message) {
            $('#errorModalMessage').text(message);
            $('#errorModal').fadeIn();
        }

        function closeErrorModal() {
            $('#errorModal').fadeOut();
        }

        // ฟังก์ชันการเพิ่มหรือลดจำนวนสินค้า
        function changeQty(index, change) {
            let cart = JSON.parse(localStorage.getItem('cart')) || [];
            let product = cart[index];

            if (product.qty + change > 0 && product.qty + change <= product.product_stock) {
                product.qty += change;
            }

            localStorage.setItem('cart', JSON.stringify(cart));
            location.reload(); // รีเฟรชหน้าเพื่ออัปเดต
        }

        // ฟังก์ชันเพื่ออัปเดตจำนวนสินค้าจาก input
        function updateQty(index) {
            let cart = JSON.parse(localStorage.getItem('cart')) || [];
            let product = cart[index];
            const input = document.getElementById(`productQty${index}`);
            let newQty = parseInt(input.value);

            if (newQty < 1) {
                newQty = 1;
            } else if (newQty > product.product_stock) {
                newQty = product.product_stock;
            }

            product.qty = newQty;
            localStorage.setItem('cart', JSON.stringify(cart));
            location.reload(); // รีเฟรชหน้าเพื่ออัปเดต
        }

        // ฟังก์ชันลบสินค้าจากตะกร้า
        function removeFromCart(index) {
            let cart = JSON.parse(localStorage.getItem('cart')) || [];
            cart.splice(index, 1); // ลบสินค้าตาม index
            localStorage.setItem('cart', JSON.stringify(cart));
            location.reload(); // อัปเดตหน้าตะกร้า
        }

        // ฟังก์ชันยืนยันการซื้อ
        function confirmPurchase() {
            const cart = JSON.parse(localStorage.getItem('cart')) || [];

            if (cart.length === 0) {
                showErrorModal("ตะกร้าสินค้าว่าง");
                return;
            }

            const orderId = generateOrderId();
            console.log(orderId);
            generateBarcode(orderId);

            // Prepare the data to be sent to the storeOrder API
            const orderData = {
                orderId: orderId,
                cart: cart,
            };

            // Send the data to the storeOrder API
            fetch('/api/storeOrder', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        // 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify(orderData),
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Store the order ID in localStorage
                        localStorage.setItem('orderId', orderId);

                        // generateBarcode(orderId);

                        showModal();

                        localStorage.removeItem('cart');

                        // window.location.reload();
                    } else {
                        showErrorModal("เกิดข้อผิดพลาดในการยืนยันการซื้อ");
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showErrorModal("เกิดข้อผิดพลาดในการเชื่อมต่อ");
                });

        }

        function generateOrderId() {
            let orderId;
            do {
                orderId = Math.floor(Math.random() * 1000000000000).toString();
            } while (orderId.startsWith('0')); // ตรวจสอบว่าขึ้นต้นด้วย '0' หรือไม่


            const checkDigit = calculateEAN13CheckDigit(orderId);
            return orderId + checkDigit;
        }

        function generateBarcode(orderId) {
            console.log('Ongen --->', orderId);

            JsBarcode("#Mybarcode", orderId, {
                format: "EAN13", // Barcode format (you can choose different formats)
                lineColor: "#000000", // Barcode line color
                width: 4, // Width of each barcode line
                height: 100, // Height of the barcode
                displayValue: true, // Show the barcode number under the barcode
                fontSize: 18, // Font size of the displayed value
            });
        }

        function calculateEAN13CheckDigit(orderId) {
            let sum = 0;
            for (let i = 0; i < orderId.length; i++) {
                let num = parseInt(orderId[i]);
                sum += (i % 2 === 0) ? num : num * 3;
            }
            let checkDigit = (10 - (sum % 10)) % 10;
            return checkDigit.toString();
        }

        function showModal() {
            const modal = document.getElementById('barcodeModal');
            modal.style.display = 'flex';
        }

        // Function to close the barcode modal
        function closeModal() {
            const modal = document.getElementById('barcodeModal');
            modal.style.display = 'none';
        }

        document.getElementById('downloadBarcode').addEventListener('click', function() {
            const svg = document.getElementById('Mybarcode');

            // แปลง SVG เป็น Data URL (PNG)
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

                // สร้างลิงก์ดาวน์โหลด
                const link = document.createElement('a');
                link.href = canvas.toDataURL('image/png');
                const now = new Date();
                const datetime = now.toISOString().replace(/T/, '_').replace(/\..+/,
                    '');
                link.download = `barcode-${datetime}.png`;
                link.click();
            };

            img.src = url;
        });
    </script>

</body>

</html>
