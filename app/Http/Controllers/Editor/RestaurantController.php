<?php

namespace App\Http\Controllers\Editor;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Models\Restaurant;
use App\Http\Models\RestaurantTemp;
use App\Http\Models\Photo;
use App\Http\Models\PhotoTmp;
use App\Http\Models\FoodType;
use App\Http\Models\RestaurantFoodType;
use App\Http\Models\RestaurantFoodTypeTmp;
use App\Http\Models\Url;
use App\Http\Models\City;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Image;

class RestaurantController extends Controller
{
    public function __construct(){
        $this->middleware('role:Editor');
    }

    public function index(){
        $siteId                         =   config('app.siteId');
        $user                           =   Auth::user();

        $restaurantRs                   =   Restaurant::select('restaurant.id as restaurantId', 'restaurant.name', 
                                                                'city.city', 'restaurant.premium', 'restaurant.is_disabled', 'restaurant_tmp.referenceId',
                                                                'ethnic.ethnicName', 'url.urlName', 'restaurant.updated_at','users.name as updatedBy')
                                                        ->leftjoin('url','url.restaurantId', '=', 'restaurant.id')
                                                        ->leftjoin('address','address.id', '=', 'restaurant.addressId')
                                                        ->leftjoin('ethnic','ethnic.id', '=', 'restaurant.ethnicId')
                                                        ->leftjoin('site','site.siteId', '=', 'restaurant.siteId')  
                                                        ->leftjoin('city','city.cityId', '=', 'address.city')    
                                                        ->leftjoin('users','users.id', '=', 'restaurant.updated_by')      
                                                        ->leftjoin('restaurant_tmp','restaurant.id', '=', 'restaurant_tmp.referenceId')                                  
                                                        ->where('restaurant.is_deleted', '=', '0')
                                                        ->where('site.siteId', '=', $siteId)
                                                        ->orderBy('restaurant.order', 'ASC');                                                  

        $restaurantRs                    =   $restaurantRs->get();
        $restaurants                     =   $restaurantRs->toArray();

        $restaurantTmpRs                 =   RestaurantTemp::select('restaurant_tmp.id as restaurantId', 'restaurant_tmp.name', 
                                                                    'restaurant_tmp.premium', 'restaurant_tmp.is_disabled', 'restaurant_tmp.status',
                                                                    'ethnic.ethnicName', 'url.urlName', 'city.city',
                                                                    'restaurant_tmp.updated_at','users.name as updatedBy')
                                                                ->leftjoin('url','url.restaurantTempId', '=', 'restaurant_tmp.id')
                                                                ->leftjoin('address_tmp','address_tmp.id', '=', 'restaurant_tmp.addressId')
                                                                ->leftjoin('ethnic','ethnic.id', '=', 'restaurant_tmp.ethnicId')
                                                                ->leftjoin('site','site.siteId', '=', 'restaurant_tmp.siteId')  
                                                                ->leftjoin('city','city.cityId', '=', 'address_tmp.city')    
                                                                ->leftjoin('users','users.id', '=', 'restaurant_tmp.updated_by')                                       
                                                                ->where('restaurant_tmp.is_deleted', '=', '0')
                                                                ->where('site.siteId', '=', $siteId)
                                                                ->orderBy('restaurant_tmp.updated_at', 'ASC');                                                  

        $restaurantTmpRs                 =   $restaurantTmpRs->get();
        $restaurantsTmp                  =   $restaurantTmpRs->toArray();        

        return view('editor.restaurant_listing',['restaurant' => $restaurants, 'restaurant_pending' => $restaurantsTmp]);          
                
    }  

