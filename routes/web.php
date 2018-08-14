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

// Route::get('/', function () {
//     return view('welcome');
// });

Auth::routes();

Route::get('/', 'HomeController@index')->name('home');

// Authentication Routes...
$this->get('login', 'Auth\LoginController@showLoginForm')->name('login');
$this->post('login', 'Auth\LoginController@login');
$this->post('logout', 'Auth\LoginController@logout')->name('logout');

// Registration Routes...
$this->get('register', 'Auth\RegisterController@showRegistrationForm')->name('register');
$this->post('register', 'Auth\RegisterController@register');

// Password Reset Routes...
// $this->get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm');
// $this->post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail');
// $this->get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm');
// $this->post('password/reset', 'Auth\ResetPasswordController@reset');


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
$this->get('/'.config('app.defaultBaseURL.dallas-christian-church').'/{url}', 'ReligionController@getDetails')->name('religionDetails')->where('url', '[A-Za-z-+]+');
$this->get('/'.config('app.defaultBaseURL.dallas-hindu-temple').'/{url}', 'ReligionController@getDetails')->name('religionDetails')->where('url', '[A-Za-z-+]+');
$this->get('/'.config('app.defaultBaseURL.dallas-islan-mosque').'/{url}', 'ReligionController@getDetails')->name('religionDetails')->where('url', '[A-Za-z-+]+');
$this->get('/religion-related/{denomination}/{id}', 'ReligionController@getRelated')->name('religionDetails')->where(['denomination' => '[A-Za-z-+0-9]+', 'id' => '[0-9]+']);

//$this->get('/'.config('app.defaultBaseURL.dallas-indian-travels'), 'TravelController@index')->name('travels');

$this->post('/suggessionForEdit', 'CommonController@suggessionForEdit');

Route::get('sitemap', function() {
    $date                                   =   date(DateTime::ISO8601);
    
    $sitemap                                =   App::make("sitemap");
    //Home Page
    $sitemap->add(URL::to('/'), $date, '1.0', 'daily');
    
    //Grocery
    $sitemap->add(URL::to(config('app.defaultBaseURL.dallas-indian-grocery-store')), $date, '1.0', 'daily');

    //Restaurant
    $sitemap->add(URL::to(config('app.defaultBaseURL.dallas-indian-restaurant')), $date, '1.0', 'daily');

    //Religion    
    $sitemap->add(URL::to(config('app.defaultBaseURL.dallas-indian-religion')), $date, '1.0', 'daily');
    
        
    $citys                                  =   DB::select('select cityId, value from city ORDER BY city ASC'); 

    foreach ($citys as $city) {
        //Grocery    
        $sitemap->add(URL::to(config('app.defaultBaseURL.grocery-search').'/'.config('app.defaultBaseURL.dallas-indian-grocery-store').'-2/'.config('app.defaultBaseURL.indian-grocery-store-in').$city->value.'-'.$city->cityId), $date, '1.0', 'daily');
        $sitemap->add(URL::to(config('app.defaultBaseURL.grocery-search').'/'.config('app.defaultBaseURL.dallas-kerala-grocery-store').'-1/'.config('app.defaultBaseURL.kerala-grocery-store-in').$city->value.'-'.$city->cityId), $date, '1.0', 'daily');
        $sitemap->add(URL::to(config('app.defaultBaseURL.grocery-search').'/'.config('app.defaultBaseURL.dallas-tamil-grocery-store').'-3/'.config('app.defaultBaseURL.tamil-grocery-in').$city->value.'-'.$city->cityId), $date, '1.0', 'daily');        
    
        //Restaurant
        $sitemap->add(URL::to(config('app.defaultBaseURL.restaurant-search').'/'.config('app.defaultBaseURL.dallas-indian-restaurant').'-2/'.config('app.defaultBaseURL.indian-restaurant-in').$city->value.'-'.$city->cityId), $date, '1.0', 'daily');
        $sitemap->add(URL::to(config('app.defaultBaseURL.restaurant-search').'/'.config('app.defaultBaseURL.dallas-kerala-restaurant').'-1/'.config('app.defaultBaseURL.kerala-restaurant-in').$city->value.'-'.$city->cityId), $date, '1.0', 'daily');
        $sitemap->add(URL::to(config('app.defaultBaseURL.restaurant-search').'/'.config('app.defaultBaseURL.dallas-tamil-restaurant').'-3/'.config('app.defaultBaseURL.tamil-restaurant-in').$city->value.'-'.$city->cityId), $date, '1.0', 'daily');        

        //Religion
        $sitemap->add(URL::to(config('app.defaultBaseURL.religion-search').'/'.config('app.defaultBaseURL.dallas-christian-church').'-1/'.config('app.defaultBaseURL.christian-church-in').$city->value.'-'.$city->cityId), $date, '1.0', 'daily');
        $sitemap->add(URL::to(config('app.defaultBaseURL.religion-search').'/'.config('app.defaultBaseURL.dallas-hindu-temple').'-2/'.config('app.defaultBaseURL.hindu-temple-in').$city->value.'-'.$city->cityId), $date, '1.0', 'daily');
        $sitemap->add(URL::to(config('app.defaultBaseURL.religion-search').'/'.config('app.defaultBaseURL.dallas-islan-mosque').'-5/'.config('app.defaultBaseURL.islam-mosque-in').$city->value.'-'.$city->cityId), $date, '1.0', 'daily');                
    }
    
    $sitemap->store('xml', 'sitemap');
    
});
    
Route::get('/dashboard', 'HomeController@dashboard')->name('dashboard');
