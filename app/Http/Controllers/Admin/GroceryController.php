<?php

namespace App\Http\Controllers\Admin;

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
        $this->middleware('role:Admin');
    }

    public function index(){
        $siteId                         =   config('app.siteId');
        
        $groceryRs                      =   Grocery::select('grocery.id as groceryId', 'grocery.name', 
                                                'city.city', 'grocery.premium', 
                                                'grocery.updated_at','users.name as updatedBy',
                                                'grocery.is_disabled', 'ethnic.ethnicName', 'url.urlName')
                                            ->leftjoin('url','url.groceryId', '=', 'grocery.id')
                                            ->leftjoin('address','address.id', '=', 'grocery.addressId')
                                            ->leftjoin('ethnic','ethnic.id', '=', 'grocery.ethnicId')
                                            ->leftjoin('site','site.siteId', '=', 'grocery.siteId')    
                                            ->leftjoin('users','users.id', '=', 'grocery.updated_by')                                                          
                                            ->leftjoin('city','city.cityId', '=', 'address.city')                                           
                                            ->where('grocery.is_deleted', '=', '0')
                                            ->where('site.siteId', '=', $siteId)
                                            ->orderBy('grocery.premium', 'DESC')
                                            ->orderBy('grocery.order', 'ASC');                                                  
            
        $groceryRs                       =   $groceryRs->get();
        $grocerys                        =   $groceryRs->toArray();

        $groceryTmpRs                    =   GroceryTmp::select('grocery_tmp.id as groceryId', 'grocery_tmp.name', 
                                                            'city.city', 'grocery_tmp.premium', 'grocery_tmp.status',
                                                            'grocery_tmp.updated_at','users.name as updatedBy',
                                                            'grocery_tmp.is_disabled', 'ethnic.ethnicName', 'url.urlName')
                                                        ->leftjoin('url','url.groceryTempId', '=', 'grocery_tmp.id')
                                                        ->leftjoin('address','address.id', '=', 'grocery_tmp.addressId')
                                                        ->leftjoin('ethnic','ethnic.id', '=', 'grocery_tmp.ethnicId')
                                                        ->leftjoin('users','users.id', '=', 'grocery_tmp.updated_by')              
                                                        ->leftjoin('site','site.siteId', '=', 'grocery_tmp.siteId')                                            
                                                        ->leftjoin('city','city.cityId', '=', 'address.city')                                           
                                                        ->where('grocery_tmp.is_deleted', '=', '0')
                                                        ->where('site.siteId', '=', $siteId)
                                                        ->orderBy('grocery_tmp.premium', 'DESC')
                                                        ->orderBy('grocery_tmp.order', 'ASC');                                                  

        $groceryTmpRs                    =   $groceryTmpRs->get();
        $groceryTmp                      =   $groceryTmpRs->toArray();        

        return view('admin.grocery_listing',['grocery' => $grocerys, 'grocery_pending' => $groceryTmp]);          
    }  

    public function addGroceryView($id=null){
        
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
        }else{
            $grocery['id']                  =   "";
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
        return view('admin.grocery_add',['grocery' => $grocery, 'cities' => $cities, 'photos' => $photoRs]); 
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
                return redirect('/admin/grocery_add/'.$groceryVal['id'])->withErrors($validator)->withInput();
            }else{
                return redirect('/admin/grocery_add')->withErrors($validator)->withInput();
            }
        }
        
        if($groceryVal['id']){
            DB::table('grocery')
                ->where('id', $groceryVal['id'])
                ->update(
                    [
                        'name'          => $groceryVal['name'],
                        'description'   => $groceryVal['description'],
                        'workingTime'   => $groceryVal['workingTime'],
                        'ethnicId'      => $groceryVal['ethnic'],
                        'siteId'        => config('app.siteId'),
                        'website'       => $groceryVal['website'],
                        'order'         => ($groceryVal['order'])?$groceryVal['order']:0,
                        'premium'       => $groceryVal['premium'],
                        'is_disabled'   => $groceryVal['is_disabled'],
                        'updated_by'    => Auth::user()->id,
                        'updated_at'    => date("Y-m-d H:i:s")                    
                    ]
                );
            DB::table('address')
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
            DB::table('seo')
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

        if (!file_exists(public_path().'/image/grocery/'.$groceryVal['id'])) {
            mkdir(public_path().'/image/grocery/'.$groceryVal['id'], 0777, true);
        }
        if($request->hasFile('photos')){
            $files                          = $request->file('photos');
            
            DB::table('photo')->where('groceryId', $groceryVal['id'])->where('is_primary', 0)->delete();
            
        
            foreach($files as $key=> $file){
                $filename                   = $file->getClientOriginalName();
                $rand                       = (rand(10,100));
                $extension                  = $file->getClientOriginalExtension();                
                $fileName                   = $groceryVal['urlName'].'-'.$key.'-'.$rand.'.'.$extension;

                //$resizeImage                = Image::make($file);
                //$resizeImage->resize(466,350);
                //$path                       = public_path('image/grocery/'.$groceryVal['id'].'/'.$groceryVal['urlName'].'-'.$key.'-'.$rand.'.'.$extension);
                //$resizeImage->save($path);   
                
                $file->move(public_path().'/image/grocery/'.$groceryVal['id'], $groceryVal['urlName'].'-'.$key.'-'.$rand.'.'.$extension); 

                DB::table('photo')->insertGetId(
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
                
                $file->move(public_path().'/image/grocery/'.$groceryVal['id'], $groceryVal['urlName'].'-'.$key.'-'.$rand.'.'.$extension); 
               
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
        return redirect('/admin/grocery')->with('status', 'Grocery updated!');                    
        }else{

            $groceryId                      =   DB::table('grocery')->insertGetId(
                                                    [
                                                        'name'          => $groceryVal['name'],
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
                                                        'updated_by'    => Auth::user()->id,
                                                        'created_at'  => date("Y-m-d H:i:s"),
                                                        'updated_at'  => date("Y-m-d H:i:s")
                                                    ]
                                                );

            $addressId                      =   DB::table('address')->insertGetId(
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
            $urlId                          =   DB::table('url')->insertGetId(
                                                    [
                                                        'urlName'       => $groceryVal['urlName'],
                                                        'groceryId'     => $groceryId,
                                                        'created_at'  => date("Y-m-d H:i:s"),
                                                        'updated_at'  => date("Y-m-d H:i:s")
                                                    ]
                                                ); 
            DB::table('grocery')
                ->where('id', $groceryId)
                ->update(
                    [
                        'urlId'             => $urlId,
                        'addressId'         => $addressId
                    ]
                );

            $seoId                          =   DB::table('seo')->insertGetId(
                                                    [
                                                        'urlId'                             => $urlId,
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

            if (!file_exists(public_path().'/image/grocery/'.$groceryId)) {
                mkdir(public_path().'/image/grocery/'.$groceryId, 0777, true);
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
                    
                    $file->move(public_path().'/image/grocery/'.$groceryId, $groceryVal['urlName'].'-'.$key.'-'.$rand.'.'.$extension); 
    
                    DB::table('photo')->insertGetId(
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
                    
                    $file->move(public_path().'/image/grocery/'.$groceryId, $groceryVal['urlName'].'-'.$key.'-'.$rand.'.'.$extension); 
    
                    DB::table('photo')->insertGetId(
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
            return redirect('/admin/grocery')->with('status', 'Grocery added!');                                                
        }
    }
    
    public function deleteGrocery($id){
        if($id){
            DB::table('grocery')
            ->where('id', $id)
            ->update(
                [
                    'is_deleted'        => 1
                ]
            ); 
        }
        return redirect('/admin/grocery')->with('status', 'Grocery deleted!');
    }  
    
    public function rejectGrocery(Request $request){
        $groceryVal                      =   $request->post();
        if($groceryVal['id']){
            DB::table('grocery_tmp')
                ->where('id', $groceryVal['id'])
                ->update(
                    [
                        'status'            => '4',
                        'statusMsg'         =>   $groceryVal['reason']                                     
                    ]
                );        

            return redirect('/admin/grocery')->with('status', 'Grocery rejected!');
        }      
    }
    
    public function deleteTmpGrocery($id){
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

            return redirect('/admin/grocery')->with('status', 'Grocery deleted!');
        }else{
            return redirect('/admin/grocery')->with('status', 'Error!');            
        }
    }

    public function approveGrocery($id){
        $groceryTmpRs                      =   GroceryTmp::select('grocery_tmp.name', 'grocery_tmp.referenceId', 'grocery_tmp.urlId',
                                                                'grocery_tmp.description', 'grocery_tmp.urlId',
                                                                'grocery_tmp.ethnicId', 'grocery_tmp.addressId',
                                                                'grocery_tmp.website', 'grocery_tmp.workingTime', 'grocery_tmp.is_disabled',
                                                                'grocery_tmp.order', 'grocery_tmp.premium', 'grocery_tmp.updated_by',
                                                                'grocery_tmp.created_at', 'grocery_tmp.updated_at',
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
                                                                'seo_tmp.updated_at', 'grocery_tmp.updated_by'
                                                                )     
                                                            ->leftjoin('address_tmp','address_tmp.id', '=', 'grocery_tmp.addressId')   
                                                            ->leftjoin('seo_tmp','seo_tmp.urlId', '=', 'grocery_tmp.urlId')   
                                                            ->where('grocery_tmp.id', '=', $id)
                                                            ->get()->first(); 
                                                            
        $groceryTmpArr                      =   $groceryTmpRs->toArray(); 

        if($groceryTmpArr['referenceId']){

            $groceryRs                      =   Grocery::select('grocery.urlId', 'grocery.addressId')
                                                            ->where('grocery.id', '=', $groceryTmpArr['referenceId'])->get()->first();                                                   

            $grocerys                      =   $groceryRs->toArray();            

            DB::table('grocery')
                ->where('id', $groceryTmpArr['referenceId'])
                ->update(
                [
                    'name'          => $groceryTmpArr['name'],
                    'description'   => $groceryTmpArr['description'],
                    'workingTime'   => $groceryTmpArr['workingTime'],
                    'ethnicId'      => $groceryTmpArr['ethnicId'],                    
                    'website'       => $groceryTmpArr['website'],
                    'order'         => ($groceryTmpArr['order'])?$groceryTmpArr['order']:0,
                    'premium'       => $groceryTmpArr['premium'],
                    'is_disabled'   => $groceryTmpArr['is_disabled'],
                    'is_deleted'    => 0,
                    'updated_by'    => $groceryTmpArr['updated_by'],
                    'updated_at'    => date("Y-m-d H:i:s")                    
                ]
            );
            DB::table('address')
                ->where('id', $grocerys['addressId'])
                ->update(
                    [
                        'address1'      => $groceryTmpArr['address1'],
                        'address2'      => $groceryTmpArr['address2'],
                        'city'          => $groceryTmpArr['city'],
                        'state'         => $groceryTmpArr['state'],
                        'zip'           => $groceryTmpArr['zip'],
                        'county'        => $groceryTmpArr['county'],
                        'phone1'        => $groceryTmpArr['phone1'],
                        'phone2'        => $groceryTmpArr['phone2'],
                        'latitude'      => $groceryTmpArr['latitude'],
                        'longitude'     => $groceryTmpArr['longitude'],                   
                    ]
            );
            DB::table('url')
                ->where('id', $grocerys['urlId'])
                ->update(
                    [
                        'groceryTempId'    => 0                
                    ]
                );
            DB::table('seo')
                ->where('urlId', $grocerys['urlId'])
                ->update(
                    [
                        'SEOMetaTitle'                      => $groceryTmpArr['SEOMetaTitle'],
                        'SEOMetaDesc'                       => $groceryTmpArr['SEOMetaDesc'],
                        'SEOMetaPublishedTime'              => $groceryTmpArr['SEOMetaPublishedTime'],
                        'SEOMetaKeywords'                   => $groceryTmpArr['SEOMetaKeywords'],
                        'OpenGraphTitle'                    => $groceryTmpArr['OpenGraphTitle'],
                        'OpenGraphDesc'                     => $groceryTmpArr['OpenGraphDesc'],
                        'OpenGraphUrl'                      => $groceryTmpArr['OpenGraphUrl'],
                        'OpenGraphPropertyType'             => $groceryTmpArr['OpenGraphPropertyType'],
                        'OpenGraphPropertyLocale'           => $groceryTmpArr['OpenGraphPropertyLocale'],
                        'OpenGraphPropertyLocaleAlternate'  => $groceryTmpArr['OpenGraphPropertyLocaleAlternate'],
                        'OpenGraph'                         => $groceryTmpArr['OpenGraph'],
                        'updated_at'                        => date("Y-m-d H:i:s")
                    ]
            ); 

            $photoArr                       =   PhotoTmp::select('photoName','is_primary', 'order', 'is_deleted', 'is_disabled')
                                                        ->orderBy('order', 'asc')
                                                        ->where('groceryId', '=', $id)
                                                        ->get();  
            $photoRs                        =   $photoArr->toArray();    
            
            if(count($photoRs) >0){
                for($i =0; $i < count($photoRs); $i++){
                    DB::table('photo_tmp')->insert([
                        ['photoName' => $photoRs[$i]['photoName'], 
                        'is_primary' => $photoRs[$i]['is_primary'], 
                        'order' => $photoRs[$i]['order'], 
                        'is_deleted' => $photoRs[$i]['is_deleted'], 
                        'is_disabled' => $photoRs[$i]['is_disabled'], 
                        'groceryId' => $groceryTmpArr['referenceId']]
                    ]);  
                }
            }  
            DB::table('grocery_tmp')->where('id', $id)->delete();
            DB::table('address_tmp')->where('id', $groceryTmpArr['addressTempId'])->delete();
            DB::table('seo_tmp')->where('urlId', $groceryTmpArr['urlId'])->delete();        
            DB::table('photo_tmp')->where('groceryId', $id)->delete();    
                                    
        }else{
            $addressId                      =   DB::table('address')->insertGetId(
                                                    [
                                                        'address1'      => $groceryTmpArr['address1'],
                                                        'address2'      => $groceryTmpArr['address2'],
                                                        'city'          => $groceryTmpArr['city'],
                                                        'state'         => $groceryTmpArr['state'],
                                                        'zip'           => $groceryTmpArr['zip'],
                                                        'county'        => $groceryTmpArr['county'],
                                                        'phone1'        => $groceryTmpArr['phone1'],
                                                        'phone2'        => $groceryTmpArr['phone2'],
                                                        'latitude'      => $groceryTmpArr['latitude'],
                                                        'longitude'     => $groceryTmpArr['longitude'],
                                                    ]
            );        

            $groceryId                      =   DB::table('grocery')->insertGetId(
                                                    [
                                                        'name'          => $groceryTmpArr['name'],
                                                        'description'   => $groceryTmpArr['description'],
                                                        'workingTime'   => $groceryTmpArr['workingTime'],
                                                        'ethnicId'      => $groceryTmpArr['ethnicId'], 
                                                        'siteId'        => config('app.siteId'),
                                                        'website'       => $groceryTmpArr['website'],
                                                        'order'         => ($groceryTmpArr['order'])?$groceryTmpArr['order']:0,
                                                        'premium'       => $groceryTmpArr['premium'],
                                                        'is_disabled'   => $groceryTmpArr['is_disabled'],
                                                        'is_deleted'    => 0,
                                                        'urlId'         => $groceryTmpArr['urlId'],
                                                        'addressId'     => $addressId,
                                                        'updated_by'    => $groceryTmpArr['updated_by'],
                                                        'created_at'  => $groceryTmpArr['created_at'],
                                                        'updated_at'  => $groceryTmpArr['updated_at']
                                                    ]
                            );

            DB::table('url')
                ->where('id', $groceryTmpArr['urlId'])
                ->update(
                [
                'groceryId'          => $groceryId,
                'groceryTempId'      => 0
                ]
            );  

            $seoId                          =   DB::table('seo')->insertGetId(
                                                        [
                                                        'urlId'                             => $groceryTmpArr['urlId'],
                                                        'SEOMetaTitle'                      => $groceryTmpArr['SEOMetaTitle'],
                                                        'SEOMetaDesc'                       => $groceryTmpArr['SEOMetaDesc'],
                                                        'SEOMetaPublishedTime'              => $groceryTmpArr['SEOMetaPublishedTime'],
                                                        'SEOMetaKeywords'                   => $groceryTmpArr['SEOMetaKeywords'],
                                                        'OpenGraphTitle'                    => $groceryTmpArr['OpenGraphTitle'],
                                                        'OpenGraphDesc'                     => $groceryTmpArr['OpenGraphDesc'],
                                                        'OpenGraphUrl'                      => $groceryTmpArr['OpenGraphUrl'],
                                                        'OpenGraphPropertyType'             => $groceryTmpArr['OpenGraphPropertyType'],
                                                        'OpenGraphPropertyLocale'           => $groceryTmpArr['OpenGraphPropertyLocale'],
                                                        'OpenGraphPropertyLocaleAlternate'  => $groceryTmpArr['OpenGraphPropertyLocaleAlternate'],
                                                        'OpenGraph'                         => $groceryTmpArr['OpenGraph'],
                                                        'created_at'                        => $groceryTmpArr['OpenGraph'],
                                                        'updated_at'                        => $groceryTmpArr['OpenGraph']
                                                        ]
                                                );             

            $photoTypeRs                     =   PhotoTmp::select('photo_tmp.photoName', 'photo_tmp.is_primary', 'photo_tmp.order')
                                                        ->where('photo_tmp.groceryId', '=', $id)
                                                        ->get();    

            $photoTypeArr                    =   $photoTypeRs->toArray();      
            foreach($photoTypeArr as $photoType){
                DB::table('photo')->insert([
                    ['photoName' => $photoType['photoName'], 
                    'is_primary' => $photoType['is_primary'], 
                    'order' => $photoType['order'], 
                    'groceryId' => $groceryId]
                ]); 
            }   
            // if(count($photoTypeArr) > 0){
                if (is_dir(public_path().'/image/grocery/'.$id.'_tmp')) {
                    rename (public_path().'/image/grocery/'.$id.'_tmp', public_path().'/image/grocery/'.$groceryId);     
                }               
            // }

            DB::table('grocery_tmp')->where('id', $id)->delete();
            DB::table('address_tmp')->where('id', $groceryTmpArr['id'])->delete();
            DB::table('seo_tmp')->where('urlId', $groceryTmpArr['urlId'])->delete();        
            DB::table('photo_tmp')->where('groceryId', $id)->delete();
        }

        return redirect('/admin/grocery')->with('status', 'Grocery Approved!');        
    }     
}