    public function addRestaurantView($id=null){        

        $resFoodTypes                       =   array();
        
        if($id){
            $restaurantRs                   =   RestaurantTemp::select('restaurant_tmp.id', 'restaurant_tmp.name', 'restaurant_tmp.referenceId',
                                                        'restaurant_tmp.description', 'restaurant_tmp.workingTime',
                                                        'restaurant_tmp.premium', 'restaurant_tmp.order','restaurant_tmp.is_disabled', 
                                                        'address_tmp.address1', 'address_tmp.address2', 'address_tmp.id as addressId',
                                                        'restaurant_tmp.website',  'url.urlName', 'url.id as urlId',   
                                                        'restaurant_tmp.status', 'restaurant_tmp.statusMsg',                                           
                                                        'address_tmp.state', 'address_tmp.city as city',
                                                        'address_tmp.zip', 'address_tmp.county',
                                                        'address_tmp.phone1', 'address_tmp.phone2', 'address_tmp.latitude',
                                                        'address_tmp.longitude', 'ethnic.ethnicName',
                                                        'ethnic.id as ethnic',
                                                        'seo_tmp.seoId', 'seo_tmp.SEOMetaTitle',
                                                        'seo_tmp.SEOMetaDesc', 'seo_tmp.SEOMetaPublishedTime',
                                                        'seo_tmp.SEOMetaKeywords', 'seo_tmp.OpenGraphTitle',
                                                        'seo_tmp.OpenGraphDesc', 'seo_tmp.OpenGraphUrl',
                                                        'seo_tmp.OpenGraphPropertyType', 'seo_tmp.OpenGraphPropertyLocale',
                                                        'seo_tmp.OpenGraphPropertyLocaleAlternate', 'seo_tmp.OpenGraph')
                                                    ->leftjoin('url','url.restaurantTempId', '=', 'restaurant_tmp.id')
                                                    ->leftjoin('address_tmp','address_tmp.id', '=', 'restaurant_tmp.addressId')
                                                    ->leftjoin('ethnic','ethnic.id', '=', 'restaurant_tmp.ethnicId')
                                                    ->leftjoin('site','site.siteId', '=', 'restaurant_tmp.siteId')
                                                    ->leftjoin('city','city.cityId', '=', 'address_tmp.city')   
                                                    ->leftjoin('seo_tmp','seo_tmp.urlId', '=', 'url.id')                                                    
                                                    ->where('restaurant_tmp.id', '=', $id)
                                                    ->get()->first(); 

            $restaurant                     =   $restaurantRs->toArray(); 
            $restaurant['ref_id']           =   "";

            $photoArr                       =   PhotoTmp::select('photoName','is_primary')
                                                        ->orderBy('order', 'desc')
                                                        ->where('restaurantId', '=', $id)
                                                        ->get();  
            $photoRs                        =   $photoArr->toArray();    
            $resFoodTypeRs                  =   RestaurantFoodTypeTmp::select('foodTypeId')
                                                    ->where('restaurantId', '=', $id)
                                                    ->get();  
            $resFoodTypes1                  =   $resFoodTypeRs->toArray(); 
            if(count($resFoodTypes1) > 0){
                for($i = 0; $i < count($resFoodTypes1); $i++){
                    $resFoodTypes[$i]       =   $resFoodTypes1[$i]['foodTypeId'];
                }
            }
        }else{
            $restaurant['ref_id']              =   "";
            $restaurant['id']                  =   "";
            $restaurant['addressId']           =   "";
            $restaurant['urlId']               =   "";
            $restaurant['name']                =   "";
            $restaurant['description']         =   "";
            $restaurant['workingTime']         =   "";
            $restaurant['address1']            =   "";
            $restaurant['address2']            =   "";
            $restaurant['website']             =   "";
            $restaurant['urlName']             =   "";
            $restaurant['city']                =   "";
            $restaurant['state']               =   "";
            $restaurant['zip']                 =   "";
            $restaurant['county']              =   "";
            $restaurant['phone1']              =   "";
            $restaurant['phone2']              =   "";
            $restaurant['latitude']            =   "";
            $restaurant['longitude']           =   "";
            $restaurant['ethnic']              =   "";
            $restaurant['premium']             =   "";
            $restaurant['is_disabled']         =   "";
            $restaurant['order']               =   "";
            $restaurant['status']               =   ""; 
            $restaurant['seoId']                               =   ""; 
            $restaurant['SEOMetaTitle']                        =   ""; 
            $restaurant['SEOMetaDesc']                         =   ""; 
            $restaurant['SEOMetaPublishedTime']                =   ""; 
            $restaurant['SEOMetaKeywords']                     =   ""; 
            $restaurant['OpenGraphTitle']                      =   ""; 
            $restaurant['OpenGraphDesc']                       =   ""; 
            $restaurant['OpenGraphUrl']                        =   ""; 
            $restaurant['OpenGraphPropertyType']               =   ""; 
            $restaurant['OpenGraphPropertyLocale']             =   ""; 
            $restaurant['OpenGraphPropertyLocaleAlternate']    =   ""; 
            $restaurant['OpenGraph']                           =   "";  
            
            
            $photoRs                        =   array();
        }

        $cityRs                             =   City::select('cityId','city', 'value')
                                                    ->orderBy('city', 'asc')
                                                    ->get();  
        $cities                             =   $cityRs->toArray();  

        $foodTypeRs                         =   FoodType::select('id','type')
                                                    ->orderBy('type', 'asc')
                                                    ->get();  
        $foodType                           =   $foodTypeRs->toArray(); 

        return view('editor.restaurant_add',['restaurant' => $restaurant, 'cities' => $cities, 'photos' => $photoRs, 'foodTypes' => $foodType, 'resFoodTypes' => $resFoodTypes]); 
    }

