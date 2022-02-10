<?php

namespace App\Http\Common;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use function view;

class CheckUser
{
    public static function AdminUser()
    {
        if (!CheckUser::isUser() && \session()->get('accessTypeId') != UserAccess::$Admin) {
            return view('auth.login');
        }
    }

    public static function isUser()
    {
        $userId = DB::table('Users')
            ->find(Session::get('userId'));
        if ($userId) {
            return true;
        } else {
            return false;
        }
    }
}
