<?php

namespace App\Http\Controllers\Admin;

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
        $this->middleware('role:Admin');
    }

    public function index(){
        $siteId                         =   config('app.siteId');
        
        $restaurantRs                   =   Restaurant::select('restaurant.id as restaurantId', 'restaurant.name', 
                                                'city.city', 'restaurant.premium', 'restaurant.is_disabled',
                                                'ethnic.ethnicName', 'url.urlName', 'users.name as updatedBy', 'restaurant.updated_at')
                                            ->leftjoin('url','url.restaurantId', '=', 'restaurant.id')
                                            ->leftjoin('address','address.id', '=', 'restaurant.addressId')
                                            ->leftjoin('ethnic','ethnic.id', '=', 'restaurant.ethnicId')
                                            ->leftjoin('site','site.siteId', '=', 'restaurant.siteId')  
                                            ->leftjoin('city','city.cityId', '=', 'address.city')      
                                            ->leftjoin('users','users.id', '=', 'restaurant.updated_by')                                                                                   
                                            ->where('restaurant.is_deleted', '=', '0')
                                            ->where('site.siteId', '=', $siteId)
                                            ->orderBy('restaurant.premium', 'DESC')
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
                                                                ->orderBy('restaurant_tmp.premium', 'DESC')
                                                                ->orderBy('restaurant_tmp.order', 'ASC');                                                  

        $restaurantTmpRs                 =   $restaurantTmpRs->get();
        $restaurantsTmp                  =   $restaurantTmpRs->toArray();  

        return view('admin.restaurant_listing',['restaurant' => $restaurants, 'restaurant_pending' => $restaurantsTmp]);          
    }  

    public function addRestaurantView($id=null){

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

        return view('admin.restaurant_add',['restaurant' => $restaurant, 'cities' => $cities, 'photos' => $photoRs, 'foodTypes' => $foodType, 'resFoodTypes' => $resFoodTypes]); 
    }

    public function addRestaurant(Request $request)
    {

        $restaurantVal                      =   $request->post();

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
                return redirect('/admin/restaurant_add/'.$restaurantVal['id'])->withErrors($validator)->withInput();
            }else{
                return redirect('/admin/restaurant_add')->withErrors($validator)->withInput();
            }
        }
        
        if($restaurantVal['id']){
            DB::table('restaurant')
                ->where('id', $restaurantVal['id'])
                ->update(
                    [
                        'name'          => $restaurantVal['name'],
                        'description'   => $restaurantVal['description'],
                        'workingTime'   => $restaurantVal['workingTime'],
                        'ethnicId'      => $restaurantVal['ethnic'],
                        'siteId'        => config('app.siteId'),
                        'website'       => $restaurantVal['website'],
                        'order'         => ($restaurantVal['order'])?$restaurantVal['order']:0,
                        'premium'       => $restaurantVal['premium'],
                        'is_disabled'   => $restaurantVal['is_disabled'],
                        'updated_by'    => Auth::user()->id,
                        'updated_at'    => date("Y-m-d H:i:s")                    
                    ]
                );
            DB::table('address')
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
            DB::table('seo')
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
                DB::table('restaurant_food_type')->where('restaurantId', $restaurantVal['id'])->delete();
                for($i =0; $i < count($restaurantVal['foodType']); $i++){
                    DB::table('restaurant_food_type')->insert([
                        ['foodTypeId' => $restaurantVal['foodType'][$i], 
                        'restaurantId' => $restaurantVal['id']]
                    ]);  
                }
            }                  

            if (!file_exists(public_path().'/image/restaurant/'.$restaurantVal['id'])) {
                mkdir(public_path().'/image/restaurant/'.$restaurantVal['id'], 0777, true);
            }
            if($request->hasFile('photos')){
                $files                          = $request->file('photos');
                
                DB::table('photo')->where('restaurantId', $restaurantVal['id'])->where('is_primary', 0)->delete();
                
            
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

                    $file->move(public_path().'/image/restaurant/'.$restaurantVal['id'], $restaurantVal['urlName'].'-'.$key.'-'.$rand.'.'.$extension); 

                    DB::table('photo')->insertGetId(
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
                
                DB::table('photo')->where('restaurantId', $restaurantVal['id'])->where('is_primary', 1)->delete();
            
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
                    
                    $file->move(public_path().'/image/restaurant/'.$restaurantVal['id'], $restaurantVal['urlName'].'-'.$key.'-'.$rand.'.'.$extension); 

                    DB::table('photo')->insertGetId(
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
            return redirect('/admin/restaurant')->with('status', 'Restaurant updated!');                    
        }else{

            $restaurantId                      =   DB::table('restaurant')->insertGetId(
                                                    [
                                                        'name'          => $restaurantVal['name'],
                                                        'description'   => $restaurantVal['description'],
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
                    DB::table('restaurant_food_type')->insert([
                        ['foodTypeId' => $restaurantVal['foodType'][$i], 
                        'restaurantId' => $restaurantId]
                    ]);  
                }
            }                                                

            $addressId                      =   DB::table('address')->insertGetId(
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
            $urlId                          =   DB::table('url')->insertGetId(
                                                    [
                                                        'urlName'       => $restaurantVal['urlName'],
                                                        'restaurantId'  => $restaurantId,
                                                        'created_at'    => date("Y-m-d H:i:s"),
                                                        'updated_at'    => date("Y-m-d H:i:s")
                                                    ]
                                                ); 
            DB::table('restaurant')
                ->where('id', $restaurantId)
                ->update(
                    [
                        'urlId'             => $urlId,
                        'addressId'         => $addressId
                    ]
                );

            $seoId                          =   DB::table('seo')->insertGetId(
                                                    [
                                                        'urlId'                             => $urlId,
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

            if (!file_exists(public_path().'/image/restaurant/'.$restaurantId)) {
                mkdir(public_path().'/image/restaurant/'.$restaurantId, 0777, true);
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
                    
                    $file->move(public_path().'/image/restaurant/'.$restaurantId, $restaurantVal['urlName'].'-'.$key.'-'.$rand.'.'.$extension); 
    
                    DB::table('photo')->insertGetId(
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
                    
                    $file->move(public_path().'/image/restaurant/'.$restaurantId, $restaurantVal['urlName'].'-'.$key.'-'.$rand.'.'.$extension); 
    
                    DB::table('photo')->insertGetId(
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
            return redirect('/admin/restaurant')->with('status', 'Restaurant added!');                                                
        }
    }
    
    public function deleteRestaurant($id){
        if($id){
            DB::table('restaurant')
            ->where('id', $id)
            ->update(
                [
                    'is_deleted'        => 1
                ]
            ); 
        }
        return redirect('/admin/restaurant')->with('status', 'Restaurant deleted!');
    }     

    public function rejectRestarunt(Request $request){
        $restaurantVal                      =   $request->post();
        if($restaurantVal['id']){
            DB::table('restaurant_tmp')
                ->where('id', $restaurantVal['id'])
                ->update(
                    [
                        'status'            => '4',
                        'statusMsg'         =>   $restaurantVal['reason']                                     
                    ]
                );        

            return redirect('/admin/restaurant')->with('status', 'Restaurant rejected!');
        }      
    }

    public function approveRestarunt($id){

        $restaurantTmpRs                 =   RestaurantTemp::select('restaurant_tmp.name', 'restaurant_tmp.referenceId', 'restaurant_tmp.urlId',
                                                                'restaurant_tmp.description', 'restaurant_tmp.urlId',
                                                                'restaurant_tmp.ethnicId', 'restaurant_tmp.addressId',
                                                                'restaurant_tmp.website', 'restaurant_tmp.workingTime', 'restaurant_tmp.is_disabled',
                                                                'restaurant_tmp.order', 'restaurant_tmp.premium', 'restaurant_tmp.updated_by',
                                                                'restaurant_tmp.created_at', 'restaurant_tmp.updated_at',
                                                                'address_tmp.id as addressTempId', 'seo_tmp.seoId as seoTempId',
                                                                'address_tmp.address1', 'address_tmp.address2','address_tmp.id',
                                                                'address_tmp.city', 'address_tmp.state',
                                                                'address_tmp.zip', 'address_tmp.county',
                                                                'address_tmp.phone1', 'address_tmp.phone2', 'address_tmp.fax',
                                                                'address_tmp.latitude', 'address_tmp.longitude',
                                                                'seo_tmp.keyValue', 'seo_tmp.index', 
                                                                'seo_tmp.SEOMetaTitle', 'seo_tmp.SEOMetaDesc', 
                                                                'seo_tmp.SEOMetaPublishedTime', 'seo_tmp.SEOMetaKeywords', 
                                                                'seo_tmp.OpenGraphTitle', 'seo_tmp.OpenGraphDesc', 
                                                                'seo_tmp.OpenGraphUrl', 'seo_tmp.OpenGraphPropertyType', 
                                                                'seo_tmp.OpenGraphPropertyLocale', 'seo_tmp.OpenGraphPropertyLocaleAlternate', 
                                                                'seo_tmp.OpenGraph', 'seo_tmp.created_at',
                                                                'seo_tmp.updated_at', 'restaurant_tmp.updated_by'
                                                                )     
                                                            ->leftjoin('address_tmp','address_tmp.id', '=', 'restaurant_tmp.addressId')   
                                                            ->leftjoin('seo_tmp','seo_tmp.urlId', '=', 'restaurant_tmp.urlId')   
                                                            ->where('restaurant_tmp.id', '=', $id)
                                                            ->get()->first(); 
                                                            
        $restaurantTmpArr               =   $restaurantTmpRs->toArray(); 

        if($restaurantTmpArr['referenceId']){

            $restaurantRs                   =   Restaurant::select('restaurant.urlId', 'restaurant.addressId')
                                                            ->where('restaurant.id', '=', $restaurantTmpArr['referenceId'])->get()->first();                                                   

            $restaurants                     =   $restaurantRs->toArray();            

            DB::table('restaurant')
                ->where('id', $restaurantTmpArr['referenceId'])
                ->update(
                [
                    'name'          => $restaurantTmpArr['name'],
                    'description'   => $restaurantTmpArr['description'],
                    'workingTime'   => $restaurantTmpArr['workingTime'],
                    'ethnicId'      => $restaurantTmpArr['ethnicId'],
                    'website'       => $restaurantTmpArr['website'],
                    'order'         => ($restaurantTmpArr['order'])?$restaurantTmpArr['order']:0,
                    'premium'       => $restaurantTmpArr['premium'],
                    'is_disabled'   => $restaurantTmpArr['is_disabled'],
                    'updated_by'    => $restaurantTmpArr['updated_by'],
                    'updated_at'    => date("Y-m-d H:i:s")                    
                ]
            );
            DB::table('address')
                ->where('id', $restaurants['addressId'])
                ->update(
                    [
                        'address1'      => $restaurantTmpArr['address1'],
                        'address2'      => $restaurantTmpArr['address2'],
                        'city'          => $restaurantTmpArr['city'],
                        'state'         => $restaurantTmpArr['state'],
                        'zip'           => $restaurantTmpArr['zip'],
                        'county'        => $restaurantTmpArr['county'],
                        'phone1'        => $restaurantTmpArr['phone1'],
                        'phone2'        => $restaurantTmpArr['phone2'],
                        'latitude'      => $restaurantTmpArr['latitude'],
                        'longitude'     => $restaurantTmpArr['longitude'],                   
                    ]
            );
            DB::table('url')
                ->where('id', $restaurants['urlId'])
                ->update(
                    [
                        'restaurantTempId'    => 0                
                    ]
                );
            DB::table('seo')
                ->where('urlId', $restaurants['urlId'])
                ->update(
                    [
                        'SEOMetaTitle'                      => $restaurantTmpArr['SEOMetaTitle'],
                        'SEOMetaDesc'                       => $restaurantTmpArr['SEOMetaDesc'],
                        'SEOMetaPublishedTime'              => $restaurantTmpArr['SEOMetaPublishedTime'],
                        'SEOMetaKeywords'                   => $restaurantTmpArr['SEOMetaKeywords'],
                        'OpenGraphTitle'                    => $restaurantTmpArr['OpenGraphTitle'],
                        'OpenGraphDesc'                     => $restaurantTmpArr['OpenGraphDesc'],
                        'OpenGraphUrl'                      => $restaurantTmpArr['OpenGraphUrl'],
                        'OpenGraphPropertyType'             => $restaurantTmpArr['OpenGraphPropertyType'],
                        'OpenGraphPropertyLocale'           => $restaurantTmpArr['OpenGraphPropertyLocale'],
                        'OpenGraphPropertyLocaleAlternate'  => $restaurantTmpArr['OpenGraphPropertyLocaleAlternate'],
                        'OpenGraph'                         => $restaurantTmpArr['OpenGraph'],
                        'updated_at'                        => date("Y-m-d H:i:s")
                    ]
            ); 

            $resFoodTypeRs                  =   RestaurantFoodTypeTmp::select('foodTypeId')
                                                        ->where('restaurantId', '=', $id)
                                                        ->get();  
            $resFoodTypes1                  =   $resFoodTypeRs->toArray(); 
            if(count($resFoodTypes1) >0){
                DB::table('restaurant_food_type')->where('restaurantId', $restaurantTmpArr['referenceId'])->delete();
                for($i =0; $i < count($resFoodTypes1); $i++){
                    DB::table('restaurant_food_type')->insert([
                        ['foodTypeId' => $resFoodTypes1[$i]['foodTypeId'], 
                        'restaurantId' => $restaurantTmpArr['referenceId']]
                    ]);  
                }
            } 
            
            $photoArr                       =   PhotoTmp::select('photoName','is_primary', 'order', 'is_deleted', 'is_disabled')
                                                        ->orderBy('order', 'asc')
                                                        ->where('restaurantId', '=', $id)
                                                        ->get();  
            $photoRs                        =   $photoArr->toArray();    
            
            if(count($photoRs) >0){
                DB::table('restaurant_food_type')->where('restaurantId', $restaurantTmpArr['referenceId'])->delete();
                for($i =0; $i < count($photoRs); $i++){
                    DB::table('photo_tmp')->insert([
                        ['photoName' => $photoRs[$i]['photoName'], 
                        'is_primary' => $photoRs[$i]['is_primary'], 
                        'order' => $photoRs[$i]['order'], 
                        'is_deleted' => $photoRs[$i]['is_deleted'], 
                        'is_disabled' => $photoRs[$i]['is_disabled'], 
                        'restaurantId' => $restaurantTmpArr['referenceId']]
                    ]);  
                }
            }  
            DB::table('restaurant_tmp')->where('id', $id)->delete();
            DB::table('address_tmp')->where('id', $restaurantTmpArr['addressTempId'])->delete();
            DB::table('seo_tmp')->where('urlId', $restaurantTmpArr['urlId'])->delete();        
            DB::table('restaurant_food_type_tmp')->where('restaurantId', $id)->delete();
            DB::table('photo_tmp')->where('restaurantId', $id)->delete();    
                                   
        }else{
            $addressId                      =   DB::table('address')->insertGetId(
                [
                    'address1'      => $restaurantTmpArr['address1'],
                    'address2'      => $restaurantTmpArr['address2'],
                    'city'          => $restaurantTmpArr['city'],
                    'state'         => $restaurantTmpArr['state'],
                    'zip'           => $restaurantTmpArr['zip'],
                    'county'        => $restaurantTmpArr['county'],
                    'phone1'        => $restaurantTmpArr['phone1'],
                    'phone2'        => $restaurantTmpArr['phone2'],
                    'latitude'      => $restaurantTmpArr['latitude'],
                    'longitude'     => $restaurantTmpArr['longitude'],
                ]
            );        

            $restaurantId                      =   DB::table('restaurant')->insertGetId(
                                [
                                    'name'          => $restaurantTmpArr['name'],
                                    'description'   => $restaurantTmpArr['description'],
                                    'workingTime'   => $restaurantTmpArr['workingTime'],
                                    'ethnicId'      => $restaurantTmpArr['ethnicId'],
                                    'siteId'        => config('app.siteId'),
                                    'website'       => $restaurantTmpArr['website'],
                                    'order'         => ($restaurantTmpArr['order'])?$restaurantTmpArr['order']:0,
                                    'premium'       => $restaurantTmpArr['premium'],
                                    'is_disabled'   => $restaurantTmpArr['is_disabled'],
                                    'urlId'         => $restaurantTmpArr['urlId'],
                                    'addressId'     => $addressId,
                                    'updated_by'    => $restaurantTmpArr['updated_by'],
                                    'created_at'  => $restaurantTmpArr['created_at'],
                                    'updated_at'  => $restaurantTmpArr['updated_at']
                                ]
                            );

            DB::table('url')
                ->where('id', $restaurantTmpArr['urlId'])
                ->update(
                [
                'restaurantId'          => $restaurantId,
                'restaurantTempId'      => 0
                ]
            );  

            $seoId                          =   DB::table('seo')->insertGetId(
                                                        [
                                                        'urlId'                             => $restaurantTmpArr['urlId'],
                                                        'SEOMetaTitle'                      => $restaurantTmpArr['SEOMetaTitle'],
                                                        'SEOMetaDesc'                       => $restaurantTmpArr['SEOMetaDesc'],
                                                        'SEOMetaPublishedTime'              => $restaurantTmpArr['SEOMetaPublishedTime'],
                                                        'SEOMetaKeywords'                   => $restaurantTmpArr['SEOMetaKeywords'],
                                                        'OpenGraphTitle'                    => $restaurantTmpArr['OpenGraphTitle'],
                                                        'OpenGraphDesc'                     => $restaurantTmpArr['OpenGraphDesc'],
                                                        'OpenGraphUrl'                      => $restaurantTmpArr['OpenGraphUrl'],
                                                        'OpenGraphPropertyType'             => $restaurantTmpArr['OpenGraphPropertyType'],
                                                        'OpenGraphPropertyLocale'           => $restaurantTmpArr['OpenGraphPropertyLocale'],
                                                        'OpenGraphPropertyLocaleAlternate'  => $restaurantTmpArr['OpenGraphPropertyLocaleAlternate'],
                                                        'OpenGraph'                         => $restaurantTmpArr['OpenGraph'],
                                                        'created_at'                        => $restaurantTmpArr['OpenGraph'],
                                                        'updated_at'                        => $restaurantTmpArr['OpenGraph']
                                                        ]
                                                );             

            $foodTypeRs                     =   RestaurantFoodTypeTmp::select('restaurant_food_type_tmp.foodTypeId')
                                                    ->where('restaurant_food_type_tmp.restaurantId', '=', $id)
                                                    ->get();    

            $foodTypeRs                     =   $foodTypeRs->toArray();      
            foreach($foodTypeRs as $foodType){
                DB::table('restaurant_food_type')->insert([
                    ['foodTypeId' => $foodType['foodTypeId'], 
                    'restaurantId' => $restaurantId]
                ]); 
            }   

            $photoTypeRs                     =   PhotoTmp::select('photo_tmp.photoName', 'photo_tmp.is_primary', 'photo_tmp.order')
                                                        ->where('photo_tmp.restaurantId', '=', $id)
                                                        ->get();    

            $photoTypeArr                    =   $photoTypeRs->toArray();      
            foreach($photoTypeArr as $photoType){
                DB::table('photo')->insert([
                    ['photoName' => $photoType['photoName'], 
                    'is_primary' => $photoType['is_primary'], 
                    'order' => $photoType['order'], 
                    'restaurantId' => $restaurantId]
                ]); 
            }   
            // if(count($photoTypeArr) > 0){
                if (is_dir(public_path().'/image/restaurant/'.$id.'_tmp')) {
                    rename (public_path().'/image/restaurant/'.$id.'_tmp', public_path().'/image/restaurant/'.$restaurantId);     
                }               
            // }

            DB::table('restaurant_tmp')->where('id', $id)->delete();
            DB::table('address_tmp')->where('id', $restaurantTmpArr['id'])->delete();
            DB::table('seo_tmp')->where('urlId', $restaurantTmpArr['urlId'])->delete();        
            DB::table('restaurant_food_type_tmp')->where('restaurantId', $id)->delete();
            DB::table('photo_tmp')->where('restaurantId', $id)->delete();
        }

        return redirect('/admin/restaurant')->with('status', 'Restaurant Approved!');        
    }

    public function deleteTmpRestaurant($id){
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

            if (is_dir(public_path().'/image/restaurant/'.$id.'_tmp')) {
                rmdir (public_path().'/image/restaurant/'.$id.'_tmp');     
            }             

            return redirect('/admin/restaurant')->with('status', 'Restaurant deleted!');
        }else{
            return redirect('/admin/restaurant')->with('status', 'Error!');            
        }
    }
}



