<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
    public function all(Request $request)
    {
        $id_user = DB::table('users')->select('id')->where('token',$request->input('token'))->get()->first();

        return DB::table('carts')->where('id_user',$id_user->id)->get();
    }

    public function add_cart($id, Request $request)
    {
        $id_user = DB::table('users')->select('id')->where('token',$request->input('token'))->get()->first();
        
        DB::table('carts')->insert([
            'id_user' => $id_user->id,
            'id_product' => $id
        ]);

        return response(json_encode([
            'message' => 'Товар в корзине'
        ]));
    }
}
