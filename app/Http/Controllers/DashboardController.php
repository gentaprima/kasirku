<?php

namespace App\Http\Controllers;

use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $firstdate = $request->date;
        if ($firstdate == "") {
            $stringDate = date('Y-m-d');
            $date = new DateTime(date('Y-m-d') . ' 10:00:00');
        } else {
            $stringDate = $firstdate;
            $date = new DateTime($firstdate . ' 10:00:00');
        }
        //set date now + 1
        // $date = new DateTime(date('Y-m-d').' 10:00:00');
        $dateNow = $date->format('Y-m-d H:i:s');
        $datePlus = $date->modify('+1 day');
        $afterPlus = $datePlus->format('Y-m-d 03:00:00');
        // echo 'date before day adding: ' . $date->format('Y-m-d H:i:s');
        // echo 'date after adding 1 day: ' . $dateNow->format('Y-m-d H:i:s');

        $dataExitItem = DB::table('tbl_transaction')
            ->select(DB::raw('tbl_product.group,sum(quantity*stock_reduction)as barang_keluar'))
            ->join('tbl_cart', 'tbl_transaction.id_cart', '=', 'tbl_cart.id')
            ->join('tbl_product', 'tbl_cart.id_product', '=', 'tbl_product.id')
            // ->whereDate('tbl_transaction.created_at', '=', Carbon::today())
            ->whereBetween('tbl_transaction.created_at', [
                $dateNow,
                $afterPlus
            ])
            ->groupBy('tbl_product.group')->get();

        $dataTransactionToday = DB::table('tbl_transaction')
            // ->whereDate('created_at', '=', Carbon::today())
            ->whereBetween('tbl_transaction.created_at', [
                $dateNow,
                $afterPlus
            ])
            ->groupBy('order_id')
            ->get();
        $totalToday = 0;
        for ($i = 0; $i < count($dataTransactionToday); $i++) {
            $totalToday += $dataTransactionToday[$i]->total;
        }

        $now = Carbon::now();
        $dataTransactionOfWeek = DB::table('tbl_transaction')
            ->whereBetween("created_at", [
                $now->startOfWeek()->format('Y-m-d'), //This will return date in format like this: 2022-01-10
                $now->endOfWeek()->format('Y-m-d')
            ])
            ->groupBy('order_id')
            ->get();

        $totalWeek = 0;
        for ($i = 0; $i < count($dataTransactionOfWeek); $i++) {
            $totalWeek += $dataTransactionOfWeek[$i]->total;
        }

        $dataTransactionOfMonth = DB::table('tbl_transaction')
            ->whereMonth('created_at', date('m'))
            ->groupBy('order_id')
            ->get();
        // dd($dataTransactionOfMonth);
        $totalMonth = 0;
        for ($i = 0; $i < count($dataTransactionOfMonth); $i++) {
            $totalMonth += $dataTransactionOfMonth[$i]->total;
        }

        $data['dataExitItem'] = $dataExitItem;
        $data['dataTransactionToday'] = $totalToday;
        $data['dataTransactionMonth'] = $totalMonth;
        $data['dataTransactionWeek'] = $totalWeek;
        $data['date'] = $stringDate;
        return view('dashboard', $data);
    }

    public function getProduct()
    {
        $dataProduct = DB::table('tbl_product')->orderBy('product_name', 'asc')->get();
        $data['dataProduct'] = $dataProduct;
        return view('data-produk', $data);
    }

    public function getKaryawan()
    {
        $datKaryawan = DB::table('tbl_users')->get();
        $data['dataKaryawan'] = $datKaryawan;
        return view('data-karyawan', $data);
    }

    public function stock()
    {
        $dataStock = DB::table('tbl_product')
            ->groupBy('tbl_product.group')->get();

        $data['stock'] = $dataStock;
        return view('data-stock', $data);
    }

    public function transaction(Request $request)
    {
        $firstdate = $request->date;
        if ($firstdate == "") {
            $stringDate = date('Y-m-d');
            $date = new DateTime(date('Y-m-d') . ' 10:00:00');
        } else {
            $stringDate = $firstdate;
            $date = new DateTime($firstdate . ' 10:00:00');
        }
        //set date now + 1
        // $date = new DateTime(date('Y-m-d').' 10:00:00');
        $dateNow = $date->format('Y-m-d H:i:s');
        $datePlus = $date->modify('+1 day');
        $afterPlus = $datePlus->format('Y-m-d 03:00:00');

        $dataTransaction = DB::table('tbl_transaction')
            ->select('tbl_transaction.order_id', 'tbl_transaction.created_at', 'tbl_cart.quantity', 'tbl_cart.total', 'tbl_product.product_name')
            ->join('tbl_cart', 'tbl_transaction.id_cart', '=', 'tbl_cart.id')
            ->join('tbl_product', 'tbl_cart.id_product', '=', 'tbl_product.id')
            ->whereBetween('tbl_transaction.created_at', [
                $dateNow,
                $afterPlus
            ])->get();


        $dataTransactionToday = DB::table('tbl_transaction')
            // ->whereDate('created_at', '=', Carbon::today())
            ->whereBetween('tbl_transaction.created_at', [
                $dateNow,
                $afterPlus
            ])
            ->groupBy('order_id')
            ->get();
        $totalToday = 0;
        for ($i = 0; $i < count($dataTransactionToday); $i++) {
            $totalToday += $dataTransactionToday[$i]->total;
        }
        $data['transaction'] = $dataTransaction;
        $data['totalToday'] = $totalToday;
        $data['filter'] = $firstdate != null ? true : false;
        return view('data-transaction', $data);
    }

    public function input()
    {
        return view('input-barang');
    }

    public function getHistoryStock(Request $request)
    {
        $filter = $request->bulan;
        if ($filter == null) {
            $bulan = date('m');
            $tahun = date('Y');
        } else {
            $split = explode('-', $filter);
            $bulan = $split[1];
            $tahun = $split[0];
        }
        $$filter = $request->bulan;
        if ($filter == null) {
            $bulan = date('m');
            $tahun = date('Y');
        } else {
            $split = explode('-', $filter);
            $bulan = $split[1];
            $tahun = $split[0];
        }
        $formatBulan = DateTime::createFromFormat('!m', $bulan)->format('F') . ' ' . $tahun;

        $dataHistory = DB::table('tbl_history_stock')
            ->whereYear('date', $tahun)  // 'tanggal' adalah kolom dengan tipe data DATE
            ->whereMonth('date', $bulan)
            ->get();
        $data['history'] = $dataHistory;
        $data['bulan'] = $formatBulan;
        return view('data-history', $data);
    }

    public function getProductComponent()
    {
        $data['dataProduct'] = DB::table('product_components')->get();
        return view('data-product-component', $data);
    }

    public function showProductComponent() {}

    public function clearCache()
    {
        Artisan::call('cache:clear');
        Artisan::call('config:clear');
        Artisan::call('view:clear');
        Artisan::call('route:clear');
        echo "Cache cleared!";
    }
}
