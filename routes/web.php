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
//$this->get('register', 'Auth\RegisterController@showRegistrationForm')->name('register');
//$this->post('register', 'Auth\RegisterController@register');

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
$this->get('/'.config('app.defaultBaseURL.restaurant-related').'/{ethnicId}/{id}', 'RestaurantController@getRelated')->name('religionDetails')->where(['ethnicId' => '[0-9]+', 'id' => '[0-9]+']);


$this->get('/'.config('app.defaultBaseURL.dallas-indian-religion'), 'ReligionController@index')->name('religion');
$this->get('/'.config('app.defaultBaseURL.religion-search').'/{type?}/{city?}/{keyword?}', 'ReligionController@index')->where(['city' => '[A-Za-z-+0-9]+', 'type' => '[A-Za-z-+0-9]+']);
$this->get('/'.config('app.defaultBaseURL.dallas-christian-church').'/{url}', 'ReligionController@getDetails')->name('religionDetails')->where('url', '[A-Za-z-+0-9]+');
$this->get('/'.config('app.defaultBaseURL.dallas-hindu-temple').'/{url}', 'ReligionController@getDetails')->name('religionDetails')->where('url', '[A-Za-z-+0-9]+');
$this->get('/'.config('app.defaultBaseURL.dallas-islan-mosque').'/{url}', 'ReligionController@getDetails')->name('religionDetails')->where('url', '[A-Za-z-+0-9]+');
$this->get('/religion-related/{denomination}/{id}', 'ReligionController@getRelated')->name('religionDetails')->where(['denomination' => '[0-9]+', 'id' => '[0-9]+']);

//$this->get('/'.config('app.defaultBaseURL.dallas-indian-travels'), 'TravelController@index')->name('travels');

$this->post('/suggessionForEdit', 'SuggessionForEditController@suggessionForEdit');
    
Route::get('/getSuggessionNotification', 'SuggessionForEditController@getSuggessionNotification');

$this->get('/'.config('app.defaultBaseURL.dallas-indian-movie'), 'MovieController@index')->name('movie');


