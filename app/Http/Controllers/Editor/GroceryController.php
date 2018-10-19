<?php

namespace App\Http\Controllers\Editor;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Models\Grocery;
use App\Http\Models\GroceryTmp;
use App\Http\Models\Photo;
use App\Http\Models\PhotoTmp;
use App\Http\Models\Url;
use App\Http\Models\City;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Image;
class GroceryController extends Controller
{
    public function __construct(){
        $this->middleware('role:Editor');
    }

    public function index(){
        $siteId                         =   config('app.siteId');
        $user                           =   Auth::user();
        
        $groceryRs                      =   Grocery::select('grocery.id as groceryId', 'grocery.name', 
                                                            'city.city', 'grocery.premium', 'grocery_tmp.referenceId',
                                                            'grocery.is_disabled', 'ethnic.ethnicName', 'url.urlName',
                                                            'grocery.updated_at','users.name as updatedBy')
                                                        ->leftjoin('url','url.groceryId', '=', 'grocery.id')
                                                        ->leftjoin('address','address.id', '=', 'grocery.addressId')
                                                        ->leftjoin('ethnic','ethnic.id', '=', 'grocery.ethnicId')
                                                        ->leftjoin('site','site.siteId', '=', 'grocery.siteId')                                            
                                                        ->leftjoin('city','city.cityId', '=', 'address.city')  
                                                        ->leftjoin('users','users.id', '=', 'grocery.updated_by') 
                                                        ->leftjoin('grocery_tmp','grocery.id', '=', 'grocery_tmp.referenceId')                                                         
                                                        ->where('grocery.is_deleted', '=', '0')
                                                        ->where('site.siteId', '=', $siteId)
                                                        ->orderBy('grocery.premium', 'DESC')
                                                        ->orderBy('grocery.order', 'ASC');                                                  
            
        $groceryRs                       =   $groceryRs->get();
        $grocerys                        =   $groceryRs->toArray();

        $groceryTmpRs                    =   GroceryTmp::select('grocery_tmp.id as groceryId', 'grocery_tmp.name', 'grocery_tmp.status',
                                                            'city.city', 'grocery_tmp.premium', 'grocery_tmp.referenceId',
                                                            'grocery_tmp.is_disabled', 'ethnic.ethnicName', 'url.urlName',
                                                            'grocery_tmp.updated_at','users.name as updatedBy')
                                                        ->leftjoin('url','url.groceryTempId', '=', 'grocery_tmp.id')
                                                        ->leftjoin('address','address.id', '=', 'grocery_tmp.addressId')
                                                        ->leftjoin('ethnic','ethnic.id', '=', 'grocery_tmp.ethnicId')
                                                        ->leftjoin('site','site.siteId', '=', 'grocery_tmp.siteId')                                            
                                                        ->leftjoin('city','city.cityId', '=', 'address.city')  
                                                        ->leftjoin('users','users.id', '=', 'grocery_tmp.updated_by') 
                                                        ->where('grocery_tmp.is_deleted', '=', '0')
                                                        ->where('site.siteId', '=', $siteId)
                                                        ->orderBy('grocery_tmp.premium', 'DESC')
                                                        ->orderBy('grocery_tmp.order', 'ASC');                                                  

        $groceryTmpRs                    =   $groceryTmpRs->get();
        $groceryTmp                      =   $groceryTmpRs->toArray();        

        return view('editor.grocery_listing',['grocery' => $grocerys, 'grocery_pending' => $groceryTmp]);   
    
    }

