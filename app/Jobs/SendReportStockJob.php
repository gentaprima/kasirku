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
        $dataProduct = DB::table('tbl_product')
            ->where('stock', '>', 0)
            ->groupBy('tbl_product.group')
            ->get();

        $textMessage = "📢 *STOK BARANG " . date("d/m/Y") . "* \n\n";

        foreach ($dataProduct as $product) {
            $textMessage .= "🔹 " . $product->group . ": *" . $product->stock . " pcs*\n";
        }

        $this->sendToFonnte($textMessage);
    }

    private function sendToFonnte($message)
    {
        $token = "Y2gHrxWHxAZm6KdaLK21";
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
