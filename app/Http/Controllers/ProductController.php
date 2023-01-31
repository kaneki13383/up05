<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    public function all()
    {
        return DB::table('products')->get();
    }

    public function delete_product($id)
    {
        DB::table('products')->where('id',$id)->delete();

        return response(json_encode([
            'message' => 'Товар удален'
        ]));
    }

    public function edit_product($id, Request $request)
    {
        DB::table('products')->where('id',$id)->update([
            'name' => $request->input('name'),
            'description' => $request->input('description'),
            'price' => $request->input('price'),
        ]);

        return response(json_encode([
            'id' => $id,
            'message' => 'Данные обновленны'
        ]));
    }

    public function add_product(Request $request)
    {
        DB::table('products')->insert([
            'name' => $request->input('name'),
            'description' => $request->input('description'),
            'price' => $request->input('price'),
        ]);

        $product = DB::table('products')->get()->last();

        return response(json_encode([
            'id' => $product->id,
            'message' => 'Товар добавлен'
        ]));
    }
}
