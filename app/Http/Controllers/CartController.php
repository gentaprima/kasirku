<?php

namespace App\Http\Controllers;

use App\Models\ModelCart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
    public function getCart(Request $request)
    {
        $cart = DB::table('tbl_cart')
            ->where('is_order', '=', 0)
            ->where('id_users', '=', $request->idUsers)
            ->join('tbl_product', 'tbl_product.id', '=', 'tbl_cart.id_product')->get();
        $totalCart = 0;
        for ($i = 0; $i < count($cart); $i++) {
            $totalCart += $cart[$i]->total;
        }


        return response()->json([
            'success' => "true",
            'data'   => $cart,
            'total_cart' => $totalCart
        ]);
    }

    public function addCart(Request $request)
    {
        $idProduct = $request->idProduct;
        $topping = $request->topping;
        $idUsers = $request->idUsers;

        if($topping == ""){
            return response()->json([
                'message' => "Silahkan pilih topping terlebih dahulu.",
                'success' => false
            ]);
        }

        $dataProduct = DB::table('tbl_product')->where('id', '=', $idProduct)->first();

        $checkProductInCart = DB::table('tbl_cart')->where('id_product', '=', $idProduct)->where('topping', '=', $topping)->where('is_order', '=', 0)->where('id_users','=',$idUsers)->first();
        if ($checkProductInCart != null) {
            // $total = $dataProduct->price + $dataTopping->price;
            $totalTopping = 0;
            $explodeTopping = explode(",", $topping);
            for ($i = 0; $i < count($explodeTopping); $i++) {
                $dataTopping = DB::table('tbl_topping')->where('topping_name', '=', ltrim($explodeTopping[$i]))->first();
                $hargaTopping = $dataTopping->price;
                $totalTopping += $hargaTopping;
            }
            $totalAfterTopping = $dataProduct->price + $totalTopping;
            $cart = ModelCart::find($checkProductInCart->id);
            $cart->quantity = $checkProductInCart->quantity + 1;
            $cart->total = $totalAfterTopping * ($checkProductInCart->quantity + 1);
            $cart->save();
        } else {
            $totalTopping = 0;
            $explodeTopping = explode(",", $topping);
            for ($i = 0; $i < count($explodeTopping); $i++) {
                $dataTopping = DB::table('tbl_topping')->where('topping_name', '=', ltrim($explodeTopping[$i]))->first();
                $hargaTopping = $dataTopping->price;
                $totalTopping += $hargaTopping;
            }

            $totalAfterTopping = $dataProduct->price + $totalTopping;

            ModelCart::create([
                'id_product'    => $idProduct,
                'quantity'      => 1,
                'date'          => date('Y-m-d'),
                'is_order'      => 0,
                'created_at'    => date('Y-m-d H:i:s'),
                'total'         => $totalAfterTopping,
                'topping'       => $topping,
                'id_users'      => $idUsers
            ]);
        }


        return response()->json([
            'message' => "Produk berhasil ditambahkan kedalam keranjang",
            'success' => true
        ]);
    }

    public function minCart(Request $request)
    {
        $idProduct = $request->idProduct;
        $topping = $request->topping;
        $idUsers = $request->idUsers;

        $dataProduct = DB::table('tbl_product')->where('id', '=', $idProduct)->first();

        $checkProductInCart = DB::table('tbl_cart')->where('id_product', '=', $idProduct)->where('topping', '=', $topping)->where('is_order', '=', 0)->where('id_users','=',$idUsers)->first();
        if ($checkProductInCart != null) {
            $cart = ModelCart::find($checkProductInCart->id);
            if ($checkProductInCart->quantity - 1 != 0) {
                // $total = $dataProduct->price + $dataTopping->price;
                // $cart->quantity = $checkProductInCart->quantity - 1;
                // $cart->total = $total * ($checkProductInCart->quantity - 1);
                // $cart->save();
                // $total = $dataProduct->price + $dataTopping->price;
                $totalTopping = 0;
                $explodeTopping = explode(",", $topping);
                for ($i = 0; $i < count($explodeTopping); $i++) {
                    $dataTopping = DB::table('tbl_topping')->where('topping_name', '=', ltrim($explodeTopping[$i]))->first();
                    $hargaTopping = $dataTopping->price;
                    $totalTopping += $hargaTopping;
                }
                $totalAfterTopping = $dataProduct->price + $totalTopping;
                $cart = ModelCart::find($checkProductInCart->id);
                $cart->quantity = $checkProductInCart->quantity - 1;
                $cart->total = $totalAfterTopping * ($checkProductInCart->quantity - 1);
                $cart->save();
            } else {
                $cart->delete();
            }
        } else {
            // ModelCart::create([
            //     'id_product'    => $idProduct,
            //     'quantity'      => 1,
            //     'date'          => date('Y-m-d'),
            //     'is_order'      => 0,
            //     'created_at'    => date('Y-m-d H:i:s')

            // ]);
        }


        return response()->json([
            'message' => "Produk berhasil dikurangkan dari keranjang",
            'success' => true
        ]);
    }
}