    public function addGroceryView($id=null){
        
        if($id){
            $groceryRs                      =   GroceryTmp::select('grocery_tmp.id', 'grocery_tmp.name', 'grocery_tmp.referenceId',
                                                        'grocery_tmp.description', 'grocery_tmp.workingTime','grocery_tmp.status','grocery_tmp.statusMsg',
                                                        'grocery_tmp.premium', 'grocery_tmp.order','grocery_tmp.is_disabled', 
                                                        'address_tmp.address1', 'address_tmp.address2', 'address_tmp.id as addressId',
                                                        'grocery_tmp.website',  'url.urlName', 'url.id as urlId',                                              
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
                                                    ->leftjoin('url','url.groceryTempId', '=', 'grocery_tmp.id')
                                                    ->leftjoin('address_tmp','address_tmp.id', '=', 'grocery_tmp.addressId')
                                                    ->leftjoin('ethnic','ethnic.id', '=', 'grocery_tmp.ethnicId')
                                                    ->leftjoin('site','site.siteId', '=', 'grocery_tmp.siteId')
                                                    ->leftjoin('city','city.cityId', '=', 'address_tmp.city')   
                                                    ->leftjoin('seo_tmp','seo_tmp.urlId', '=', 'url.id')                                                    
                                                    ->where('grocery_tmp.id', '=', $id)
                                                    ->get()->first();

            $grocery                        =   $groceryRs->toArray(); 
            $grocery['ref_id']              =   "";

            $photoArr                       =   PhotoTmp::select('photoName','is_primary')
                                                        ->orderBy('order', 'desc')
                                                        ->where('groceryId', '=', $id)
                                                        ->get();  
            $photoRs                        =   $photoArr->toArray();             
        }else{
            $grocery['id']                  =   "";
            $grocery['ref_id']              =   "";
            $grocery['status']              =   "";
            $grocery['referenceId']         =   "";
            $grocery['addressId']           =   "";
            $grocery['urlId']               =   "";
            $grocery['name']                =   "";
            $grocery['description']         =   "";
            $grocery['workingTime']         =   "";
            $grocery['address1']            =   "";
            $grocery['address2']            =   "";
            $grocery['website']             =   "";
            $grocery['urlName']             =   "";
            $grocery['city']                =   "";
            $grocery['state']               =   "";
            $grocery['zip']                 =   "";
            $grocery['county']              =   "";
            $grocery['phone1']              =   "";
            $grocery['phone2']              =   "";
            $grocery['latitude']            =   "";
            $grocery['longitude']           =   "";
            $grocery['ethnic']              =   "";
            $grocery['premium']             =   "";
            $grocery['is_disabled']         =   "";
            $grocery['order']               =   ""; 
            $grocery['seoId']                               =   ""; 
            $grocery['SEOMetaTitle']                        =   ""; 
            $grocery['SEOMetaDesc']                         =   ""; 
            $grocery['SEOMetaPublishedTime']                =   ""; 
            $grocery['SEOMetaKeywords']                     =   ""; 
            $grocery['OpenGraphTitle']                      =   ""; 
            $grocery['OpenGraphDesc']                       =   ""; 
            $grocery['OpenGraphUrl']                        =   ""; 
            $grocery['OpenGraphPropertyType']               =   ""; 
            $grocery['OpenGraphPropertyLocale']             =   ""; 
            $grocery['OpenGraphPropertyLocaleAlternate']    =   ""; 
            $grocery['OpenGraph']                           =   "";  
            
            $photoRs                        =   array();
        }

        $cityRs                             =   City::select('cityId','city', 'value')
                                                    ->orderBy('city', 'asc')
                                                    ->get();  
        $cities                             =   $cityRs->toArray();  
        return view('editor.grocery_add',['grocery' => $grocery, 'cities' => $cities, 'photos' => $photoRs]); 
    }  
    
    public function addGrocery(Request $request){
        
        $groceryVal                         =   $request->post();

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'urlName' => [
                        'required',
                        Rule::unique('url')->ignore($groceryVal['urlId'], 'id'),
            ],
            'address1' => 'required',
            'city' => 'required',
            'state' => 'required',
            'photos.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'thumbnail.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048'            
        ]);

        if ($validator->fails()) {
            if($groceryVal['id']){
                return redirect('/editor/grocery_add/'.$groceryVal['id'])->withErrors($validator)->withInput();
            }else{
                return redirect('/editor/grocery_add')->withErrors($validator)->withInput();
            }
        }
        
