<?php

namespace App\Http\Controllers;

use App\Models\ModelTopping;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ToppingController extends Controller
{
    public function store(Request $request){
        ModelTopping::create([
            'topping_name' => $request->toppingName,
            'price'        => $request->price,
            'id_product'   => $request->idProduct 
        ]);

        return response()->json([
            'status' => true,
            'message' => "successfully inserted data."
        ]);
    }

    public function getData($id){
        $data = DB::table("tbl_topping")->where('id_product','=',$id)->get();

        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }
}
