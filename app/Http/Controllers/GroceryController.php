<?php

namespace App\Http\Controllers;

use App\Http\Controllers\CommonController;
use Illuminate\Http\Request;
use App\Http\Models\Grocery;
use App\Http\Models\Photo;
use App\Http\Models\Url;
use App\Http\Models\City;

class GroceryController extends Controller
{
    public function __construct(){

    }

    public function index(Request $request)
    {
        $siteId                         =   config('app.siteId');
        $commonCtrl                     =   new CommonController;

        $groceryRs                      =   Grocery::select('grocery.id', 'grocery.name', 
                                                'grocery.description', 'grocery.workingTime',
                                                'address.address1', 'address.address2',
                                                'city.city', 'address.state',
                                                'address.zip', 'address.county',
                                                'address.phone1', 'address.latitude',
                                                'address.longitude', 'ethnic.ethnicName',
                                                'url.urlName', 'photo.photoName')
                                            ->leftjoin('url','url.groceryId', '=', 'grocery.id')
                                            ->leftjoin('address','address.id', '=', 'grocery.addressId')
                                            ->leftjoin('ethnic','ethnic.id', '=', 'grocery.ethnicId')
                                            ->leftjoin('site','site.siteId', '=', 'grocery.siteId')
                                            ->leftJoin('photo', function($join){
                                                    $join->on('photo.groceryId', '=', 'grocery.id')
                                                        //->on('photo.is_primary','=',1);
                                                        ->where('photo.is_primary','=',1);
                                            })   
                                            ->leftjoin('city','city.cityId', '=', 'address.city')                                           
                                            ->where('grocery.is_deleted', '=', '0')
                                            ->where('grocery.is_disabled', '=', '0')
                                            ->where('site.siteId', '=', $siteId)
                                            ->orderBy('grocery.premium', 'asc')
                                            ->orderBy('grocery.order', 'asc')                                                    
                                            ->get(); 
        
        $grocerys                        =   $groceryRs->toArray();

        foreach($grocerys as $key => $grocery) {    

            if(isset($_COOKIE['lat']) && isset($_COOKIE['long'])){
                $distance                       =   "";
                $lat                            =   ($grocery['latitude'])?$grocery['latitude']:'';
                $long                           =   ($grocery['longitude'])?$grocery['longitude']:'';
                if($lat && $long){
                    $dist                       =   $commonCtrl->distance($lat, $long, "M");
                    if($dist){
                        $grocerys[$key]["distance"]   =   number_format((float)$dist, 1, '.', '')." Miles";
                    }
                }
            }
        }

        $cityRs                             =   City::select('city', 'value')
                                                ->orderBy('city', 'asc')
                                                ->get();  
        $cities                             =   $cityRs->toArray();      
        $commonCtrl->setMeta($request->path(),1);
        // echo "<pre>";
        // print_r($grocerys);
        
        return view('grocery',['grocery' => $grocerys, 'cities' => $cities]);
    }

    public function search(Request $request)
    {
        $siteId                         =   config('app.siteId');
        $commonCtrl                     =   new CommonController;

        $groceryRs                      =   Grocery::select('grocery.id', 'grocery.name', 
                                                'grocery.description', 'grocery.workingTime',
                                                'address.address1', 'address.address2',
                                                'city.city', 'address.state',
                                                'address.zip', 'address.county',
                                                'address.phone1', 'address.latitude',
                                                'address.longitude', 'ethnic.ethnicName',
                                                'url.urlName', 'photo.photoName')
                                            ->leftjoin('url','url.groceryId', '=', 'grocery.id')
                                            ->leftjoin('address','address.id', '=', 'grocery.addressId')
                                            ->leftjoin('ethnic','ethnic.id', '=', 'grocery.ethnicId')
                                            ->leftjoin('site','site.siteId', '=', 'grocery.siteId')
                                            ->leftJoin('photo', function($join){
                                                    $join->on('photo.groceryId', '=', 'grocery.id')
                                                        //->on('photo.is_primary','=',1);
                                                        ->where('photo.is_primary','=',1);
                                            })   
                                            ->leftjoin('city','city.cityId', '=', 'address.city')                                           
                                            ->where('grocery.is_deleted', '=', '0')
                                            ->where('grocery.is_disabled', '=', '0')
                                            ->where('site.siteId', '=', $siteId)
                                            ->orderBy('grocery.premium', 'asc')
                                            ->orderBy('grocery.order', 'asc')                                                    
                                            ->get(); 
        
        $grocerys                        =   $groceryRs->toArray();

        foreach($grocerys as $key => $grocery) {    

            if(isset($_COOKIE['lat']) && isset($_COOKIE['long'])){
                $distance                       =   "";
                $lat                            =   ($grocery['latitude'])?$grocery['latitude']:'';
                $long                           =   ($grocery['longitude'])?$grocery['longitude']:'';
                if($lat && $long){
                    $dist                       =   $commonCtrl->distance($lat, $long, "M");
                    if($dist){
                        $grocerys[$key]["distance"]   =   number_format((float)$dist, 1, '.', '')." Miles";
                    }
                }
            }
        }

        $cityRs                             =   City::select('city', 'value')
                                                ->orderBy('city', 'asc')
                                                ->get();  
        $cities                             =   $cityRs->toArray();      
        $commonCtrl->setMeta($request->path(),1);
        // echo "<pre>";
        // print_r($grocerys);
        
        return view('grocery',['grocery' => $grocerys, 'cities' => $cities]);
    }    

