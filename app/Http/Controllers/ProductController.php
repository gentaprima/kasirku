<?php

namespace App\Http\Controllers;

use App\Models\HistoryStock;
use App\Models\ModelProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Request as FacadesRequest;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function getProductJson(Request $request)
    {
        if ($request->search != '') {
            $dataProduct = DB::table('tbl_product')
                ->where('product_name', 'like', '%' . $request->search . '%')
                ->orWhere('price', 'like', '%' . $request->search . '%')
                ->orWhere('product_category', 'like', '%' . $request->search . '%')
                ->paginate(10);
        } else {
            $dataProduct = DB::table('tbl_product')
                ->paginate(10);
        }

        return response()->json([
            'success'   => true,
            'data'      => $dataProduct,
            'linkPhoto'    => asset('uploads/product')
        ]);
    }

    public function store(Request $request)
    {
        if ($request->imageProduct != "") {

            $imageProduct = $request->file('image');
            $filename = uniqid() . time() . "."  . explode("/", $imageProduct->getMimeType())[1];
            Storage::disk('uploads')->put('product/' . $filename, File::get($imageProduct));
        }

        ModelProduct::create([
            'product_name' => $request->productName,
            'product_category' => $request->productCategory,
            'price' => $request->price,
            'is_active' => $request->isActive,
            'photo' => "",
            'group' => $request->group != null ? $request->group : $request->productName,
            'stock_reduction' => $request->stockReduction != null ? $request->stockReduction : 1,
            'unit'=>$request->unit,
            'remark'=>$request->remark
        ]);

        Session::flash('message', 'Product berhasil ditambahkan.');
        Session::flash('icon', 'success');
        return redirect()->back()
            ->withInput($request->input());
    }

    public function update(Request $request, $id)
    {
        $imageProduct = $request->file('image');
        $product = ModelProduct::find($id);
        if ($imageProduct == null) {
            $filename = $product['photo'];
        } else {

            $filename = uniqid() . time() . "."  . explode("/", $imageProduct->getMimeType())[1];
            Storage::disk('uploads')->put('product/' . $filename, File::get($imageProduct));
        }

        $product->product_name = $request->productName;
        $product->product_category = $request->productCategory;
        $product->is_active = $request->isActive;
        $product->price = $request->price;
        $product->photo = $filename;
        $product->group = $request->group != null ? $request->group : $request->product_name;
        $product->stock_reduction = $request->stockReduction != null ? $request->stockReduction : 1;
        $product->remark = $request->remark;
        $product->unit = $request->unit;
        $product->save();

        Session::flash('message', 'Produk berhasil diperbarui.');
        Session::flash('icon', 'success');
        return redirect()->back()
            ->withInput($request->input());
    }

    public function destroy($id)
    {
        $product = ModelProduct::find($id);
        $fileName = public_path() . '/uploads/product/' . $product['photo'];
        unlink($fileName);

        $product->delete();
        Session::flash('message', 'Produk berhasil dihapus.');
        Session::flash('icon', 'success');
        return redirect()->back();
    }

    public function updateStock(Request $request)
    {
        $satuan = $request->satuan;
        $stockIncoming = $request->stock;
        if ($satuan == null) {
            Session::flash('message', 'Satuan tidak boleh kosong.');
            Session::flash('icon', 'error');
            return redirect()->back();
        }
        if ($stockIncoming == null) {
            Session::flash('message', 'Stok masuk tidak boleh kosong.');
            Session::flash('icon', 'error');
            return redirect()->back();
        }

        if ($satuan == "Renceng12") {
            $stock = $stockIncoming * 12;
        } else if ($satuan == "Renceng6") {
            $stock = $stockIncoming * 6;
        } else if ($satuan == "Renceng10") {
            $stock = $stockIncoming * 10;
        } else {
            $stock = $stockIncoming;
        }

        $product = DB::table("tbl_product")->where('group', '=', $request->group)->first();

        DB::table('tbl_product')
            ->where('tbl_product.group', '=', $request->group)
            ->update([
                'stock' => $product->stock + $stock
            ]);

        HistoryStock::create([
            'product_name'  => $product->group,
            'incoming_stock' => $stock,
            'date' => date('Y-m-d')
        ]);
        Session::flash('message', 'Stock Produk berhasil diperbarui.');
        Session::flash('icon', 'success');
        return redirect()->back();
    }

    public function updateStockApi(Request $request)
    {
        $satuan = $request->satuan;
        $stockIncoming = $request->stock;
        if ($satuan == null) {
            return response()->json([
                'message' => "Satuan tidak boleh kosong",
                'success' => true
            ]);
        }
        if ($stockIncoming == null) {
            return response()->json([
                'message' => "Stock tidak Boleh kosong ",
                'success' => false
            ]);
        }

        if ($satuan == "Renceng12") {
            $stock = $stockIncoming * 12;
        } else if ($satuan == "Renceng6") {
            $stock = $stockIncoming * 6;
        } else if ($satuan == "Renceng10") {
            $stock = $stockIncoming * 10;
        } else {
            $stock = $stockIncoming;
        }

        $product = DB::table("tbl_product")->where('group', '=', $request->group)->first();

        DB::table('tbl_product')
            ->where('tbl_product.group', '=', $request->group)
            ->update([
                'stock' => $product->stock + $stock
            ]);

        HistoryStock::create([
            'product_name'  => $product->group,
            'incoming_stock' => $stock,
            'date' => date('Y-m-d')
        ]);

        return response()->json([
            'message' => "Stock berhasil ditambahkan ",
            'success' => true
        ]);
    }

    // API
    public function getProduct(Request $request)
    {
        $data = DB::table('tbl_product')
            ->where('product_category', '=', $request->category)
            ->where('product_name', 'like', '%' . $request->search . '%')->paginate(10);

        return response()->json([
            'data' => $data,
            'success' => true
        ]);
    }
}
