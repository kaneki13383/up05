<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function all(Request $request)
    {
        $user = User::where('token',$request->input('token'))->get()->first();
        return Order::select('id','id_products','order_price')->where('id_user',$user->id)->get();
    }

    public function create_order(Request $request)
    {
        $user = User::where('token',$request->input('token'))->get()->first();
        $cart = Cart::where('id_user',$user->id)->get();

        $id_products = '';
        $order_price = 0;

        for ($i=0; $i < count($cart); $i++) { 
            $id_products = $id_products.','.$cart[$i]['id_product'];
            $order_price += $cart[$i]['summ'];
        }

        $order = Order::create([
            'id_user' => $user->id,
            'id_products' => substr($id_products, 1),
            'order_price' => $order_price
        ]);

        for ($i=0; $i < count($cart); $i++) { 
            Cart::where('id',$cart[$i]['id'])->delete();
        }

        return response(json_encode([
            'order_id' => $order->id,
            'message' => 'Заказ оформлен'
        ]));
    }
}
