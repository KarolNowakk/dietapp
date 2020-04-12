<?php

namespace App\Services;

use App\Meal;
use Illuminate\Support\Facades\Auth;

class DietService
{
    protected $userSettings;

    public function __construct()
    {
        $this->setUserSettings();
    }

    public function generateDays($days)
    {
        $createdDietDays = [];
        for ($i = 0; $i < $days; ++$i) {
            array_push($createdDietDays, $this->generateOneDay());
        }

        return $createdDietDays;
    }

    public function generateOneDay()
    {
        $dayDate = DietHelperService::getDayDateOfMeal();
        $mealsPerDay = $this->userSettings->meals_per_day;
        $meals = collect();
        $meals_hours = DietHelperService::getMealHour($mealsPerDay, $this->userSettings->start, $this->userSettings->end);
        for ($i = 1; $i <= $mealsPerDay; ++$i) {
            $newMeal = Meal::create($this->generateOneMeal($i, $dayDate, $meals_hours[$i - 1]));
            if ($newMeal) {
                $meals->push($newMeal);
            }
        }

        return $meals;
    }

    public function generateOneMeal($mealNumber, $dayDate, $meal_hour)
    {
        $recipe = DietHelperService::getRandomNonPrivateRecipe();
        $factor = DietHelperService::getMealKcalFactor($recipe);

        return [
            'user_id' => $this->userSettings->id,
            'recipe_id' => $recipe->id,
            'meal_date' => $dayDate,
            'meal_number' => $mealNumber,
            'meal_hour' => $meal_hour,
            'factor' => $factor,
        ];
    }

    protected function setUserSettings()
    {
        $this->userSettings = Auth::user()->settings;
    }
}
