<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/products',                         ['uses' => 'App\Http\Controllers\ProductController@showAll']);
Route::get('/products/{arg}',                   ['uses' => 'App\Http\Controllers\ProductController@show']);
Route::get('/products/category/{arg}',          ['uses' => 'App\Http\Controllers\ProductController@showByCategoryIdOrName']);
Route::get('/products/price/{min}/{max}',       ['uses' => 'App\Http\Controllers\ProductController@showByPrice']);

