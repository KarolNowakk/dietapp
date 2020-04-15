<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/login', 'AuthController@login');
Route::middleware('auth:api')->post('/logout', 'AuthController@logout');
Route::post('/register', 'AuthController@register');

Route::group(['middleware' => ['auth:api']], function () {
    Route::get('/products', 'ProductController@index');
    Route::match(['post', 'put'], '/product/{product?}', 'ProductController@store');
    Route::delete('/product/{product}', 'ProductController@destroy');
    Route::get('/product/{product}', 'ProductController@show');
    Route::post('/product/search/product', 'ProductController@search');

    Route::get('/recipes', 'RecipeController@index');
    Route::match(['post', 'put'], '/recipe/{recipe?}', 'RecipeController@store');
    Route::delete('/recipe/{recipe}', 'RecipeController@destroy');
    Route::get('/recipe/{recipe}', 'RecipeController@show');
    Route::post('/recipe/search/recipe', 'RecipeController@search');

    Route::match(['post', 'put'], '/user', 'UserController@store');
    Route::get('/user', 'UserController@show');
    Route::post('/user/generate', 'UserController@generate');
    Route::get('/user/not_wanted_substances', 'UserController@showSubstances');
    Route::get('/user/shopping_list/{from}_{to}', 'UserController@getShoppingList');

    Route::get('/meals/{date}', 'MealController@index');
    Route::get('/meal/{date}/{number}', 'MealController@show');
    Route::put('/meal/{meal}', 'MealController@update');
    Route::post('/meal', 'MealController@store');
});
