<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class SendReportLowStockJob implements ShouldQueue
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
    public function handle()
    {
        // $dataProduct = DB::table('tbl_product')
        //     ->where('stock', '>', 0)
        //     ->where('stock', '<=', 5)
        //     ->where(function ($query) {
        //         $query->where('remark', 1)
        //               ->orWhere('remark', 3);
        //     })
        //     ->groupBy('tbl_product.group')
        //     ->get();

        $dataProduct = DB::table('tbl_product')
            ->where('stock', '<=', 5)
            ->where(function ($query) {
                $query->where('remark', 1)
                    ->orWhere('remark', 3);
            })
            ->whereNotIn('product_category', ['Produk komponen','Produk komponen 2' ,'Non stock']) // Tambahkan pengecualian kategori
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
        curl_exec($curl);
        curl_close($curl);
    }
}
