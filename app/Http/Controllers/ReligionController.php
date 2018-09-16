<?php

namespace App\Http\Controllers;

use App\Http\Controllers\CommonController;
use Illuminate\Http\Request;
use App\Http\Models\Religion;
use App\Http\Models\Photo;
use App\Http\Models\Url;
use App\Http\Models\City;

class ReligionController extends Controller
{
    public function index(Request $request,$type,$city=null,$keyword=null){

        $typeVal                        =   "";
        $cityVal                        =   "";
        $keywordVal                     =   "";

        if($type){
            $typeArr                    =   explode("-",$type);
            $typeVal                    =   $typeArr[count($typeArr)-1];
            if(!is_numeric($typeVal)){
                $typeVal                =   "";
            }
        }
        if($city && $city !='all'){
            $cityArr                    =   explode("-",$city);
            $cityVal                    =   $cityArr[count($cityArr)-1];
        } 
        if($keyword){
            $keywordVal                 =   $keyword;
        }         

        $distance                       =   "";
        $commonCtrl                     =   new CommonController;

        $siteId                         =   config('app.siteId');
        $religionRs                     =   Religion::select('religion.id', 'religion.name', 
                                                    'address.address1', 'address.address2',
                                                    'address.zip', 
                                                    'city.city', 'address.state',
                                                    'address.phone1', 'address.latitude',
                                                    'address.longitude','religion_type.religionName',
                                                    'url.urlName', 'photo.photoName',
                                                    'denomination.denominationName')
                                                ->leftjoin('religion_type','religion_type.id', '=', 'religion.religionTypeId')                                                
                                                ->leftjoin('denomination','denomination.id', '=', 'religion.denominationId')                                                
                                                ->leftjoin('url','url.religionId', '=', 'religion.id')
                                                ->leftjoin('address','address.id', '=', 'religion.addressId')
                                                ->leftjoin('site','site.siteId', '=', 'religion.siteId')
                                                ->leftjoin('city','city.cityId', '=', 'address.city')                                                                                                                                       
                                                ->leftJoin('photo', function($join){
                                                    $join->on('photo.religionId', '=', 'religion.id')
                                                        ->where('photo.is_primary','=',1);
                                                })   
                                                ->where('religion.is_deleted', '=', '0')
                                                ->where('religion.is_disabled', '=', '0')
                                                ->where('site.siteId', '=', $siteId)
                                                ->orderBy('religion.premium', 'DESC')
                                                ->orderBy('religion.order', 'asc');                                                    

        if($cityVal){
            $religionRs->where('city.cityId', '=', $cityVal);
        }
        if($typeVal){
            $religionRs->where('religion_type.id', '=', $typeVal);
        }      
        if($keywordVal){
            $religionRs->where('religion.name', 'like', '%'.$keywordVal.'%');
        }           

        $religionRs                         =   $religionRs->get();
        $religions                          =   $religionRs->toArray();  

        if(isset($_COOKIE['lat']) && isset($_COOKIE['long'])){
            foreach($religions as $key => $religion) {    
                $distance                       =   "";
                $religions[$key]["distance"]    =   "";
                $lat                            =   ($religion['latitude'])?$religion['latitude']:'';
                $long                           =   ($religion['longitude'])?$religion['longitude']:'';
                if($lat && $long){
                    $dist                       =   $commonCtrl->distance($lat, $long, "M");
                    if($dist){
                        $religions[$key]["distance"]   =   number_format((float)$dist, 1, '.', '')." Miles";
                    }
                }
            }
        } 
        
        $cityRs                             =   City::select('cityId','city', 'value')
        ->orderBy('city', 'asc')
        ->get();  
        $cities                             =   $cityRs->toArray();      
        //print_r($religions);

        $commonCtrl->setMeta($request->path(),1);
        
        return view('religion',['religion' => $religions, 'cities' => $cities, 'type' => $type, 'cityVal' => $cityVal, 'keyword' => $keyword]);
    }

