<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class SendReportStockJob implements ShouldQueue
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
        $dataProductFrozen = DB::table('tbl_product')
            ->where('stock', '>', 0)
            ->where('remark', 3) // Frozen Food
            ->groupBy('tbl_product.group')
            ->get();

        $dataProductKita = DB::table('tbl_product')
            ->where('stock', '>', 0)
            ->where('remark', 1) // Produk Kita
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

    private function sendToFonnte($message)
    {
        $token = "GJT6zWdjCzLQtkE23ktc";
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
