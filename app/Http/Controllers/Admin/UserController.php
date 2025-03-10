<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
class UserController extends Controller
{
    public function getUsers($start = null, $limit = null)
    {
        $start = $start ?? 0;
        $limit = $limit ?? 15;

        $user = DB::table('users')
            ->select('name', 'email', 'created_at')
            ->offset($start)
            ->limit($limit)
            ->get();

        return view('admin.users', ['user' => $user]);
    }
    public function getAdmins($start = null, $limit = null)
    {
        $start = $start ?? 0;
        $limit = $limit ?? 15;

        $user = DB::table('admins')
            ->select('name', 'email', 'created_at')
            ->offset($start)
            ->limit($limit)
            ->get();

        return view('admin.admin', ['user' => $user]);
    }
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

        return view('myproduct', ['products' => $products]);
    }

}
