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

$this->get('/'.config('app.defaultBaseURL.dallas-indian-grocery-store'), 'GroceryController@index')->name('grocery');
$this->get('/'.config('app.defaultBaseURL.grocery-search').'/{type?}/{city?}/{keyword?}', 'GroceryController@index')->where(['city' => '[A-Za-z-+0-9]+', 'type' => '[A-Za-z-+0-9]+']);
$this->get('/'.config('app.defaultBaseURL.grocery-store-details').'/{url}', 'GroceryController@getDetails')->name('grocery_details')->where('url', '[A-Za-z-+0-9]+');
$this->get('/'.config('app.defaultBaseURL.grocery-related').'/{ethnicId}/{id}', 'GroceryController@getRelated')->name('religionDetails')->where(['id' => '[0-9]+', 'ethnicId' => '[0-9]+']);

$this->get('/'.config('app.defaultBaseURL.dallas-indian-restaurant'), 'RestaurantController@index')->name('restaurant');
$this->get('/'.config('app.defaultBaseURL.restaurant-search').'/{type?}/{city?}/{keyword?}', 'RestaurantController@index')->where(['city' => '[A-Za-z-+0-9]+', 'type' => '[A-Za-z-+0-9]+']);
$this->get('/'.config('app.defaultBaseURL.dallas-indian-restaurant').'/{url}', 'RestaurantController@getDetails')->name('restaurant_details')->where('url', '[A-Za-z-+0-9]+');
$this->get('/'.config('app.defaultBaseURL.restaurant-related').'/{ethnicId}/{id}', 'RestaurantController@getRelated')->name('religionDetails')->where(['ethnicId' => '[A-Za-z-+0-9]+', 'id' => '[0-9]+']);


$this->get('/'.config('app.defaultBaseURL.dallas-indian-religion'), 'ReligionController@index')->name('religion');
$this->get('/'.config('app.defaultBaseURL.religion-search').'/{type?}/{city?}/{keyword?}', 'ReligionController@index')->where(['city' => '[A-Za-z-+0-9]+', 'type' => '[A-Za-z-+0-9]+']);
$this->get('/'.config('app.defaultBaseURL.dallas-malayali-church').'/{url}', 'ReligionController@getDetails')->name('religionDetails')->where('url', '[A-Za-z-+]+');
$this->get('/'.config('app.defaultBaseURL.dallas-malayali-temple').'/{url}', 'ReligionController@getDetails')->name('religionDetails')->where('url', '[A-Za-z-+]+');
$this->get('/'.config('app.defaultBaseURL.dallas-malayali-mosque').'/{url}', 'ReligionController@getDetails')->name('religionDetails')->where('url', '[A-Za-z-+]+');
$this->get('/religion-related/{denomination}/{id}', 'ReligionController@getRelated')->name('religionDetails')->where(['denomination' => '[A-Za-z-+0-9]+', 'id' => '[0-9]+']);

$this->get('/'.config('app.defaultBaseURL.dallas-indian-travels'), 'TravelController@index')->name('travels');

//$this->get('/dallas-malayali-restaurant', 'RestaurantController@index')->name('restaurant');
//$this->get('/dallas-malayali-restaurant/{url}', 'RestaurantController@getDetails')->name('restaurantDetails')->where('url', '[A-Za-z-+]+');



