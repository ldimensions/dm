<?php

return [

    'siteId' => env('SITE_ID', 1),   // 1 - Dallas
    'siteLocatioin' => env('SITE_LOCATION', 'Dallas'),   // 1 - Dallas
    'fromEmail' =>  'lindosebastian@gmail.com',
    'toEmail' =>  'lindosebastian@gmail.com',
    'tweetHashTags' => ['DallasIndianPortal'],
    'defaultSEO' => [
        '1' => [    // Dallas
            'SEOMetaTitle'                        => "Dallas Indian Portal | Dallas Indian Community - dallasindianportal.com",
            'SEOMetaDesc'                         =>  'Find indian grocery shops, indian restarunt, indian christian churches, indian hindu temple, indian muslim mosque, indian movies, indian events, indain classifieds ',
            'SEOMetaKeywords'                     =>  [
                                                         'Dallas indian portal',
                                                         'Dallas indian community', 
                                                         'Dallas indian grocery', 
                                                         'Dallas indian restaurants',
                                                         'Dallas indian events',
                                                         'Dallas indian christian church',
                                                         'Dallas indian hindu temple',
                                                         'Dallas indian movies',
                                                         'Dallas indian muslim mosque',
                                                         'Dallas indian travels'
                                                      ],
            'SEOMetaPublishedTime'                =>  '',
            'OpenGraphTitle'                      =>  'Dallas Indian Portal | Dallas Indian Community - dallasindianportal.com',
            'OpenGraphDesc'                       =>  'Find indian grocery shops, indian restarunt, indian christian churches, indian hindu temple, indian muslim mosque, indian movies, indian events, indain classifieds ',
            'OpenGraphUrl'                        =>  'dallasindianportal.com',
            'OpenGraphPropertyType'               =>  '',
            'OpenGraphPropertyLocale'             =>  '',
            'OpenGraphPropertyLocaleAlternate'    =>  '',
            'OpenGraph'                           =>  '',    
        ]
    ],
    
    'movieLanguage' => [
        '1'                        => "Hindi",
        '2'                        => "Malayalam",
        '3'                        => "Tamil",
        '4'                        => "Telugu",
        '5'                        => "Kannada",
        '6'                        => "Punjabi",
        '7'                        => "Urdu",
        '8'                        => "Bengali",
        '9'                        => "Gujarathi",
        '10'                       => "Marathi",        
    ],   

    'defaultBaseURL' => [
        'dallas-indian-grocery-store'           => 'dallas-indian-grocery-store',
        'dallas-kerala-grocery-store'           => 'dallas-kerala-grocery-store',
        'dallas-tamil-grocery-store'            => 'dallas-tamil-grocery-store',
        'indian-grocery-store'                  => 'indian-grocery-store',
        'grocery-store-details'                 => 'dallas-indian-grocery-store', 
        'grocery-search'                        => 'grocery-search',
        'grocery-related'                       => 'grocery-related',
        'indian-grocery-store-in'               => 'indian-grocery-store-in-',
        'kerala-grocery-store-in'               => 'kerala-grocery-store-in-',
        'tamil-grocery-in'                      => 'tamil-grocery-in-',        

        'dallas-indian-restaurant'              => 'dallas-indian-restaurant',
        'dallas-kerala-restaurant'              => 'dallas-kerala-restaurant',
        'dallas-tamil-restaurant'               => 'dallas-tamil-restaurant',
        'restaurant-related'                    => 'restaurant-related',
        'restaurant-search'                     => 'restaurant-search',
        'indian-restaurant-in'                  => 'indian-restaurant-in-',
        'kerala-restaurant-in'                  => 'kerala-restaurant-in-',
        'tamil-restaurant-in'                   => 'tamil-restaurant-in-',

        'dallas-indian-religion'                => 'dallas-indian-religion',
        'religion-search'                       => 'religion-search',
        'dallas-christian-church'               => 'dallas-christian-church',
        'dallas-hindu-temple'                   => 'dallas-hindu-temple',
        'dallas-islan-mosque'                   => 'dallas-islan-mosque',
        'christian-church-in'                   => 'christian-church-in-',
        'hindu-temple-in'                       => 'hindu-temple-in-',
        'islam-mosque-in'                       => 'islam-mosque-in-',        
        
        'indian-movie'                          => 'dallas-indian-movies',
        'indian-theatre'                        => 'dallas-theatre',
        'movie-search'                          => 'movie-search',

        'events'                                => 'dallas-indian-events',
        'event-search'                          => 'event-search',

        'dallas-indian-travels'                 => 'dallas-indian-travels',        
        
    ],

    
    /*
    |--------------------------------------------------------------------------
    | Application Name
    |--------------------------------------------------------------------------
    |
    | This value is the name of your application. This value is used when the
    | framework needs to place the application's name in a notification or
    | any other location as required by the application or its packages.
    |
    */

    'name' => env('APP_NAME', 'Laravel'),

    /*
    |--------------------------------------------------------------------------
    | Application Environment
    |--------------------------------------------------------------------------
    |
    | This value determines the "environment" your application is currently
    | running in. This may determine how you prefer to configure various
    | services your application utilizes. Set this in your ".env" file.
    |
    */

    'env' => env('APP_ENV', 'production'),

    /*
    |--------------------------------------------------------------------------
    | Application Debug Mode
    |--------------------------------------------------------------------------
    |
    | When your application is in debug mode, detailed error messages with
    | stack traces will be shown on every error that occurs within your
    | application. If disabled, a simple generic error page is shown.
    |
    */

    'debug' => env('APP_DEBUG', false),

    /*
    |--------------------------------------------------------------------------
    | Application URL
    |--------------------------------------------------------------------------
    |
    | This URL is used by the console to properly generate URLs when using
    | the Artisan command line tool. You should set this to the root of
    | your application so that it is used when running Artisan tasks.
    |
    */

    'url' => env('APP_URL', 'http://localhost'),

    /*
    |--------------------------------------------------------------------------
    | Application Timezone
    |--------------------------------------------------------------------------
    |
    | Here you may specify the default timezone for your application, which
    | will be used by the PHP date and date-time functions. We have gone
    | ahead and set this to a sensible default for you out of the box.
    |
    */

    'timezone' => 'America/Chicago',

    /*
    |--------------------------------------------------------------------------
    | Application Locale Configuration
    |--------------------------------------------------------------------------
    |
    | The application locale determines the default locale that will be used
    | by the translation service provider. You are free to set this value
    | to any of the locales which will be supported by the application.
    |
    */

    'locale' => 'en',

    /*
    |--------------------------------------------------------------------------
    | Application Fallback Locale
    |--------------------------------------------------------------------------
    |
    | The fallback locale determines the locale to use when the current one
    | is not available. You may change the value to correspond to any of
    | the language folders that are provided through your application.
    |
    */

    'fallback_locale' => 'en',

    /*
    |--------------------------------------------------------------------------
    | Encryption Key
    |--------------------------------------------------------------------------
    |
    | This key is used by the Illuminate encrypter service and should be set
    | to a random, 32 character string, otherwise these encrypted strings
    | will not be safe. Please do this before deploying an application!
    |
    */

    'key' => env('APP_KEY'),

    'cipher' => 'AES-256-CBC',

    /*
    |--------------------------------------------------------------------------
    | Autoloaded Service Providers
    |--------------------------------------------------------------------------
    |
    | The service providers listed here will be automatically loaded on the
    | request to your application. Feel free to add your own services to
    | this array to grant expanded functionality to your applications.
    |
    */

    'providers' => [

        /*
         * Laravel Framework Service Providers...
         */
        Illuminate\Auth\AuthServiceProvider::class,
        Illuminate\Broadcasting\BroadcastServiceProvider::class,
        Illuminate\Bus\BusServiceProvider::class,
        Illuminate\Cache\CacheServiceProvider::class,
        Illuminate\Foundation\Providers\ConsoleSupportServiceProvider::class,
        Illuminate\Cookie\CookieServiceProvider::class,
        Illuminate\Database\DatabaseServiceProvider::class,
        Illuminate\Encryption\EncryptionServiceProvider::class,
        Illuminate\Filesystem\FilesystemServiceProvider::class,
        Illuminate\Foundation\Providers\FoundationServiceProvider::class,
        Illuminate\Hashing\HashServiceProvider::class,
        Illuminate\Mail\MailServiceProvider::class,
        Illuminate\Notifications\NotificationServiceProvider::class,
        Illuminate\Pagination\PaginationServiceProvider::class,
        Illuminate\Pipeline\PipelineServiceProvider::class,
        Illuminate\Queue\QueueServiceProvider::class,
        Illuminate\Redis\RedisServiceProvider::class,
        Illuminate\Auth\Passwords\PasswordResetServiceProvider::class,
        Illuminate\Session\SessionServiceProvider::class,
        Illuminate\Translation\TranslationServiceProvider::class,
        Illuminate\Validation\ValidationServiceProvider::class,
        Illuminate\View\ViewServiceProvider::class,

        /*
         * Package Service Providers...
         */

        /*
         * Application Service Providers...
         */
        App\Providers\AppServiceProvider::class,
        App\Providers\AuthServiceProvider::class,
        // App\Providers\BroadcastServiceProvider::class,
        App\Providers\EventServiceProvider::class,
        App\Providers\RouteServiceProvider::class,

        Artesaos\SEOTools\Providers\SEOToolsServiceProvider::class,

        Spatie\Permission\PermissionServiceProvider::class,
        'Intervention\Image\ImageServiceProvider',

    ],

    /*
    |--------------------------------------------------------------------------
    | Class Aliases
    |--------------------------------------------------------------------------
    |
    | This array of class aliases will be registered when this application
    | is started. However, feel free to register as many as you wish as
    | the aliases are "lazy" loaded so they don't hinder performance.
    |
    */

    'aliases' => [

        'App' => Illuminate\Support\Facades\App::class,
        'Artisan' => Illuminate\Support\Facades\Artisan::class,
        'Auth' => Illuminate\Support\Facades\Auth::class,
        'Blade' => Illuminate\Support\Facades\Blade::class,
        'Broadcast' => Illuminate\Support\Facades\Broadcast::class,
        'Bus' => Illuminate\Support\Facades\Bus::class,
        'Cache' => Illuminate\Support\Facades\Cache::class,
        'Config' => Illuminate\Support\Facades\Config::class,
        'Cookie' => Illuminate\Support\Facades\Cookie::class,
        'Crypt' => Illuminate\Support\Facades\Crypt::class,
        'DB' => Illuminate\Support\Facades\DB::class,
        'Eloquent' => Illuminate\Database\Eloquent\Model::class,
        'Event' => Illuminate\Support\Facades\Event::class,
        'File' => Illuminate\Support\Facades\File::class,
        'Gate' => Illuminate\Support\Facades\Gate::class,
        'Hash' => Illuminate\Support\Facades\Hash::class,
        'Lang' => Illuminate\Support\Facades\Lang::class,
        'Log' => Illuminate\Support\Facades\Log::class,
        'Mail' => Illuminate\Support\Facades\Mail::class,
        'Notification' => Illuminate\Support\Facades\Notification::class,
        'Password' => Illuminate\Support\Facades\Password::class,
        'Queue' => Illuminate\Support\Facades\Queue::class,
        'Redirect' => Illuminate\Support\Facades\Redirect::class,
        'Redis' => Illuminate\Support\Facades\Redis::class,
        'Request' => Illuminate\Support\Facades\Request::class,
        'Response' => Illuminate\Support\Facades\Response::class,
        'Route' => Illuminate\Support\Facades\Route::class,
        'Schema' => Illuminate\Support\Facades\Schema::class,
        'Session' => Illuminate\Support\Facades\Session::class,
        'Storage' => Illuminate\Support\Facades\Storage::class,
        'URL' => Illuminate\Support\Facades\URL::class,
        'Validator' => Illuminate\Support\Facades\Validator::class,
        'View' => Illuminate\Support\Facades\View::class,

        'SEOMeta'   => Artesaos\SEOTools\Facades\SEOMeta::class,
        'OpenGraph' => Artesaos\SEOTools\Facades\OpenGraph::class,
        'Twitter'   => Artesaos\SEOTools\Facades\TwitterCard::class,
        // or
        'SEO' => Artesaos\SEOTools\Facades\SEOTools::class,

        'Image' => 'Intervention\Image\Facades\Image',

    ],

];
