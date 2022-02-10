<?php

namespace App\Http\Controllers\Auth;

use App\Http\Common\CheckUser;
use App\Http\Controllers\Controller;
use DataTables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

//use Illuminate\Http\User;

class UsersRegisterController extends Controller
{
    //Register list
    public function index()
    {
        CheckUser::AdminUser();
        $accessType = DB::table('access__types')->get();
        return view('auth.user_register', compact('accessType'));
    }

    //Add Register
    public function userRegisterAdd(Request $request)
    {
        CheckUser::AdminUser();
        $validator = \Validator::make($request->all(), [
            'user_name' => 'required',
            'email' => 'required',
            'password' => 'required',
            'confirm_password' => 'required',
            'access_type_id' => 'required',
        ]);

        if (!$validator->passes()) {
            return response()->json(['code' => 0, 'error' => $validator->errors()->toArray()]);
        } else {
            if ($request->password == $request->confirm_password) {
                $insertUserId = DB::table('users')->insertGetId([
                    'name' => $request->user_name,
                    'email' => $request->email,
                    'password' => Hash::make($request->password)
                ]);
                $insertUserAccessId = DB::table('access__users')->insertGetId([
                    'user_id' => $insertUserId,
                    'access_id' => $request->access_type_id,
                ]);

                if (!$insertUserId && !$insertUserAccessId) {
                    return response()->json(['code' => 0, 'msg' => 'Something went wrong']);
                } else {
                    return response()->json(['code' => 1, 'msg' => 'New User Register has been successfully saved']);
                }
            }
        }
        return response()->json(['code' => 0, 'msg' => 'Something went wrong']);
    }

    public function getUserRegisterList()
    {
        CheckUser::AdminUser();
        $types = json_decode(DB::table('users')
            ->join('access__users', 'users.id', 'access__users.user_id')
            ->join('access__types', 'access__types.id', 'access__users.access_id')
            ->select('users.*', 'access__types.name as access_name')
            ->get());
        return DataTables::of($types)
            ->addIndexColumn()
            ->addColumn('actions', function ($row) {
                return '
                    <div class="btn-group">
                        <button class="btn  btn-sm btn-primary" data-id="' . $row->id . '" id="editRegisterBtn">Update</button>
                        <button class="btn  btn-sm btn-danger" data-id="' . $row->id . '" id="deleteRegisterBtn">Delete</button>
                    </div>
			    ';
            })
            ->rawColumns(['actions'])
            ->make(true);
    }

    public function getUserRegisterDetails(Request $request)
    {
        CheckUser::AdminUser();
        $typeDetails = DB::table('users')
            ->join('access__users', 'users.id', 'access__users.user_id')
            ->where('users.id', $request->id)
            ->select('users.*', 'access__users.access_id')
            ->first();
        return response()->json(['details' => $typeDetails]);
    }

    public function updateUserRegisterDetails(Request $request)
    {

        CheckUser::AdminUser();
        $validator = \Validator::make($request->all(), [
            'user_name' => 'required',
            'email' => 'required',
            'access_type_id' => 'required',
        ]);

        if (!$validator->passes()) {
            return response()->json(['code' => 0, 'error' => $validator->errors()->toArray()]);
        } else {
            $user = Db::table('users')->find($request->cid);
            if ($user != null) {
                if ($request->change_password == 'on' && (Hash::check($request->current_password, $user->password))) {
                    if ($request->password == $request->confirm_password) {
                        Db::table('users')->where('id', $user->id)->update([
                            'password' => Hash::make($request->password)
                            , 'name' => $request->user_name
                            , 'email' => $request->email
                        ]);
                        $state = true;
                    } else {
                        return response()->json(['code' => 0, 'msg' => 'Password and confirm password does not match']);
                    }
                } else {
                    Db::table('users')->where('id', $user->id)
                        ->update([
                            'name' => $request->user_name
                            , 'email' => $request->email]);
                    $state = true;
                }
            }
        }
        if ($state) {
            DB::table('access__users')->where('user_id', $user->id)->update([
                'access_id' => $request->access_type_id
            ]);
            return response()->json(['code' => 1, 'msg' => 'User Updated has been successfully saved']);
        } else {
            return response()->json(['code' => 0, 'msg' => 'Something went wrong']);
        }
    }

    public function deleteUserRegister(Request $request)
    {
        CheckUser::AdminUser();
        $id = $request->id;
        if ($id == session()->get('userId')) {
            return response()->json(['code' => 0, 'msg' => 'This is Current user']);
        }
        $deleteFromAccess = DB::table('access__users')->where('user_id', $id)->delete();
        $deleteFromUser = DB::table('users')->where('id', $id)->delete();
        if ($deleteFromUser) {
            return response()->json(['code' => 1, 'msg' => 'User has been deleted from database']);
        } else {
            return response()->json(['code' => 0, 'msg' => 'Something went wrong']);
        }
    }
}
