<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Barcode Scanner Home</title>
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
        .button:hover {
            background: #0056b3;
        }
        .icon {
            width: 50px;
            height: 50px;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>

<div class="container">
    <h1>Barcode Scanner App</h1>
    <a class="button" href="{{route('merchantScan',[Auth::user()->id])}}" >เพิ่มสินค้า</a>
    <a class="button" href="{{route('getProducts',[Auth::user()->id])}}" >แดชบอร์ด</a>
</div>

<script>
    function goTo(page) {
        window.location.href = page;
    }
</script>

</body>
</html>
