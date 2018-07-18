<?php

namespace App\Http\Controllers;

use App\Http\Controllers\CommonController;
use Illuminate\Http\Request;
use App\Http\Models\Religion;
use App\Http\Models\Photo;
use App\Http\Models\Url;

use SEOMeta;
use OpenGraph;
use Twitter;
use Cookie;


class HomeController extends Controller
{

    public function __construct(){
        //$this->middleware('auth');
    }

    public function index(Request $request){


        $commonCtrl                     =   new CommonController;

        $siteId                         =   config('app.siteId');
        $religionRs                     =   Religion::select('religion.id', 'religion.name', 
                                                'religion.workingTime',
                                                'address.address1',
                                                'address.zip', 
                                                'address.city',
                                                'address.phone1', 'address.latitude',
                                                'address.longitude','religion_type.religionName',
                                                'url.urlName',
                                                'denomination.denominationName')
                                            ->leftjoin('religion_type','religion_type.id', '=', 'religion.religionTypeId')                                                
                                            ->leftjoin('denomination','denomination.id', '=', 'religion.denominationId')                                                
                                            ->leftjoin('url','url.religionId', '=', 'religion.id')
                                            ->leftjoin('address','address.id', '=', 'religion.addressId')
                                            ->leftjoin('site','site.siteId', '=', 'religion.siteId')
                                            ->where('religion.is_deleted', '=', '0')
                                            ->where('religion.is_disabled', '=', '0')
                                            ->where('site.siteId', '=', $siteId)
                                            ->orderBy('religion.premium', 'desc')
                                            ->orderBy('religion.order', 'asc') 
                                            ->take(5)                                                                                                      
                                            ->get(); 
        
        $religions                      =   $religionRs->toArray();  

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
        //print_r($religion);

        $commonCtrl->setMeta($request->path(),1);
        
        return view('home',['religion' => $religions]);
    }
}
