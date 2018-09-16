<?php

namespace App\Http\Controllers;

use App\Http\Controllers\CommonController;
use Illuminate\Http\Request;
use App\Http\Models\Restaurant;
use App\Http\Models\Photo;
use App\Http\Models\Url;
use App\Http\Models\City;

class RestaurantController extends Controller
{
    public function index(Request $request,$type,$city=null,$keyword=null)
    {

        $typeVal                        =   "";
        $cityVal                        =   "";
        $keywordVal                     =   "";

        if($type){
            $typeArr                    =   explode("-",$type);
            $typeVal                    =   $typeArr[count($typeArr)-1];
        }
        if($city && $city !='all'){
            $cityArr                    =   explode("-",$city);
            $cityVal                    =   $cityArr[count($cityArr)-1];
        } 
        if($keyword){
            $keywordVal                 =   $keyword;
        }         

        $siteId                         =   config('app.siteId');
        $commonCtrl                     =   new CommonController;

        $restaurantRs                   =   Restaurant::select('restaurant.id', 'restaurant.name', 
                                                    'address.address1', 'address.address2',
                                                    'city.city', 'address.state',
                                                    'address.zip', 
                                                    'address.phone1', 'address.latitude',
                                                    'address.longitude', 'ethnic.ethnicName',
                                                    'url.urlName', 'photo.photoName')
                                                ->leftjoin('url','url.restaurantId', '=', 'restaurant.id')
                                                ->leftjoin('address','address.id', '=', 'restaurant.addressId')
                                                ->leftjoin('ethnic','ethnic.id', '=', 'restaurant.ethnicId')
                                                ->leftjoin('site','site.siteId', '=', 'restaurant.siteId')
                                                ->leftJoin('photo', function($join){
                                                    $join->on('photo.restaurantId', '=', 'restaurant.id')
                                                        ->where('photo.is_primary','=',1);
                                                    })                                               
                                                ->leftjoin('city','city.cityId', '=', 'address.city')                                           
                                                ->where('restaurant.is_deleted', '=', '0')
                                                ->where('restaurant.is_disabled', '=', '0')
                                                ->where('site.siteId', '=', $siteId)
                                                ->orderBy('restaurant.premium', 'asc')
                                                ->orderBy('restaurant.order', 'asc');                                                   

        if($cityVal){
            $restaurantRs->where('city.cityId', '=', $cityVal);
        }
        if($type){
            $restaurantRs->where('ethnic.id', '=', $typeVal);
        }      
        if($keywordVal){
            $restaurantRs->where('restaurant.name', 'like', '%'.$keywordVal.'%');
        }           

        $restaurantRs                       =   $restaurantRs->get();                                                
        $restaurants                        =   $restaurantRs->toArray();

        if(isset($_COOKIE['lat']) && isset($_COOKIE['long'])){            
            foreach($restaurants as $key => $restaurant) {    
                $distance                       =   "";
                $lat                            =   ($restaurant['latitude'])?$restaurant['latitude']:'';
                $long                           =   ($restaurant['longitude'])?$restaurant['longitude']:'';
                if($lat && $long){
                    $dist                       =   $commonCtrl->distance($lat, $long, "M");
                    if($dist){
                        $restaurants[$key]["distance"]   =   number_format((float)$dist, 1, '.', '')." Miles";
                    }
                }
            }
        }

        // echo "<pre>";
        // print_r($restaurants);

        $cityRs                             =   City::select('cityId','city', 'value')
                                                ->orderBy('city', 'asc')
                                                ->get();  
        $cities                             =   $cityRs->toArray();      
        $commonCtrl->setMeta($request->path(),1);
        // echo "<pre>";
        // print_r($cities);
        
        return view('restaurant',['restaurant' => $restaurants, 'cities' => $cities, 'type' => $type, 'cityVal' => $cityVal, 'keyword' => $keyword]); 
    }