    public function addRestauranDuplicatetView($id){
        $resFoodTypes                       =   array();
        
        if($id){
            $restaurantRs                   =   Restaurant::select('restaurant.id', 'restaurant.name', 
                                                        'restaurant.description', 'restaurant.workingTime',
                                                        'restaurant.premium', 'restaurant.order','restaurant.is_disabled', 
                                                        'address.address1', 'address.address2', 'address.id as addressId',
                                                        'restaurant.website',  'url.urlName', 'url.id as urlId',                                              
                                                        'address.state', 'address.city as city',
                                                        'address.zip', 'address.county',
                                                        'address.phone1', 'address.phone2', 'address.latitude',
                                                        'address.longitude', 'ethnic.ethnicName',
                                                        'ethnic.id as ethnic',
                                                        'seo.seoId', 'seo.SEOMetaTitle',
                                                        'seo.SEOMetaDesc', 'seo.SEOMetaPublishedTime',
                                                        'seo.SEOMetaKeywords', 'seo.OpenGraphTitle',
                                                        'seo.OpenGraphDesc', 'seo.OpenGraphUrl',
                                                        'seo.OpenGraphPropertyType', 'seo.OpenGraphPropertyLocale',
                                                        'seo.OpenGraphPropertyLocaleAlternate', 'seo.OpenGraph')
                                                    ->leftjoin('url','url.restaurantId', '=', 'restaurant.id')
                                                    ->leftjoin('address','address.id', '=', 'restaurant.addressId')
                                                    ->leftjoin('ethnic','ethnic.id', '=', 'restaurant.ethnicId')
                                                    ->leftjoin('site','site.siteId', '=', 'restaurant.siteId')
                                                    ->leftjoin('city','city.cityId', '=', 'address.city')   
                                                    ->leftjoin('seo','seo.urlId', '=', 'url.id')                                                    
                                                    ->where('restaurant.id', '=', $id)
                                                    ->get()->first(); 

            $restaurant                     =   $restaurantRs->toArray(); 
            $restaurant['status']           =   "";
            $restaurant['ref_id']           =   $restaurant['id'];
            $restaurant['id']               =   "";
            $restaurant['addressId']        =   "";
            $restaurant['seoId']            =   "";

            $photoArr                       =   Photo::select('photoName','is_primary')
                                                        ->orderBy('order', 'desc')
                                                        ->where('restaurantId', '=', $id)
                                                        ->get();  
            $photoRs                        =   $photoArr->toArray();    
            
            $resFoodTypeRs                  =   RestaurantFoodType::select('foodTypeId')
                                                    ->where('restaurantId', '=', $id)
                                                    ->get();  
            $resFoodTypes1                  =   $resFoodTypeRs->toArray(); 
            if(count($resFoodTypes1) > 0){
                for($i = 0; $i < count($resFoodTypes1); $i++){
                    $resFoodTypes[$i]       =   $resFoodTypes1[$i]['foodTypeId'];
                }
            }
        }else{
            $restaurant['ref_id']              =   "";
            $restaurant['id']                  =   "";
            $restaurant['addressId']           =   "";
            $restaurant['urlId']               =   "";
            $restaurant['name']                =   "";
            $restaurant['description']         =   "";
            $restaurant['workingTime']         =   "";
            $restaurant['address1']            =   "";
            $restaurant['address2']            =   "";
            $restaurant['website']             =   "";
            $restaurant['urlName']             =   "";
            $restaurant['city']                =   "";
            $restaurant['state']               =   "";
            $restaurant['zip']                 =   "";
            $restaurant['county']              =   "";
            $restaurant['phone1']              =   "";
            $restaurant['phone2']              =   "";
            $restaurant['latitude']            =   "";
            $restaurant['longitude']           =   "";
            $restaurant['ethnic']              =   "";
            $restaurant['premium']             =   "";
            $restaurant['is_disabled']         =   "";
            $restaurant['order']               =   ""; 
            $restaurant['status']              =   "";             
            $restaurant['seoId']                               =   ""; 
            $restaurant['SEOMetaTitle']                        =   ""; 
            $restaurant['SEOMetaDesc']                         =   ""; 
            $restaurant['SEOMetaPublishedTime']                =   ""; 
            $restaurant['SEOMetaKeywords']                     =   ""; 
            $restaurant['OpenGraphTitle']                      =   ""; 
            $restaurant['OpenGraphDesc']                       =   ""; 
            $restaurant['OpenGraphUrl']                        =   ""; 
            $restaurant['OpenGraphPropertyType']               =   ""; 
            $restaurant['OpenGraphPropertyLocale']             =   ""; 
            $restaurant['OpenGraphPropertyLocaleAlternate']    =   ""; 
            $restaurant['OpenGraph']                           =   "";  
            
            $photoRs                        =   array();
        }

        $cityRs                             =   City::select('cityId','city', 'value')
                                                    ->orderBy('city', 'asc')
                                                    ->get();  
        $cities                             =   $cityRs->toArray();  

        $foodTypeRs                         =   FoodType::select('id','type')
                                                    ->orderBy('type', 'asc')
                                                    ->get();  
        $foodType                           =   $foodTypeRs->toArray(); 

        return view('editor.restaurant_add',['restaurant' => $restaurant, 'cities' => $cities, 'photos' => $photoRs, 'foodTypes' => $foodType, 'resFoodTypes' => $resFoodTypes]);         
    }

