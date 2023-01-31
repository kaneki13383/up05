<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    public function create(Request $request)
    {
        if ($request->input('password') === $request->input('password_confirmed')) {

            $errors = [];

            if(str_contains($request->input('email'), '@')){
                DB::table('users')->insert([
                    'name' => $request->input('name'),
                    'email' => $request->input('email'),
                    'password' => Hash::make($request->input('password')),
                    'token' => 'NULL'
                ]);

                $user = DB::table('users')->where('email',$request->input('email'))->get()->first();
                $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyz';
        
                if(Hash::check($request->input('password'),$user->password)){
                    DB::table('users')->where('email',$request->input('email'))->update([
                        'token' => str_shuffle($permitted_chars)
                    ]);
                }
                
                $user = DB::table('users')->where('email',$request->input('email'))->get()->first();

                return response(json_encode([
                    'message' => 'Аккаунт создан',
                    'code' => 200,
                    'user' => [
                        "token" => $user->token
                        ]
                ]), 200);
            }
            else{
                array_push($errors, 'Не правильный email!');
                return response(json_encode([
                    'message' => 'Несоответствие требованием',
                    'code' => 422,
                    'warning' => $errors
                ]), 422);
            }           
        }
        else{
            $errors = ['Пароли не совпадают'];

            if(str_contains($request->input('email'), '@')){

            }
            else{
                array_push($errors, 'Не правильный email!');
            }

            return response(json_encode([
                'message' => 'Несоответствие требованием',
                'code' => 422,
                'warning' => $errors
            ]), 422);
        }        
    }
}
