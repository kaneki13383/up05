<?php

namespace App\Http\Controllers;

use App\Models\User;
use Termwind\Components\Dd;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Config;

class AuthController extends Controller
{
    public function autorization(Request $request)
    {
        $user = DB::table('users')->where('email',$request->input('email'))->get()->first();
        $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyz';

        if(Hash::check($request->input('password'),$user->password)){
            DB::table('users')->where('email',$request->input('email'))->update([
                'token' => str_shuffle($permitted_chars)
            ]);
            Config::set('app.user', $user);
            return $user;
        }
        else{
            return response(json_encode([
                'message' => 'Не правильный пароль или email',
                'code' => 403
            ]), 403);
        }
    }

    public function logout()
    {
        dd(Config::get('app.user'));
    }
}
