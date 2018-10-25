<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Models\Photo;
use App\Http\Models\Url;
use App\Http\Models\City;

class EventsController extends Controller
{
    public function __construct(){}
        
    public function index(Request $request,$type,$city=null,$keyword=null){

        $typeVal                        =   "";
        $cityVal                        =   "";
        $keywordVal                     =   ""; 
        $setSeo                         =   false;       
        $siteId                         =   config('app.siteId');
        $commonCtrl                     =   new CommonController;

        if($type){
            $typeArr                        =   explode("-",$type);
            $typeVal                        =   $typeArr[count($typeArr)-1];
        }
        if($city && $city !='all'){
            $cityArr                        =   explode("-",$city);
            $cityVal                        =   $cityArr[count($cityArr)-1];
        } 
        if($keyword){
            $keywordVal                     =   $keyword;
        }         

        $cityRs                             =   City::select('cityId','city', 'value')
                                                        ->orderBy('city', 'asc')
                                                        ->get();  
        $cities                             =   $cityRs->toArray();  
        return view('events',['cities' => $cities, 'cityVal' => $cityVal, 'keyword' => $keyword]);
    }

    public function getDetails(Request $request,$url){

        return view('event_details');
    }
}
