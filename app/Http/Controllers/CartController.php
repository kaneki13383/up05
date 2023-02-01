<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
    public function all(Request $request)
    {
        $id_user = DB::table('users')->select('id')->where('token',$request->input('token'))->get()->first();

        return DB::table('carts')
            ->join('products', 'carts.id_product', '=', 'products.id')
            ->where('id_user',$id_user->id)
            ->select('products.*')->get();
    }

    public function add_cart($id, Request $request)
    {
        $id_user = User::select('id')->where('token',$request->input('token'))->get()->first();

        $id_product = Product::where('id',$id)->get()->first();

        $check_cart = Cart::where('id_user',$id_user->id)->where('id_product',$id_product->id)->get()->first();

        if($id_product != null){
            if(!$check_cart){
                DB::table('carts')->insert([
                    'id_user' => $id_user->id,
                    'id_product' => $id,
                    'summ' => $id_product->price
                ]);
            }
            else{
                $count = 1;
                $cart = Cart::where('id_product',$id_product->id)->where('id_user',$id_user->id)->first();
                $price = Product::where('id',$id)->get()->first();
                $summ = $cart->summ + $price->price;
                $count += $cart->count;

                Cart::where('id_product',$id_product->id)->where('id_user',$id_user->id)->update([
                    'count' => $count,
                    'summ' => $summ
                ]);
                return response(json_encode([
                    'message' => 'Товар добавлен'
                ]));
            }

            return response(json_encode([
                'message' => 'Товар в корзине'
            ]));
        }
        else{
            return response(json_encode([
                'message' => 'Товар не существует'
            ]));
        }
    }

    public function del_cart($id, Request $request)
    {
        $id_user = User::select('id')->where('token',$request->input('token'))->get()->first();

        $cart = Cart::where('id_user',$id_user->id)->where('id_product',$id)->first();

        dd($cart);

        if($cart->count == 1){
            Cart::where('id',$cart->id)->delete();
        }
        else{
            

            Cart::where('id',$cart->id)->update([
                'count' => 1,
                'summ' => 1
            ]);
        }
    }
}
