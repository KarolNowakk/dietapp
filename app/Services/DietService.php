<?php

namespace App\Services;

use App\Meal;
use App\Recipe;
use App\Services\DietHelperService;
use Illuminate\Support\Facades\Auth;

class DietService
{
    public function generate()
    {
        $recipe = DietHelperService::getRandomNonPrivateRecipe();

        return [
            //'recipe' => $recipe,
            DietHelperService::getDayDateOfMeal(),
        ];
    }

    public function generateOneDay()
    {
        $dayDate = DietHelperService::getDayDateOfMeal();
        $mealsPerDay = DietHelperService::getMealsPerDay();
        $meals = [];
        //return $dayDate;
        for ($i = 1; $i <= $mealsPerDay; $i++) {
            $meal_hour = DietHelperService::getMealHour($mealsPerDay, $i);
            $newMeal = Meal::updateOrCreate($this->generateOneMeal($i, $dayDate, $meal_hour));
            if ($newMeal) {
                array_push($meals, $this->generateOneMeal($i, $dayDate, $meal_hour));
            }
        }
        return $meals;
    }

    public function generateOneMeal($mealNumber, $dayDate, $meal_hour)
    {
        $recipe = DietHelperService::getRandomNonPrivateRecipe();
        $factor = DietHelperService::getMealKcalFactor($recipe);
        return [
            'user_id' => Auth::id(),
            'recipe_id' => $recipe->id,
            'meal_date' => $dayDate,
            'meal_number' => $mealNumber,
            'meal_hour' => $meal_hour,
            'factor' => $factor,
        ];
    }
}
