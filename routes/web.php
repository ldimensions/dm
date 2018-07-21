<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/', 'HomeController@index')->name('home');

$this->get('/dallas-indian-grocery-store', 'GroceryController@index')->name('grocery');
$this->get('/dallas-indian-grocery-store/{type}/{city}', 'GroceryController@index');
$this->get('/dallas-indian-grocery-store/{url}', 'GroceryController@getDetails')->name('grocery_details')->where('url', '[A-Za-z-+]+');

$this->get('/'.config('app.defaultBaseURL.dallas-malayali-church'), 'ReligionController@index')->name('religion');
$this->get('/'.config('app.defaultBaseURL.dallas-malayali-church').'/{url}', 'ReligionController@getDetails')->name('religionDetails')->where('url', '[A-Za-z-+]+');
$this->get('/'.config('app.defaultBaseURL.dallas-malayali-temple').'/{url}', 'ReligionController@getDetails')->name('religionDetails')->where('url', '[A-Za-z-+]+');
$this->get('/'.config('app.defaultBaseURL.dallas-malayali-mosque').'/{url}', 'ReligionController@getDetails')->name('religionDetails')->where('url', '[A-Za-z-+]+');

$this->get('/religion-related/{denomination}/{id}', 'ReligionController@getRelated')->name('religionDetails')->where('url', '[A-Za-z-+]+');

$this->get('/dallas-malayali-restaurant', 'RestaurantController@index')->name('restaurant');
$this->get('/dallas-malayali-restaurant/{url}', 'RestaurantController@getDetails')->name('restaurantDetails')->where('url', '[A-Za-z-+]+');



