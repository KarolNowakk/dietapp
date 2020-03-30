<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Meal;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\Meal as MealResource;
use App\Exceptions\MealNotFoundException;

class MealController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param string $date
     * @return MealResource
     */
    public function index($date)
    {
        $meals = Meal::where('user_id', Auth::id())->where('meal_date', $date)->get();
        //return gettype($meals);
        if (blank($meals)) {
            throw new MealNotFoundException("Meals with passed date not found.");
        } else {
            return MealResource::collection($meals);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param string $date
     * @return MealResource
     */
    public function show($date)
    {
        $meal = Meal::where('user_id', Auth::id())->where('meal_date', $date)->first();
        if ($meal) {
            return new MealResource($meal);
        } else {
            throw new MealNotFoundException("Meal with passed date not found.", 1);
        }

    }
}
