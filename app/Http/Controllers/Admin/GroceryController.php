<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Models\Grocery;
use App\Http\Models\Photo;
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
                                                'grocery.description', 'grocery.workingTime',
                                                'address.address1', 'address.address2',
                                                'city.city', 'address.state',
                                                'address.zip', 'address.county',
                                                'address.phone1', 'address.latitude',
                                                'grocery.premium', 'grocery.is_disabled',
                                                'address.longitude', 'ethnic.ethnicName',
                                                'url.urlName')
                                            ->leftjoin('url','url.groceryId', '=', 'grocery.id')
                                            ->leftjoin('address','address.id', '=', 'grocery.addressId')
                                            ->leftjoin('ethnic','ethnic.id', '=', 'grocery.ethnicId')
                                            ->leftjoin('site','site.siteId', '=', 'grocery.siteId')                                            
                                            ->leftjoin('city','city.cityId', '=', 'address.city')                                           
                                            ->where('grocery.is_deleted', '=', '0')
                                            ->where('site.siteId', '=', $siteId)
                                            ->orderBy('grocery.premium', 'DESC')
                                            ->orderBy('grocery.order', 'ASC');                                                  
            
        $groceryRs                       =   $groceryRs->get();
        $grocerys                        =   $groceryRs->toArray();

        return view('admin.grocery_listing',['grocery' => $grocerys]);          
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

    public function addGrocery(Request $request)
    {

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
                        'longitude'     => $groceryVal['latitude'],                   
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

                $resizeImage                = Image::make($file);
                $resizeImage->resize(466,350);
                $path                       = public_path('image/grocery/'.$groceryVal['id'].'/'.$groceryVal['urlName'].'-'.$key.'-'.$rand.'.'.$extension);
                $resizeImage->save($path);   
                //$file->move(public_path().'/image/grocery/'.$groceryVal['id'], $fileName); 

                DB::table('photo')->insertGetId(
                    [
                        'photoName'         => $fileName,
                        'order'             => $key,
                        'groceryId'         => $groceryVal['id'],
                        'created_at'  => date("Y-m-d H:i:s"),
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
                $resizeImage                = Image::make($file);
                $resizeImage->resize(128,95);
                $path                       = public_path('image/grocery/'.$groceryVal['id'].'/'.$groceryVal['urlName'].'-'.$key.'-'.$rand.'.'.$extension);
                $resizeImage->save($path);                 
               
                DB::table('photo')->insertGetId(
                    [
                        'photoName'         => $fileName,
                        'order'             => $key,
                        'groceryId'         => $groceryVal['id'],
                        'is_primary'        => 1,
                        'created_at'  => date("Y-m-d H:i:s"),
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
                                                        'urlid'         => 0,
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
                                                        'longitude'     => $groceryVal['latitude'],
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
                    $resizeImage                = Image::make($file);
                    $resizeImage->resize(466,350);
                    $path                       = public_path('image/grocery/'.$groceryId.'/'.$groceryVal['urlName'].'-'.$key.'-'.$rand.'.'.$extension);
                    $resizeImage->save($path);                      
    
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
                    $resizeImage                = Image::make($file);
                    $resizeImage->resize(128,95);
                    $path                       = public_path('image/grocery/'.$groceryId.'/'.$groceryVal['urlName'].'-'.$key.'-'.$rand.'.'.$extension);
                    $resizeImage->save($path);                     
    
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
}


