<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $dataExitItem = DB::table('tbl_transaction')
            ->select(DB::raw('tbl_product.group,sum(quantity*stock_reduction)as barang_keluar'))
            ->join('tbl_cart', 'tbl_transaction.id_cart', '=', 'tbl_cart.id')
            ->join('tbl_product', 'tbl_cart.id_product', '=', 'tbl_product.id')
            ->groupBy('tbl_product.group')->get();
        
        return view('dashboard');
    }

    public function getProduct()
    {
        $dataProduct = DB::table('tbl_product')->get();
        $data['dataProduct'] = $dataProduct;
        return view('data-produk', $data);
    }

    public function getKaryawan()
    {
        $datKaryawan = DB::table('tbl_users')->get();
        $data['dataKaryawan'] = $datKaryawan;
        return view('data-karyawan', $data);
    }
}
