<?php

namespace App\Http\Controllers;

use App\Models\ModelCart;
use App\Models\ModelTransaction;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{
    public function store(Request $request)
    {
        $idUsers = $request->idUsers;
        $total = $request->total;

        $dataCart = DB::table('tbl_cart')->where('id_users', '=', $idUsers)->where('is_order', '=', 0)->get();
        $dataTransaction = [];

        $idOrder = "ORD-" . date('YmdHis');
        for ($i = 0; $i < count($dataCart); $i++) {
            $data = [
                "order_id"  => $idOrder,
                'total'     => $total,
                'date'      => date('Y-m-d'),
                'created_at' => date('Y-m-d H:i:s'),
                'id_cart'   => $dataCart[$i]->id
            ];
            array_push($dataTransaction, $data);

            // update cart
            $dataCartUpdate = ModelCart::find($dataCart[$i]->id);
            $dataCartUpdate->is_order = 1;
            $dataCartUpdate->save();
        }
        DB::table('tbl_transaction')->insert($dataTransaction);

        return response()->json([
            'success' => true,
            'message' => "Transaksi berhasil dilakukan."
        ]);
    }

    public function getIncome(Request $request)
    {
        $firstdate = $request->date;
        $timestamp = strtotime($firstdate);
        $convertDate = date("Y-m-d", $timestamp);
        if ($firstdate == "") {
            if (date('H') >= '03') {
                $date = new DateTime(date('Y-m-d') . ' 10:00:00');
            } else {
                // Mendapatkan tanggal sekarang
                $tanggalSekarang = date("Y-m-d");

                // Mengurangkan 1 hari dari tanggal sekarang
                $tanggalKemarin = date("Y-m-d", strtotime("-1 day", strtotime($tanggalSekarang)));

                $date = new DateTime($tanggalKemarin . ' 10:00:00');
            }
        } else {
            $date = new DateTime($convertDate . ' 10:00:00');
        }

        // $date = new DateTime(date('Y-m-d') . ' 10:00:00');
        $dateNow = $date->format('Y-m-d H:i:s');
        $datePlus = $date->modify('+1 day');
        $afterPlus = $datePlus->format('Y-m-d 03:00:00');
        $dataTransactionToday = DB::table('tbl_transaction')
            // ->whereDate('created_at','=',Carbon::today())
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

        return response()->json([
            'success' => true,
            'dataTransactionToday' => $totalToday,
            'dataTransactionOfWeek' => $totalWeek,
            'dataTransactionOfMonth' => $totalMonth
        ]);
    }

    public function getExitItem(Request $request)
    {
        $firstdate = $request->date;
        $timestamp = strtotime($firstdate);
        $convertDate = date("Y-m-d", $timestamp);
        if ($firstdate == "") {
            if (date('H') >= '03') {
                $date = new DateTime(date('Y-m-d') . ' 10:00:00');
            } else {
                // Mendapatkan tanggal sekarang
                $tanggalSekarang = date("Y-m-d");

                // Mengurangkan 1 hari dari tanggal sekarang
                $tanggalKemarin = date("Y-m-d", strtotime("-1 day", strtotime($tanggalSekarang)));

                $date = new DateTime($tanggalKemarin . ' 10:00:00');
            }
        } else {
            $date = new DateTime($convertDate . ' 10:00:00');
        }


        $dateNow = $date->format('Y-m-d H:i:s');
        $datePlus = $date->modify('+1 day');
        $afterPlus = $datePlus->format('Y-m-d 03:00:00');


        $dataExitItem = DB::table('tbl_transaction')
            ->select(DB::raw('tbl_product.group,sum(quantity*stock_reduction)as barang_keluar'))
            ->join('tbl_cart', 'tbl_transaction.id_cart', '=', 'tbl_cart.id')
            ->join('tbl_product', 'tbl_cart.id_product', '=', 'tbl_product.id')
            // ->whereDate('tbl_transaction.created_at','=',Carbon::today())
            ->whereBetween('tbl_transaction.created_at', [
                $dateNow,
                $afterPlus
            ])
            ->orderBy('tbl_product.group')
            ->groupBy('tbl_product.group')->get();

        return response()->json([
            'success' => true,
            'data'    => $dataExitItem
        ]);
    }
}
