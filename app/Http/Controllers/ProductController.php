<?php

namespace App\Http\Controllers;

use App\Product;
use Illuminate\Http\Request;
//use App\Http\Request;
use App\Http\Resources\Product as ProductResource;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $products = Product::all();
        return ProductResource::collection($products);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $product = $request->isMethod('put') ? Product::findOrFail
        ($request->product_id) : new Product;

        $product->id = $request->input('product_id');
        $product->name = $request->input('name');
        $product->proteins = $request->input('proteins');
        $product->carbs = $request->input('carbs');
        $product->fats = $request->input('fats');
        $product->saturated_fats = $request->input('saturated_fats');
        $product->polysaturated_fats = $request->input('polysaturated_fats');
        $product->monosaturated_fats = $request->input('monosaturated_fats');
        $product->is_private = $request->input('is_private');
        $product->user_id = $request->input('user_id');

        if($product->save()){
            return new ProductResource($product);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $product = Product::findOrFail($id);
        ProductResource::withoutWrapping();
        return new ProductResource($product);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $product = Product::findOrFail($id);

        if($product->delete()){
            return new ProductResource($product);
        }

    }
}
