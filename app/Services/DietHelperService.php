<?php

namespace App\Services;

use App\Meal;
use App\Recipe;
use App\UserSettings;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class DietHelperService
{
    public static function getMealKcalFactor(Recipe $recipe)
    {
        $recipeKcal = $recipe->nutritions->get('kcal');
        $usersSettings = UserSettings::where('user_id', Auth::id())->first();
        $usersRequiredKcalPerMeal = $usersSettings->required_kcal / $usersSettings->meals_per_day;

        return $usersRequiredKcalPerMeal / $recipeKcal;
    }

    /**
     * @return Recipe
     */
    public static function getRandomNonPrivateRecipe()
    {
        // This function can be written smoothly I suppose
        $id = Recipe::where('is_private', false)->inRandomOrder()->limit(1)->get()[0]->id;

        return Recipe::findOrFail($id);
    }

    public static function getDayDateOfMeal($daysToAdd = 1)
    {
        $recentDayOfDiet = Meal::where('user_id', Auth::id())->orderBy('meal_date', 'desc')->first();
        $mealsPerDay = self::getMealsPerDay();
        if (!$recentDayOfDiet) { // OR $recentDayOfDiet->meal_number == $mealsPerDay
            return Carbon::today()->addDays($daysToAdd)->format('Y-m-d');
        }

        return Carbon::create($recentDayOfDiet->meal_date)->addDays(1)->format('Y-m-d');
    }

    public static function getMealsPerDay()
    {
        $usersSettings = UserSettings::where('user_id', Auth::id())->first();

        return $usersSettings->meals_per_day;
    }

    public static function getMealHour($meals_per_day, $currentMeal)
    {
        // TODO: add start, end columns to user_settings table
        $start = '08:00';
        $end = '21:00';
        $start = Carbon::create($start);
        --$currentMeal;
        if (0 == $currentMeal) {
            return $start->format('H:i');
        }
        $diffrence = round((strtotime($end) - strtotime($start)) / 60 / ($meals_per_day - 1) * $currentMeal, -1);

        return $start->addMinutes($diffrence)->format('H:i');
    }
}