    public function addRestaurant(Request $request)
    {

        $restaurantVal                      =   $request->post();
        $user                               =   Auth::user();

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'urlName' => [
                        'required',
                        Rule::unique('url')->ignore($restaurantVal['urlId'], 'id'),
            ],
            'address1' => 'required',
            'foodType' => 'required',
            'city' => 'required',
            'state' => 'required',
            'photos.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'thumbnail.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048'            
        ]);

        if ($validator->fails()) {
            if($restaurantVal['id']){
                return redirect('/editor/restaurant_add/'.$restaurantVal['id'])->withErrors($validator)->withInput();
            }else if($restaurantVal['ref_id']){
                return redirect('/editor/restaurant_add_duplicate/'.$restaurantVal['ref_id'])->withErrors($validator)->withInput();                
            }else{
                return redirect('/editor/restaurant_add')->withErrors($validator)->withInput();
            }
        }

        if($restaurantVal['id']){
            
            DB::table('restaurant_tmp')
            ->where('id', $restaurantVal['id'])
            ->update(
                [
                    'name'          => $restaurantVal['name'],
                    'description'   => $restaurantVal['description'],
                    'workingTime'   => $restaurantVal['workingTime'],
                    'ethnicId'      => $restaurantVal['ethnic'],
                    'status'        => '2',
                    'siteId'        => config('app.siteId'),
                    'website'       => $restaurantVal['website'],
                    'order'         => ($restaurantVal['order'])?$restaurantVal['order']:0,
                    'premium'       => $restaurantVal['premium'],
                    'is_disabled'   => $restaurantVal['is_disabled'],
                    'updated_by'    => Auth::user()->id,
                    'updated_at'    => date("Y-m-d H:i:s")                    
                ]
            );
            DB::table('address_tmp')
                ->where('id', $restaurantVal['addressId'])
                ->update(
                    [
                        'address1'      => $restaurantVal['address1'],
                        'address2'      => $restaurantVal['address2'],
                        'city'          => $restaurantVal['city'],
                        'state'         => $restaurantVal['state'],
                        'zip'           => $restaurantVal['zip'],
                        'county'        => $restaurantVal['county'],
                        'phone1'        => $restaurantVal['phone1'],
                        'phone2'        => $restaurantVal['phone2'],
                        'latitude'      => $restaurantVal['latitude'],
                        'longitude'     => $restaurantVal['longitude'],                   
                    ]
            );
            if($restaurantVal['urlName'] != $restaurantVal['urlNameChk']){
                DB::table('url')
                ->where('id', $restaurantVal['urlId'])
                ->update(
                    [
                        'urlName'       => $restaurantVal['urlName'],
                        'updated_at'    => date("Y-m-d H:i:s")                 
                    ]
                );
            }
            DB::table('seo_tmp')
                ->where('seoId', $restaurantVal['seoId'])
                ->update(
                    [
                        'SEOMetaTitle'                      => $restaurantVal['SEOMetaTitle'],
                        'SEOMetaDesc'                       => $restaurantVal['SEOMetaDesc'],
                        'SEOMetaPublishedTime'              => $restaurantVal['SEOMetaPublishedTime'],
                        'SEOMetaKeywords'                   => $restaurantVal['SEOMetaKeywords'],
                        'OpenGraphTitle'                    => $restaurantVal['OpenGraphTitle'],
                        'OpenGraphDesc'                     => $restaurantVal['OpenGraphDesc'],
                        'OpenGraphUrl'                      => $restaurantVal['OpenGraphUrl'],
                        'OpenGraphPropertyType'             => $restaurantVal['OpenGraphPropertyType'],
                        'OpenGraphPropertyLocale'           => $restaurantVal['OpenGraphPropertyLocale'],
                        'OpenGraphPropertyLocaleAlternate'  => $restaurantVal['OpenGraphPropertyLocaleAlternate'],
                        'OpenGraph'                         => $restaurantVal['OpenGraph'],
                        'updated_at'                        => date("Y-m-d H:i:s")
                    ]
                ); 

            if($restaurantVal['foodType']){
                DB::table('restaurant_food_type_tmp')->where('restaurantId', $restaurantVal['id'])->delete();
                for($i =0; $i < count($restaurantVal['foodType']); $i++){
                    DB::table('restaurant_food_type_tmp')->insert([
                        ['foodTypeId' => $restaurantVal['foodType'][$i], 
                        'restaurantId' => $restaurantVal['id']]
                    ]);  
                }
            }                  

            if (!file_exists(public_path().'/image/restaurant/'.$restaurantVal['id'].'_tmp')) {
                mkdir(public_path().'/image/restaurant/'.$restaurantVal['id'].'_tmp', 0777, true);
            }
            if($request->hasFile('photos')){
                $files                          = $request->file('photos');
                
                DB::table('photo_tmp')->where('restaurantId', $restaurantVal['id'])->where('is_primary', 0)->delete();
                
            
                foreach($files as $key=> $file){
                    $filename                   = $file->getClientOriginalName();
                    $rand                       = (rand(10,100));
                    $extension                  = $file->getClientOriginalExtension();                
                    $fileName                   = $restaurantVal['urlName'].'-'.$key.'-'.$rand.'.'.$extension;
                    //$file->move(public_path().'/image/restaurant/'.$restaurantVal['id'], $fileName); 
                    //$resizeImage                = Image::make($file);
                    //$resizeImage->resize(466,350);
                    //$path                       = public_path('image/restaurant/'.$restaurantVal['id'].'/'.$restaurantVal['urlName'].'-'.$key.'-'.$rand.'.'.$extension);
                    //$resizeImage->save($path); 

                    $file->move(public_path().'/image/restaurant/'.$restaurantVal['id'].'_tmp', $restaurantVal['urlName'].'-'.$key.'-'.$rand.'.'.$extension); 

                    DB::table('photo_tmp')->insertGetId(
                        [
                            'photoName'         => $fileName,
                            'order'             => $key,
                            'restaurantId'      => $restaurantVal['id'],
                            'updated_at'  => date("Y-m-d H:i:s")
                        ]
                    );
                }
            }
            if($request->hasFile('thumbnail')){
                $files                          = $request->file('thumbnail');
                
                DB::table('photo_tmp')->where('restaurantId', $restaurantVal['id'])->where('is_primary', 1)->delete();
            
                foreach($files as $key=> $file){
                    $filename                   = $file->getClientOriginalName();
                    $rand                       = (rand(10,1000));
                    $extension                  = $file->getClientOriginalExtension();                
                    $fileName                   = $restaurantVal['urlName'].'-'.$key.'-'.$rand.'.'.$extension;
                    //$file->move(public_path().'/image/restaurant/'.$restaurantVal['id'], $fileName); 
                    //$resizeImage                = Image::make($file);
                    //$resizeImage->resize(128,95);
                    //$path                       = public_path('image/restaurant/'.$restaurantVal['id'].'/'.$restaurantVal['urlName'].'-'.$key.'-'.$rand.'.'.$extension);
                    //$resizeImage->save($path);      
                    
                    $file->move(public_path().'/image/restaurant/'.$restaurantVal['id'].'_tmp', $restaurantVal['urlName'].'-'.$key.'-'.$rand.'.'.$extension); 

                    DB::table('photo_tmp')->insertGetId(
                        [
                            'photoName'         => $fileName,
                            'order'             => $key,
                            'restaurantId'      => $restaurantVal['id'],
                            'is_primary'        => 1,
                            'updated_at'  => date("Y-m-d H:i:s")
                        ]
                    );
                }
            }               
            return redirect('/editor/restaurant')->with('status', 'Restaurant updated!');                    
        }else{

            $restaurantId                      =   DB::table('restaurant_tmp')->insertGetId(
                                                    [
                                                        'name'          => $restaurantVal['name'],
                                                        'description'   => $restaurantVal['description'],
                                                        'referenceId'   => ($restaurantVal['ref_id'])?$restaurantVal['ref_id']:0,
                                                        'status'        => 2,
                                                        'workingTime'   => $restaurantVal['workingTime'],
                                                        'ethnicId'      => $restaurantVal['ethnic'],
                                                        'siteId'        => config('app.siteId'),
                                                        'website'       => $restaurantVal['website'],
                                                        'order'         => ($restaurantVal['order'])?$restaurantVal['order']:0,
                                                        'premium'       => $restaurantVal['premium'],
                                                        'is_disabled'   => $restaurantVal['is_disabled'],
                                                        'urlId'         => 0,
                                                        'addressId'     => 0,
                                                        'updated_by'    => Auth::user()->id,
                                                        'created_at'  => date("Y-m-d H:i:s"),
                                                        'updated_at'  => date("Y-m-d H:i:s")
                                                    ]
                                                );

            if($restaurantVal['foodType']){
                for($i =0; $i < count($restaurantVal['foodType']); $i++){
                    DB::table('restaurant_food_type_tmp')->insert([
                        ['foodTypeId' => $restaurantVal['foodType'][$i], 
                        'restaurantId' => $restaurantId]
                    ]);  
                }
            }                                                

            $addressId                      =   DB::table('address_tmp')->insertGetId(
                                                    [
                                                        'address1'      => $restaurantVal['address1'],
                                                        'address2'      => $restaurantVal['address2'],
                                                        'city'          => $restaurantVal['city'],
                                                        'state'         => $restaurantVal['state'],
                                                        'zip'           => $restaurantVal['zip'],
                                                        'county'        => $restaurantVal['county'],
                                                        'phone1'        => $restaurantVal['phone1'],
                                                        'phone2'        => $restaurantVal['phone2'],
                                                        'latitude'      => $restaurantVal['latitude'],
                                                        'longitude'     => $restaurantVal['longitude'],
                                                    ]
                                                );

            if($restaurantVal['ref_id'] && $restaurantVal['urlId']){
                DB::table('url')
                    ->where('id', $restaurantVal['urlId'])
                    ->update(
                        [
                            'restaurantTempId'       => $restaurantId,
                        ]
                    );  
            }else{
                $urlId                          =   DB::table('url')->insertGetId(
                    [
                        'urlName'       => $restaurantVal['urlName'],
                        'restaurantTempId'  => $restaurantId,
                        'created_at'    => date("Y-m-d H:i:s"),
                        'updated_at'    => date("Y-m-d H:i:s")
                    ]
                );  
            }                                               

            DB::table('restaurant_tmp')
                ->where('id', $restaurantId)
                ->update(
                    [
                        'urlId'             => ($restaurantVal['urlId'])?$restaurantVal['urlId']:$urlId,
                        'addressId'         => $addressId
                    ]
                );

            $seoId                          =   DB::table('seo_tmp')->insertGetId(
                                                    [
                                                        'urlId'                             => ($restaurantVal['urlId'])?$restaurantVal['urlId']:$urlId,
                                                        'SEOMetaTitle'                      => $restaurantVal['SEOMetaTitle'],
                                                        'SEOMetaDesc'                       => $restaurantVal['SEOMetaDesc'],
                                                        'SEOMetaPublishedTime'              => $restaurantVal['SEOMetaPublishedTime'],
                                                        'SEOMetaKeywords'                   => $restaurantVal['SEOMetaKeywords'],
                                                        'OpenGraphTitle'                    => $restaurantVal['OpenGraphTitle'],
                                                        'OpenGraphDesc'                     => $restaurantVal['OpenGraphDesc'],
                                                        'OpenGraphUrl'                      => $restaurantVal['OpenGraphUrl'],
                                                        'OpenGraphPropertyType'             => $restaurantVal['OpenGraphPropertyType'],
                                                        'OpenGraphPropertyLocale'           => $restaurantVal['OpenGraphPropertyLocale'],
                                                        'OpenGraphPropertyLocaleAlternate'  => $restaurantVal['OpenGraphPropertyLocaleAlternate'],
                                                        'OpenGraph'                         => $restaurantVal['OpenGraph'],
                                                        'created_at'                        => date("Y-m-d H:i:s"),
                                                        'updated_at'                        => date("Y-m-d H:i:s")
                                                    ]
                                                ); 
                                                
            if(!$restaurantVal['ref_id']){
                if (!file_exists(public_path().'/image/restaurant/'.$restaurantId.'_tmp')) {
                    mkdir(public_path().'/image/restaurant/'.$restaurantId.'_tmp', 0777, true);
                }  
            }
                                              
            if($request->hasFile('photos')){
                $files                          = $request->file('photos');
               
                foreach($files as $key=> $file){
                    $filename                   = $file->getClientOriginalName();
                    $rand                       = (rand(10,100));
                    $extension                  = $file->getClientOriginalExtension();                
                    $fileName                   = $restaurantVal['urlName'].'-'.$key.'-'.$rand.'.'.$extension;
                    //$file->move(public_path().'/image/restaurant/'.$restaurantVal['id'], $fileName); 
                    //$resizeImage                = Image::make($file);
                    //$resizeImage->resize(466,350);
                    //$path                       = public_path('image/restaurant/'.$restaurantId.'/'.$restaurantVal['urlName'].'-'.$key.'-'.$rand.'.'.$extension);
                    //$resizeImage->save($path);    

                    if(!$restaurantVal['ref_id']){
                        $file->move(public_path().'/image/restaurant/'.$restaurantId.'_tmp', $restaurantVal['urlName'].'-'.$key.'-'.$rand.'.'.$extension);                         
                    }else{
                        $file->move(public_path().'/image/restaurant/'.$restaurantId, $restaurantVal['urlName'].'-'.$key.'-'.$rand.'.'.$extension);                         
                    }
    
                    DB::table('photo_tmp')->insertGetId(
                        [
                            'photoName'         => $fileName,
                            'order'             => $key,
                            'restaurantId'      => $restaurantId,
                            'created_at'  => date("Y-m-d H:i:s"),
                            'updated_at'  => date("Y-m-d H:i:s")
                        ]
                    );
                }
            }
            if($request->hasFile('thumbnail')){
                $files                          = $request->file('thumbnail');
                
                foreach($files as $key=> $file){
                    $filename                   = $file->getClientOriginalName();
                    $rand                       = (rand(10,1000));
                    $extension                  = $file->getClientOriginalExtension();                
                    $fileName                   = $restaurantVal['urlName'].'-'.$key.'-'.$rand.'.'.$extension;
                    //$file->move(public_path().'/image/restaurant/'.$restaurantVal['id'], $fileName); 
                    //$resizeImage                = Image::make($file);
                    //$resizeImage->resize(128,95);
                    //$path                       = public_path('image/restaurant/'.$restaurantId.'/'.$restaurantVal['urlName'].'-'.$key.'-'.$rand.'.'.$extension);
                    //$resizeImage->save($path);    
                    
                    if(!$restaurantVal['ref_id']){
                        $file->move(public_path().'/image/restaurant/'.$restaurantId.'_tmp', $restaurantVal['urlName'].'-'.$key.'-'.$rand.'.'.$extension); 
                    }else{
                        $file->move(public_path().'/image/restaurant/'.$restaurantId, $restaurantVal['urlName'].'-'.$key.'-'.$rand.'.'.$extension);                         
                    }
                    DB::table('photo_tmp')->insertGetId(
                        [
                            'photoName'         => $fileName,
                            'order'             => $key,
                            'restaurantId'      => $restaurantId,
                            'is_primary'        => 1,
                            'created_at'  => date("Y-m-d H:i:s"),
                            'updated_at'  => date("Y-m-d H:i:s")
                        ]
                    );
                }
            }                                                 
            return redirect('/editor/restaurant')->with('status', 'Restaurant added!');                                                
        }
    }

    public function deleteRestaurant($id){
        if($id){

            $restaurantRs                   =   RestaurantTemp::select('restaurant_tmp.urlId', 'restaurant_tmp.addressId', 'restaurant_tmp.referenceId')
                                                        ->where('restaurant_tmp.id', '=', $id)
                                                        ->get()->first();

            $restaurant                         =   $restaurantRs->toArray(); 

            DB::table('restaurant_tmp')->where('id', $id)->delete();
            DB::table('address_tmp')->where('id', $restaurant['addressId'])->delete();
            DB::table('restaurant_food_type_tmp')->where('restaurantId', $id)->delete();
            DB::table('seo_tmp')->where('urlId', $restaurant['urlId'])->delete();
            DB::table('photo_tmp')->where('restaurantId', $id)->delete();            
            if($restaurant['referenceId']){
                DB::table('url')
                    ->where('id', $restaurant['urlId'])
                    ->update(
                        [
                            'restaurantTempId'       => 0,
                        ]
                    );
            }else{
                DB::table('url')->where('restaurantTempId', $id)->delete();                
            }
            return redirect('/editor/restaurant')->with('status', 'Restaurant deleted!');
        }else{
            return redirect('/editor/restaurant')->with('status', 'Error!');            
        }
    } 

}
