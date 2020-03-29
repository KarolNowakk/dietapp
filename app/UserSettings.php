<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserSettings extends Model
{
    protected $fillable = [
        'user_id',
        'required_kcal',
        'required_proteins',
        'required_carbs',
        'required_fats',
        'meals_per_day'
    ];

}
