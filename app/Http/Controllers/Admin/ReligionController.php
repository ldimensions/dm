<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Models\Religion;
use App\Http\Models\ReligionTmp;
use App\Http\Models\Photo;
use App\Http\Models\PhotoTmp;
use App\Http\Models\Url;
use App\Http\Models\City;
use App\Http\Models\ReligionType;
use App\Http\Models\Denomination;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Image;

class ReligionController extends Controller
{
    public function __construct(){
        $this->middleware('role:Admin');
    }

    public function index(){
        $siteId                         =   config('app.siteId');
        
        $religionRs                     =   Religion::select('religion.id as religionId', 'religion.name', 
                                               'religion.premium', 'religion.is_disabled',
                                                'religion_type.religionName', 'city.city',
                                                'users.name as updatedBy', 'religion.updated_at',
                                                'url.urlName','denomination.denominationName')
                                            ->leftjoin('url','url.religionId', '=', 'religion.id')
                                            ->leftjoin('address','address.id', '=', 'religion.addressId')
                                            ->leftjoin('religion_type','religion_type.id', '=', 'religion.religionTypeId')
                                            ->leftjoin('site','site.siteId', '=', 'religion.siteId')                                            
                                            ->leftjoin('city','city.cityId', '=', 'address.city')    
                                            ->leftjoin('denomination','denomination.id', '=', 'religion.denominationId')    
                                            ->leftjoin('users','users.id', '=', 'religion.updated_by')                                                                                                                               
                                            ->where('religion.is_deleted', '=', '0')
                                            ->where('site.siteId', '=', $siteId)
                                            ->orderBy('religion.premium', 'DESC')
                                            ->orderBy('religion.order', 'ASC');                                                  
            
        $religionRs                       =   $religionRs->get();
        $religions                        =   $religionRs->toArray();

        $religionTmpRs                  =   ReligionTmp::select('religion_tmp.id as religionId', 'religion_tmp.name', 
                                                                'religion_tmp.premium', 'religion_tmp.is_disabled', 'religion_tmp.status',
                                                                'religion_type.religionName', 'city.city',
                                                                'url.urlName','denomination.denominationName','religion_tmp.updated_at','users.name as updatedBy')
                                                            ->leftjoin('url','url.religionTempId', '=', 'religion_tmp.id')
                                                            ->leftjoin('address_tmp','address_tmp.id', '=', 'religion_tmp.addressId')
                                                            ->leftjoin('religion_type','religion_type.id', '=', 'religion_tmp.religionTypeId')
                                                            ->leftjoin('site','site.siteId', '=', 'religion_tmp.siteId')                                            
                                                            ->leftjoin('city','city.cityId', '=', 'address_tmp.city')    
                                                            ->leftjoin('users','users.id', '=', 'religion_tmp.updated_by') 
                                                            ->leftjoin('denomination','denomination.id', '=', 'religion_tmp.denominationId')                                            
                                                            ->where('religion_tmp.is_deleted', '=', '0')
                                                            ->where('site.siteId', '=', $siteId)
                                                            ->orderBy('religion_tmp.premium', 'DESC')
                                                            ->orderBy('religion_tmp.order', 'ASC');                                                  

        $religionTmpRs                  =   $religionTmpRs->get();
        $religionTmp                    =   $religionTmpRs->toArray();      

        return view('admin.religion_listing',['religion' => $religions, 'religion_pending' => $religionTmp]);          
    }  

