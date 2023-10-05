<?php

namespace App\Http\Controllers;

use App\Models\ModelCart;
use App\Models\ModelTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{
    public function store(Request $request){
        $idUsers = $request->idUsers;
        $total = $request->total;

        $dataCart = DB::table('tbl_cart')->where('id_users','=',$idUsers)->where('is_order','=',0)->get();
        $dataTransaction = [];

        $idOrder = "ORD-".date('YmdHis');
        for($i = 0;$i<count($dataCart);$i++){
            $data = [
                "order_id"  => $idOrder,
                'total'     => $total,
                'date'      => date('Y-m-d'),
                'created_at'=> date('Y-m-d H:i:s'),
                'id_cart'   => $dataCart[$i]->id
            ];
            array_push($dataTransaction,$data);

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

    public function getIncome(){
        // $dataTransactionToday = DB::table('tbl_transaction')
        //                             ->select(DB::raw('sum(total) as total'))
        //                             ->groupBy('order_id')->first();
        $dataTransactionToday = DB::table('tbl_transaction')
                                    ->whereDate('created_at','=',Carbon::today())
                                    ->groupBy('order_id')
                                    ->get();
        $totalToday = 0;
        for($i = 0;$i < count($dataTransactionToday);$i++){
            $totalToday+=$dataTransactionToday[$i]->total;
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
        for($i = 0;$i < count($dataTransactionOfWeek);$i++){
            $totalWeek+=$dataTransactionOfWeek[$i]->total;
        }

        $dataTransactionOfMonth = DB::table('tbl_transaction')
                                    ->whereMonth('created_at',date('m'))
                                    ->groupBy('order_id')
                                    ->get();
        // dd($dataTransactionOfMonth);
        $totalMonth = 0;
        for($i = 0;$i < count($dataTransactionOfMonth);$i++){
            $totalMonth+=$dataTransactionOfMonth[$i]->total;
        }

        return response()->json([
            'success' => true,
            'dataTransactionToday' => $totalToday,
            'dataTransactionOfWeek' => $totalWeek,
            'dataTransactionOfMonth' => $totalMonth
        ]);
    }

    public function getExitItem(){
        $dataExitItem = DB::table('tbl_transaction')
                                    ->select(DB::raw('tbl_product.group,sum(quantity*stock_reduction)as barang_keluar'))
                                    ->join('tbl_cart','tbl_transaction.id_cart','=','tbl_cart.id')
                                    ->join('tbl_product','tbl_cart.id_product','=','tbl_product.id')
                                    ->groupBy('tbl_product.group')->get();
        
        return response()->json([
            'success' => true,
            'data'    => $dataExitItem
        ]);
    }
}
