<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(){
        return view('dashboard');
    }

    public function getProduct(){
        $dataProduct = DB::table('tbl_product')->get();
        $data['dataProduct'] = $dataProduct;
        return view('data-produk',$data);
    }

    public function getKaryawan(){
        $datKaryawan = DB::table('tbl_users')->get();
        $data['dataKaryawan'] = $datKaryawan;
        return view('data-karyawan',$data);
    }
}
