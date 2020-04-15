<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $guarded = [];

    public function recipes()
    {
        return $this->belongsToMany(App\Recipe::class);
    }

    public function meals()
    {
        return $this->belongsToMany(App\Product::class);
    }

    public function substances()
    {
        return $this->belongsToMany(Substance::class, 'product_substance');
    }
}
