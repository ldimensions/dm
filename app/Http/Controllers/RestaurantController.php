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
    public function index(Request $request)
    {
        $siteId                         =   config('app.siteId');
        $commonCtrl                     =   new CommonController;

        $restaurantRs                   =   Restaurant::select('restaurant.id', 'restaurant.name', 
                                                'restaurant.description', 'restaurant.workingTime',
                                                'address.address1', 'address.address2',
                                                'city.city', 'address.state',
                                                'address.zip', 'address.county',
                                                'address.phone1', 'address.latitude',
                                                'address.longitude', 'ethnic.ethnicName',
                                                'url.urlName', 'photo.photoName')
                                            ->leftjoin('url','url.restaurantId', '=', 'restaurant.id')
                                            ->leftjoin('address','address.id', '=', 'restaurant.addressId')
                                            ->leftjoin('ethnic','ethnic.id', '=', 'restaurant.ethnicId')
                                            ->leftjoin('site','site.siteId', '=', 'restaurant.siteId')
                                            ->leftjoin('photo','photo.restaurantId', '=', 'restaurant.id')    
                                            ->leftjoin('city','city.cityId', '=', 'address.city')                                           
                                            ->where('restaurant.is_deleted', '=', '0')
                                            ->where('restaurant.is_disabled', '=', '0')
                                            ->where('site.siteId', '=', $siteId)
                                            ->where('photo.is_primary', '=', '1')
                                            ->orderBy('restaurant.premium', 'asc')
                                            ->orderBy('restaurant.order', 'asc')                                                    
                                            ->get(); 
        
        $restaurants                     =   $restaurantRs->toArray();

        foreach($restaurants as $key => $restaurant) {    

            if(isset($_COOKIE['lat']) && isset($_COOKIE['long'])){
                $distance                       =   "";
                $lat                            =   ($restaurant['latitude'])?$restaurant['latitude']:'';
                $long                           =   ($restaurant['longitude'])?$restaurant['longitude']:'';
                if($lat && $long){
                    $dist                       =   $commonCtrl->distance($lat, $long, "M");
                    if($dist){
                        $restaurant[$key]["distance"]   =   number_format((float)$dist, 1, '.', '')." Miles";
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
        // print_r($cities);
        
        return view('restaurant',['restaurant' => $restaurants, 'cities' => $cities]);    }

    public function getDetails(){
        echo "details";
    }
}
