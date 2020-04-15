<?php

use Illuminate\Database\Seeder;

class UserSettingsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        App\UserSettings::create([
            'meals_per_day' => 4,
            'user_id' => 1,
            'required_kcal' => 2400,
            'required_proteins' => 250,
            'required_carbs' => 140,
            'required_fats' => 50,
            'count_by' => 'kcal',
            'start' => '12:00',
            'end' => '20:00',
        ]);
    }
}