    public function addReligionView($id=null){
        
        if($id){
            $religionRs                      =   Religion::select('religion.id', 'religion.name', 
                                                        'religion.description', 'religion.workingTime',
                                                        'religion.premium', 'religion.order','religion.is_disabled', 
                                                        'address.address1', 'address.address2', 'address.id as addressId',
                                                        'religion.website',  'url.urlName', 'url.id as urlId',                                              
                                                        'address.state', 'address.city as city',
                                                        'address.zip', 'address.county',
                                                        'address.phone1', 'address.phone2', 'address.latitude',
                                                        'address.longitude', 'religion.denominationId',
                                                        'religion.religionTypeId',
                                                        'seo.seoId', 'seo.SEOMetaTitle',
                                                        'seo.SEOMetaDesc', 'seo.SEOMetaPublishedTime',
                                                        'seo.SEOMetaKeywords', 'seo.OpenGraphTitle',
                                                        'seo.OpenGraphDesc', 'seo.OpenGraphUrl',
                                                        'seo.OpenGraphPropertyType', 'seo.OpenGraphPropertyLocale',
                                                        'seo.OpenGraphPropertyLocaleAlternate', 'seo.OpenGraph')
                                                    ->leftjoin('url','url.religionId', '=', 'religion.id')
                                                    ->leftjoin('address','address.id', '=', 'religion.addressId')
                                                    ->leftjoin('religion_type','religion_type.id', '=', 'religion.religionTypeId')
                                                    ->leftjoin('site','site.siteId', '=', 'religion.siteId')
                                                    // ->leftjoin('denomination','denomination.id', '=', 'religion_type.id')
                                                    ->leftjoin('city','city.cityId', '=', 'address.city')   
                                                    ->leftjoin('seo','seo.urlId', '=', 'url.id')                                                    
                                                    ->where('religion.id', '=', $id)
                                                    ->get()->first();

            $religion                        =   $religionRs->toArray(); 

            $photoArr                       =   Photo::select('photoName','is_primary')
                                                        ->orderBy('order', 'desc')
                                                        ->where('religionId', '=', $id)
                                                        ->get();  
            $photoRs                        =   $photoArr->toArray();             
        }else{
            $religion['id']                  =   "";
            $religion['addressId']           =   "";
            $religion['urlId']               =   "";
            $religion['name']                =   "";
            $religion['description']         =   "";
            $religion['workingTime']         =   "";
            $religion['address1']            =   "";
            $religion['address2']            =   "";
            $religion['website']             =   "";
            $religion['urlName']             =   "";
            $religion['city']                =   "";
            $religion['state']               =   "";
            $religion['zip']                 =   "";
            $religion['county']              =   "";
            $religion['phone1']              =   "";
            $religion['phone2']              =   "";
            $religion['latitude']            =   "";
            $religion['longitude']           =   "";
            $religion['religionTypeId']      =   "";
            $religion['denominationId']      =   "";
            $religion['premium']             =   "";
            $religion['is_disabled']         =   "";
            $religion['order']               =   ""; 
            $religion['seoId']                               =   ""; 
            $religion['SEOMetaTitle']                        =   ""; 
            $religion['SEOMetaDesc']                         =   ""; 
            $religion['SEOMetaPublishedTime']                =   ""; 
            $religion['SEOMetaKeywords']                     =   ""; 
            $religion['OpenGraphTitle']                      =   ""; 
            $religion['OpenGraphDesc']                       =   ""; 
            $religion['OpenGraphUrl']                        =   ""; 
            $religion['OpenGraphPropertyType']               =   ""; 
            $religion['OpenGraphPropertyLocale']             =   ""; 
            $religion['OpenGraphPropertyLocaleAlternate']    =   ""; 
            $religion['OpenGraph']                           =   "";  
            
            $photoRs                        =   array();
        }

        $cityRs                             =   City::select('cityId','city', 'value')
                                                    ->orderBy('city', 'asc')
                                                    ->get();  
        $cities                             =   $cityRs->toArray(); 

        $religionTypeRs                     =   ReligionType::select('id', 'religionName')
                                                    ->orderBy('order', 'asc')
                                                    ->get();  
        $religionType                       =   $religionTypeRs->toArray(); 

        $denominationRs                     =   Denomination::select('id', 'denominationName')
                                                    ->get();  
        $denominations                      =   $denominationRs->toArray();

        return view('admin.religion_add',['religion' => $religion, 'cities' => $cities, 'photos' => $photoRs, 'religionTypes' => $religionType, 'denominations' => $denominations]); 
    }

