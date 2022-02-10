<?php

namespace App\Http\Controllers\Product;

use App\Http\Common\CheckUser;
use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Product_price;
use DataTables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use function response;
use function view;

class ProductController extends Controller
{
    //Product
    public function index()
    {
        if (!CheckUser::isUser()) {
            return view('auth.login');
        }
        $productType = DB::table('product__types')->get();
        return view('product.product', compact('productType'));
    }

    //Add Product
    public function productAdd(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'name' => 'required|max:255'
        ]);
        if (!$validator->passes()) {
            return response()->json(['code' => 0, 'error' => $validator->errors()->toArray()]);
        } else {
            $product = new Product();
            $product->name = $request->name;
            $product->description = $request->description;
            $product->type_id = $request->type_id;
            $query = $product->save();


            $productPrice = new Product_price();
            $productPrice->product_id = $product->id;
            $productPrice->price = $request->price;
            $q = $productPrice->save();

            $product->price_id = $productPrice->id;
            $qq = $product->save();

            if (!$qq) {
                return response()->json(['code' => 0, 'msg' => 'Something went wrong']);
            } else {
                return response()->json(['code' => 1, 'msg' => 'New Product has been successfully saved']);
            }
        }
    }


    public function getProductList()
    {
        $products = Product::all();
        foreach ($products as $value) {
            $value->type_id = DB::table('product__types')->where('id', $value->type_id)->value('name');
            $value->price_id = DB::table('product_prices')->where('id', $value->price_id)->value('price');
        }
        return DataTables::of($products)
            ->addIndexColumn()
            ->addColumn('action', function ($row) {
                return '
				<div class="btn-group">
					<button class="btn  btn-sm btn-primary" data-id="' . $row['id'] . '" id="editTypeBtn">Update</button>
					<button class="btn  btn-sm btn-danger" data-id="' . $row['id'] . '" id="deleteTypeBtn">Delete</button>
					<button class="btn  btn-sm btn-success" data-id="' . $row['id'] . '" id="chartTypeBtn">Chart</button>
				</div> ';
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    //update Selected
    public function getProductDetails(Request $request)
    {
        $id = $request->id;
        $productDetails = Product::find($id);
        $productDetails->price_id = DB::table('product_prices')->where('id', $productDetails->price_id)->value('price');
        return response()->json(['details' => $productDetails]);
    }

    //update
    public function updateProductDetails(Request $request)
    {
        $type_id = $request->cid;
        $validator = \Validator::make($request->all(), [
            'name' => 'required'
        ]);

        if (!$validator->passes()) {
            return response()->json(['code' => 0, 'error' => $validator->errors()->toArray()]);
        } else {
            $product = Product::find($type_id);
            $product->name = $request->name;
            $product->description = $request->description;
            $product->type_id = $request->type_id;
            $query = $product->save();
            $priceProduct = DB::table('product_prices')->where('id', $product->price_id)->value('price');
            if ($priceProduct != $request->price) {
                $productPrice = new Product_price();
                $productPrice->product_id = $product->id;
                $productPrice->price = $request->price;
                $productPrice->save();

                $product->price_id = $productPrice->id;
                $product->save();
            }
            if (!$query) {
                return response()->json(['code' => 0, 'msg' => 'Something went wrong']);
            } else {
                return response()->json(['code' => 1, 'msg' => 'New Product has been successfully Updated']);
            }
        }
    }

    //delete
    public function deleteProduct(Request $request)
    {
        $id = $request->id;
        DB::table('product_prices')->where('product_id', $id)->delete();
        $query = Product::find($id)->delete();

        if ($query) {
            return response()->json(['code' => 1, 'msg' => 'Product has been deleted from database']);
        } else {
            return response()->json(['code' => 0, 'msg' => 'Something went wrong']);
        }
    }

}