    public function getDetails(Request $request,$url){

        $distance                       =   "";
        $commonCtrl                     =   new CommonController;

        $seoUrl                         =   $commonCtrl->seoUrl($request->path(),2);        

        $siteId                         =   config('app.siteId');
        $groceryRs                      =   Grocery::select('grocery.id', 'grocery.name', 
                                                'grocery.description', 'grocery.workingTime',
                                                'address.address1', 'address.address2',
                                                'grocery.website',                                                
                                                'city.city', 'address.state',
                                                'address.zip', 'address.county',
                                                'address.phone1', 'address.latitude',
                                                'address.longitude', 'ethnic.ethnicName',
                                                'ethnic.id as ethnicId')
                                            ->leftjoin('url','url.groceryId', '=', 'grocery.id')
                                            ->leftjoin('address','address.id', '=', 'grocery.addressId')
                                            ->leftjoin('ethnic','ethnic.id', '=', 'grocery.ethnicId')
                                            ->leftjoin('site','site.siteId', '=', 'grocery.siteId')
                                            ->leftjoin('city','city.cityId', '=', 'address.city')                                                                                       
                                            ->where('site.siteId', '=', $siteId)
                                            ->where('url.urlName', '=', $url)
                                            ->where('grocery.is_deleted', '=', '0')
                                            ->where('grocery.is_disabled', '=', '0')
                                            ->get(); 

        $grocery                            =   $groceryRs->toArray(); 
        $grocery                            =   $grocery[0]; 

        if($grocery){

            $groceryId                      =   $grocery['id'];
            
            $lat                            =   ($grocery['latitude'])?$grocery['latitude']:'';
            $long                           =   ($grocery['longitude'])?$grocery['longitude']:'';
    
            if($lat && $long){
                $distance                   =   number_format((float)$commonCtrl->distance($lat, $long, "M"), 1, '.', '')." Miles";
            }

            $workingTimes                   =   json_decode($grocery['workingTime'], true);
            foreach($workingTimes as $rootKey => $workingTime) {
                foreach($workingTime as $subkey => $subWorkingTime) {
                    foreach($subWorkingTime as $dayKey => $dayWorkingTime) {
                        foreach($dayWorkingTime as $key => $time) {
                            $workingTimes[$rootKey][$subkey][$dayKey][$key]['time'] = date("H:i a", strtotime($workingTimes[$rootKey][$subkey][$dayKey][$key]['time']));
                        }
                    }
                }
            }
    
            $photoRs                        =   Photo::select('photo.photoId', 'photo.photoName', 
                                                    'photo.is_primary', 'photo.order')
                                                ->where('photo.is_deleted', '=', '0')
                                                ->where('photo.is_disabled', '=', '0')
                                                ->where('photo.groceryId', '=', $groceryId)
                                                ->orderBy('photo.order', 'asc') 
                                                ->get();        
            
            $photo                          =   $photoRs->toArray();  

            $commonCtrl->setMeta($request->path(),2);

            // $now = strtotime("now");
            // $yourTime   =   strtotime('2018-06-22 11:04:00');
            // $diff  = $now - $yourTime;

            // $hours = floor($diff / (60 * 60));
            // $minutes = $diff - $hours * (60 * 60);

            $todaysDate =   date("l");     
            
            return view('grocery_details',['grocery' => $grocery, 'photos' => $photo, 'distance' => $distance, 'workingTimes' => $workingTimes, 'today' => $todaysDate]);
        }else{
            return redirect()->back();
        }
    }

    public function getRelated(Request $request,$ethnicId,$id){
        
        $distance                       =   "";
        $commonCtrl                     =   new CommonController;        

        $siteId                         =   config('app.siteId');
        $relatedRs                      =   Grocery::select('grocery.id', 'grocery.name', 
                                            'grocery.description', 'grocery.workingTime',
                                            'address.address1', 'address.address2',
                                            'grocery.website',                                                
                                            'address.city', 'address.state',
                                            'address.zip', 'address.county',
                                            'address.city', 'address.state',
                                            'address.phone1', 'address.latitude',
                                            'address.longitude', 'ethnic.ethnicName',
                                            'ethnic.id as ethnicId', 'url.urlName')
                                                ->leftjoin('url','url.groceryId', '=', 'grocery.id')
                                                ->leftjoin('address','address.id', '=', 'grocery.addressId')
                                                ->leftjoin('ethnic','ethnic.id', '=', 'grocery.ethnicId')
                                                ->leftjoin('site','site.siteId', '=', 'grocery.siteId')
                                                ->leftJoin('photo', function($join){
                                                    $join->on('photo.groceryId', '=', 'grocery.id')
                                                        ->where('photo.is_primary','=',1);
                                                    })                                                                                   
                                                ->where('site.siteId', '=', $siteId)
                                                ->where('grocery.id', '!=', $id)
                                                //->where('ethnic.id', '=', $ethnicId)                                        
                                                ->where('grocery.is_deleted', '=', '0')
                                                ->where('grocery.is_disabled', '=', '0')
                                                ->orderBy('grocery.premium', 'desc')
                                                ->orderBy('grocery.order', 'asc')                                         
                                                ->take(5)->get();                                          
        
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
                
        return view('related',['related' => $related, 'type' => 'grocery']);
    }     

}
