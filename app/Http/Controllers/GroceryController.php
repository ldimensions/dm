<?php

namespace App\Http\Controllers;

use App\Http\Controllers\CommonController;
use Illuminate\Http\Request;
use App\Http\Models\Grocery;
use App\Http\Models\Photo;
use App\Http\Models\Url;

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
                                                'address.city', 'address.state',
                                                'address.zip', 'address.county',
                                                'address.city', 'address.state',
                                                'address.phone1', 'address.latitude',
                                                'address.longitude', 'ethnic.ethnicName',
                                                'url.urlName', 'photo.photoName')
                                            ->leftjoin('url','url.groceryId', '=', 'grocery.id')
                                            ->leftjoin('address','address.id', '=', 'grocery.addressId')
                                            ->leftjoin('ethnic','ethnic.id', '=', 'grocery.ethnicId')
                                            ->leftjoin('site','site.siteId', '=', 'grocery.siteId')
                                            ->leftjoin('photo','photo.groceryId', '=', 'grocery.id')                                            
                                            ->where('grocery.is_deleted', '=', '0')
                                            ->where('grocery.is_disabled', '=', '0')
                                            ->where('site.siteId', '=', $siteId)
                                            ->where('photo.is_primary', '=', '1')
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

        //     $today                              =    date('l');
        //     $workingTimes                       =   ($grocery['workingTime'])?json_decode($grocery['workingTime'], true):'';

        //     if($workingTimes){
        //         $now                            =   strtotime(date("Y-m-d h:i:s"));
        //         //echo (date("Y-m-d h:i:s"))."----";
        //         foreach($workingTimes['default'][0][$today] as $skey => $specificDayArr) {    
        //             foreach($specificDayArr as $sdArrkey => $specificDaySubArr) {    
        //                 foreach($specificDaySubArr as $sdkey => $specificDay) { 
        //                     $splitOldDateArr    =   explode(" ",$specificDay);
        //                     $splitNewDate       =   date("Y-m-d")." ".$splitOldDateArr[1];
        //                     $strtotime          =   strtotime($splitNewDate);
        //                     $diff  = $now - $strtotime;
        //                     //echo "----";

        //                     $hours = floor($diff / (60 * 60));
        //                     $minutes = $diff - $hours * (60 * 60);
        //                     //echo $splitNewDate.' Remaining time: ' . $hours .  ' hours, ' . floor( $minutes / 60 ) . ' minutes<br/>';
        //                 }
        //             }   
        //         }
        //     }
        // }
        
        // //echo date('l');
        
                        
        $commonCtrl->setMeta($request->path(),1);
        
        return view('grocery',['grocery' => $grocerys]);
    }

    public function getDetails(Request $request){

        $url                            =   "";
        $siteId                         =   config('app.siteId');
        $groceryRs                      =   Grocery::select('grocery.id', 'grocery.name', 
                                                'grocery.description', 'grocery.workingTime',
                                                'address.address1', 'address.address2',
                                                'address.city', 'address.state',
                                                'address.zip', 'address.county',
                                                'address.city', 'address.state',
                                                'address.phone1', 'address.latitude',
                                                'address.longitude', 'ethnic.ethnicName')
                                            ->leftjoin('url','url.groceryId', '=', 'grocery.id')
                                            ->leftjoin('address','address.id', '=', 'grocery.addressId')
                                            ->leftjoin('ethnic','ethnic.id', '=', 'grocery.ethnicId')
                                            ->leftjoin('site','site.siteId', '=', 'grocery.siteId')
                                            ->where('site.siteId', '=', $siteId)
                                            ->where('url.urlName', '=', $url)
                                            ->where('url.groceryId', '=', 'grocery.id')                                            
                                            ->where('grocery.is_deleted', '=', '0')
                                            ->where('grocery.is_disabled', '=', '0')
                                            ->get(); 
        
        $grocery                        =   $groceryRs->toArray();
        
        $groceryId                      =   '';//$grocery['id'];

        $photoRs                        =   Photo::select('photo.photoName')
                                            ->where('photo.groceryId', '=', $groceryId)                                            
                                            ->where('photo.is_deleted', '=', '0')
                                            ->where('photo.is_disabled', '=', '0')
                                            ->orderBy('photo.is_primary', 'asc')
                                            ->get(); 

        $photo                          =   $photoRs->toArray();
                
        return view('grocery_details',['grocery' => $grocery, 'photo' => $photo]);
    }
      
}
