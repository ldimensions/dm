<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Models\Restaurant;
use App\Http\Models\Photo;
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
                                                'ethnic.ethnicName', 'url.urlName')
                                            ->leftjoin('url','url.restaurantId', '=', 'restaurant.id')
                                            ->leftjoin('address','address.id', '=', 'restaurant.addressId')
                                            ->leftjoin('ethnic','ethnic.id', '=', 'restaurant.ethnicId')
                                            ->leftjoin('site','site.siteId', '=', 'restaurant.siteId')  
                                            ->leftjoin('city','city.cityId', '=', 'address.city')                                           
                                            ->where('restaurant.is_deleted', '=', '0')
                                            ->where('site.siteId', '=', $siteId)
                                            ->orderBy('restaurant.premium', 'DESC')
                                            ->orderBy('restaurant.order', 'ASC');                                                  
            

        $restaurantRs                    =   $restaurantRs->get();
        $restaurants                     =   $restaurantRs->toArray();

        return view('admin.restaurant_listing',['restaurant' => $restaurants]);          
    }  

    public function addRestaurantView($id=null){
        
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
        return view('admin.restaurant_add',['restaurant' => $restaurant, 'cities' => $cities, 'photos' => $photoRs]); 
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
                        'longitude'     => $restaurantVal['latitude'],                   
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
                    $resizeImage                = Image::make($file);
                    $resizeImage->resize(466,350);
                    $path                       = public_path('image/restaurant/'.$restaurantVal['id'].'/'.$restaurantVal['urlName'].'-'.$key.'-'.$rand.'.'.$extension);
                    $resizeImage->save($path); 

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
                    $resizeImage                = Image::make($file);
                    $resizeImage->resize(128,95);
                    $path                       = public_path('image/restaurant/'.$restaurantVal['id'].'/'.$restaurantVal['urlName'].'-'.$key.'-'.$rand.'.'.$extension);
                    $resizeImage->save($path);                

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
                                                        'longitude'     => $restaurantVal['latitude'],
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
                    $resizeImage                = Image::make($file);
                    $resizeImage->resize(466,350);
                    $path                       = public_path('image/restaurant/'.$restaurantId.'/'.$restaurantVal['urlName'].'-'.$key.'-'.$rand.'.'.$extension);
                    $resizeImage->save($path);                     
    
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
                    $resizeImage                = Image::make($file);
                    $resizeImage->resize(128,95);
                    $path                       = public_path('image/restaurant/'.$restaurantId.'/'.$restaurantVal['urlName'].'-'.$key.'-'.$rand.'.'.$extension);
                    $resizeImage->save($path);                     
    
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
}



