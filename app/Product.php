<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $guarded = [];

    public function recipes()
    {
        $this->belongsToMany(App\Recipe::class);
    }
}
