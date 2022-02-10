<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use function view;

class LoginController extends Controller
{

    public function index()
    {
        return view('auth\login');
    }

    public function login(Request $request)
    {
        $user = DB::table('Users')
            ->where('name', $request->username)
            ->get();
        if (count($user) == 1) {
            if (Hash::check($request->password, $user[0]->password)) {
                $accessTypeId = DB::table('access__users')
                    ->where('user_id', $user[0]->id)
                    ->value('access_id');
                $productType = DB::table('product__types')->get();
                Session::put('userId', $user[0]->id);
                Session::put('accessTypeId', $accessTypeId);
                return view('product.product', compact('productType'));
            }
        }
        return $this->index();
    }

    public function signOut()
    {
        session()->forget('userId');
        session()->forget('accessTypeId');
        session()->flush();
        return $this->index();
    }

}
