<?php

namespace App\Http\Controllers;

use App\Exceptions\MealNotFoundException;
use App\Http\Resources\Meal as MealResource;
use App\Meal;
use App\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MealController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param string $date
     *
     * @return MealResource
     */
    public function index($date)
    {
        $meals = Auth::user()->meals()->where('meal_date', $date)->get();

        if (blank($meals)) {
            throw new MealNotFoundException('Meals with passed date not found.');
        }

        return MealResource::collection($meals);
    }

    /**
     * Display the specified resource.
     *
     * @param string $date
     * @param mixed  $number
     *
     * @return MealResource
     */
    public function show($date, $number)
    {
        $meal = Auth::user()->meals()->where('meal_date', $date)->where('meal_number', $number)->first();

        //return $meal->nutritions;
        if (blank($meal)) {
            throw new MealNotFoundException('Meal with passed date not found.', 1);
        }

        return new MealResource($meal);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param null       $recipe
     * @param null|mixed $meal
     *
     * @return MealResource
     */
    public function store(Request $request, $meal = null)
    {
        $data = $request->validate([
            'ingredients.*.product_id' => ['numeric', 'required'],
            'ingredients.*.not_include' => ['boolean', 'required'],
            'ingredients.*.amount' => ['numeric', 'between:0,1000'],
        ]);

        if (!Meal::find($meal)->user_id == Auth::id()) {
            return response()->json('You are not allowed to edit this meal');
        }

        $newMeal = Meal::updateOrCreate(['id' => $meal], $data);

        if ($newMeal) {
            $this->attachProducts($newMeal, $data);
        }

        return new MealResource($newMeal);
    }

    /**
     * Attach meal to products.
     *
     * @param array $data
     *
     * @return void
     */
    protected function attachProducts(Meal $meal, $data)
    {
        $meal->products()->detach();
        collect($data['ingredients'])->each(function ($ingredient) use ($meal) {
            $product = Product::findOrFail($ingredient['product_id']);

            $meal->products()->attach($product, [
                'amount' => $ingredient['amount'],
                'not_include' => $ingredient['not_include'],
            ]);
        });
    }
}