    public function getDetails(Request $request,$url){

        $distance                       =   "";
        $todaysMassTime                 =   "";
        $todaysConfessionTime           =   "";
        $todaysAdorationTime            =   "";
        $descriptionHeight              =   "20";
        $commonCtrl                     =   new CommonController;

        $seoUrl                         =   $commonCtrl->seoUrl($request->path(),2);

        $siteId                         =   config('app.siteId');
        $religionRs                     =   Url::select('religion.id', 'religion.name', 
                                                'religion.description', 'religion.workingTime',
                                                'religion.website',
                                                'address.address1', 'address.address2',
                                                'address.zip', 'religion.shortDescription',
                                                'city.city', 'address.state',
                                                'address.phone1', 'address.latitude',
                                                'address.longitude','religion_type.religionName',
                                                'denomination.denominationName')
                                            ->leftjoin('religion','url.religionId', '=', 'religion.id')
                                            ->leftjoin('religion_type','religion_type.id', '=', 'religion.religionTypeId')                                                
                                            ->leftjoin('denomination','denomination.id', '=', 'religion.denominationId')                                                
                                            ->leftjoin('address','address.id', '=', 'religion.addressId')
                                            ->leftjoin('city','city.cityId', '=', 'address.city')         
                                            ->where('religion.is_deleted', '=', '0')
                                            ->where('religion.is_disabled', '=', '0')
                                            ->where('url.urlName', '=', $url)
                                            ->get()->first(); 
        
        if($religionRs){

            $lat                            =   ($religionRs['latitude'])?$religionRs['latitude']:'';
            $long                           =   ($religionRs['longitude'])?$religionRs['longitude']:'';
    
            if($lat && $long){
                $distance                   =   number_format((float)$commonCtrl->distance($lat, $long, "M"), 1, '.', '')." Miles";
            }

            $workingTimes                   =   json_decode($religionRs['workingTime'], true);

            $todaysDate                     =   date("l");

            if($workingTimes){
                foreach($workingTimes as $rootKey => $workingTime) {
                    foreach($workingTime as $subkey => $subWorkingTime) {
                        foreach($subWorkingTime as $dayKey => $dayWorkingTime) {                          
                            foreach($dayWorkingTime as $key => $time) {
                                $workingTimes[$rootKey][$subkey][$dayKey][$key]['time'] = date("H:i a", strtotime($workingTimes[$rootKey][$subkey][$dayKey][$key]['time']));
                                if($dayKey == $todaysDate && $rootKey == "Mass"){
                                    $todaysMassTime             .=   ($todaysMassTime)?', '.date("H:i a", strtotime($workingTimes[$rootKey][$subkey][$dayKey][$key]['time'])):date("H:i a", strtotime($workingTimes[$rootKey][$subkey][$dayKey][$key]['time']));
                                }elseif($dayKey == $todaysDate && $rootKey == "Confession"){
                                    $todaysConfessionTime       .=   ($todaysConfessionTime)?', '.date("H:i a", strtotime($workingTimes[$rootKey][$subkey][$dayKey][$key]['time'])):date("H:i a", strtotime($workingTimes[$rootKey][$subkey][$dayKey][$key]['time']));
                                }else if($dayKey == $todaysDate && $rootKey == "Adoration"){
                                    $todaysAdorationTime        .=   ($todaysAdorationTime)?', '.date("H:i a", strtotime($workingTimes[$rootKey][$subkey][$dayKey][$key]['time'])):date("H:i a", strtotime($workingTimes[$rootKey][$subkey][$dayKey][$key]['time']));
                                }
                            }
                        }
                    }
                 }
            }            
            $religion                       =   $religionRs->toArray(); 
            $religionId                     =   $religion['id'];
    
            $photoRs                        =   Photo::select('photo.photoId', 'photo.photoName', 
                                                    'photo.is_primary', 'photo.order')
                                                ->where('photo.is_deleted', '=', '0')
                                                ->where('photo.is_primary', '=', '0')
                                                ->where('photo.is_disabled', '=', '0')
                                                ->where('photo.religionId', '=', $religionId)
                                                ->orderBy('photo.order', 'asc') 
                                                ->get();        
            
            $photo                          =   $photoRs->toArray();  

            $commonCtrl->setMeta($request->path(),2);
            
            // $now = strtotime("now");
            // $yourTime   =   strtotime('2018-06-22 11:04:00');
            // $diff  = $now - $yourTime;

            // $hours = floor($diff / (60 * 60));
            // $minutes = $diff - $hours * (60 * 60);

                
            // echo $todaysDate;exit();  
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
    }

    public function getRelated(Request $request, $denominationName,$id){
        
        $distance                       =   "";
        $commonCtrl                     =   new CommonController;        

        $siteId                         =   config('app.siteId');
        $relatedRs                      =   Religion::select('religion.id', 'religion.name', 
                                                'address.address1', 'address.address2',
                                                'address.zip', 
                                                'city.city', 'address.state',
                                                'address.phone1', 'address.latitude',
                                                'address.longitude','religion_type.religionName',
                                                'url.urlName', 'photo.photoName',
                                                'denomination.denominationName')
                                            ->leftjoin('religion_type','religion_type.id', '=', 'religion.religionTypeId')                                                
                                            ->leftjoin('denomination','denomination.id', '=', 'religion.denominationId')                                                
                                            ->leftjoin('url','url.religionId', '=', 'religion.id')
                                            ->leftjoin('address','address.id', '=', 'religion.addressId')
                                            ->leftjoin('site','site.siteId', '=', 'religion.siteId')
                                            ->leftjoin('photo','photo.religionId', '=', 'religion.id')   
                                            ->leftjoin('city','city.cityId', '=', 'address.city')                                             
                                            ->where('religion.is_deleted', '=', '0')
                                            ->where('religion.is_disabled', '=', '0')
                                            ->where('site.siteId', '=', $siteId)
                                            ->where('religion.id', '!=', $id)
                                            //->where('denomination.denominationName', '=', $denominationName)
                                            ->where('photo.is_primary', '=', '1')
                                            ->where('religion.is_deleted', '=', '0')
                                            ->where('religion.is_disabled', '=', '0')                                            
                                            ->orderBy('religion.premium', 'desc')
                                            ->orderBy('religion.order', 'asc')                                                    
                                            ->take(10)->get();
        
        $related                        =   $relatedRs->toArray();  

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
                
        return view('related',['related' => $related, 'type' => 'religion']);
    }    
}
