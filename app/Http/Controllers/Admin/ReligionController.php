<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Models\Religion;
use App\Http\Models\Photo;
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
                                                'url.urlName','denomination.denominationName')
                                            ->leftjoin('url','url.religionId', '=', 'religion.id')
                                            ->leftjoin('address','address.id', '=', 'religion.addressId')
                                            ->leftjoin('religion_type','religion_type.id', '=', 'religion.religionTypeId')
                                            ->leftjoin('site','site.siteId', '=', 'religion.siteId')                                            
                                            ->leftjoin('city','city.cityId', '=', 'address.city')    
                                            ->leftjoin('denomination','denomination.id', '=', 'religion.denominationId')                                            
                                            ->where('religion.is_deleted', '=', '0')
                                            ->where('site.siteId', '=', $siteId)
                                            ->orderBy('religion.premium', 'DESC')
                                            ->orderBy('religion.order', 'ASC');                                                  
            
        $religionRs                       =   $religionRs->get();
        $religions                        =   $religionRs->toArray();

        return view('admin.religion_listing',['religion' => $religions]);          
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

    public function addReligion(Request $request)
    {

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
                        'longitude'     => $religionVal['latitude'],                   
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
                $resizeImage                = Image::make($file);
                $resizeImage->resize(466,350);
                $path                       = public_path('image/religion/'.$religionVal['id'].'/'.$religionVal['urlName'].'-'.$key.'-'.$rand.'.'.$extension);
                $resizeImage->save($path); 

                DB::table('photo')->insertGetId(
                    [
                        'photoName'         => $fileName,
                        'order'             => $key,
                        'religionId'         => $religionVal['id'],
                        'created_at'  => date("Y-m-d H:i:s"),
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

                $resizeImage                = Image::make($file);
                $resizeImage->resize(128,95);
                $path                       = public_path('image/religion/'.$religionVal['id'].'/'.$religionVal['urlName'].'-'.$key.'-'.$rand.'.'.$extension);
                $resizeImage->save($path); 

                DB::table('photo')->insertGetId(
                    [
                        'photoName'         => $fileName,
                        'order'             => $key,
                        'religionId'         => $religionVal['id'],
                        'is_primary'        => 1,
                        'created_at'  => date("Y-m-d H:i:s"),
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
                                                        'longitude'     => $religionVal['latitude'],
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
                    $resizeImage                = Image::make($file);
                    $resizeImage->resize(466,350);
                    $path                       = public_path('image/religion/'.$religionId.'/'.$religionVal['urlName'].'-'.$key.'-'.$rand.'.'.$extension);
                    $resizeImage->save($path);                      
    
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
                    $resizeImage                = Image::make($file);
                    $resizeImage->resize(128,95);
                    $path                       = public_path('image/religion/'.$religionId.'/'.$religionVal['urlName'].'-'.$key.'-'.$rand.'.'.$extension);
                    $resizeImage->save($path);                      
    
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
}



