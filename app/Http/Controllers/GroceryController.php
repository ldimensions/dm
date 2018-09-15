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

    public function index_old(Request $request)
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

        if(isset($_COOKIE['lat']) && isset($_COOKIE['long'])){            
            foreach($grocerys as $key => $grocery) {    
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

        $cityRs                             =   City::select('cityId','city', 'value')
                                                ->orderBy('city', 'asc')
                                                ->get();  
        $cities                             =   $cityRs->toArray();      
        $commonCtrl->setMeta($request->path(),1);
        // echo "<pre>";
        // print_r($cities);
        
        return view('grocery',['grocery' => $grocerys, 'cities' => $cities]);
    }

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

        $groceryRs                      =   Grocery::select('grocery.id', 'grocery.name', 
                                                    'address.address1', 'address.address2',
                                                    'city.city', 'address.state',
                                                    'address.zip',
                                                    'address.phone1', 'address.latitude',
                                                    'address.longitude', 'ethnic.ethnicName',
                                                    'url.urlName', 'photo.photoName')
                                                ->leftjoin('url','url.groceryId', '=', 'grocery.id')
                                                ->leftjoin('address','address.id', '=', 'grocery.addressId')
                                                ->leftjoin('ethnic','ethnic.id', '=', 'grocery.ethnicId')
                                                ->leftjoin('site','site.siteId', '=', 'grocery.siteId')
                                                ->leftJoin('photo', function($join){
                                                    $join->on('photo.groceryId', '=', 'grocery.id')
                                                        ->where('photo.is_primary','=',1);
                                                })   
                                                ->leftjoin('city','city.cityId', '=', 'address.city')                                           
                                                ->where('grocery.is_deleted', '=', '0')
                                                ->where('grocery.is_disabled', '=', '0')
                                                ->where('site.siteId', '=', $siteId)
                                                ->orderBy('grocery.premium', 'DESC')
                                                ->orderBy('grocery.order', 'asc');                                                  
                                                

        if($cityVal){
            $groceryRs->where('city.cityId', '=', $cityVal);
        }
        if($type){
            $groceryRs->where('ethnic.id', '=', $typeVal);
        }      
        if($keywordVal){
            $groceryRs->where('grocery.name', 'like', '%'.$keywordVal.'%');
        }           

        $groceryRs                       =   $groceryRs->get();
        $grocerys                        =   $groceryRs->toArray();

        if(isset($_COOKIE['lat']) && isset($_COOKIE['long'])){            
            foreach($grocerys as $key => $grocery) {    
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

        $cityRs                             =   City::select('cityId','city', 'value')
                                                    ->orderBy('city', 'asc')
                                                    ->get();  
        $cities                             =   $cityRs->toArray();      
        $commonCtrl->setMeta($request->path(),1);
        // echo "<pre>";
        // print_r($grocerys);
        
        return view('grocery',['grocery' => $grocerys, 'cities' => $cities, 'type' => $type, 'cityVal' => $cityVal, 'keyword' => $keyword]);        
    }    

    public function getDetails(Request $request,$url){

        $distance                       =   "";
        $todaysWorkingTime              =   "";
        $descriptionHeight              =   "60";
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
                                            ->get()->first();

        $grocery                            =   $groceryRs->toArray(); 

        if($grocery){
            $groceryId                      =   $grocery['id'];
            
            $lat                            =   ($grocery['latitude'])?$grocery['latitude']:'';
            $long                           =   ($grocery['longitude'])?$grocery['longitude']:'';
    
            if($lat && $long){
                $distance                   =   number_format((float)$commonCtrl->distance($lat, $long, "M"), 1, '.', '')." Miles";
            }

            $workingTimes                   =   json_decode($grocery['workingTime'], true);
            $todaysDate                     =   date("l");     
            if($workingTimes){
                foreach($workingTimes as $rootKey => $workingTime) {
                    foreach($workingTime as $subkey => $subWorkingTime) {
                        foreach($subWorkingTime as $dayKey => $dayWorkingTime) {
                            foreach($dayWorkingTime as $key => $time) {
                                $oldKey                     =   "";
                                $workingTimes[$rootKey][$subkey][$dayKey][$key]['time'] = date("H:i a", strtotime($workingTimes[$rootKey][$subkey][$dayKey][$key]['time']));
                                if($dayKey == $todaysDate){
                                    if($oldKey != $key){
                                        $todaysWorkingTime      .=   ' - '.$workingTimes[$rootKey][$subkey][$dayKey][$key]['time'];                            
                                    }else{
                                        $todaysWorkingTime      .=   ($todaysWorkingTime)?', '.$workingTimes[$rootKey][$subkey][$dayKey][$key]['time']: $workingTimes[$rootKey][$subkey][$dayKey][$key]['time'];
                                    }
                                }
                                $oldKey                         =  $key; 
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
                                                ->where('photo.groceryId', '=', $groceryId)
                                                ->orderBy('photo.order', 'asc') 
                                                ->get();        
            
            $photo                          =   $photoRs->toArray();  

            $commonCtrl->setMeta($request->path(),2);

            $descriptionHeight              =   $commonCtrl->descriptionLength(strlen($grocery['description']));
            
            return view('grocery_details',['grocery' => $grocery, 'photos' => $photo, 'distance' => $distance, 'workingTimes' => $workingTimes, 'today' => $todaysDate, 'todaysWorkingTime' => $todaysWorkingTime, 'descriptionHeight' => $descriptionHeight]);
        }else{
            return redirect()->back();
        }
    }

    public function getRelated(Request $request,$ethnicId,$id){
        
        $distance                       =   "";
        $commonCtrl                     =   new CommonController;        

        $siteId                         =   config('app.siteId');
        $relatedRs                      =   Grocery::select('grocery.id', 'grocery.name', 
                                                'address.address1', 'address.address2',                                            
                                                'city.city', 'address.state',
                                                'address.zip', 'photo.photoName',
                                                'address.phone1', 'address.latitude',
                                                'address.longitude', 'url.urlName')
                                                    ->leftjoin('url','url.groceryId', '=', 'grocery.id')
                                                    ->leftjoin('address','address.id', '=', 'grocery.addressId')
                                                    ->leftjoin('city','city.cityId', '=', 'address.city')                                                                                           
                                                    ->leftjoin('ethnic','ethnic.id', '=', 'grocery.ethnicId')
                                                    ->leftjoin('site','site.siteId', '=', 'grocery.siteId')
                                                    ->leftJoin('photo', function($join){
                                                        $join->on('photo.groceryId', '=', 'grocery.id')
                                                            ->where('photo.is_primary','=',1);
                                                        })                                                                                   
                                                    ->where('site.siteId', '=', $siteId)
                                                    ->where('grocery.id', '!=', $id)
                                                    ->where('ethnic.id', '=', $ethnicId)                                        
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
