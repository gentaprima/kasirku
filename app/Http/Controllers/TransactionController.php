<?php

namespace App\Http\Controllers;

use App\Models\ModelCart;
use App\Models\ModelProduct;
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

        $dataCart = DB::table('tbl_cart')
            ->select('tbl_cart.*', 'tbl_product.id as id_product', 'tbl_product.product_name', 'tbl_product.product_category', 'tbl_product.group', 'tbl_product.stock_reduction', 'tbl_product.stock', 'tbl_product.remaining_stock')
            ->where('id_users', '=', $idUsers)->where('is_order', '=', 0)
            ->join('tbl_product', 'tbl_cart.id_product', '=', 'tbl_product.id')->get();
        $dataTransaction = [];
        for ($i = 0; $i < count($dataCart); $i++) {
            $product = DB::table('tbl_product')->where('id', '=', $dataCart[$i]->id_product)->first();
            $reductStock = $product->stock_reduction * $dataCart[$i]->quantity;
            $remainStock = $product->stock - $reductStock;
            $productUpdate = ModelProduct::where('group', $dataCart[$i]->group);
            $productUpdate->update(['stock' => $remainStock]);
        }


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

    public function storeNew(Request $request)
    {
        $idUsers = $request->idUsers;
        $total = $request->total;

        $dataCart = DB::table('tbl_cart')
            ->select('tbl_cart.*', 'tbl_product.id as id_product', 'tbl_product.product_name', 'tbl_product.product_category', 'tbl_product.group', 'tbl_product.stock_reduction', 'tbl_product.stock', 'tbl_product.remaining_stock')
            ->where('id_users', '=', $idUsers)->where('is_order', '=', 0)
            ->join('tbl_product', 'tbl_cart.id_product', '=', 'tbl_product.id')->get();
        $dataTransaction = [];
        for ($i = 0; $i < count($dataCart); $i++) {
            //kurangi stock main product
            $product = DB::table('tbl_product')->where('id', '=', $dataCart[$i]->id_product)->first();
            $reductStock = $product->stock_reduction * $dataCart[$i]->quantity;
            $remainStock = $product->stock - $reductStock;
            $productUpdate = ModelProduct::where('group', $dataCart[$i]->group);
            $productUpdate->update(['stock' => $remainStock]);

            //kurangi stock component product
            $componentProduct = DB::table('product_components')->where('product_id', '=', $dataCart[$i]->id_product)->get();
            if (count($componentProduct) > 0) {
                for ($x = 0; $x < count($componentProduct); $x++) {
                    $productComponentId = $componentProduct[$x]->component_id;
                    $product = DB::table('tbl_product')->where('id', '=', $productComponentId)->first();
                    $reductStock =  $componentProduct[$x]->quantity * $dataCart[$i]->quantity;
                    $remainStock = $product->stock - $reductStock;
                    $productUpdate = ModelProduct::where('group', $product->group);
                    $productUpdate->update(['stock' => $remainStock]);
                }
            }
        }


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

    function sendWhatsAppMessage()
    {
        // $dataProduct = DB::table('tbl_product')->groupBy('tbl_product.group')->get();
        $dataProduct = DB::table('tbl_product')
            ->where('stock', '>', 0)
            ->groupBy('tbl_product.group')
            ->get();
        dd($dataProduct);
        $textMessage = "📢 *STOK BARANG " . date("d/m/Y") . "* \n\n";

        // Loop untuk menambahkan produk
        foreach ($dataProduct as $product) {
            $textMessage .= "🔹 " . $product->product_name . ": *" . $product->stock . " pcs*\n";
        }

        $apiUrl = "https://messages-sandbox.nexmo.com/v1/messages";
        $apiKey = "8bbdaf30";
        $apiSecret = "A6Fy1lM78uDE4ISl";

        $recipient = ['6289669615426'];

        foreach ($recipient as $recipient) {
            $data = [
                "from" => "14157386102",
                "to" => $recipient,
                "message_type" => "text",
                "text" => $textMessage,
                "channel" => "whatsapp"
            ];

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $apiUrl);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                "Content-Type: application/json",
                "Accept: application/json"
            ]);
            curl_setopt($ch, CURLOPT_USERPWD, "$apiKey:$apiSecret");
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

            $response = curl_exec($ch);
            curl_close($ch);

            // Log atau debugging (opsional)
            // echo "Response for $recipient: " . json_encode($response) . "\n";
        }

        return response()->json(["status" => "Messages sent successfully"]);
    }
    function sendWhatsAppMessageStockLitle()
    {
        // $dataProduct = DB::table('tbl_product')->groupBy('tbl_product.group')->get();
        $dataProduct = DB::table('tbl_product')
            ->where('stock', '>', 0)
            ->where('stock', '<=', 5)
            ->groupBy('tbl_product.group')
            ->get();
        $textMessage = "📢 *STOCK TINGGAL DIKIT NICHHHH* \n\n";

        // Loop untuk menambahkan produk
        foreach ($dataProduct as $product) {
            $textMessage .= "🔹 " . $product->group . ": *" . $product->stock . " pcs*\n";
        }

        $apiUrl = "https://messages-sandbox.nexmo.com/v1/messages";
        $apiKey = "8bbdaf30";
        $apiSecret = "A6Fy1lM78uDE4ISl";

        $recipient = ['6289669615426', '6289688681973'];

        foreach ($recipient as $recipient) {
            $data = [
                "from" => "14157386102",
                "to" => $recipient,
                "message_type" => "text",
                "text" => $textMessage,
                "channel" => "whatsapp"
            ];

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $apiUrl);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                "Content-Type: application/json",
                "Accept: application/json"
            ]);
            curl_setopt($ch, CURLOPT_USERPWD, "$apiKey:$apiSecret");
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

            $response = curl_exec($ch);
            curl_close($ch);

            // Log atau debugging (opsional)
            // echo "Response for $recipient: " . json_encode($response) . "\n";
        }

        return response()->json(["status" => "Messages sent successfully"]);
    }
    public function sendGroup()
    {
        // $dataProduct = DB::table('tbl_product')
        // ->where('stock','>',0)
        // ->groupBy('tbl_product.group')
        // ->get();
        // $textMessage = "📢 *STOK BARANG " . date("d/m/Y") . "* \n\n";

        // // Loop untuk menambahkan produk
        // foreach ($dataProduct as $product) {
        //     $textMessage .= "🔹 " . $product->group . ": *" . $product->stock . " pcs*\n";
        // }

        // $apiUrl = "https://messages-sandbox.nexmo.com/v1/messages";
        // $apiKey = "8bbdaf30";
        // $apiSecret = "A6Fy1lM78uDE4ISl";

        // $recipient = ['6289669615426'];

        // $token = "Y2gHrxWHxAZm6KdaLK21  ";
        // $curl = curl_init();

        // curl_setopt_array($curl, array(
        //     CURLOPT_URL => 'https://api.fonnte.com/send',
        //     CURLOPT_RETURNTRANSFER => true,
        //     CURLOPT_ENCODING => '',
        //     CURLOPT_MAXREDIRS => 10,
        //     CURLOPT_TIMEOUT => 0,
        //     CURLOPT_FOLLOWLOCATION => true,
        //     CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        //     CURLOPT_CUSTOMREQUEST => 'POST',
        //     CURLOPT_POSTFIELDS => array(
        //         'target' => '120363166640669368@g.us',
        //         'message' => $textMessage,
        //     ),
        //     CURLOPT_HTTPHEADER => array(
        //         "Authorization: $token"
        //     ),
        // ));

        // $response = curl_exec($curl);
        // if (curl_errno($curl)) {
        //     $error_msg = curl_error($curl);
        // }
        // curl_close($curl);

        // if (isset($error_msg)) {
        //     echo $error_msg;
        // }
        // echo $response;
        $dataProduct = DB::table('tbl_product')
            ->where('stock', '>', 0)
            ->groupBy('tbl_product.group')
            ->get();
        $textMessage = "📢 *STOK BARANG " . date("d/m/Y") . "* \n\n";

        // Loop untuk menambahkan produk
        foreach ($dataProduct as $product) {
            $textMessage .= "🔹 " . $product->group . ": *" . $product->stock . " pcs*\n";
        }

        $token = "Y2gHrxWHxAZm6KdaLK21  ";
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.fonnte.com/send',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => array(
                'target' => '120363166640669368@g.us',
                'message' => $textMessage,
            ),
            CURLOPT_HTTPHEADER => array(
                "Authorization: $token"
            ),
        ));

        $response = curl_exec($curl);
        if (curl_errno($curl)) {
            $error_msg = curl_error($curl);
        }
        curl_close($curl);

        if (isset($error_msg)) {
            echo $error_msg;
        }
        echo $response;

        $dataProduct = DB::table('tbl_product')
            ->where('stock', '>', 0)
            ->where('stock', '<=', 5)
            ->groupBy('tbl_product.group')
            ->get();
        $textMessage = "📢 *STOCK TINGGAL DIKIT NICHHHH* \n\n";

        // Loop untuk menambahkan produk
        foreach ($dataProduct as $product) {
            $textMessage .= "🔹 " . $product->group . ": *" . $product->stock . " pcs*\n";
        }


        $token = "Y2gHrxWHxAZm6KdaLK21  ";
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.fonnte.com/send',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => array(
                'target' => '120363166640669368@g.us',
                'message' => $textMessage,
            ),
            CURLOPT_HTTPHEADER => array(
                "Authorization: $token"
            ),
        ));

        $response = curl_exec($curl);
        if (curl_errno($curl)) {
            $error_msg = curl_error($curl);
        }
        curl_close($curl);

        if (isset($error_msg)) {
            echo $error_msg;
        }
        echo $response;
    }

    public function sendReportStock()
    {
        // $dataProductFrozen = DB::table('tbl_product')
        //     ->where('stock', '>', 0)
        //     ->where('remark', 3) // Frozen Food
        //     ->groupBy('tbl_product.group')
        //     ->get();

        $dataProductFrozen = DB::table('tbl_product')
            ->where('remark', 3) // Produk Frozen Food
            ->whereNotIn('product_category', ['Produk komponen', 'Produk komponen 2', 'Non stock']) // Mengecualikan kategori tertentu
            ->groupBy('tbl_product.group')
            ->get();

        // $dataProductFrozen = DB::table('tbl_product')
        //     ->where('stock', '>', 0)
        //     ->where('remark', 3) // Frozen Food
        //     ->groupBy('tbl_product.group')
        //     ->get();

        // $dataProductKita = DB::table('tbl_product')
        //     ->where('stock', '>', 0)
        //     ->where('remark', 1) // Produk Kita
        //     ->groupBy('tbl_product.group')
        //     ->get();
        $dataProductKita = DB::table('tbl_product')
            ->where('remark', 1) // Produk Kita
            ->whereNotIn('product_category', ['Produk komponen', 'Produk komponen 2', 'Non stock']) // Mengecualikan kategori tertentu
            ->groupBy('tbl_product.group')
            ->get();


        $textMessage = "📢 *STOK BARANG " . date("d/m/Y") . "* \n";

        // Tambahkan pembatas untuk Frozen Food
        $textMessage .= "\n====================\n";
        $textMessage .= "❄️ *FROZEN FOOD*";
        $textMessage .= "\n====================\n";
        foreach ($dataProductFrozen as $product) {
            $textMessage .= "🔹 " . $product->group . ": *" . $product->stock . " " . $product->unit . "*\n";
        }

        // Tambahkan pembatas untuk Produk Kita
        $textMessage .= "\n====================\n";
        $textMessage .= "🏠 *PRODUK KITA*";
        $textMessage .= "\n====================\n";
        foreach ($dataProductKita as $product) {
            $textMessage .= "🔹 " . $product->group . ": *" . $product->stock . " " . $product->unit . "*\n";
        }


        $this->sendToFonnte($textMessage);
    }

    public function sendReportLowStock()
    {
        $dataProduct = DB::table('tbl_product')
            ->where('stock', '<=', 5)
            ->where(function ($query) {
                $query->where('remark', 1)
                    ->orWhere('remark', 3);
            })
            ->whereNotIn('product_category', ['Produk komponen', 'Produk komponen 2', 'Non stock']) // Tambahkan pengecualian kategori
            ->groupBy('tbl_product.group')
            ->get();
            

        $textMessage = "📢 *STOCK TINGGAL DIKIT NICHHHH* \n\n";

        foreach ($dataProduct as $product) {
            $textMessage .= "🔹 " . $product->group . ": *" . $product->stock . " " . $product->unit . "*\n";
        }

        $this->sendToFonnte($textMessage);
    }

    private function sendToFonnte($message)
    {
        $token = "PuFfsE4GYJoPzfMgznpw";
        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => 'https://api.fonnte.com/send',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => ['target' => '120363166640669368@g.us', 'message' => $message],
            CURLOPT_HTTPHEADER => ["Authorization: $token"],
        ]);
        $response = curl_exec($curl);
        if (curl_errno($curl)) {
            $error_msg = curl_error($curl);
        }
        curl_close($curl);

        if (isset($error_msg)) {
            echo $error_msg;
        }
        echo $response;
    }
}
