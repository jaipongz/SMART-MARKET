<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\DB;
class UserController extends Controller
{
    public function getProducts($merchantId, $start = null, $limit = null)
    {
        $start = $start ?? 0;
        $limit = $limit ?? 15;

        $products = DB::table('products')
            ->where('merchant_id', $merchantId)
            ->select('product_id', 'product_name', 'amount', 'price', 'created_at')
            ->offset($start)
            ->limit($limit)
            ->get();
        // dd($products);
        $user = DB::table('users')
        ->where('id', $merchantId)
        ->select('id', 'name', 'email', 'created_at')
        ->first(); // Use first() since it should return one row
        return view('myproduct', ['products' => $products,'merchant'=>$user]);
    }
    public function merchantScan($merchantId)
    {
        return view('merchant.scaner', ['merchant'=>$merchantId]);
    }
    public function storeProduct(Request $request)
    {
        dd($request);
    }

}
