<?php
namespace App\Http\Controllers;
use App\Http\Controllers\CommonController;
use Illuminate\Http\Request;
use App\Http\Models\Religion;
use App\Http\Models\Restaurant;
use App\Http\Models\Grocery;
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
                                            ->take(3)                                                                                                      
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
        // Top groceries
        
        $groceryRs                      =   Grocery::select('grocery.id', 'grocery.name', 
                                                'grocery.description', 'grocery.workingTime',
                                                'address.address1', 'address.address2',
                                                'city.city', 'address.state',
                                                'address.zip', 'address.county',
                                                'address.phone1', 'address.latitude',
                                                'address.longitude', 'ethnic.ethnicName',
                                                'url.urlName')
                                            ->leftjoin('url','url.groceryId', '=', 'grocery.id')
                                            ->leftjoin('address','address.id', '=', 'grocery.addressId')
                                            ->leftjoin('ethnic','ethnic.id', '=', 'grocery.ethnicId')
                                            ->leftjoin('site','site.siteId', '=', 'grocery.siteId')
                                            ->leftjoin('city','city.cityId', '=', 'address.city')                                           
                                            ->where('grocery.is_deleted', '=', '0')
                                            ->where('grocery.is_disabled', '=', '0')
                                            ->where('site.siteId', '=', $siteId)
                                            ->orderBy('grocery.premium', 'desc')
                                            ->orderBy('grocery.order', 'asc') 
                                            ->take(3)                                                                                                      
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
        // Top restaurants
        $restaurantRs                   =   Restaurant::select('restaurant.id', 'restaurant.name', 
                                                    'restaurant.description', 'restaurant.workingTime',
                                                    'address.address1', 'address.address2',
                                                    'city.city', 'address.state',
                                                    'address.zip', 'address.county',
                                                    'address.phone1', 'address.latitude',
                                                    'address.longitude', 'ethnic.ethnicName',
                                                    'url.urlName')
                                                ->leftjoin('url','url.restaurantId', '=', 'restaurant.id')
                                                ->leftjoin('address','address.id', '=', 'restaurant.addressId')
                                                ->leftjoin('ethnic','ethnic.id', '=', 'restaurant.ethnicId')
                                                ->leftjoin('site','site.siteId', '=', 'restaurant.siteId')
                                                ->leftjoin('city','city.cityId', '=', 'address.city')                                           
                                                ->where('restaurant.is_deleted', '=', '0')
                                                ->where('restaurant.is_disabled', '=', '0')
                                                ->where('site.siteId', '=', $siteId)
                                                ->orderBy('restaurant.premium', 'desc')
                                                ->orderBy('restaurant.order', 'asc') 
                                                ->take(3)                                                                                                      
                                                ->get(); 
        
        $restaurants                     =   $restaurantRs->toArray();
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
        
        $commonCtrl->setMeta($request->path(),1);
        
        return view('home',['religion' => $religions, 'grocery' => $grocerys, 'restaurants' => $restaurants]);
    }
}
