<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Products;
use App\Models\User;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;
class UserController extends Controller
{
    public function getProducts($merchantId, $start = null, $limit = null)
    {
        $start = $start ?? 0;
        $limit = $limit ?? 15;

        $products = DB::table('products')
            ->where('merchant_id', $merchantId)
            ->select('id', 'product_id', 'product_pic', 'product_name', 'amount', 'price', 'created_at')
            ->offset($start)
            ->limit($limit)
            ->get();
        // dd($products);
        $user = DB::table('users')
            ->where('id', $merchantId)
            ->select('id', 'name', 'email', 'profile_pic', 'created_at')
            ->first(); // Use first() since it should return one row
        return view('myproduct', ['products' => $products, 'merchant' => $user]);
    }
    public function merchantScan($merchantId)
    {
        return view('merchant.scaner', ['merchant' => $merchantId]);
    }
    public function welcome()
    {
        return view('dashboard');
    }
    public function verifyStore()
    {
        return view('scan-store');
    }
    public function scanOrder()
    {
        return view('merchant.scaner-detail');
    }
    public function storeProduct(Request $request)
    {
        // ตรวจสอบค่าที่ส่งมาจากฟอร์ม
        // $request->validate([
        //     'merchantId' => 'required|exists:users,id',
        //     'barcode' => 'required|string', // product_id ห้ามซ้ำ
        //     'name' => 'required|string',
        //     'price' => 'required|numeric|min:0',
        //     'stock' => 'required|integer|min:0',
        //     'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        // ]);

        // Debug เช็คค่าที่ส่งมาทั้งหมด
        // dd($request->all());
        if (!$request->has('barcode')) {
            // If barcode doesn't exist, generate a new 13-digit barcode
            $barcode = '855' . str_pad(rand(0, 9999999999), 10, '0', STR_PAD_LEFT); // Generates a 10-digit random number and prepends 855
        } else {
            // If barcode exists in the request, use the provided barcode
            $barcode = $request->barcode;
        }

        // dd($barcode);
        // แปลงรูปภาพเป็น Base64
        $imageBase64 = null;
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageBase64 = base64_encode(file_get_contents($image->path()));
        }

        // บันทึกสินค้า
        Products::create([
            'product_id' =>  $barcode, // ใช้ barcode เป็น product_id
            'merchant_id' => $request->merchantId,
            'product_name' => $request->name,
            'product_pic' => $imageBase64, // เก็บ Base64
            'amount' => $request->stock, // stock
            'price' => $request->price,
        ]);

