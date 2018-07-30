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
//$this->get('/dallas-indian-grocery-store/{type}/{city}', 'GroceryController@index');
// $this->get('/'.config('app.defaultBaseURL.dallas-indian-grocery-store').'/{city?}', 'GroceryController@search')->name('indian_grocery')->where('city', '[A-Za-z-+0-9]+');
// $this->get('/'.config('app.defaultBaseURL.dallas-kerala-grocery-store').'/{city?}', 'GroceryController@search')->name('indian_grocery')->where('city', '[A-Za-z-+0-9]+');
// $this->get('/'.config('app.defaultBaseURL.dallas-tamil-grocery-store').'/{city?}', 'GroceryController@search')->name('indian_grocery')->where('city', '[A-Za-z-+0-9]+');

$this->get('/grocery-search/{type?}/{city?}/{keyword?}', 'GroceryController@index')->where(['city' => '[A-Za-z-+0-9]+', 'type' => '[A-Za-z-+0-9]+']);


// $this->get('/dallas-indian-grocery-store/{city}', 'GroceryController@search')->name('indian_grocery')->where('city', '[A-Za-z-+0-9]+');
// $this->get('/dallas-kerala-grocery-store/{city}', 'GroceryController@search')->name('kerala_grocery')->where('city', '[A-Za-z-+0-9]+');
// $this->get('/dallas-tamil-grocery-store/{city}', 'GroceryController@search')->name('tamil_grocery')->where('city', '[A-Za-z-+0-9]+');
$this->get('/dallas-grocery-store/{url}', 'GroceryController@getDetails')->name('grocery_details')->where('url', '[A-Za-z-+0-9]+');
$this->get('/grocery-related/{ethnicId}/{id}', 'GroceryController@getRelated')->name('religionDetails')->where(['id' => '[0-9]+', 'ethnicId' => '[0-9]+']);


$this->get('/dallas-indian-restaurant', 'RestaurantController@index')->name('restaurant');
$this->get('/dallas-indian-restaurant/{url}', 'RestaurantController@getDetails')->name('restaurant_details')->where('url', '[A-Za-z-+0-9]+');

$this->get('/restaurant-related/{ethnicId}/{id}', 'RestaurantController@getRelated')->name('religionDetails')->where(['ethnicId' => '[A-Za-z-+0-9]+', 'id' => '[0-9]+']);


$this->get('/'.config('app.defaultBaseURL.dallas-malayali-church'), 'ReligionController@index')->name('religion');
$this->get('/'.config('app.defaultBaseURL.dallas-malayali-church').'/{url}', 'ReligionController@getDetails')->name('religionDetails')->where('url', '[A-Za-z-+]+');
$this->get('/'.config('app.defaultBaseURL.dallas-malayali-temple').'/{url}', 'ReligionController@getDetails')->name('religionDetails')->where('url', '[A-Za-z-+]+');
$this->get('/'.config('app.defaultBaseURL.dallas-malayali-mosque').'/{url}', 'ReligionController@getDetails')->name('religionDetails')->where('url', '[A-Za-z-+]+');

$this->get('/religion-related/{denomination}/{id}', 'ReligionController@getRelated')->name('religionDetails')->where(['denomination' => '[A-Za-z-+0-9]+', 'id' => '[0-9]+']);

//$this->get('/dallas-malayali-restaurant', 'RestaurantController@index')->name('restaurant');
//$this->get('/dallas-malayali-restaurant/{url}', 'RestaurantController@getDetails')->name('restaurantDetails')->where('url', '[A-Za-z-+]+');



