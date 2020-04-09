<?php

namespace App\Http\Controllers;

use App\Exceptions\MealNotFoundException;
use App\Http\Resources\Meal as MealResource;
use App\Meal;
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
        $meals = Meal::where('user_id', Auth::id())->where('meal_date', $date)->get();

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
        $meal = Meal::where('user_id', Auth::id())->where('meal_date', $date)->where('meal_number', $number)->first();

        if ($meal) {
            return new MealResource($meal);
        }

        throw new MealNotFoundException('Meal with passed date not found.', 1);
    }
}