        return redirect()->back()->with('success', 'เพิ่มสินค้าสำเร็จ!');
    }

    public function update(Request $request)
    {
        // dd($request);        // ค้นหาสินค้า
        $product = Products::where('id', $request->product_id)->firstOrFail();

        if ($request->hasFile('product_pic')) {
            $image = $request->file('product_pic');
            $product->product_pic = base64_encode(file_get_contents($image->path()));
        }

        $product->product_name = $request->product_name;
        $product->amount = $request->product_amount;
        $product->price = $request->product_price;
        $product->save();

        return redirect()->back()->with('success', 'อัปเดตสินค้าสำเร็จ!');
    }

    public function destroy($id)
    {
        $product = Products::find($id);

        if ($product) {
            $product->delete();
            return redirect()->back()->with('status', 'Product deleted successfully!');
        }

        return redirect()->back()->with('error', 'Product not found!');
    }

    public function getMerchantInfo(Request $request)
    {
        $merchantId = $request->input('merchantId');
        Log::info("Received merchantId: $merchantId");

        $merchant = DB::table('users')
            ->where('id', $merchantId)
            ->select('id', 'name', 'email', 'created_at', 'profile_pic')
            ->first();

        if (!$merchant) {
            Log::error("Merchant not found for ID: $merchantId");
            return response()->json(['error' => 'Merchant not found'], 404);
        }

        return response()->json($merchant);
    }

    public function updateProfilepic(Request $request)
    {
        Log::debug('Request received:', $request->all());

        $merchant = User::where('id', $request->merchant_id)->firstOrFail();
        Log::debug('Merchant found:', ['merchant_id' => $request->merchant_id, 'merchant' => $merchant]);

        if ($request->hasFile('profile_pic')) {
            $image = $request->file('profile_pic');

            if ($image->isValid()) {
                Log::debug('File uploaded:', ['file_name' => $image->getClientOriginalName(), 'file_size' => $image->getSize()]);

                $imageData = base64_encode(file_get_contents($image->getRealPath()));
                Log::debug('Image data encoded to base64:', ['image_data' => substr($imageData, 0, 50)]);  // แสดงแค่ส่วนแรกของ base64 เพื่อไม่ให้ยาวเกินไป

                $merchant->profile_pic = $imageData;
            } else {
                Log::debug('Uploaded file is invalid');
            }
        } else {
            Log::debug('No file uploaded');
        }

        $merchant->save();
        Log::debug('Profile picture saved for merchant:', ['merchant_id' => $merchant->id]);

        return response()->json([
            'message' => 'Profile picture updated successfully',
            'profile_pic' => $merchant->profile_pic,
        ]);
    }

    public function scanProduct(Request $request)
    {
        // รับข้อมูลจาก query parameters
        // dd($request);
        $merchantId = $request->query('id');
        $merchantName = $request->query('name');

        // ส่งข้อมูลไปยัง view
        return view('scan-product', compact('merchantName', 'merchantId'));
    }

    public function getProductDetails(Request $request)
    {
        $merchantId = $request->query('merchantId');
        $barcode = $request->query('barcode');

        // ดึงข้อมูลสินค้าจากฐานข้อมูลตาม merchantId และ barcode
        $product = Products::where('merchant_id', $merchantId)
            ->where('product_id', $barcode)
            ->first();

        if ($product) {
            // เช็คจำนวนสินค้า
            if ($product->amount <= 0) {
                return response()->json([
                    'error' => 'sold_out'
                ], 404);
            }

            return response()->json([
                'product' => [

                    'product_id' => $product->product_id,
                    'merchant_id' => $product->merchant_id,
                    'name' => $product->product_name,
                    'price' => $product->price,
                    'image' => $product->product_pic, // base64
                    'amount' => $product->amount
                ]
            ]);
        } else {
            return response()->json(['error' => 'not_found'], 404);
        }
    }

    public function createOrder(Request $request)
    {
        // Log::debug('📌 Request Received:', $request->all());

        try {
            $orderId = $request->input('orderId');
            $cartItems = $request->input('cart');

            // ตรวจสอบว่า cart มีข้อมูลหรือไม่
            if (!$cartItems || !is_array($cartItems) || count($cartItems) === 0) {
                Log::error('❌ Cart is empty or invalid', ['cart' => $cartItems]);
                return response()->json(['success' => false, 'message' => 'Cart is empty'], 400);
            }

            $merchantId = $cartItems[0]['merchant_id'] ?? null;

            // ตรวจสอบ merchant_id
            if (!$merchantId) {
                Log::error('❌ Missing merchant_id in cart', ['cart' => $cartItems]);
                return response()->json(['success' => false, 'message' => 'Merchant ID is missing'], 400);
            }

            // คำนวณราคารวม
            $totalPrice = collect($cartItems)->sum(function ($item) {
                return ($item['price'] ?? 0) * ($item['qty'] ?? 0);
            });

            Log::debug('🛒 Total Price Calculated:', ['total_price' => $totalPrice]);

            // บันทึก Order
            $order = Order::create([
                'order_id' => $orderId,
                'merchant_id' => $merchantId,
                'total_price' => $totalPrice,
                'order_status' => 'pending',
            ]);

            Log::info('✅ Order Created:', ['order_id' => $order->id]);

            // บันทึก Order Items
            foreach ($cartItems as $item) {
                if (!isset($item['produc_id'], $item['name'], $item['price'], $item['qty'])) {
                    Log::error('❌ Missing required product fields', ['item' => $item]);
                    continue;
                }

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item['produc_id'], // ✅ แก้ไขจาก produc_id -> product_id
                    'name' => $item['name'],
                    'price' => $item['price'],
                    'qty' => $item['qty'],
                    'subtotal' => $item['price'] * $item['qty'],
                ]);

                Log::info('🛍 Order Item Created:', ['product_id' => $item['produc_id']]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Order created successfully!',
                'order_id' => $orderId,
            ]);
        } catch (\Exception $e) {
            Log::error('🚨 Internal Server Error:', ['error' => $e->getMessage()]);
            return response()->json(['success' => false, 'message' => 'Internal Server Error'], 500);
        }
    }

    public function getOrdersByMerchantId($merchantId)
    {
        if (!$merchantId) {
            return response()->json([
                'success' => false,
                'message' => 'Merchant ID is required',
            ], 400);
        }
        $orders = Order::where('merchant_id', $merchantId)
            ->latest() // เรียงลำดับจากออร์เดอร์ล่าสุด
            ->limit(15) // จำกัดผลลัพธ์ 15 ออร์เดอร์
            ->get(); // ดึงข้อมูลทั้งหมด
        // dd($orders);
        $user = DB::table('users')
            ->where('id', $merchantId)
            ->select('id', 'name', 'email', 'profile_pic', 'created_at')
            ->first(); // Use first() since it should return one row
        return view('myOrdes', ['orders' => $orders, 'merchant' => $user]);
    }
    public function getOrderDetails($orderId)
    {
        // ค้นหาข้อมูลออร์เดอร์ที่มี order_id ตรงกัน
        $order = Order::where('order_id', $orderId)->first();

        if (!$order) {
            return response()->json([
                'success' => false,
                'message' => 'Order not found',
            ], 404);
        }

        // ดึงรายการสินค้าในออร์เดอร์นั้นๆ
        $orderItems = OrderItem::where('order_id', $order->id)->get(); // ใช้ Eloquent Relationship

        // รวมข้อมูลออร์เดอร์และรายการสินค้า
        return response()->json([
            'success' => true,
            'order' => $order,
            'items' => $orderItems,
        ]);
    }

    public function updateOrderStatus(Request $request)
    {
        // เริ่มต้นการบันทึก log
        Log::info("เริ่มการอัปเดตสถานะออร์เดอร์", ['order_id' => $request->order_id]);

        // ค้นหาออร์เดอร์ที่ต้องการ
        $order = Order::where('order_id', $request->order_id)->first();

        // หากไม่พบออร์เดอร์
        if (!$order) {
            Log::error("ไม่พบออร์เดอร์", ['order_id' => $request->order_id]);
            return response()->json([
                'success' => false,
                'message' => 'Order not found'
            ], 404);
        }

        // บันทึกการอัปเดตสถานะออร์เดอร์
        Log::info("อัปเดตสถานะออร์เดอร์", ['order_id' => $order->order_id, 'old_status' => $order->order_status, 'new_status' => $request->status]);

        // อัปเดตสถานะออร์เดอร์
        $order->order_status = $request->status;
        $order->save();

        $orderItems = OrderItem::where('order_id', $order->id)->get();
        foreach ($orderItems as $item) {
            Log::info("อัปเดตสต๊อกสินค้า", [
                'order_id' => $order->order_id,
                'product_id' => $item->product_id,
                'quantity_sold' => $item->qty
            ]);

            // หาสินค้าและหักสต๊อก
            $product = Products::where('product_id', $item->product_id)
                ->where('merchant_id', $order->merchant_id)->first();
            if ($product) {
                // หักสต๊อก
                $product->amount -= $item->qty;
                $product->save();

                // บันทึกการหักสต๊อก
                Log::info("หักสต๊อกสินค้า", [
                    'product_id' => $product->id,
                    'stock_after' => $product->amount
                ]);
            } else {
                Log::warning("ไม่พบสินค้าสำหรับหักสต๊อก", ['product_id' => $item->product_id]);
            }
        }

        // บันทึกการสำเร็จ
        Log::info("การอัปเดตคำสั่งซื้อสำเร็จ", ['order_id' => $order->order_id]);

        return response()->json([
            'success' => true,
            'message' => 'Order updated successfully'
        ]);
    }

    public function cancelOrder($id){
        $order = Order::where('order_id', $id)->first();
        $order->order_status = "canceled";
        $order->save();
        return redirect()->back()->with('status', 'Product deleted successfully!');
    }

}