    public function addReligion(Request $request){

        $religionVal                         =   $request->post();

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'urlName' => [
                        'required',
                        Rule::unique('url')->ignore($religionVal['urlId'], 'id'),
            ],
            'address1' => 'required',
            'city' => 'required',
            'state' => 'required',
            'photos.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'thumbnail.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048'            
        ]);

        if ($validator->fails()) {
            if($religionVal['id']){
                return redirect('/admin/religion_add/'.$religionVal['id'])->withErrors($validator)->withInput();
            }else{
                return redirect('/admin/religion_add')->withErrors($validator)->withInput();
            }
        }
        
        if($religionVal['id']){
            DB::table('religion')
                ->where('id', $religionVal['id'])
                ->update(
                    [
                        'name'          => $religionVal['name'],
                        'description'   => $religionVal['description'],
                        'workingTime'   => $religionVal['workingTime'],
                        'religionTypeId'=> $religionVal['religionTypeId'],
                        'denominationId'=> $religionVal['denominationId'],
                        'siteId'        => config('app.siteId'),
                        'website'       => $religionVal['website'],
                        'order'         => ($religionVal['order'])?$religionVal['order']:0,
                        'premium'       => $religionVal['premium'],
                        'is_disabled'   => $religionVal['is_disabled'],
                        'updated_by'    => Auth::user()->id,
                        'updated_at'    => date("Y-m-d H:i:s")                    
                    ]
                );
            DB::table('address')
                ->where('id', $religionVal['addressId'])
                ->update(
                    [
                        'address1'      => $religionVal['address1'],
                        'address2'      => $religionVal['address2'],
                        'city'          => $religionVal['city'],
                        'state'         => $religionVal['state'],
                        'zip'           => $religionVal['zip'],
                        'county'        => $religionVal['county'],
                        'phone1'        => $religionVal['phone1'],
                        'phone2'        => $religionVal['phone2'],
                        'latitude'      => $religionVal['latitude'],
                        'longitude'     => $religionVal['longitude'],                   
                    ]
            );
            if($religionVal['urlName'] != $religionVal['urlNameChk']){
                DB::table('url')
                ->where('id', $religionVal['urlId'])
                ->update(
                    [
                        'urlName'       => $religionVal['urlName'],
                        'updated_at'    => date("Y-m-d H:i:s")                 
                    ]
                );
            }
            DB::table('seo')
                ->where('seoId', $religionVal['seoId'])
                ->update(
                    [
                        'SEOMetaTitle'                      => $religionVal['SEOMetaTitle'],
                        'SEOMetaDesc'                       => $religionVal['SEOMetaDesc'],
                        'SEOMetaPublishedTime'              => $religionVal['SEOMetaPublishedTime'],
                        'SEOMetaKeywords'                   => $religionVal['SEOMetaKeywords'],
                        'OpenGraphTitle'                    => $religionVal['OpenGraphTitle'],
                        'OpenGraphDesc'                     => $religionVal['OpenGraphDesc'],
                        'OpenGraphUrl'                      => $religionVal['OpenGraphUrl'],
                        'OpenGraphPropertyType'             => $religionVal['OpenGraphPropertyType'],
                        'OpenGraphPropertyLocale'           => $religionVal['OpenGraphPropertyLocale'],
                        'OpenGraphPropertyLocaleAlternate'  => $religionVal['OpenGraphPropertyLocaleAlternate'],
                        'OpenGraph'                         => $religionVal['OpenGraph'],
                        'updated_at'                        => date("Y-m-d H:i:s")
                    ]
                ); 

            if (!file_exists(public_path().'/image/religion/'.$religionVal['id'])) {
                mkdir(public_path().'/image/religion/'.$religionVal['id'], 0777, true);
            }                

            if($request->hasFile('photos')){
                $files                          = $request->file('photos');
                
                DB::table('photo')->where('religionId', $religionVal['id'])->where('is_primary', 0)->delete();
                
                foreach($files as $key=> $file){
                    $filename                   = $file->getClientOriginalName();
                    $rand                       = (rand(10,100));
                    $extension                  = $file->getClientOriginalExtension();                
                    $fileName                   = $religionVal['urlName'].'-'.$key.'-'.$rand.'.'.$extension;
                    //$file->move(public_path().'/image/religion/'.$religionVal['id'], $fileName); 
                    //$resizeImage                = Image::make($file);
                    //$resizeImage->resize(466,350);
                    //$path                       = public_path('image/religion/'.$religionVal['id'].'/'.$religionVal['urlName'].'-'.$key.'-'.$rand.'.'.$extension);
                    //$resizeImage->save($path); 

                    $file->move(public_path().'/image/religion/'.$religionVal['id'], $religionVal['urlName'].'-'.$key.'-'.$rand.'.'.$extension); 

                    DB::table('photo')->insertGetId(
                        [
                            'photoName'         => $fileName,
                            'order'             => $key,
                            'religionId'         => $religionVal['id'],
                            'updated_at'  => date("Y-m-d H:i:s")
                        ]
                    );
                }
            }
            if($request->hasFile('thumbnail')){
                $files                          = $request->file('thumbnail');

                DB::table('photo')->where('religionId', $religionVal['id'])->where('is_primary', 1)->delete();
                
                foreach($files as $key=> $file){
                    $filename                   = $file->getClientOriginalName();
                    $rand                       = (rand(10,1000));
                    $extension                  = $file->getClientOriginalExtension();                
                    $fileName                   = $religionVal['urlName'].'-'.$key.'-'.$rand.'.'.$extension;
                    //$file->move(public_path().'/image/religion/'.$religionVal['id'], $fileName); 

                    //$resizeImage                = Image::make($file);
                    //$resizeImage->resize(128,95);
                    //$path                       = public_path('image/religion/'.$religionVal['id'].'/'.$religionVal['urlName'].'-'.$key.'-'.$rand.'.'.$extension);
                    //$resizeImage->save($path); 

                    $file->move(public_path().'/image/religion/'.$religionVal['id'], $religionVal['urlName'].'-'.$key.'-'.$rand.'.'.$extension); 

                    DB::table('photo')->insertGetId(
                        [
                            'photoName'         => $fileName,
                            'order'             => $key,
                            'religionId'         => $religionVal['id'],
                            'is_primary'        => 1,
                            'updated_at'  => date("Y-m-d H:i:s")
                        ]
                    );
                }
            }        
            return redirect('/admin/religion')->with('status', 'Religion updated!');                    
        }else{

            $religionId                      =   DB::table('religion')->insertGetId(
                                                    [
                                                        'name'          => $religionVal['name'],
                                                        'description'   => $religionVal['description'],
                                                        'workingTime'   => $religionVal['workingTime'],
                                                        'religionTypeId'=> $religionVal['religionTypeId'],
                                                        'denominationId'=> $religionVal['denominationId'],
                                                        'siteId'        => config('app.siteId'),
                                                        'website'       => $religionVal['website'],
                                                        'order'         => ($religionVal['order'])?$religionVal['order']:0,
                                                        'premium'       => $religionVal['premium'],
                                                        'is_disabled'   => $religionVal['is_disabled'],
                                                        'urlId'         => 0,
                                                        'addressId'     => 0,
                                                        'updated_by'    => Auth::user()->id,
                                                        'created_at'  => date("Y-m-d H:i:s"),
                                                        'updated_at'  => date("Y-m-d H:i:s")
                                                    ]
                                                );

            $addressId                      =   DB::table('address')->insertGetId(
                                                    [
                                                        'address1'      => $religionVal['address1'],
                                                        'address2'      => $religionVal['address2'],
                                                        'city'          => $religionVal['city'],
                                                        'state'         => $religionVal['state'],
                                                        'zip'           => $religionVal['zip'],
                                                        'county'        => $religionVal['county'],
                                                        'phone1'        => $religionVal['phone1'],
                                                        'phone2'        => $religionVal['phone2'],
                                                        'latitude'      => $religionVal['latitude'],
                                                        'longitude'     => $religionVal['longitude'],
                                                    ]
                                                );
            $urlId                          =   DB::table('url')->insertGetId(
                                                    [
                                                        'urlName'       => $religionVal['urlName'],
                                                        'religionId'     => $religionId,
                                                        'created_at'  => date("Y-m-d H:i:s"),
                                                        'updated_at'  => date("Y-m-d H:i:s")
                                                    ]
                                                ); 
            DB::table('religion')
                ->where('id', $religionId)
                ->update(
                    [
                        'urlId'             => $urlId,
                        'addressId'         => $addressId
                    ]
                );

            $seoId                          =   DB::table('seo')->insertGetId(
                                                    [
                                                        'urlId'                             => $urlId,
                                                        'SEOMetaTitle'                      => $religionVal['SEOMetaTitle'],
                                                        'SEOMetaDesc'                       => $religionVal['SEOMetaDesc'],
                                                        'SEOMetaPublishedTime'              => $religionVal['SEOMetaPublishedTime'],
                                                        'SEOMetaKeywords'                   => $religionVal['SEOMetaKeywords'],
                                                        'OpenGraphTitle'                    => $religionVal['OpenGraphTitle'],
                                                        'OpenGraphDesc'                     => $religionVal['OpenGraphDesc'],
                                                        'OpenGraphUrl'                      => $religionVal['OpenGraphUrl'],
                                                        'OpenGraphPropertyType'             => $religionVal['OpenGraphPropertyType'],
                                                        'OpenGraphPropertyLocale'           => $religionVal['OpenGraphPropertyLocale'],
                                                        'OpenGraphPropertyLocaleAlternate'  => $religionVal['OpenGraphPropertyLocaleAlternate'],
                                                        'OpenGraph'                         => $religionVal['OpenGraph'],
                                                        'created_at'                        => date("Y-m-d H:i:s"),
                                                        'updated_at'                        => date("Y-m-d H:i:s")
                                                    ]
                                                );  

            if (!file_exists(public_path().'/image/religion/'.$religionId)) {
                mkdir(public_path().'/image/religion/'.$religionId, 0777, true);
            }                                                  
            if($request->hasFile('photos')){
                $files                          = $request->file('photos');
                
                foreach($files as $key=> $file){
                    $filename                   = $file->getClientOriginalName();
                    $rand                       = (rand(10,100));
                    $extension                  = $file->getClientOriginalExtension();                
                    $fileName                   = $religionVal['urlName'].'-'.$key.'-'.$rand.'.'.$extension;
                    //$file->move(public_path().'/image/religion/'.$religionVal['id'], $fileName); 
                    //$resizeImage                = Image::make($file);
                    //$resizeImage->resize(466,350);
                    //$path                       = public_path('image/religion/'.$religionId.'/'.$religionVal['urlName'].'-'.$key.'-'.$rand.'.'.$extension);
                    //$resizeImage->save($path);  
                    
                    $file->move(public_path().'/image/religion/'.$religionId, $religionVal['urlName'].'-'.$key.'-'.$rand.'.'.$extension); 
    
                    DB::table('photo')->insertGetId(
                        [
                            'photoName'         => $fileName,
                            'order'             => $key,
                            'religionId'         => $religionId,
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
                    $fileName                   = $religionVal['urlName'].'-'.$key.'-'.$rand.'.'.$extension;
                    //$file->move(public_path().'/image/religion/'.$religionVal['id'], $fileName); 
                    //$resizeImage                = Image::make($file);
                    //$resizeImage->resize(128,95);
                    //$path                       = public_path('image/religion/'.$religionId.'/'.$religionVal['urlName'].'-'.$key.'-'.$rand.'.'.$extension);
                    //$resizeImage->save($path);    
                    
                    $file->move(public_path().'/image/religion/'.$religionId, $religionVal['urlName'].'-'.$key.'-'.$rand.'.'.$extension); 
    
                    DB::table('photo')->insertGetId(
                        [
                            'photoName'         => $fileName,
                            'order'             => $key,
                            'religionId'        => $religionId,
                            'is_primary'        => 1,
                            'created_at'  => date("Y-m-d H:i:s"),
                            'updated_at'  => date("Y-m-d H:i:s")
                        ]
                    );
                }
            }                                                  
            return redirect('/admin/religion')->with('status', 'Religion added!');                                                
        }
    }
    
    public function deleteReligion($id){
        if($id){
            DB::table('religion')
            ->where('id', $id)
            ->update(
                [
                    'is_deleted'        => 1
                ]
            ); 
        }
        return redirect('/admin/religion')->with('status', 'Religion deleted!');
    }   
    
    public function rejectReligion(Request $request){
        $religionVal                      =   $request->post();
        if($religionVal['id']){
            DB::table('religion_tmp')
                ->where('id', $religionVal['id'])
                ->update(
                    [
                        'status'            => '4',
                        'statusMsg'         =>   $religionVal['reason']                                     
                    ]
                );        

            return redirect('/admin/religion')->with('status', 'Religion rejected!');
        }      
    }   
    
    public function deleteTmpReligion($id){
        if($id){

            $religionRs                         =   ReligionTmp::select('religion_tmp.urlId', 'religion_tmp.addressId', 'religion_tmp.referenceId')
                                                        ->where('religion_tmp.id', '=', $id)
                                                        ->get()->first();

            $religion                           =   $religionRs->toArray(); 

            DB::table('religion_tmp')->where('id', $id)->delete();
            DB::table('address_tmp')->where('id', $religion['addressId'])->delete();
            DB::table('seo_tmp')->where('urlId', $religion['urlId'])->delete();
            DB::table('photo_tmp')->where('religionId', $id)->delete();            
            if($religion['referenceId']){
                DB::table('url')
                    ->where('id', $religion['urlId'])
                    ->update(
                        [
                            'religionTempId'       => 0,
                        ]
                    );
            }else{
                DB::table('url')->where('religionTempId', $id)->delete();                
            }

            if (is_dir(public_path().'/image/religion/'.$id.'_tmp')) {
                rmdir (public_path().'/image/religion/'.$id.'_tmp');     
            }             

            return redirect('/admin/religion')->with('status', 'Religion deleted!');
        }else{
            return redirect('/admin/religion')->with('status', 'Error!');            
        }
    }   
    
    public function approveReligion($id){
        $religionTmpRs                      =   ReligionTmp::select('religion_tmp.name', 'religion_tmp.referenceId', 'religion_tmp.urlId',
                                                                'religion_tmp.description', 'religion_tmp.urlId',
                                                                'religion_tmp.religionTypeId', 'religion_tmp.denominationId', 'religion_tmp.addressId',
                                                                'religion_tmp.website', 'religion_tmp.workingTime', 'religion_tmp.is_disabled',
                                                                'religion_tmp.order', 'religion_tmp.premium', 'religion_tmp.updated_by',
                                                                'religion_tmp.created_at', 'religion_tmp.updated_at',
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
                                                                'seo_tmp.updated_at', 'religion_tmp.updated_by'
                                                                )     
                                                            ->leftjoin('address_tmp','address_tmp.id', '=', 'religion_tmp.addressId')   
                                                            ->leftjoin('seo_tmp','seo_tmp.urlId', '=', 'religion_tmp.urlId')   
                                                            ->where('religion_tmp.id', '=', $id)
                                                            ->get()->first(); 
                                                            
        $religionTmpArr                     =   $religionTmpRs->toArray(); 

        if($religionTmpArr['referenceId']){

            $religionRs                     =   Religion::select('religion.urlId', 'religion.addressId')
                                                            ->where('religion.id', '=', $religionTmpArr['referenceId'])->get()->first();                                                   

            $religions                      =   $religionRs->toArray();            

            DB::table('religion')
                ->where('id', $religionTmpArr['referenceId'])
                ->update(
                [
                    'name'          => $religionTmpArr['name'],
                    'description'   => $religionTmpArr['description'],
                    'workingTime'   => $religionTmpArr['workingTime'],
                    'religionTypeId'=> $religionTmpArr['religionTypeId'],
                    'denominationId'=> $religionTmpArr['denominationId'],                    
                    'website'       => $religionTmpArr['website'],
                    'order'         => ($religionTmpArr['order'])?$religionTmpArr['order']:0,
                    'premium'       => $religionTmpArr['premium'],
                    'is_disabled'   => $religionTmpArr['is_disabled'],
                    'is_deleted'    => 0,
                    'updated_by'    => $religionTmpArr['updated_by'],
                    'updated_at'    => date("Y-m-d H:i:s")                    
                ]
            );
            DB::table('address')
                ->where('id', $religions['addressId'])
                ->update(
                    [
                        'address1'      => $religionTmpArr['address1'],
                        'address2'      => $religionTmpArr['address2'],
                        'city'          => $religionTmpArr['city'],
                        'state'         => $religionTmpArr['state'],
                        'zip'           => $religionTmpArr['zip'],
                        'county'        => $religionTmpArr['county'],
                        'phone1'        => $religionTmpArr['phone1'],
                        'phone2'        => $religionTmpArr['phone2'],
                        'latitude'      => $religionTmpArr['latitude'],
                        'longitude'     => $religionTmpArr['longitude'],                   
                    ]
            );
            DB::table('url')
                ->where('id', $religions['urlId'])
                ->update(
                    [
                        'religionTempId'    => 0                
                    ]
                );
            DB::table('seo')
                ->where('urlId', $religions['urlId'])
                ->update(
                    [
                        'SEOMetaTitle'                      => $religionTmpArr['SEOMetaTitle'],
                        'SEOMetaDesc'                       => $religionTmpArr['SEOMetaDesc'],
                        'SEOMetaPublishedTime'              => $religionTmpArr['SEOMetaPublishedTime'],
                        'SEOMetaKeywords'                   => $religionTmpArr['SEOMetaKeywords'],
                        'OpenGraphTitle'                    => $religionTmpArr['OpenGraphTitle'],
                        'OpenGraphDesc'                     => $religionTmpArr['OpenGraphDesc'],
                        'OpenGraphUrl'                      => $religionTmpArr['OpenGraphUrl'],
                        'OpenGraphPropertyType'             => $religionTmpArr['OpenGraphPropertyType'],
                        'OpenGraphPropertyLocale'           => $religionTmpArr['OpenGraphPropertyLocale'],
                        'OpenGraphPropertyLocaleAlternate'  => $religionTmpArr['OpenGraphPropertyLocaleAlternate'],
                        'OpenGraph'                         => $religionTmpArr['OpenGraph'],
                        'updated_at'                        => date("Y-m-d H:i:s")
                    ]
            ); 

            $photoArr                       =   PhotoTmp::select('photoName','is_primary', 'order', 'is_deleted', 'is_disabled')
                                                        ->orderBy('order', 'asc')
                                                        ->where('religionId', '=', $id)
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
                        'religionId' => $religionTmpArr['referenceId']]
                    ]);  
                }
            }  
            DB::table('religion_tmp')->where('id', $id)->delete();
            DB::table('address_tmp')->where('id', $religionTmpArr['addressTempId'])->delete();
            DB::table('seo_tmp')->where('urlId', $religionTmpArr['urlId'])->delete();        
            DB::table('photo_tmp')->where('religionId', $id)->delete();    
                                    
        }else{
            $addressId                      =   DB::table('address')->insertGetId(
                                                    [
                                                        'address1'      => $religionTmpArr['address1'],
                                                        'address2'      => $religionTmpArr['address2'],
                                                        'city'          => $religionTmpArr['city'],
                                                        'state'         => $religionTmpArr['state'],
                                                        'zip'           => $religionTmpArr['zip'],
                                                        'county'        => $religionTmpArr['county'],
                                                        'phone1'        => $religionTmpArr['phone1'],
                                                        'phone2'        => $religionTmpArr['phone2'],
                                                        'latitude'      => $religionTmpArr['latitude'],
                                                        'longitude'     => $religionTmpArr['longitude'],
                                                    ]
            );        

            $religionId                      =   DB::table('religion')->insertGetId(
                                                    [
                                                        'name'          => $religionTmpArr['name'],
                                                        'description'   => $religionTmpArr['description'],
                                                        'workingTime'   => $religionTmpArr['workingTime'],
                                                        'religionTypeId'=> $religionTmpArr['religionTypeId'],
                                                        'denominationId'=> $religionTmpArr['denominationId'],
                                                        'siteId'        => config('app.siteId'),
                                                        'website'       => $religionTmpArr['website'],
                                                        'order'         => ($religionTmpArr['order'])?$religionTmpArr['order']:0,
                                                        'premium'       => $religionTmpArr['premium'],
                                                        'is_disabled'   => $religionTmpArr['is_disabled'],
                                                        'is_deleted'    => 0,
                                                        'urlId'         => $religionTmpArr['urlId'],
                                                        'addressId'     => $addressId,
                                                        'updated_by'    => $religionTmpArr['updated_by'],
                                                        'created_at'  => $religionTmpArr['created_at'],
                                                        'updated_at'  => $religionTmpArr['updated_at']
                                                    ]
                            );

            DB::table('url')
                ->where('id', $religionTmpArr['urlId'])
                ->update(
                [
                'religionId'          => $religionId,
                'religionTempId'      => 0
                ]
            );  

            $seoId                          =   DB::table('seo')->insertGetId(
                                                        [
                                                        'urlId'                             => $religionTmpArr['urlId'],
                                                        'SEOMetaTitle'                      => $religionTmpArr['SEOMetaTitle'],
                                                        'SEOMetaDesc'                       => $religionTmpArr['SEOMetaDesc'],
                                                        'SEOMetaPublishedTime'              => $religionTmpArr['SEOMetaPublishedTime'],
                                                        'SEOMetaKeywords'                   => $religionTmpArr['SEOMetaKeywords'],
                                                        'OpenGraphTitle'                    => $religionTmpArr['OpenGraphTitle'],
                                                        'OpenGraphDesc'                     => $religionTmpArr['OpenGraphDesc'],
                                                        'OpenGraphUrl'                      => $religionTmpArr['OpenGraphUrl'],
                                                        'OpenGraphPropertyType'             => $religionTmpArr['OpenGraphPropertyType'],
                                                        'OpenGraphPropertyLocale'           => $religionTmpArr['OpenGraphPropertyLocale'],
                                                        'OpenGraphPropertyLocaleAlternate'  => $religionTmpArr['OpenGraphPropertyLocaleAlternate'],
                                                        'OpenGraph'                         => $religionTmpArr['OpenGraph'],
                                                        'created_at'                        => $religionTmpArr['OpenGraph'],
                                                        'updated_at'                        => $religionTmpArr['OpenGraph']
                                                        ]
                                                );             

            $photoTypeRs                     =   PhotoTmp::select('photo_tmp.photoName', 'photo_tmp.is_primary', 'photo_tmp.order')
                                                        ->where('photo_tmp.religionId', '=', $id)
                                                        ->get();    

            $photoTypeArr                    =   $photoTypeRs->toArray();      
            foreach($photoTypeArr as $photoType){
                DB::table('photo')->insert([
                    ['photoName' => $photoType['photoName'], 
                    'is_primary' => $photoType['is_primary'], 
                    'order' => $photoType['order'], 
                    'religionId' => $religionId]
                ]); 
            }   
            // if(count($photoTypeArr) > 0){
                if (is_dir(public_path().'/image/religion/'.$id.'_tmp')) {
                    rename (public_path().'/image/religion/'.$id.'_tmp', public_path().'/image/religion/'.$religionId);     
                }               
            // }

            DB::table('religion_tmp')->where('id', $id)->delete();
            DB::table('address_tmp')->where('id', $religionTmpArr['id'])->delete();
            DB::table('seo_tmp')->where('urlId', $religionTmpArr['urlId'])->delete();        
            DB::table('photo_tmp')->where('religionId', $id)->delete();
        }

        return redirect('/admin/religion')->with('status', 'Religion Approved!');        
    }    
}



