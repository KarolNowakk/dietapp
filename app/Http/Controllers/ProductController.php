<?php

namespace App\Http\Controllers;

use App\Product;
use Illuminate\Http\Request;
use App\Http\Resources\Product as ProductResource;
use Illuminate\Support\Facades\Auth;


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
        $data = $request->validate([
            'name' => ['string', 'max:255', 'required'],
            'kcal' => ['numeric', 'between:0,1000', 'required'],
            'proteins' => ['numeric', 'between:0,100', 'required'],
            'carbs' => ['numeric', 'between:0,100', 'required'],
            'fats' => ['numeric', 'between:0,100', 'required'],
            'saturated_fats' => ['numeric', 'between:0,100', 'required'],
            'polysaturated_fats' => ['numeric', 'between:0,100', 'required'],
            'monosaturated_fats' => ['numeric', 'between:0,100', 'required'],
            'is_private' => ['boolean']
        ]);

        if($data['is_private']){
            $data['user_id'] = Auth::id();
        }

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
