<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Shop</title>
    <link rel="icon" type="image/x-icon" href="{{ secure_asset('public/assets/img/myshop.png') }}">
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
        }

        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background: linear-gradient(135deg, #f0f4f8, #d9e2ec);
            padding: 20px;
        }

        .container {
            background: #ffffff;
            padding: 30px;
            border-radius: 16px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.1);
            text-align: center;
            width: 100%;
            max-width: 400px;
        }

        h1 {
            font-size: 24px;
            color: #102a43;
            margin-bottom: 20px;
        }

        .button {
            display: block;
            width: 100%;
            padding: 15px;
            margin: 10px 0;
            background: #007bff;
            color: #fff;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            cursor: pointer;
            transition: background 0.3s;
        }
        .button-logout {
            display: block;
            width: 100%;
            padding: 15px;
            margin: 10px 0;
            background: #ff441f;
            color: #fff;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            cursor: pointer;
            transition: background 0.3s;
        }

        .button:hover {
            background: #0056b3;
        }
        .button-logout:hover {
            background: #b31e00;
        }

        .icon {
            width: 50px;
            height: 50px;
            margin-bottom: 10px;
        }

        a {
            text-decoration: none;
        }

        .logo-container {
            width: 120px;
            /* ขนาดของกรอบวงกลม */
            height: 120px;
            border-radius: 50%;
            overflow: hidden;
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 0 auto 20px auto;
            /* จัดกึ่งกลาง */
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .logo-container img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            /* ครอบรูปให้พอดีวงกลม */
        }
    </style>
</head>

<body>

    <div class="container">
        <h1>My Shop App</h1>
        <div class="logo-container">
            <img src="{{ secure_asset('public/assets/img/myshop.png') }}" alt="logo">
        </div>
        <a class="button" href="{{ route('merchantScan', [Auth::user()->id]) }}">เพิ่มสินค้า</a>
        <a class="button" href="{{ route('getProducts', [Auth::user()->id]) }}">แดชบอร์ด</a>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="button-logout">ออกจากระบบ</button>
        </form>
    </div>

    <script>
        function goTo(page) {
            window.location.href = page;
        }
    </script>

</body>

</html>
