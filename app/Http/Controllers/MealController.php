<?php

namespace App\Http\Controllers;

use App\Exceptions\MealNotFoundException;
use App\Http\Resources\Meal as MealResource;
use App\Meal;
use App\Product;
use App\Recipe;
use App\Services\DietHelperService;
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
        $meals = Auth::user()->meals->where('meal_date', $date)->get();

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
        $meal = Auth::user()->meals->where('meal_date', $date)->where('meal_number', $number)->first();

        if (blank($meal)) {
            throw new MealNotFoundException('Meal with passed date not found.', 1);
        }

        return new MealResource($meal);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param int   $id
     * @param mixed $meal
     *
     * @return MealResource
     */
    public function update(Request $request, $meal)
    {
        $data = $request->validate([
            'recipe_id' => ['numeric'],
            'ingredients.*.product_id' => ['numeric'],
            'ingredients.*.not_include' => ['boolean'],
            'ingredients.*.amount' => ['numeric', 'between:0,1000'],
        ]);

        if (!Meal::find($meal)->user_id == Auth::id()) {
            return response()->json('You are not allowed to edit this meal');
        }

        if (isset($data['recipe_id']) and $data['recipe_id'] != Meal::find($meal)->recipe_id) {
            $recipe = Recipe::findOrFail($data['recipe_id']);
            $data['factor'] = DietHelperService::getMealKcalFactor($recipe);
        }

        $newMeal = Meal::updateOrCreate(['id' => $meal], $data);

        if ($newMeal and array_key_exists('ingredients', $data)) {
            $this->attachProducts($newMeal, $data);
        }

        return new MealResource($newMeal);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'recipe_id' => ['numeric'],
            'meal_date' => ['date_format:Y-m-d', 'required'],
            'meal_number' => ['numeric', 'between:1,15', 'required'],
            'meal_hour' => ['date_format:H:i', 'required'],
            'ingredients.*.product_id' => ['numeric'],
            'ingredients.*.not_include' => ['boolean'],
            'ingredients.*.amount' => ['numeric', 'between:0,1000'],
        ]);
        $data['user_id'] = Auth::id();

        if (array_key_exists('recipe_id', $data)) {
            $recipe = Recipe::findOrFail($data['recipe_id']);
            $data['factor'] = DietHelperService::getMealKcalFactor($recipe);
        }

        $newMeal = Meal::create($data);

        if ($newMeal and array_key_exists('ingredients', $data)) {
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
