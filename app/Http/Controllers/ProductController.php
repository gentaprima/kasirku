<?php

namespace App\Http\Controllers;

use App\Models\HistoryStock;
use App\Models\ModelProduct;
use App\Models\ProductComponent;
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
            'unit' => $request->unit,
            'remark' => $request->remark
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
        if ($request->category == "Makanan") {
            $data = DB::table('tbl_product')
                ->where(function ($query) use ($request) {
                    $query->where('product_category', '=', $request->category)
                        ->orWhere('product_category', '=', "Produk komponen");
                })
                ->where('product_name', 'like', '%' . $request->search . '%')
                ->paginate(10);
        } else if ($request->category == "Minuman") {
            $data = DB::table('tbl_product')
                ->where(function ($query) use ($request) {
                    $query->where('product_category', '=', $request->category)
                        ->orWhere('product_category', '=', "Produk komponen 2");
                })
                ->where('product_name', 'like', '%' . $request->search . '%')
                ->paginate(10);
        } else {
            $data = DB::table('tbl_product')
                ->where('product_category', '=', $request->category)
                ->where('product_name', 'like', '%' . $request->search . '%')->paginate(10);
        }



        return response()->json([
            'data' => $data,
            'success' => true
        ]);
    }

    public function getComponent(Request $request)
    {
        if ($request->search != '') {
            $dataProduct = DB::table('tbl_product')
                ->where('product_name', 'like', '%' . $request->search . '%')
                ->orWhere('price', 'like', '%' . $request->search . '%')
                ->orWhere('product_category', '=', 'Bahan')
                ->paginate(10);
        } else {
            $dataProduct = DB::table('tbl_product')->where('product_category', '=', 'Bahan')
                ->paginate(10);
        }

        return response()->json([
            'success'   => true,
            'data'      => $dataProduct,
        ]);
    }
    public function getComponentProduct(Request $request)
    {
        if ($request->search != '') {
            $dataProduct = DB::table('tbl_product')
                ->where('product_name', 'like', '%' . $request->search . '%')
                ->get();
        } else {
            $dataProduct = DB::table('tbl_product')->where('product_category', '=', 'Makanan')->groupBy('group')
                ->get();
        }

        return response()->json([
            'success'   => true,
            'data'      => $dataProduct,
        ]);
    }

    public function storeComponentProduct(Request $request)
    {
        if ($request->product == null) {
            return response()->json([
                'success' => false,
                'message' => "Produk tidak boleh kosong!"
            ]);
        }
        if ($request->component == null) {
            return response()->json([
                'success' => false,
                'message' => "Bahan tidak boleh kosong!"
            ]);
        }
        if ($request->qty == null) {
            return response()->json([
                'success' => false,
                'message' => "Quantity tidak boleh kosong!"
            ]);
        }

        ProductComponent::create([
            'product_id' => $request->product,
            'component_id' => $request->component,
            'quantity' => $request->qty
        ]);

        return response()->json([
            'success' => true,
            'message' => "Produk Komponen berhasil ditambahkan"
        ]);
    }

    public function getProductComponent()
    {
        $data = DB::table('product_components')
            ->join('tbl_product as main_product', 'product_components.product_id', '=', 'main_product.id') // Produk utama
            ->join('tbl_product as component_product', 'product_components.component_id', '=', 'component_product.id') // Komponen bahan
            ->select(
                'main_product.product_name as main_product_name', // Nama produk utama
                DB::raw("GROUP_CONCAT(component_product.product_name ORDER BY component_product.product_name SEPARATOR ', ') as component_names") // Gabungkan bahan dalam satu kolom
            )
            ->groupBy('main_product.product_name') // Kelompokkan berdasarkan produk utama
            ->get();

        return response()->json([
            'success'   => true,
            'data'      => $data,
        ]);
    }

    public function getStock(Request $request)
    {
        $dataStock = DB::table('tbl_product')
            ->when(request('search'), function ($query) {
                $query->where('tbl_product.group', 'like', '%' . request('search') . '%');
            })
            ->whereIn('product_category', ['Makanan', 'Minuman', 'Bahan'])
            ->groupBy('tbl_product.group')
            ->paginate(10);

        return response()->json([
            'success' => true,
            'data' => $dataStock
        ]);
    }
}