Route::group(['middleware' => ['role:Admin']], function () {
    
    Route::get('/admin/dashboard', 'Admin\DashboardController@dashboard')->name('dashboard');
    
    Route::get('/admin/grocery', 'Admin\GroceryController@index')->name('grocery_listing');
    Route::get('/admin/grocery_add/{id?}', 'Admin\GroceryController@addGroceryView')->name('grocery_add')->where(['id' => '[0-9]+']);
    Route::post('/admin/grocery_add', 'Admin\GroceryController@addGrocery');
    Route::get('admin/grocery/delete/{id}', 'Admin\GroceryController@deleteGrocery')->where(['id' => '[0-9]+']);
    
    Route::get('/admin/restaurant', 'Admin\RestaurantController@index')->name('restaurant_listing');
    Route::get('/admin/restaurant_add/{id?}', 'Admin\RestaurantController@addRestaurantView')->name('grocery_add')->where(['id' => '[0-9]+']);
    Route::post('/admin/restaurant_add', 'Admin\RestaurantController@addRestaurant');
    Route::get('admin/restaurant/delete/{id}', 'Admin\RestaurantController@deleteRestaurant')->where(['id' => '[0-9]+']);
    
    Route::get('/admin/religion', 'Admin\ReligionController@index')->name('religion_listing');
    Route::get('/admin/religion_add/{id?}', 'Admin\ReligionController@addReligionView')->name('religion_add')->where(['id' => '[0-9]+']);
    Route::post('/admin/religion_add', 'Admin\ReligionController@addReligion');
    Route::get('admin/religion/delete/{id}', 'Admin\ReligionController@deleteReligion')->where(['id' => '[0-9]+']);
    
    Route::get('/admin/suggession_for_edit', 'Admin\SuggessionForEditController@index')->name('suggession_for_edit');
    Route::get('/admin/suggession_for_edit/{id}', 'Admin\SuggessionForEditController@suggessionView')->name('suggessionView')->where(['id' => '[0-9]+']);
    Route::get('admin/suggession_for_edit/delete/{id}', 'Admin\SuggessionForEditController@deleteSuggession')->where(['id' => '[0-9]+']);

    Route::get('/admin/events', 'Admin\EventsController@index')->name('events');
    Route::get('/admin/event_add/{id?}', 'Admin\EventsController@addEventsView')->name('addEventsView')->where(['id' => '[0-9]+']);
    Route::post('/admin/event_add', 'Admin\EventsController@addEvents');
    Route::get('/admin/event/delete/{id}', 'Admin\EventsController@deleteEvent')->where(['id' => '[0-9]+']);

    Route::get('/admin/events_category', 'Admin\EventsController@eventsCategory')->name('eventsCategory');
    Route::get('/admin/events_category_add/{id?}', 'Admin\EventsController@eventsCategoryView')->name('eventsCategoryView')->where(['id' => '[0-9]+']);
    Route::post('/admin/events_category_add', 'Admin\EventsController@addEventsCategory');
    Route::get('/admin/events_category/delete/{id}', 'Admin\EventsController@deleteCategory')->where(['id' => '[0-9]+']);
    

    Route::get('/admin/theatre', 'Admin\MovieController@theatreListing')->name('theatre_listing');
    Route::get('/admin/theatre_add/{id?}', 'Admin\MovieController@addtheatreView')->name('add_theatre_view')->where(['id' => '[0-9]+']);
    Route::post('/admin/theatre_add', 'Admin\MovieController@addTheatre');
    Route::get('admin/theatre/delete/{id}', 'Admin\MovieController@deleteTheatre')->where(['id' => '[0-9]+']);

    Route::get('/admin/movies', 'Admin\MovieController@MovieListing')->name('movies_listing');
    Route::get('/admin/movie_add/{id?}', 'Admin\MovieController@addMovieView')->name('add_movie_view')->where(['id' => '[0-9]+']);
    Route::post('/admin/movie_add', 'Admin\MovieController@addMovie');
    Route::get('admin/movie/delete/{id}', 'Admin\MovieController@deleteMovie')->where(['id' => '[0-9]+']);

    Route::get('/admin/seo', 'Admin\SEOController@index')->name('seo');
    Route::get('/admin/seo_add/{id?}', 'Admin\SEOController@addSeoView')->name('addSeoView')->where(['id' => '[0-9]+']);
    Route::post('/admin/seo_add', 'Admin\SEOController@addSeo');
    Route::get('/admin/seo/delete/{id}', 'Admin\SEOController@deleteEvent')->where(['id' => '[0-9]+']);
    



    Route::get('/admin/dbBackup', 'Admin\DashboardController@dbBackUp');
    
});



Route::group(['middleware' => ['role:Admin']], function () {
    
    Route::get('admin/sitemap', function() {
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

        //Grocery    
        $grocerys                                  =   DB::select('select url.urlName from grocery left join url on url.groceryId = grocery.id where grocery.is_deleted = 0 and grocery.is_disabled = 0');
        foreach ($grocerys as $grocery) {
            $sitemap->add(URL::to(config('app.defaultBaseURL.grocery-store-details').'/'.$grocery->urlName), $date, '1.0', 'daily');
        }

        //Restaurant
        $restaurants                               =   DB::select('select url.urlName from restaurant left join url on url.restaurantId = restaurant.id where restaurant.is_deleted = 0 and restaurant.is_disabled = 0');
        foreach ($restaurants as $restaurant) {
            $sitemap->add(URL::to(config('app.defaultBaseURL.dallas-indian-restaurant').'/'.$restaurant->urlName), $date, '1.0', 'daily');
        }

        //Religion
        $religions                                 =   DB::select('select url.urlName, religion_type.religionName from religion left join url on url.religionId = religion.id left join religion_type on religion_type.id = religion.religionTypeId where religion.is_deleted = 0 and religion.is_disabled = 0');
        foreach ($religions as $religion) {
            if($religion->religionName == "Christianity"){
                $sitemap->add(URL::to(config('app.defaultBaseURL.dallas-christian-church').'/'.$religion->urlName), $date, '1.0', 'daily');
            }elseif($religion->religionName == "Hinduism"){
                $sitemap->add(URL::to(config('app.defaultBaseURL.dallas-hindu-temple').'/'.$religion->urlName), $date, '1.0', 'daily');
            }elseif($religion->religionName == "Islam"){
                $sitemap->add(URL::to(config('app.defaultBaseURL.dallas-islan-mosque').'/'.$religion->urlName), $date, '1.0', 'daily');
            }
        }             
        
        $sitemap->store('xml', 'sitemap');
        return redirect('/admin/dashboard');
        
    });
});
