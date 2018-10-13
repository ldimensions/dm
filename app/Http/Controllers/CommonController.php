<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Models\Url;
use App\Http\Models\Seo;
use App\Http\Models\RestaurantTemp;
use App\Http\Models\PhotoTmp;
use App\Http\Models\RestaurantFoodTypeTmp;
use App\Http\Models\ReligionTmp;


use SEOMeta;
use OpenGraph;
use Twitter;

class CommonController extends Controller
{
    public function __construct(){

    }

    function distance($lat2, $lon2, $unit) {

        $currentLat                         =   (isset($_COOKIE['lat']))?$_COOKIE['lat']:'';
        $currentLong                        =   (isset($_COOKIE['long']))?$_COOKIE['long']:'';
        if($currentLat && $currentLong){
            $theta                              =   $currentLong - $lon2;
            $dist                               =   sin(deg2rad($currentLat)) * sin(deg2rad($lat2)) +  cos(deg2rad($currentLat)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
            $dist                               =   acos($dist);
            $dist                               =   rad2deg($dist);
            $miles                              =   $dist * 60 * 1.1515;
            $unit                               =   strtoupper($unit);
          
            if ($unit == "K") {
              return ($miles * 1.609344);
            } else if ($unit == "N") {
              return ($miles * 0.8684);
            } else {
              return $miles;
            }    
        }
        return false;
    }

    function seoUrl($url, $index){
        $url                                =   explode("/",$url);
        return $url[$index-1];
    }

    function setMeta($url, $index=null, $keyValue=null,$tmp=null){

        $seoUrl                             =   "";
        if($url == '/'){
            $keyValue                       =   "default";
        }else if($index){
            $url                            =   explode("/",$url);
            $seoUrl                         =   $url[$index-1];
        }

        // check the review  - get it from temp table
        if($tmp){
            $seoRs                              =   Url::select('seo_tmp.SEOMetaTitle', 'seo_tmp.SEOMetaDesc', 
                                                                'seo_tmp.SEOMetaPublishedTime','seo_tmp.SEOMetaKeywords',
                                                                'seo_tmp.OpenGraphTitle','seo_tmp.OpenGraphDesc',
                                                                'seo_tmp.OpenGraphUrl','seo_tmp.OpenGraphPropertyType',
                                                                'seo_tmp.OpenGraphPropertyLocale','seo_tmp.OpenGraphPropertyLocaleAlternate',
                                                                'seo_tmp.OpenGraph')
                                                        ->join('seo_tmp','seo_tmp.urlId', '=', 'url.id')
                                                        ->where('url.urlName', '=', $seoUrl)
                                                        ->get()->first();  
        }else{
            $seoRs                              =   Url::select('seo.SEOMetaTitle', 'seo.SEOMetaDesc', 
                                                                'seo.SEOMetaPublishedTime','seo.SEOMetaKeywords',
                                                                'seo.OpenGraphTitle','seo.OpenGraphDesc',
                                                                'seo.OpenGraphUrl','seo.OpenGraphPropertyType',
                                                                'seo.OpenGraphPropertyLocale','seo.OpenGraphPropertyLocaleAlternate',
                                                                'seo.OpenGraph')
                                                        ->join('seo','seo.urlId', '=', 'url.id')
                                                        ->where('url.urlName', '=', $seoUrl)
                                                        ->get()->first();  
        }

        if($seoRs){
            $seo                            =   $seoRs->toArray();
            SEOMeta::setTitle($seo['SEOMetaTitle']);
            SEOMeta::setDescription($seo['SEOMetaDesc']);
            SEOMeta::addKeyword([$seo['SEOMetaKeywords']]); 
            if($seo['SEOMetaPublishedTime']){
                SEOMeta::addMeta('article:published_time', $seo['SEOMetaPublishedTime']->toW3CString(), 'property');
            }
            return true;    
        }

        // Checcking the Key value is there or not

        if($keyValue){
            $seoRs                              =   Seo::select('seo.SEOMetaTitle', 'seo.SEOMetaDesc', 
                                                                'seo.SEOMetaPublishedTime','seo.SEOMetaKeywords',
                                                                'seo.OpenGraphTitle','seo.OpenGraphDesc',
                                                                'seo.OpenGraphUrl','seo.OpenGraphPropertyType',
                                                                'seo.OpenGraphPropertyLocale','seo.OpenGraphPropertyLocaleAlternate',
                                                                'seo.OpenGraph')
                                                            ->where('seo.keyValue', '=', $keyValue)
                                                            ->get()->first();    
                                                            
            if($seoRs){
                $seo                            =   $seoRs->toArray();
                SEOMeta::setTitle($seo['SEOMetaTitle']);
                SEOMeta::setDescription($seo['SEOMetaDesc']);
                SEOMeta::addKeyword([$seo['SEOMetaKeywords']]); 
                if($seo['SEOMetaPublishedTime']){
                    SEOMeta::addMeta('article:published_time', $seo['SEOMetaPublishedTime']->toW3CString(), 'property');
                }
                return true;                    
            }                                                            
        }

        // Default

        $seoRs                              =   Seo::select('seo.SEOMetaTitle', 'seo.SEOMetaDesc', 
                                                            'seo.SEOMetaPublishedTime','seo.SEOMetaKeywords',
                                                            'seo.OpenGraphTitle','seo.OpenGraphDesc',
                                                            'seo.OpenGraphUrl','seo.OpenGraphPropertyType',
                                                            'seo.OpenGraphPropertyLocale','seo.OpenGraphPropertyLocaleAlternate',
                                                            'seo.OpenGraph')
                                                        ->where('seo.keyValue', '=', 'default')
                                                        ->get()->first();    
                                                        
        if($seoRs){
            $seo                            =   $seoRs->toArray();
            SEOMeta::setTitle($seo['SEOMetaTitle']);
            SEOMeta::setDescription($seo['SEOMetaDesc']);
            SEOMeta::addKeyword([$seo['SEOMetaKeywords']]); 
            if($seo['SEOMetaPublishedTime']){
                SEOMeta::addMeta('article:published_time', $seo['SEOMetaPublishedTime']->toW3CString(), 'property');
            }
            return true;                    
        }      


        




        // else{
        //     $defaultSEOValues               =   config('app.defaultSEO');
        //     $defaultSEO                     =   $defaultSEOValues[1];
        //     SEOMeta::setTitle($defaultSEO['SEOMetaTitle']);
        //     SEOMeta::setDescription($defaultSEO['SEOMetaDesc']);
        //     SEOMeta::addKeyword($defaultSEO['SEOMetaKeywords']);    
        // }

    }

    function setMeta1(){
      SEOMeta::setTitle($post->title);
      SEOMeta::setDescription($post->resume);
      SEOMeta::addMeta('article:published_time', $post->published_date->toW3CString(), 'property');
      SEOMeta::addMeta('article:section', $post->category, 'property');
      SEOMeta::addKeyword(['key1', 'key2', 'key3']);

      OpenGraph::setDescription($post->resume);
      OpenGraph::setTitle($post->title);
      OpenGraph::setUrl('http://current.url.com');
      OpenGraph::addProperty('type', 'article');
      OpenGraph::addProperty('locale', 'pt-br');
      OpenGraph::addProperty('locale:alternate', ['pt-pt', 'en-us']);

      OpenGraph::addImage($post->cover->url);
      OpenGraph::addImage($post->images->list('url'));
      OpenGraph::addImage(['url' => 'http://image.url.com/cover.jpg', 'size' => 300]);
      OpenGraph::addImage('http://image.url.com/cover.jpg', ['height' => 300, 'width' => 300]);

      // Namespace URI: http://ogp.me/ns/article#
      // article
      OpenGraph::setTitle('Article')
          ->setDescription('Some Article')
          ->setType('article')
          ->setArticle([
              'published_time' => 'datetime',
              'modified_time' => 'datetime',
              'expiration_time' => 'datetime',
              'author' => 'profile / array',
              'section' => 'string',
              'tag' => 'string / array'
          ]);

      // Namespace URI: http://ogp.me/ns/book#
      // book
      OpenGraph::setTitle('Book')
          ->setDescription('Some Book')
          ->setType('book')
          ->setBook([
              'author' => 'profile / array',
              'isbn' => 'string',
              'release_date' => 'datetime',
              'tag' => 'string / array'
          ]);

      // Namespace URI: http://ogp.me/ns/profile#
      // profile
      OpenGraph::setTitle('Profile')
           ->setDescription('Some Person')
          ->setType('profile')
          ->setProfile([
              'first_name' => 'string',
              'last_name' => 'string',
              'username' => 'string',
              'gender' => 'enum(male, female)'
          ]);

      // Namespace URI: http://ogp.me/ns/music#
      // music.song
      OpenGraph::setType('music.song')
          ->setMusicSong([
              'duration' => 'integer',
              'album' => 'array',
              'album:disc' => 'integer',
              'album:track' => 'integer',
              'musician' => 'array'
          ]);

      // music.album
      OpenGraph::setType('music.album')
          ->setMusicAlbum([
              'song' => 'music.song',
              'song:disc' => 'integer',
              'song:track' => 'integer',
              'musician' => 'profile',
              'release_date' => 'datetime'
          ]);

       //music.playlist
      OpenGraph::setType('music.playlist')
          ->setMusicPlaylist([
              'song' => 'music.song',
              'song:disc' => 'integer',
              'song:track' => 'integer',
              'creator' => 'profile'
          ]);

      // music.radio_station
      OpenGraph::setType('music.radio_station')
          ->setMusicRadioStation([
              'creator' => 'profile'
          ]);

      // Namespace URI: http://ogp.me/ns/video#
      // video.movie
      OpenGraph::setType('video.movie')
          ->setVideoMovie([
              'actor' => 'profile / array',
              'actor:role' => 'string',
              'director' => 'profile /array',
              'writer' => 'profile / array',
              'duration' => 'integer',
              'release_date' => 'datetime',
              'tag' => 'string / array'
          ]);

      // video.episode
      OpenGraph::setType('video.episode')
          ->setVideoEpisode([
              'actor' => 'profile / array',
              'actor:role' => 'string',
              'director' => 'profile /array',
              'writer' => 'profile / array',
              'duration' => 'integer',
              'release_date' => 'datetime',
              'tag' => 'string / array',
              'series' => 'video.tv_show'
          ]);

      // video.tv_show
      OpenGraph::setType('video.tv_show')
          ->setVideoTVShow([
              'actor' => 'profile / array',
              'actor:role' => 'string',
              'director' => 'profile /array',
              'writer' => 'profile / array',
              'duration' => 'integer',
              'release_date' => 'datetime',
              'tag' => 'string / array'
          ]);

      // video.other
      OpenGraph::setType('video.other')
          ->setVideoOther([
              'actor' => 'profile / array',
              'actor:role' => 'string',
              'director' => 'profile /array',
              'writer' => 'profile / array',
              'duration' => 'integer',
              'release_date' => 'datetime',
              'tag' => 'string / array'
          ]);

      // og:video
      OpenGraph::addVideo('http://example.com/movie.swf', [
              'secure_url' => 'https://example.com/movie.swf',
              'type' => 'application/x-shockwave-flash',
              'width' => 400,
              'height' => 300
          ]);

      // og:audio
      OpenGraph::addAudio('http://example.com/sound.mp3', [
              'secure_url' => 'https://secure.example.com/sound.mp3',
              'type' => 'audio/mpeg'
          ]);

    }

    public static function activeMenu($type){
        $isActive           =   false;
        $hostName           =   $_SERVER['REQUEST_URI'];
        $hostNameArr1       =   explode("?",$hostName);
        $hostNameArr        =   explode("/",$hostNameArr1[0]);

        $groceryArr         =   array('dallas-indian-grocery-store','dallas-grocery-store','grocery-search');
        $restaurantArr      =   array('dallas-indian-restaurant','restaurant-search');
        $religionArr        =   array('dallas-indian-religion','religion-search','dallas-malayali-church','dallas-christian-church','dallas-hindu-temple','dallas-islan-mosque');
        $travelsArr         =   array('dallas-indian-travels','travels-search');        
        switch($type){
            case 'home':
                if(count(array_filter($hostNameArr))  ==  0){
                    $isActive = true;
                }
                break;            
            case 'grocery':
                foreach($groceryArr as $index => $grocery){
                    if (in_array($grocery, $hostNameArr)){
                        $isActive = true;
                    }
                }
                break;
            case 'restaurant':
                foreach($restaurantArr as $restaurant){
                    if (in_array($restaurant, $hostNameArr)){
                        $isActive = true;
                    }
                }
                break;
            case 'religion':
                foreach($religionArr as $religion){
                    if (in_array($religion, $hostNameArr)){
                        $isActive = true;
                    }
                }
                break;
            case 'travels':
                foreach($travelsArr as $travel){
                    if (in_array($travel, $hostNameArr)){
                        $isActive = true;
                    }
                }
                break;
                
        }
        return $isActive;
    }

    function time_elapsed_string($datetimeVal, $full = false) {
        $now = new \DateTime();
        $ago = new \DateTime($datetimeVal);
        $diff = $now->diff($ago);
    
        $diff->w = floor($diff->d / 7);
        $diff->d -= $diff->w * 7;
    
        $string = array(
            'y' => 'year',
            'm' => 'month',
            'w' => 'week',
            'd' => 'day',
            'h' => 'hour',
            'i' => 'minute',
            's' => 'second',
        );
        foreach ($string as $k => &$v) {
            if ($diff->$k) {
                $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
            } else {
                unset($string[$k]);
            }
        }
    
        if (!$full) $string = array_slice($string, 0, 1);
        return $string ? implode(', ', $string) . ' ago' : 'just now';
    }

    function descriptionLength($length) {
        if($length <= 65){
            return 20;
        }else if($length <= 130){
            return 40;
        }else if($length <= 195){
            return 60;
        }
        // else if($length <= 220){
        //      return 76;
        // }
        //else if($length >= 320){
        //     return 100;
        // }
        else{
            return 80;
        }
    }

    function review(Request $request, $type, $url){
        
        if($type == 'restaurant'){
            $distance                       =   "";
            $todaysWorkingTime              =   "";
            $descriptionHeight              =   "20";
            $commonCtrl                     =   new CommonController;
    
            $seoUrl                         =   $commonCtrl->seoUrl($request->path(),3);        
    
            $siteId                         =   config('app.siteId');
            $restaurantRs                   =   RestaurantTemp::select('restaurant_tmp.id', 'restaurant_tmp.name', 
                                                        'restaurant_tmp.description', 'restaurant_tmp.workingTime',
                                                        'address_tmp.address1', 'address_tmp.address2',
                                                        'restaurant_tmp.website',                                                
                                                        'city.city', 'address_tmp.state',
                                                        'address_tmp.zip', 'address_tmp.county',
                                                        'address_tmp.phone1', 'address_tmp.latitude',
                                                        'address_tmp.longitude', 'ethnic.ethnicName',
                                                        'ethnic.id as ethnicId', 'url.urlName')
                                                        ->leftjoin('url','url.restaurantTempId', '=', 'restaurant_tmp.id')
                                                        ->leftjoin('address_tmp','address_tmp.id', '=', 'restaurant_tmp.addressId')
                                                        ->leftjoin('ethnic','ethnic.id', '=', 'restaurant_tmp.ethnicId')
                                                        ->leftjoin('site','site.siteId', '=', 'restaurant_tmp.siteId')
                                                        ->leftjoin('city','city.cityId', '=', 'address_tmp.city')                                                                                       
                                                        ->where('site.siteId', '=', $siteId)
                                                        ->where('url.urlName', '=', $url)
                                                        ->where('restaurant_tmp.is_deleted', '=', '0')
                                                        ->where('restaurant_tmp.is_disabled', '=', '0')
                                                        ->get()->first();

            $restaurant                         =   $restaurantRs->toArray(); 
    
            if($restaurant){
                $restaurantId                   =   $restaurant['id'];
                
                $lat                            =   ($restaurant['latitude'])?$restaurant['latitude']:'';
                $long                           =   ($restaurant['longitude'])?$restaurant['longitude']:'';
        
                $workingTimes                   =   json_decode($restaurant['workingTime'], true);
                $todaysDate                     =   date("l");   
                if($workingTimes){
                    foreach($workingTimes as $rootKey => $workingTime) {
                        foreach($workingTime as $subkey => $subWorkingTime) {
                            foreach($subWorkingTime as $dayKey => $dayWorkingTime) {
                                foreach($dayWorkingTime as $keys => $times) {
                                    foreach($times as $key => $time) {
                                        $oldKey                     =   "";
                                        $workingTimes[$rootKey][$subkey][$dayKey][$keys][$key]['time'] = date("g:i a", strtotime($workingTimes[$rootKey][$subkey][$dayKey][$keys][$key]['time']));
                                        if($dayKey == $todaysDate){
                                            if($oldKey != $key){
                                                $todaysWorkingTime      .=   ' - '.$workingTimes[$rootKey][$subkey][$dayKey][$keys][$key]['time'];                            
                                            }else{
                                                $todaysWorkingTime      .=   ($todaysWorkingTime)?', '.$workingTimes[$rootKey][$subkey][$dayKey][$keys][$key]['time']: $workingTimes[$rootKey][$subkey][$dayKey][$keys][$key]['time'];
                                            }
                                        }
                                        $oldKey                         =  $key; 
                                    }                                
                                }
                            }
                        }
                    }
                }
        
                $photoRs                        =   PhotoTmp::select('photo_tmp.photoId', 'photo_tmp.photoName', 
                                                        'photo_tmp.is_primary', 'photo_tmp.order')
                                                        ->where('photo_tmp.is_deleted', '=', '0')
                                                        ->where('photo_tmp.is_primary', '=', '0')
                                                        ->where('photo_tmp.is_disabled', '=', '0')
                                                        ->where('photo_tmp.restaurantId', '=', $restaurantId)
                                                        ->orderBy('photo_tmp.order', 'asc') 
                                                        ->get();        
                
                $photo                          =   $photoRs->toArray();  
    
                $foodTypeRs                     =   RestaurantFoodTypeTmp::select('food_type.id', 'food_type.type')
                                                        ->leftjoin('food_type','food_type.id', '=', 'restaurant_food_type_tmp.foodTypeId')
                                                        ->where('restaurant_food_type_tmp.restaurantId', '=', $restaurantId)
                                                        ->get();        
    
                $foodType                       =   $foodTypeRs->toArray();   
                $foodTypeStr                    =   "";  
                if(count($foodType) > 0){
                    $foodTypeArr                =   array();  
                    for($i = 0; $i < count($foodType); $i++){
                        $foodTypeArr[$i]       =   $foodType[$i]['type'];
                    }
                    $foodTypeStr                    =   implode(", ",$foodTypeArr);
                }
                
    
                $commonCtrl->setMeta($request->path(),3,'','tmp');
                $descriptionHeight              =   $commonCtrl->descriptionLength(strlen($restaurant['description']));
                return view('restaurant_details',['restaurant' => $restaurant, 'photos' => $photo, 'distance' => $distance, 'workingTimes' => $workingTimes, 'today' => $todaysDate, 'todaysWorkingTime' => $todaysWorkingTime, 'descriptionHeight' => $descriptionHeight, 'foodTypeStr' => $foodTypeStr]);
            }else{
                return redirect()->back();
            }
        }else if($type == 'religion'){
            $distance                       =   "";
            $todaysMassTime                 =   "";
            $todaysConfessionTime           =   "";
            $todaysAdorationTime            =   "";
            $descriptionHeight              =   "20";
            $commonCtrl                     =   new CommonController;
    
            $seoUrl                         =   $commonCtrl->seoUrl($request->path(),2);
    
            $siteId                         =   config('app.siteId');
            $religionRs                     =   ReligionTmp::select('religion_tmp.id', 'religion_tmp.name', 
                                                    'religion_tmp.description', 'religion_tmp.workingTime',
                                                    'religion_tmp.website',
                                                    'address_tmp.address1', 'address_tmp.address2',
                                                    'address_tmp.zip', 'religion_tmp.shortDescription',
                                                    'city.city', 'address_tmp.state',
                                                    'address_tmp.phone1', 'address_tmp.latitude',
                                                    'address_tmp.longitude','religion_type.religionName',
                                                    'denomination.denominationName',
                                                    'denomination.id as denominationId', 'url.urlName')
                                                //->leftjoin('religion','url.religionId', '=', 'religion.id')
                                                ->leftjoin('url','url.religionTempId', '=', 'religion_tmp.id')
                                                ->leftjoin('religion_type','religion_type.id', '=', 'religion_tmp.religionTypeId')                                                
                                                ->leftjoin('denomination','denomination.id', '=', 'religion_tmp.denominationId')                                                
                                                ->leftjoin('address_tmp','address_tmp.id', '=', 'religion_tmp.addressId')
                                                ->leftjoin('city','city.cityId', '=', 'address_tmp.city')         
                                                ->where('religion_tmp.is_deleted', '=', '0')
                                                ->where('religion_tmp.is_disabled', '=', '0')
                                                ->where('url.urlName', '=', $url)
                                                ->get()->first(); 
            
            if($religionRs){
    
                $lat                            =   ($religionRs['latitude'])?$religionRs['latitude']:'';
                $long                           =   ($religionRs['longitude'])?$religionRs['longitude']:'';
    
                $workingTimes                   =   json_decode($religionRs['workingTime'], true);
    
                $todaysDate                     =   date("l");
    
                if($workingTimes){
                    foreach($workingTimes as $rootKey => $workingTime) {
                        foreach($workingTime as $subkey => $subWorkingTime) {
                            foreach($subWorkingTime as $dayKey => $dayWorkingTime) {                          
                                foreach($dayWorkingTime as $key => $time) {
                                    $workingTimes[$rootKey][$subkey][$dayKey][$key]['time'] = date("g:i a", strtotime($workingTimes[$rootKey][$subkey][$dayKey][$key]['time']));
                                    if($dayKey == $todaysDate && $rootKey == "Mass"){
                                        $todaysMassTime             .=   ($todaysMassTime)?', '.date("g:i a", strtotime($workingTimes[$rootKey][$subkey][$dayKey][$key]['time'])):date("H:i a", strtotime($workingTimes[$rootKey][$subkey][$dayKey][$key]['time']));
                                    }elseif($dayKey == $todaysDate && $rootKey == "Confession"){
                                        $todaysConfessionTime       .=   ($todaysConfessionTime)?', '.date("g:i a", strtotime($workingTimes[$rootKey][$subkey][$dayKey][$key]['time'])):date("H:i a", strtotime($workingTimes[$rootKey][$subkey][$dayKey][$key]['time']));
                                    }else if($dayKey == $todaysDate && $rootKey == "Adoration"){
                                        $todaysAdorationTime        .=   ($todaysAdorationTime)?', '.date("g:i a", strtotime($workingTimes[$rootKey][$subkey][$dayKey][$key]['time'])):date("H:i a", strtotime($workingTimes[$rootKey][$subkey][$dayKey][$key]['time']));
                                    }
                                }
                            }
                        }
                     }
                }            
                $religion                       =   $religionRs->toArray(); 
                $religionId                     =   $religion['id'];
        
                $photoRs                        =   PhotoTmp::select('photo_tmp.photoId', 'photo_tmp.photoName', 
                                                        'photo_tmp.is_primary', 'photo_tmp.order')
                                                    ->where('photo_tmp.is_deleted', '=', '0')
                                                    ->where('photo_tmp.is_primary', '=', '0')
                                                    ->where('photo_tmp.is_disabled', '=', '0')
                                                    ->where('photo_tmp.religionId', '=', $religionId)
                                                    ->orderBy('photo_tmp.order', 'asc') 
                                                    ->get();        
                
                $photo                          =   $photoRs->toArray();  
    
                $commonCtrl->setMeta($request->path(),3,'','tmp');
            
                $descriptionHeight              =   $commonCtrl->descriptionLength(strlen($religionRs['description']));
                
                return view('religion_details',[
                                                    'religion' => $religion, 
                                                    'photos' => $photo, 
                                                    'distance' => $distance, 
                                                    'workingTimes' => $workingTimes, 
                                                    'today' => $todaysDate,
                                                    'todaysMassTime' =>$todaysMassTime,
                                                    'todaysConfessionTime' => $todaysConfessionTime,
                                                    'todaysAdorationTime' => $todaysAdorationTime,
                                                    'descriptionHeight' => $descriptionHeight
                                                ]
                        );
            }else{
                return redirect()->back();
            }
        }else if($type == ''){
            
        }

    }
}
