<?php

namespace App\Http\Controllers\ProductType;

use App\Http\Common\CheckUser;
use App\Http\Controllers\Controller;
use App\Models\Product_Type;
use DataTables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use function response;
use function view;

class ProductTypeController extends Controller
{
    //Type list
    public function index()
    {
        if (!CheckUser::isUser()) {
            return view('auth.login');
        }
        return view('product_type.product_type');
    }

    //Add Type
    public function productTypeAdd(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'name' => 'required|max:255'
        ]);

        if (!$validator->passes()) {
            return response()->json(['code' => 0, 'error' => $validator->errors()->toArray()]);
        } else {
            $type = new Product_Type();
            $type->name = $request->name;
            $query = $type->save();
            if (!$query) {
                return response()->json(['code' => 0, 'msg' => 'Something went wrong']);
            } else {
                return response()->json(['code' => 1, 'msg' => 'New Type has been successfully saved']);
            }
        }
    }

    public function getProductTypeList()
    {
        $types = Product_Type::all();
        return DataTables::of($types)
            ->addIndexColumn()
            ->addColumn('actions', function ($row) {
                return '
                    <div class="btn-group">
                        <button class="btn  btn-sm btn-primary" data-id="' . $row['id'] . '" id="editTypeBtn">Update</button>
                        <button class="btn  btn-sm btn-danger" data-id="' . $row['id'] . '" id="deleteTypeBtn">Delete</button>
                    </div>
			    ';
            })
            ->rawColumns(['actions'])
            ->make(true);
    }

    public function getProductTypeDetails(Request $request)
    {
        $type_id = $request->type_id;
        $typeDetails = Product_Type::find($type_id);
        return response()->json(['details' => $typeDetails]);
    }

    public function updateProductTypeDetails(Request $request)
    {
        $type_id = $request->cid;
        $validator = \Validator::make($request->all(), [
            'name' => 'required'
        ]);

        if (!$validator->passes()) {
            return response()->json(['code' => 0, 'error' => $validator->errors()->toArray()]);
        } else {
            $type = Product_Type::find($type_id);
            $type->name = $request->name;
            $query = $type->save();

            if (!$query) {
                return response()->json(['code' => 0, 'msg' => 'Something went wrong']);
            } else {
                return response()->json(['code' => 1, 'msg' => 'New Type has been successfully Updated']);
            }
        }
    }

    public function deleteProductType(Request $request)
    {
        $type_id = $request->type_id;
        $query = Product_Type::find($type_id)->delete();
        if ($query) {
            return response()->json(['code' => 1, 'msg' => 'Product Type has been deleted from database']);
        } else {
            return response()->json(['code' => 0, 'msg' => 'Something went wrong']);
        }
    }
}
