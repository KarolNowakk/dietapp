<?php

namespace App\Http\Controllers;

use App\Http\Resources\Product as ProductResource;
use App\Product;
use Illuminate\Http\Request;
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
     * @param null $product
     *
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
            'is_private' => ['boolean'],
        ]);

        if ($data['is_private']) {
            $data['user_id'] = Auth::id();
        }

        $prod = Product::updateOrCreate(['id' => $product], $data);

        if ($prod) {
            return new ProductResource($prod);
        }

        return response()->json("It didn't happend, sorry", 401);
    }

    /**
     * Display the specified resource.
     *
     * @return ProductResource
     */
    public function show(Product $product)
    {
        return new ProductResource($product);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @throws \Exception
     *
     * @return ProductResource
     */
    public function destroy(Product $product)
    {
        if ($product->delete()) {
            return new ProductResource($product);
        }
    }

    public function search(Request $request)
    {
        $phrase = $request->validate([
            'phrase' => 'string',
        ]);
        $phrase = $phrase['phrase'];
        $products = Product::where('name', 'like', "%{$phrase}%")->paginate(10); //where('name', 'like', "%{$q}%")->paginate(10);

        if (!$products) {
            return response()->json('No product found', 404);
        }

        return ProductResource::collection($products);
    }
}
