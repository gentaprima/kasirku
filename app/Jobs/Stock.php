<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class Stock implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle():void
    {
    //     $dataProduct = DB::table('tbl_product')->groupBy('tbl_product.group')->get();
    //     $textMessage = "\u{1F4E2} *STOK BARANG " . date("d/m/Y") . "* \n\n";

    //     foreach ($dataProduct as $product) {
    //         $textMessage .= "\u{1F539} " . $product->product_name . ": *" . $product->stock . " pcs*\n";
    //     }

    //     $apiUrl = "https://messages-sandbox.nexmo.com/v1/messages";
    //     $apiKey = "8bbdaf30";
    //     $apiSecret = "A6Fy1lM78uDE4ISl";
        
    //     $recipients = ['6289669615426']; // Tambahkan nomor lain jika perlu

    //     foreach ($recipients as $recipient) {
    //         $data = [
    //             "from" => "14157386102",
    //             "to" => $recipient,
    //             "message_type" => "text",
    //             "text" => $textMessage,
    //             "channel" => "whatsapp"
    //         ];

    //         $ch = curl_init();
    //         curl_setopt($ch, CURLOPT_URL, $apiUrl);
    //         curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    //         curl_setopt($ch, CURLOPT_HTTPHEADER, [
    //             "Content-Type: application/json",
    //             "Accept: application/json"
    //         ]);
    //         curl_setopt($ch, CURLOPT_USERPWD, "$apiKey:$apiSecret");
    //         curl_setopt($ch, CURLOPT_POST, 1);
    //         curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

    //         $response = curl_exec($ch);
    //         curl_close($ch);

    //         Log::info("WA Message Sent to $recipient: " . json_encode($response));
    //     }
    // }
    $dataProduct = DB::table('tbl_product')
        ->where('stock','>',0)
        ->groupBy('tbl_product.group')
        ->get();
        $textMessage = "📢 *STOK BARANG " . date("d/m/Y") . "* \n\n";

        // Loop untuk menambahkan produk
        foreach ($dataProduct as $product) {
            $textMessage .= "🔹 " . $product->group . ": *" . $product->stock . " pcs*\n";
        }

        $apiUrl = "https://messages-sandbox.nexmo.com/v1/messages";
        $apiKey = "8bbdaf30";
        $apiSecret = "A6Fy1lM78uDE4ISl";

        $recipient = ['6289669615426'];

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
}
