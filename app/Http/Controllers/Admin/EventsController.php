<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Models\Photo;
use App\Http\Models\Url;
use App\Http\Models\City;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class EventsController extends Controller
{
    public function __construct(){
        $this->middleware('role:Admin');
    }

    public function index(){
        $siteId                         =   config('app.siteId');
        
        

        return view('admin.events_listing',['events' => []]);          
    } 

    public function addEventsView($id=null){
        
        if($id){
            
            
        }else{
            $event['id']                  =   "";
            $event['addressId']           =   "";
            $event['urlId']               =   "";
            $event['name']                =   "";
            $event['description']         =   "";
            //$event['workingTime']         =   "";
            $event['address1']            =   "";
            $event['address2']            =   "";
            $event['website']             =   "";
            $event['urlName']             =   "";
            $event['city']                =   "";
            $event['state']               =   "";
            $event['zip']                 =   "";
            $event['county']              =   "";
            $event['phone1']              =   "";
            $event['phone2']              =   "";
            $event['latitude']            =   "";
            $event['longitude']           =   "";
            //$event['ethnic']              =   "";
            $event['premium']             =   "";
            $event['is_disabled']         =   "";
            $event['order']               =   ""; 
            $event['seoId']                               =   ""; 
            $event['SEOMetaTitle']                        =   ""; 
            $event['SEOMetaDesc']                         =   ""; 
            $event['SEOMetaPublishedTime']                =   ""; 
            $event['SEOMetaKeywords']                     =   ""; 
            $event['OpenGraphTitle']                      =   ""; 
            $event['OpenGraphDesc']                       =   ""; 
            $event['OpenGraphUrl']                        =   ""; 
            $event['OpenGraphPropertyType']               =   ""; 
            $event['OpenGraphPropertyLocale']             =   ""; 
            $event['OpenGraphPropertyLocaleAlternate']    =   ""; 
            $event['OpenGraph']                           =   "";  
            
            $photoRs                        =   array();
        }

        $cityRs                             =   City::select('cityId','city', 'value')
                                                    ->orderBy('city', 'asc')
                                                    ->get();  
        $cities                             =   $cityRs->toArray();  
        return view('admin.events_add',['event' => $event, 'cities' => $cities, 'photos' => $photoRs]); 
    }    

}
