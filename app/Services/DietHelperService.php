<?php

namespace App\Services;

use App\Meal;
use App\Recipe;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class DietHelperService
{
    /**
     * Returns meal factor for kcal.
     *
     * @param mixed $usersRequiredKcalPerMeal
     *
     * @return float
     */
    public static function getMealKcalFactor(Recipe $recipe, $usersRequiredKcalPerMeal = null)
    {
        $recipeKcal = $recipe->nutritions->get('kcal');
        if (!isset($usersRequiredKcalPerMeal)) {
            $usersSettings = Auth::user()->settings;
            $usersRequiredKcalPerMeal = $usersSettings->required_kcal / $usersSettings->meals_per_day;
        }

        return $usersRequiredKcalPerMeal / $recipeKcal;
    }

    /**
     * Returns random recipe.
     *
     * @return Recipe
     */
    public static function getRandomNonPrivateRecipe()
    {
        // This function can be written smoothly I suppose
        $id = Recipe::where('is_private', false)->inRandomOrder()->limit(1)->get()[0]->id;

        return Recipe::findOrFail($id);
    }

    /**
     * Returns date of next meal.
     *
     * @param int $daysToAdd
     *
     * @return Carbon
     */
    public static function getDayDateOfMeal($daysToAdd = 1)
    {
        $recentDayOfDiet = Meal::where('user_id', Auth::id())->orderBy('meal_date', 'desc')->first();
        if (!$recentDayOfDiet) {
            return Carbon::today()->addDays($daysToAdd)->format('Y-m-d');
        }

        return Carbon::create($recentDayOfDiet->meal_date)->addDays(1)->format('Y-m-d');
    }

    /**
     * Returns hour of passed meal.
     *
     * @param int   $meals_per_day, $currentMeal
     * @param mixed $currentMeal
     * @param mixed $start
     * @param mixed $end
     *
     * @return Carbon
     */
    public static function getMealHour($meals_per_day, $start, $end)
    {
        $start = Carbon::create($start);
        $hours = collect();
        for ($i = 0; $i < $meals_per_day; ++$i) {
            if ($i == 0) {
                $hours->push($start->format('H:i'));
            } else {
                $diffrence = round((strtotime($end) - strtotime($start)) / 60 / ($meals_per_day - 1) * $i, -1);
                $hours->push($start->addMinutes($diffrence)->format('H:i'));
            }
        }

        return $hours;
    }
}
