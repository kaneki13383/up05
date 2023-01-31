<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

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

            $user = DB::table('users')->where('email',$request->input('email'))->get()->first();

            return response(json_encode([
                'token' => $user->token,
            ]));
        }
        else{
            return response(json_encode([
                'message' => 'Неудачный вход',
                'code' => 401
            ]), 401);
        }
    }

    public function logout(Request $request)
    {
        DB::table('users')->where('token', $request->input('token'))->update([
            'token' => 'NULL'
        ]);

        return response(json_encode([
            'message' => 'Вы вышли из аккаунта',
            'code' => 200
        ]));
    }
}
