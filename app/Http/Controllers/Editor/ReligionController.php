<?php

namespace App\Http\Controllers\Editor;

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
        $this->middleware('role:Editor');
    }

    public function index(){
        $siteId                         =   config('app.siteId');
        $user                           =   Auth::user();

        $religionRs                     =   Religion::select('religion.id as religionId', 'religion.name', 
                                                                'religion.premium', 'religion.is_disabled', 'religion_tmp.referenceId',
                                                                'religion_type.religionName', 'city.city',
                                                                'url.urlName','denomination.denominationName','religion.updated_at','users.name as updatedBy')
                                                            ->leftjoin('url','url.religionId', '=', 'religion.id')
                                                            ->leftjoin('address','address.id', '=', 'religion.addressId')
                                                            ->leftjoin('religion_type','religion_type.id', '=', 'religion.religionTypeId')
                                                            ->leftjoin('site','site.siteId', '=', 'religion.siteId')                                            
                                                            ->leftjoin('city','city.cityId', '=', 'address.city')    
                                                            ->leftjoin('users','users.id', '=', 'religion.updated_by') 
                                                            ->leftjoin('denomination','denomination.id', '=', 'religion.denominationId')       
                                                            ->leftjoin('religion_tmp','religion.id', '=', 'religion_tmp.referenceId')                                              
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
        
        return view('editor.religion_listing',['religion' => $religions, 'religion_pending' => $religionTmp]);          
                
    }   
    
    public function addReligionView($id=null){
        
        if($id){
            $religionRs                      =   ReligionTmp::select('religion_tmp.id', 'religion_tmp.name', 'religion_tmp.referenceId',
                                                        'religion_tmp.description', 'religion_tmp.workingTime',
                                                        'religion_tmp.status', 'religion_tmp.statusMsg',          
                                                        'religion_tmp.premium', 'religion_tmp.order','religion_tmp.is_disabled', 
                                                        'address_tmp.address1', 'address_tmp.address2', 'address_tmp.id as addressId',
                                                        'religion_tmp.website',  'url.urlName', 'url.id as urlId',                                              
                                                        'address_tmp.state', 'address_tmp.city as city',
                                                        'address_tmp.zip', 'address_tmp.county',
                                                        'address_tmp.phone1', 'address_tmp.phone2', 'address_tmp.latitude',
                                                        'address_tmp.longitude', 'religion_tmp.denominationId',
                                                        'religion_tmp.religionTypeId',
                                                        'seo_tmp.seoId', 'seo_tmp.SEOMetaTitle',
                                                        'seo_tmp.SEOMetaDesc', 'seo_tmp.SEOMetaPublishedTime',
                                                        'seo_tmp.SEOMetaKeywords', 'seo_tmp.OpenGraphTitle',
                                                        'seo_tmp.OpenGraphDesc', 'seo_tmp.OpenGraphUrl',
                                                        'seo_tmp.OpenGraphPropertyType', 'seo_tmp.OpenGraphPropertyLocale',
                                                        'seo_tmp.OpenGraphPropertyLocaleAlternate', 'seo_tmp.OpenGraph')
                                                    ->leftjoin('url','url.religionTempId', '=', 'religion_tmp.id')
                                                    ->leftjoin('address_tmp','address_tmp.id', '=', 'religion_tmp.addressId')
                                                    ->leftjoin('religion_type','religion_type.id', '=', 'religion_tmp.religionTypeId')
                                                    ->leftjoin('site','site.siteId', '=', 'religion_tmp.siteId')
                                                    ->leftjoin('denomination','denomination.id', '=', 'religion_type.id')
                                                    ->leftjoin('city','city.cityId', '=', 'address_tmp.city')   
                                                    ->leftjoin('seo_tmp','seo_tmp.urlId', '=', 'url.id')                                                    
                                                    ->where('religion_tmp.id', '=', $id)
                                                    ->get()->first();

            $religion                       =   $religionRs->toArray(); 
            $religion['ref_id']             =   "";

            $photoArr                       =   PhotoTmp::select('photoName','is_primary')
                                                        ->orderBy('order', 'desc')
                                                        ->where('religionId', '=', $id)
                                                        ->get();  
            $photoRs                        =   $photoArr->toArray();             
        }else{
            $religion['id']                  =   "";
            $religion['ref_id']              =   "";
            $religion['referenceId']         =   "";
            $religion['addressId']           =   "";
            $religion['urlId']               =   "";
            $religion['name']                =   "";
            $religion['status']              =   "";
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

        return view('editor.religion_add',['religion' => $religion, 'cities' => $cities, 'photos' => $photoRs, 'religionTypes' => $religionType, 'denominations' => $denominations]); 
    }   
    
    public function addReligionDuplicatetView($id){
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
                                                    ->leftjoin('denomination','denomination.id', '=', 'religion_type.id')
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
            $religion['status']             =   "";
            $religion['ref_id']             =   $religion['id'];
            $religion['id']                 =   "";
            $religion['addressId']          =   "";
            $religion['seoId']              =   "";     
        }else{
            $religion['id']                  =   "";
            $religion['referenceId']         =   "";
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

        return view('editor.religion_add',['religion' => $religion, 'cities' => $cities, 'photos' => $photoRs, 'religionTypes' => $religionType, 'denominations' => $denominations]); 
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
                return redirect('/editor/religion_add/'.$religionVal['id'])->withErrors($validator)->withInput();
            }else if($religionVal['ref_id']){
                return redirect('/editor/religion_add_duplicate/'.$religionVal['ref_id'])->withErrors($validator)->withInput();                
            }else{
                return redirect('/editor/religion_add')->withErrors($validator)->withInput();
            }
        }
        
        if($religionVal['id']){
            DB::table('religion_tmp')
                ->where('id', $religionVal['id'])
                ->update(
                    [
                        'name'          => $religionVal['name'],
                        'description'   => $religionVal['description'],
                        'workingTime'   => $religionVal['workingTime'],
                        'religionTypeId'=> $religionVal['religionTypeId'],
                        'denominationId'=> $religionVal['denominationId'],
                        'status'        => '2',
                        'siteId'        => config('app.siteId'),
                        'website'       => $religionVal['website'],
                        'order'         => ($religionVal['order'])?$religionVal['order']:0,
                        'premium'       => $religionVal['premium'],
                        'is_disabled'   => $religionVal['is_disabled'],
                        'updated_by'    => Auth::user()->id,
                        'updated_at'    => date("Y-m-d H:i:s")                    
                    ]
                );
            DB::table('address_tmp')
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
            DB::table('seo_tmp')
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

            if (!file_exists(public_path().'/image/religion/'.$religionVal['id'].'_tmp')) {
                mkdir(public_path().'/image/religion/'.$religionVal['id'].'_tmp', 0777, true);
            }                

            if($request->hasFile('photos')){
                $files                          = $request->file('photos');
                
                DB::table('photo_tmp')->where('religionId', $religionVal['id'])->where('is_primary', 0)->delete();
                
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

                    $file->move(public_path().'/image/religion/'.$religionVal['id'].'_tmp', $religionVal['urlName'].'-'.$key.'-'.$rand.'.'.$extension); 

                    DB::table('photo_tmp')->insertGetId(
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

                    $file->move(public_path().'/image/religion/'.$religionVal['id'].'_tmp', $religionVal['urlName'].'-'.$key.'-'.$rand.'.'.$extension); 

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
            return redirect('/editor/religion')->with('status', 'Religion updated!');                    
        }else{

            $religionId                      =   DB::table('religion_tmp')->insertGetId(
                                                    [
                                                        'name'          => $religionVal['name'],
                                                        'referenceId'   => ($religionVal['ref_id'])?$religionVal['ref_id']:0,
                                                        'description'   => $religionVal['description'],
                                                        'workingTime'   => $religionVal['workingTime'],
                                                        'religionTypeId'=> $religionVal['religionTypeId'],
                                                        'denominationId'=> $religionVal['denominationId'],
                                                        'siteId'        => config('app.siteId'),
                                                        'status'        => 2,
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

            $addressId                      =   DB::table('address_tmp')->insertGetId(
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

            if($religionVal['ref_id'] && $religionVal['urlId']){
                DB::table('url')
                    ->where('id', $religionVal['urlId'])
                    ->update(
                        [
                            'religionTempId'       => $religionId,
                        ]
                    );  
            }else{
                $urlId                          =   DB::table('url')->insertGetId(
                    [
                        'urlName'       => $religionVal['urlName'],
                        'religionTempId'  => $religionId,
                        'created_at'    => date("Y-m-d H:i:s"),
                        'updated_at'    => date("Y-m-d H:i:s")
                    ]
                );  
            } 

            DB::table('religion_tmp')
                ->where('id', $religionId)
                ->update(
                    [
                        'urlId'             => ($religionVal['urlId'])?$religionVal['urlId']:$urlId,
                        'addressId'         => $addressId
                    ]
                );
                

            $seoId                          =   DB::table('seo_tmp')->insertGetId(
                                                    [
                                                        'urlId'                             => ($religionVal['urlId'])?$religionVal['urlId']:$urlId,
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
            
            if(!$religionVal['ref_id']){
                if (!file_exists(public_path().'/image/religion/'.$religionId.'_tmp')) {
                    mkdir(public_path().'/image/religion/'.$religionId.'_tmp', 0777, true);
                }     
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
                    if(!$religionVal['ref_id']){
                        $file->move(public_path().'/image/religion/'.$religionId.'_tmp', $religionVal['urlName'].'-'.$key.'-'.$rand.'.'.$extension); 
                    }else{
                        $file->move(public_path().'/image/religion/'.$religionId, $religionVal['urlName'].'-'.$key.'-'.$rand.'.'.$extension); 
                    }
    
                    DB::table('photo_tmp')->insertGetId(
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
                    
                    if(!$religionVal['ref_id']){
                        $file->move(public_path().'/image/religion/'.$religionId.'_tmp', $religionVal['urlName'].'-'.$key.'-'.$rand.'.'.$extension); 
                    }else{
                        $file->move(public_path().'/image/religion/'.$religionId, $religionVal['urlName'].'-'.$key.'-'.$rand.'.'.$extension); 
                    }
    
                    DB::table('photo_tmp')->insertGetId(
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
            return redirect('/editor/religion')->with('status', 'Religion added!');                                                
        }
    }  
    
    public function deleteReligion($id){
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
            return redirect('/editor/religion')->with('status', 'Religion deleted!');
        }else{
            return redirect('/editor/religion')->with('status', 'Error!');            
        }
    }     
}
