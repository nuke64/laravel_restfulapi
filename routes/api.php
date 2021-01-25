<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


// products
Route::get('/products',                         ['uses' => 'App\Http\Controllers\ProductController@index']);
Route::get('/products/{arg}',                   ['uses' => 'App\Http\Controllers\ProductController@show']);
Route::get('/products/category/{arg}',          ['uses' => 'App\Http\Controllers\ProductController@showByCategoryIdOrName']);
Route::get('/products/price/{min?}/{max?}',     ['uses' => 'App\Http\Controllers\ProductController@showByPrice']);

Route::post('/products',                        ['uses' => 'App\Http\Controllers\ProductController@store']);
Route::put('/products/{id}',                    ['uses' => 'App\Http\Controllers\ProductController@update']);
Route::delete('/products/{id}',                 ['uses' => 'App\Http\Controllers\ProductController@delete']);

// category
Route::get('/category',                         ['uses' => 'App\Http\Controllers\CategoryController@index']);
Route::post('/category',                        ['uses' => 'App\Http\Controllers\CategoryController@store']);
Route::put('/category/{id}',                    ['uses' => 'App\Http\Controllers\CategoryController@update']);
Route::delete('/category/{id}',                 ['uses' => 'App\Http\Controllers\CategoryController@delete']);

