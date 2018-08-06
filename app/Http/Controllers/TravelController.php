<?php

namespace App\Http\Controllers;

use App\Http\Controllers\CommonController;
use Illuminate\Http\Request;
use App\Http\Models\Travel;
use App\Http\Models\Photo;
use App\Http\Models\Url;
use App\Http\Models\City;

class TravelController extends Controller
{
    public function __construct(){

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

        $travelRs                       =   Travel::select('travel.id', 'travel.name', 
                                                    'travel.description', 'travel.workingTime',
                                                    'address.address1', 'address.address2',
                                                    'city.city', 'address.state',
                                                    'address.zip', 'address.county',
                                                    'address.phone1', 'address.latitude',
                                                    'address.longitude', 'ethnic.ethnicName',
                                                    'url.urlName', 'photo.photoName')
                                                ->leftjoin('url','url.travelId', '=', 'travel.id')
                                                ->leftjoin('address','address.id', '=', 'travel.addressId')
                                                ->leftjoin('ethnic','ethnic.id', '=', 'travel.ethnicId')
                                                ->leftjoin('site','site.siteId', '=', 'travel.siteId')
                                                ->leftJoin('photo', function($join){
                                                    $join->on('photo.travelId', '=', 'travel.id')
                                                        ->where('photo.is_primary','=',1);
                                                })   
                                                ->leftjoin('city','city.cityId', '=', 'address.city')                                           
                                                ->where('travel.is_deleted', '=', '0')
                                                ->where('travel.is_disabled', '=', '0')
                                                ->where('site.siteId', '=', $siteId)
                                                ->orderBy('travel.premium', 'asc')
                                                ->orderBy('travel.order', 'asc');                                                  
                                                

        if($cityVal){
            $travelRs->where('city.cityId', '=', $cityVal);
        }
        if($type){
            $travelRs->where('ethnic.id', '=', $typeVal);
        }      
        if($keywordVal){
            $travelRs->where('travel.name', 'like', '%'.$keywordVal.'%');
        }           

        $travelRs                           =   $travelRs->get();
        $travels                            =   $travelRs->toArray();

        if(isset($_COOKIE['lat']) && isset($_COOKIE['long'])){            
            foreach($travels as $key => $travel) {    
                $distance                       =   "";
                $lat                            =   ($travel['latitude'])?$travel['latitude']:'';
                $long                           =   ($travel['longitude'])?$travel['longitude']:'';
                if($lat && $long){
                    $dist                       =   $commonCtrl->distance($lat, $long, "M");
                    if($dist){
                        $travels[$key]["distance"]   =   number_format((float)$dist, 1, '.', '')." Miles";
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
        // print_r($travels);
        
        return view('travel',['travels' => $travels, 'cities' => $cities, 'type' => $type, 'cityVal' => $cityVal, 'keyword' => $keyword]);        
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
                                            'city.city', 'address.state',
                                            'address.zip', 'address.county',
                                            'address.phone1', 'address.latitude',
                                            'address.longitude', 'ethnic.ethnicName',
                                            'ethnic.id as ethnicId', 'url.urlName')
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
