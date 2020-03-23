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
     * @param \Illuminate\Http\Request $request
     * @param null $product
     * @return ProductResource
     */
    public function store(Request $request, $product = null)
    {
        /* TODO: validate everything */
        $data = $request->validate([
            'name' => '',
            'proteins' => '',
            'carbs' => '',
            'fats' => '',
            'saturated_fats' => '',
            'polysaturated_fats' => '',
            'monosaturated_fats' => '',
            'is_private' => '',
            'user_id' => '',
        ]);

        /*
         * TODO: Set user_id from auth not from request
         * $data['user_id'] = Auth::user();
         * */

        $prod = Product::updateOrCreate(['id' => $product], $data);


        if ($prod) {
            return new ProductResource($prod);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param Product $product
     * @return ProductResource
     */
    public function show(Product $product)
    {
        return new ProductResource($product);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Product $product
     * @return ProductResource
     * @throws \Exception
     */
    public function destroy(Product $product)
    {
        if ($product->delete()) {
            return new ProductResource($product);
        }
    }
}
