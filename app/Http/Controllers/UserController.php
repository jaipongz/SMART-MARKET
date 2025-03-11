<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Products;
class UserController extends Controller
{
    public function getProducts($merchantId, $start = null, $limit = null)
    {
        $start = $start ?? 0;
        $limit = $limit ?? 15;
        
        $products = DB::table('products')
        ->where('merchant_id', $merchantId)
        ->select('id','product_id','product_pic', 'product_name', 'amount', 'price', 'created_at')
        ->offset($start)
        ->limit($limit)
        ->get();
        // dd($products);
        $user = DB::table('users')
        ->where('id', $merchantId)
        ->select('id', 'name', 'email', 'created_at')
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
    public function storeProduct(Request $request)
    {
        // ตรวจสอบค่าที่ส่งมาจากฟอร์ม
        $request->validate([
            'merchantId' => 'required|exists:users,id',
            'barcode' => 'required|string', // product_id ห้ามซ้ำ
            'name' => 'required|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        
        // Debug เช็คค่าที่ส่งมาทั้งหมด
        // dd($request->all());
        
        // แปลงรูปภาพเป็น Base64
        $imageBase64 = null;
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageBase64 = base64_encode(file_get_contents($image->path()));
        }
        
        // บันทึกสินค้า
        Products::create([
            'product_id' => $request->barcode, // ใช้ barcode เป็น product_id
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
    
}
