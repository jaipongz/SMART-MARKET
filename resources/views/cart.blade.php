<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ตะกร้าสินค้า</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.1.2/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.0/dist/JsBarcode.all.min.js"></script>

    <style>
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
            /* display: flex; */
            justify-content: center;
            align-items: center;
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
        }

        .close-btn {
            position: absolute;
            top: 10px;
            right: 15px;
            cursor: pointer;
            font-size: 20px;
        }

        .modal-content label {
            display: block;
            text-align: left;
            margin-top: 10px;
            font-weight: 600;
        }

        .modal-content input {
            width: calc(100% - 20px);
            padding: 10px;
            margin-top: 5px;
            border: 1px solid #ddd;
            border-radius: 5px;
            display: block;
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
    <div id="barcodeModal" class="modal">
        <div class="modal-content">
            <h2>Barcode ของร้านค้าของคุณ</h2>
            <svg id="Mybarcode"></svg>
            <div class="mt-4">
                <button id="closeBarModal"
                    class="bg-gray-600 text-white py-2 px-4 rounded hover:bg-gray-700">ปิด</button>
            </div>
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
                alert("ตะกร้าสินค้าว่าง");
                return;
            }

            const orderId = generateOrderId();

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
                    },
                    body: JSON.stringify(orderData),
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Store the order ID in localStorage
                        localStorage.setItem('orderId', orderId);

                        generateBarcode(orderId);

                        showModal();

                        localStorage.removeItem('cart');

                        alert("การซื้อเสร็จสิ้น!");
                        window.location.href = '/'; // Redirect to another page or reload the page
                    } else {
                        alert("เกิดข้อผิดพลาดในการยืนยันการซื้อ");
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert("เกิดข้อผิดพลาดในการเชื่อมต่อ");
                });

        }

        function generateOrderId() {
            return Math.floor(Math.random() * 1000000000000).toString().padStart(13, '0');
        }

        function generateBarcode(orderId) {
            JsBarcode("#Mybarcode", orderId, {
                format: "EAN13", // Barcode format (you can choose different formats)
                lineColor: "#000000", // Barcode line color
                width: 4, // Width of each barcode line
                height: 100, // Height of the barcode
                displayValue: true, // Show the barcode number under the barcode
                fontSize: 18, // Font size of the displayed value
            });
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
    </script>

</body>

</html>