        if($groceryVal['id']){
            DB::table('grocery_tmp')
                ->where('id', $groceryVal['id'])
                ->update(
                    [
                        'name'          => $groceryVal['name'],
                        'description'   => $groceryVal['description'],
                        'workingTime'   => $groceryVal['workingTime'],
                        'ethnicId'      => $groceryVal['ethnic'],
                        'status'        => '2',
                        'siteId'        => config('app.siteId'),
                        'website'       => $groceryVal['website'],
                        'order'         => ($groceryVal['order'])?$groceryVal['order']:0,
                        'premium'       => $groceryVal['premium'],
                        'is_disabled'   => $groceryVal['is_disabled'],
                        'updated_by'    => Auth::user()->id,
                        'updated_at'    => date("Y-m-d H:i:s")                    
                    ]
                );
            DB::table('address_tmp')
                ->where('id', $groceryVal['addressId'])
                ->update(
                    [
                        'address1'      => $groceryVal['address1'],
                        'address2'      => $groceryVal['address2'],
                        'city'          => $groceryVal['city'],
                        'state'         => $groceryVal['state'],
                        'zip'           => $groceryVal['zip'],
                        'county'        => $groceryVal['county'],
                        'phone1'        => $groceryVal['phone1'],
                        'phone2'        => $groceryVal['phone2'],
                        'latitude'      => $groceryVal['latitude'],
                        'longitude'     => $groceryVal['longitude'],                   
                    ]
            );
            if($groceryVal['urlName'] != $groceryVal['urlNameChk']){
                DB::table('url')
                ->where('id', $groceryVal['urlId'])
                ->update(
                    [
                        'urlName'       => $groceryVal['urlName'],
                        'updated_at'    => date("Y-m-d H:i:s")                 
                    ]
                );
            }
            DB::table('seo_tmp')
                ->where('seoId', $groceryVal['seoId'])
                ->update(
                    [
                        'SEOMetaTitle'                      => $groceryVal['SEOMetaTitle'],
                        'SEOMetaDesc'                       => $groceryVal['SEOMetaDesc'],
                        'SEOMetaPublishedTime'              => $groceryVal['SEOMetaPublishedTime'],
                        'SEOMetaKeywords'                   => $groceryVal['SEOMetaKeywords'],
                        'OpenGraphTitle'                    => $groceryVal['OpenGraphTitle'],
                        'OpenGraphDesc'                     => $groceryVal['OpenGraphDesc'],
                        'OpenGraphUrl'                      => $groceryVal['OpenGraphUrl'],
                        'OpenGraphPropertyType'             => $groceryVal['OpenGraphPropertyType'],
                        'OpenGraphPropertyLocale'           => $groceryVal['OpenGraphPropertyLocale'],
                        'OpenGraphPropertyLocaleAlternate'  => $groceryVal['OpenGraphPropertyLocaleAlternate'],
                        'OpenGraph'                         => $groceryVal['OpenGraph'],
                        'updated_at'                        => date("Y-m-d H:i:s")
                    ]
                ); 

            if (!file_exists(public_path().'/image/grocery/'.$groceryVal['id'].'_tmp')) {
                mkdir(public_path().'/image/grocery/'.$groceryVal['id'].'_tmp', 0777, true);
            }
            if($request->hasFile('photos')){
                $files                          = $request->file('photos');
                
                DB::table('photo_tmp')->where('groceryId', $groceryVal['id'])->where('is_primary', 0)->delete();
                
            
                foreach($files as $key=> $file){
                    $filename                   = $file->getClientOriginalName();
                    $rand                       = (rand(10,100));
                    $extension                  = $file->getClientOriginalExtension();                
                    $fileName                   = $groceryVal['urlName'].'-'.$key.'-'.$rand.'.'.$extension;

                    //$resizeImage                = Image::make($file);
                    //$resizeImage->resize(466,350);
                    //$path                       = public_path('image/grocery/'.$groceryVal['id'].'/'.$groceryVal['urlName'].'-'.$key.'-'.$rand.'.'.$extension);
                    //$resizeImage->save($path);   
                    
                    $file->move(public_path().'/image/grocery/'.$groceryVal['id'].'_tmp', $groceryVal['urlName'].'-'.$key.'-'.$rand.'.'.$extension); 

                    DB::table('photo_tmp')->insertGetId(
                        [
                            'photoName'         => $fileName,
                            'order'             => $key,
                            'groceryId'         => $groceryVal['id'],
                            'updated_at'  => date("Y-m-d H:i:s")
                        ]
                    );
                }
            }
            if($request->hasFile('thumbnail')){
                $files                          = $request->file('thumbnail');

                DB::table('photo')->where('groceryId', $groceryVal['id'])->where('is_primary', 1)->delete();
            
                foreach($files as $key=> $file){
                    $filename                   = $file->getClientOriginalName();
                    $rand                       = (rand(10,1000));
                    $extension                  = $file->getClientOriginalExtension();                
                    $fileName                   = $groceryVal['urlName'].'-'.$key.'-'.$rand.'.'.$extension;
                    //$file->move(public_path().'/image/grocery/'.$groceryVal['id'], $fileName); 
                    //$resizeImage                = Image::make($file);
                    //$resizeImage->resize(128,95);
                    //$path                       = public_path('image/grocery/'.$groceryVal['id'].'/'.$groceryVal['urlName'].'-'.$key.'-'.$rand.'.'.$extension);
                    //$resizeImage->save($path);    
                    
                    $file->move(public_path().'/image/grocery/'.$groceryVal['id'].'_tmp', $groceryVal['urlName'].'-'.$key.'-'.$rand.'.'.$extension); 
                    
                    DB::table('photo')->insertGetId(
                        [
                            'photoName'         => $fileName,
                            'order'             => $key,
                            'groceryId'         => $groceryVal['id'],
                            'is_primary'        => 1,
                            'updated_at'  => date("Y-m-d H:i:s")
                        ]
                    );
                }
            }        
            return redirect('/editor/grocery')->with('status', 'Grocery updated!');                    
        }else{

            $groceryId                      =   DB::table('grocery_tmp')->insertGetId(
                                                    [
                                                        'name'          => $groceryVal['name'],
                                                        'referenceId'   => ($groceryVal['ref_id'])?$groceryVal['ref_id']:0,
                                                        'description'   => $groceryVal['description'],
                                                        'workingTime'   => $groceryVal['workingTime'],
                                                        'ethnicId'      => $groceryVal['ethnic'],
                                                        'siteId'        => config('app.siteId'),
                                                        'website'       => $groceryVal['website'],
                                                        'order'         => ($groceryVal['order'])?$groceryVal['order']:0,
                                                        'premium'       => $groceryVal['premium'],
                                                        'is_disabled'   => $groceryVal['is_disabled'],
                                                        'urlId'         => 0,
                                                        'addressId'     => 0,
                                                        'status'        => 2,
                                                        'updated_by'    => Auth::user()->id,
                                                        'created_at'  => date("Y-m-d H:i:s"),
                                                        'updated_at'  => date("Y-m-d H:i:s")
                                                    ]
                                                );

            $addressId                      =   DB::table('address_tmp')->insertGetId(
                                                    [
                                                        'address1'      => $groceryVal['address1'],
                                                        'address2'      => $groceryVal['address2'],
                                                        'city'          => $groceryVal['city'],
                                                        'state'         => $groceryVal['state'],
                                                        'zip'           => $groceryVal['zip'],
                                                        'county'        => $groceryVal['county'],
                                                        'phone1'        => $groceryVal['phone1'],
                                                        'phone2'        => $groceryVal['phone2'],
                                                        'latitude'      => $groceryVal['latitude'],
                                                        'longitude'     => $groceryVal['longitude'],
                                                    ]
                                                );

            if($groceryVal['ref_id'] && $groceryVal['urlId']){
                DB::table('url')
                    ->where('id', $groceryVal['urlId'])
                    ->update(
                        [
                            'groceryTempId'       => $groceryId,
                        ]
                    );  
            }else{
                $urlId                          =   DB::table('url')->insertGetId(
                    [
                        'urlName'       => $groceryVal['urlName'],
                        'groceryTempId'  => $groceryId,
                        'created_at'    => date("Y-m-d H:i:s"),
                        'updated_at'    => date("Y-m-d H:i:s")
                    ]
                );  
            }      

            DB::table('grocery_tmp')
                ->where('id', $groceryId)
                ->update(
                    [
                        'urlId'             => ($groceryVal['urlId'])?$groceryVal['urlId']:$urlId,
                        'addressId'         => $addressId
                    ]
                );

            $seoId                          =   DB::table('seo_tmp')->insertGetId(
                                                    [
                                                        'urlId'                             => ($groceryVal['urlId'])?$groceryVal['urlId']:$urlId,
                                                        'SEOMetaTitle'                      => $groceryVal['SEOMetaTitle'],
                                                        'SEOMetaDesc'                       => $groceryVal['SEOMetaDesc'],
                                                        'SEOMetaPublishedTime'              => $groceryVal['SEOMetaPublishedTime'],
                                                        'SEOMetaKeywords'                   => $groceryVal['SEOMetaKeywords'],
                                                        'OpenGraphTitle'                    => $groceryVal['OpenGraphTitle'],
                                                        'OpenGraphDesc'                     => $groceryVal['OpenGraphDesc'],
                                                        'OpenGraphUrl'                      => $groceryVal['OpenGraphUrl'],
                                                        'OpenGraphPropertyType'             => $groceryVal['OpenGraphPropertyType'],
                                                        'OpenGraphPropertyLocale'           => $groceryVal['OpenGraphPropertyLocale'],
                                                        'OpenGraphPropertyLocaleAlternate'  => $groceryVal['OpenGraphPropertyLocaleAlternate'],
                                                        'OpenGraph'                         => $groceryVal['OpenGraph'],
                                                        'created_at'                        => date("Y-m-d H:i:s"),
                                                        'updated_at'                        => date("Y-m-d H:i:s")
                                                    ]
                                                );   

            if(!$groceryVal['ref_id']){
                if (!file_exists(public_path().'/image/grocery/'.$groceryId.'_tmp')) {
                    mkdir(public_path().'/image/grocery/'.$groceryId.'_tmp', 0777, true);
                }   
            }                                             
            if($request->hasFile('photos')){
                $files                          = $request->file('photos');
            
                foreach($files as $key=> $file){
                    $filename                   = $file->getClientOriginalName();
                    $rand                       = (rand(10,100));
                    $extension                  = $file->getClientOriginalExtension();                
                    $fileName                   = $groceryVal['urlName'].'-'.$key.'-'.$rand.'.'.$extension;
                    //$file->move(public_path().'/image/grocery/'.$groceryVal['id'], $fileName); 
                    //$resizeImage                = Image::make($file);
                    //$resizeImage->resize(466,350);
                    //$path                       = public_path('image/grocery/'.$groceryId.'/'.$groceryVal['urlName'].'-'.$key.'-'.$rand.'.'.$extension);
                    //$resizeImage->save($path);   
                    if(!$groceryVal['ref_id']){
                        $file->move(public_path().'/image/grocery/'.$groceryId.'_tmp', $groceryVal['urlName'].'-'.$key.'-'.$rand.'.'.$extension); 
                    }else{
                        $file->move(public_path().'/image/grocery/'.$groceryId, $groceryVal['urlName'].'-'.$key.'-'.$rand.'.'.$extension); 
                    }
    
                    DB::table('photo_tmp')->insertGetId(
                        [
                            'photoName'         => $fileName,
                            'order'             => $key,
                            'groceryId'         => $groceryId,
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
                    $fileName                   = $groceryVal['urlName'].'-'.$key.'-'.$rand.'.'.$extension;
                    //$file->move(public_path().'/image/grocery/'.$groceryVal['id'], $fileName); 
                    //$resizeImage                = Image::make($file);
                    //$resizeImage->resize(128,95);
                    //$path                       = public_path('image/grocery/'.$groceryId.'/'.$groceryVal['urlName'].'-'.$key.'-'.$rand.'.'.$extension);
                    //$resizeImage->save($path);       
                    if(!$groceryVal['ref_id']){
                        $file->move(public_path().'/image/grocery/'.$groceryId.'_tmp', $groceryVal['urlName'].'-'.$key.'-'.$rand.'.'.$extension); 
                    }else{
                        $file->move(public_path().'/image/grocery/'.$groceryId, $groceryVal['urlName'].'-'.$key.'-'.$rand.'.'.$extension); 
                    }
    
                    DB::table('photo_tmp')->insertGetId(
                        [
                            'photoName'         => $fileName,
                            'order'             => $key,
                            'groceryId'         => $groceryId,
                            'is_primary'        => 1,
                            'created_at'  => date("Y-m-d H:i:s"),
                            'updated_at'  => date("Y-m-d H:i:s")
                        ]
                    );
                }
            }                                                
            return redirect('/editor/grocery')->with('status', 'Grocery added!');                                                
        }
    }   

    public function addGroceryDuplicatetView($id){
        if($id){
            $groceryRs                      =   Grocery::select('grocery.id', 'grocery.name', 
                                                                'grocery.description', 'grocery.workingTime',
                                                                'grocery.premium', 'grocery.order','grocery.is_disabled', 
                                                                'address.address1', 'address.address2', 'address.id as addressId',
                                                                'grocery.website',  'url.urlName', 'url.id as urlId',                                              
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
                                                            ->leftjoin('url','url.groceryId', '=', 'grocery.id')
                                                            ->leftjoin('address','address.id', '=', 'grocery.addressId')
                                                            ->leftjoin('ethnic','ethnic.id', '=', 'grocery.ethnicId')
                                                            ->leftjoin('site','site.siteId', '=', 'grocery.siteId')
                                                            ->leftjoin('city','city.cityId', '=', 'address.city')   
                                                            ->leftjoin('seo','seo.urlId', '=', 'url.id')                                                    
                                                            ->where('grocery.id', '=', $id)
                                                            ->get()->first();

            $grocery                        =   $groceryRs->toArray(); 

            $photoArr                       =   Photo::select('photoName','is_primary')
                                                        ->orderBy('order', 'desc')
                                                        ->where('groceryId', '=', $id)
                                                        ->get();  
            $photoRs                        =   $photoArr->toArray();         
            $grocery['status']             =   "";
            $grocery['ref_id']             =   $grocery['id'];
            $grocery['id']                 =   "";
            $grocery['addressId']          =   "";
            $grocery['seoId']              =   "";     
        }else{
            $grocery['id']                  =   "";
            $grocery['referenceId']         =   "";
            $grocery['addressId']           =   "";
            $grocery['urlId']               =   "";
            $grocery['name']                =   "";
            $grocery['description']         =   "";
            $grocery['workingTime']         =   "";
            $grocery['address1']            =   "";
            $grocery['address2']            =   "";
            $grocery['website']             =   "";
            $grocery['urlName']             =   "";
            $grocery['city']                =   "";
            $grocery['state']               =   "";
            $grocery['zip']                 =   "";
            $grocery['county']              =   "";
            $grocery['phone1']              =   "";
            $grocery['phone2']              =   "";
            $grocery['latitude']            =   "";
            $grocery['longitude']           =   "";
            $grocery['premium']             =   "";
            $grocery['is_disabled']         =   "";
            $grocery['order']               =   ""; 
            $grocery['seoId']                               =   ""; 
            $grocery['SEOMetaTitle']                        =   ""; 
            $grocery['SEOMetaDesc']                         =   ""; 
            $grocery['SEOMetaPublishedTime']                =   ""; 
            $grocery['SEOMetaKeywords']                     =   ""; 
            $grocery['OpenGraphTitle']                      =   ""; 
            $grocery['OpenGraphDesc']                       =   ""; 
            $grocery['OpenGraphUrl']                        =   ""; 
            $grocery['OpenGraphPropertyType']               =   ""; 
            $grocery['OpenGraphPropertyLocale']             =   ""; 
            $grocery['OpenGraphPropertyLocaleAlternate']    =   ""; 
            $grocery['OpenGraph']                           =   "";  
            
            $photoRs                        =   array();
        }

        $cityRs                             =   City::select('cityId','city', 'value')
                                                    ->orderBy('city', 'asc')
                                                    ->get();  
        $cities                             =   $cityRs->toArray(); 

        return view('editor.grocery_add',['grocery' => $grocery, 'cities' => $cities, 'photos' => $photoRs]); 

    }    
    
    public function deleteGrocery($id){
        if($id){

            $groceryRs                          =   GroceryTmp::select('grocery_tmp.urlId', 'grocery_tmp.addressId', 'grocery_tmp.referenceId')
                                                        ->where('grocery_tmp.id', '=', $id)
                                                        ->get()->first();

            $grocery                            =   $groceryRs->toArray(); 

            DB::table('grocery_tmp')->where('id', $id)->delete();
            DB::table('address_tmp')->where('id', $grocery['addressId'])->delete();
            DB::table('seo_tmp')->where('urlId', $grocery['urlId'])->delete();
            DB::table('photo_tmp')->where('groceryId', $id)->delete();            
            if($grocery['referenceId']){
                DB::table('url')
                    ->where('id', $grocery['urlId'])
                    ->update(
                        [
                            'groceryTempId'       => 0,
                        ]
                    );
            }else{
                DB::table('url')->where('groceryTempId', $id)->delete();                
            }
            
            if (is_dir(public_path().'/image/grocery/'.$id.'_tmp')) {
                rmdir (public_path().'/image/grocery/'.$id.'_tmp');     
            }
            return redirect('/editor/grocery')->with('status', 'Grocery deleted!');
        }else{
            return redirect('/editor/grocery')->with('status', 'Error!');            
        }
    }     

}