    public function getDetails(Request $request,$url){
        
        $distance                       =   "";
        $todaysWorkingTime              =   "";
        $descriptionHeight              =   "20";
        $commonCtrl                     =   new CommonController;

        $seoUrl                         =   $commonCtrl->seoUrl($request->path(),2);        

        $siteId                         =   config('app.siteId');
        $restaurantRs                   =   Restaurant::select('restaurant.id', 'restaurant.name', 
                                                    'restaurant.description', 'restaurant.workingTime',
                                                    'address.address1', 'address.address2',
                                                    'restaurant.website',                                                
                                                    'city.city', 'address.state',
                                                    'address.zip', 'address.county',
                                                    'address.phone1', 'address.latitude',
                                                    'address.longitude', 'ethnic.ethnicName',
                                                    'ethnic.id as ethnicId')
                                                    ->leftjoin('url','url.restaurantId', '=', 'restaurant.id')
                                                    ->leftjoin('address','address.id', '=', 'restaurant.addressId')
                                                    ->leftjoin('ethnic','ethnic.id', '=', 'restaurant.ethnicId')
                                                    ->leftjoin('site','site.siteId', '=', 'restaurant.siteId')
                                                    ->leftjoin('city','city.cityId', '=', 'address.city')                                                                                       
                                                    ->where('site.siteId', '=', $siteId)
                                                    ->where('url.urlName', '=', $url)
                                                    ->where('restaurant.is_deleted', '=', '0')
                                                    ->where('restaurant.is_disabled', '=', '0')
                                                    ->get()->first();

        $restaurant                         =   $restaurantRs->toArray(); 

        if($restaurant){
            $restaurantId                   =   $restaurant['id'];
            
            $lat                            =   ($restaurant['latitude'])?$restaurant['latitude']:'';
            $long                           =   ($restaurant['longitude'])?$restaurant['longitude']:'';
    
            if($lat && $long){
                $distance                   =   number_format((float)$commonCtrl->distance($lat, $long, "M"), 1, '.', '')." Miles";
            }

            $workingTimes                   =   json_decode($restaurant['workingTime'], true);
            $todaysDate                     =   date("l");   
            if($workingTimes){
                foreach($workingTimes as $rootKey => $workingTime) {
                    foreach($workingTime as $subkey => $subWorkingTime) {
                        foreach($subWorkingTime as $dayKey => $dayWorkingTime) {
                            foreach($dayWorkingTime as $keys => $times) {
                                foreach($times as $key => $time) {
                                    $oldKey                     =   "";
                                    $workingTimes[$rootKey][$subkey][$dayKey][$keys][$key]['time'] = date("H:i a", strtotime($workingTimes[$rootKey][$subkey][$dayKey][$keys][$key]['time']));
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
    
            $photoRs                        =   Photo::select('photo.photoId', 'photo.photoName', 
                                                    'photo.is_primary', 'photo.order')
                                                ->where('photo.is_deleted', '=', '0')
                                                ->where('photo.is_primary', '=', '0')
                                                ->where('photo.is_disabled', '=', '0')
                                                ->where('photo.restaurantId', '=', $restaurantId)
                                                ->orderBy('photo.order', 'asc') 
                                                ->get();        
            
            $photo                          =   $photoRs->toArray();  

            $commonCtrl->setMeta($request->path(),2);
            //echo $todaysWorkingTime;
            // echo "<pre>";
            // print_r($workingTimes);
            $descriptionHeight              =   $commonCtrl->descriptionLength(strlen($restaurant['description']));
            return view('restaurant_details',['restaurant' => $restaurant, 'photos' => $photo, 'distance' => $distance, 'workingTimes' => $workingTimes, 'today' => $todaysDate, 'todaysWorkingTime' => $todaysWorkingTime, 'descriptionHeight' => $descriptionHeight]);
        }else{
            return redirect()->back();
        }
    }

    public function getRelated(Request $request,$ethnicId,$id){
        
        $distance                       =   "";
        $commonCtrl                     =   new CommonController;        

        $siteId                         =   config('app.siteId');
        $relatedRs                      =   Restaurant::select('restaurant.id', 'restaurant.name', 
                                                'address.address1', 'address.address2',                                            
                                                'city.city', 'address.state',
                                                'address.zip', 'photo.photoName',
                                                'address.phone1', 'address.latitude',
                                                'address.longitude', 'url.urlName')
                                                    ->leftjoin('url','url.restaurantId', '=', 'restaurant.id')
                                                    ->leftjoin('address','address.id', '=', 'restaurant.addressId')
                                                    ->leftjoin('city','city.cityId', '=', 'address.city')                                                                                           
                                                    ->leftjoin('ethnic','ethnic.id', '=', 'restaurant.ethnicId')
                                                    ->leftjoin('site','site.siteId', '=', 'restaurant.siteId')
                                                    ->leftJoin('photo', function($join){
                                                        $join->on('photo.restaurantId', '=', 'restaurant.id')
                                                            ->where('photo.is_primary','=',1);
                                                        })                                                                                   
                                                    ->where('site.siteId', '=', $siteId)
                                                    ->where('restaurant.id', '!=', $id)
                                                    ->where('ethnic.id', '=', $ethnicId)                                        
                                                    ->where('restaurant.is_deleted', '=', '0')
                                                    ->where('restaurant.is_disabled', '=', '0')
                                                    ->orderBy('restaurant.premium', 'desc')
                                                    ->orderBy('restaurant.order', 'asc')                                         
                                                    ->take(10)->get();   
            
        $related                     =   $relatedRs->toArray();  

        if(isset($_COOKIE['lat']) && isset($_COOKIE['long'])){
            foreach($related as $key => $relatedRs) {    
                $distance                       =   "";
                $related[$key]["distance"]    =   "";
                $lat                            =   ($relatedRs['latitude'])?$relatedRs['latitude']:'';
                $long                           =   ($relatedRs['longitude'])?$relatedRs['longitude']:'';
                if($lat && $long){
                    $dist                       =   $commonCtrl->distance($lat, $long, "M");
                    if($dist){
                        $related[$key]["distance"]   =   number_format((float)$dist, 1, '.', '')." Miles";
                    }
                }
            }
        }  
                
        return view('related',['related' => $related, 'type' => 'restaurant']);
    }     
}
