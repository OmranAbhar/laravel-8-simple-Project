<?php

namespace App\Http\Controllers\Product\chart;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use function response;


class ChartProductPriceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $priceArray = array();
        $dateArray = array();
        $priceList = DB::table('product_prices')->where('product_id', $request->id)->orderByDesc('id')->select(['price'])->get();
        foreach ($priceList as $v) {
            array_push($priceArray, $v->price);
        }

        $dateList = DB::table('product_prices')->where('product_id', $request->id)->orderByDesc('id')->select(['updated_at'])->get();
        foreach ($dateList as $v) {
            array_push($dateArray, $v->updated_at);
        }

        $obj = new ChartObject();
        $obj->x = $priceArray;
        $obj->y = $dateArray;
        return response()->json(['obj' => $obj]);
    }
}
