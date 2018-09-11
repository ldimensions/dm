<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Models\Url;
use App\Http\Models\City;
use App\Http\Models\Photo;
use App\Http\Models\Events;
use App\Http\Models\EventsCategory;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class EventsController extends Controller
{
    public function __construct(){
        $this->middleware('role:Admin');
    }

    public function index(){
        $siteId                         =   config('app.siteId');
        $eventsRs                       =   Events::select('events.id as eventsId', 'events.name', 
                                                    'city.city', 'events.premium', 
                                                    'events.is_disabled', 'url.urlName')
                                                ->leftjoin('url','url.eventId', '=', 'events.id')
                                                ->leftjoin('address','address.id', '=', 'events.addressId')
                                                ->leftjoin('site','site.siteId', '=', 'events.siteId')                                            
                                                ->leftjoin('city','city.cityId', '=', 'address.city')                                           
                                                ->where('events.is_deleted', '=', '0')
                                                ->where('site.siteId', '=', $siteId)
                                                ->orderBy('events.premium', 'DESC')
                                                ->orderBy('events.order', 'ASC');                                                  

        $eventsRs                       =   $eventsRs->get();
        $events                         =   $eventsRs->toArray();
                

        return view('admin.events_listing',['events' => $events]);          
    } 

    public function addEventsView($id=null){
        
        if($id){
            $eventsRs                       =   Events::select('events.id', 'events.name', 
                                                            'events.description', 'events.categoryId', 'events.organizerName', 
                                                            'events.organizerContact', 'events.email', 'events.phone',
                                                            'events.premium', 'events.order','events.is_disabled', 
                                                            'address.address1', 'address.address2', 'address.id as addressId',
                                                            'events.website',  'url.urlName', 'url.id as urlId',                                              
                                                            'address.state', 'address.city as city',
                                                            'address.zip', 'address.county',
                                                            'address.phone1', 'address.phone2', 'address.latitude',
                                                            'address.longitude', 
                                                            'seo.seoId', 'seo.SEOMetaTitle',
                                                            'seo.SEOMetaDesc', 'seo.SEOMetaPublishedTime',
                                                            'seo.SEOMetaKeywords', 'seo.OpenGraphTitle',
                                                            'seo.OpenGraphDesc', 'seo.OpenGraphUrl',
                                                            'seo.OpenGraphPropertyType', 'seo.OpenGraphPropertyLocale',
                                                            'seo.OpenGraphPropertyLocaleAlternate', 'seo.OpenGraph')
                                                        ->leftjoin('url','url.eventId', '=', 'events.id')
                                                        ->leftjoin('address','address.id', '=', 'events.addressId')
                                                        ->leftjoin('site','site.siteId', '=', 'events.siteId')
                                                        ->leftjoin('city','city.cityId', '=', 'address.city')   
                                                        ->leftjoin('seo','seo.urlId', '=', 'url.id')                                                    
                                                        ->where('events.id', '=', $id)
                                                        ->get()->first();

            $event                          =   $eventsRs->toArray(); 
            $photoArr                       =   Photo::select('photoName','is_primary')
                        ->orderBy('order', 'desc')
                        ->where('eventId', '=', $id)
                        ->get();  
            $photoRs                        =   $photoArr->toArray();              
        }else{
            $event['id']                  =   "";
            $event['addressId']           =   "";
            $event['urlId']               =   "";
            $event['name']                =   "";
            $event['description']         =   "";
            $event['address1']            =   "";
            $event['address2']            =   "";
            $event['website']             =   "";
            $event['urlName']             =   "";
            $event['city']                =   "";
            $event['state']               =   "";
            $event['zip']                 =   "";
            $event['county']              =   "";
            $event['phone1']              =   "";
            $event['phone2']              =   "";
            $event['latitude']            =   "";
            $event['longitude']           =   "";
            $event['premium']             =   "";
            $event['is_disabled']         =   "";
            $event['order']               =   ""; 
            $event['seoId']                               =   ""; 
            $event['SEOMetaTitle']                        =   ""; 
            $event['SEOMetaDesc']                         =   ""; 
            $event['SEOMetaPublishedTime']                =   ""; 
            $event['SEOMetaKeywords']                     =   ""; 
            $event['OpenGraphTitle']                      =   ""; 
            $event['OpenGraphDesc']                       =   ""; 
            $event['OpenGraphUrl']                        =   ""; 
            $event['OpenGraphPropertyType']               =   ""; 
            $event['OpenGraphPropertyLocale']             =   ""; 
            $event['OpenGraphPropertyLocaleAlternate']    =   ""; 
            $event['OpenGraph']                           =   "";  
            
            $photoRs                        =   array();
        }

        $cityRs                             =   City::select('cityId','city', 'value')
                                                    ->orderBy('city', 'asc')
                                                    ->get();  
        $cities                             =   $cityRs->toArray();  
        return view('admin.events_add',['event' => $event, 'cities' => $cities, 'photos' => $photoRs]); 
    }

    public function addEvents(Request $request){

        $event                              =   $request->post();

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'urlName' => [
                        'required',
                        Rule::unique('url')->ignore($event['urlId'], 'id'),
            ],
            //'address1' => 'required',
            //'city' => 'required',
            //'state' => 'required',
            //'photos.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            //'thumbnail.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048'            
        ]);

        if ($validator->fails()) {
            if($event['id']){
                return redirect('/admin/events_add/'.$event['id'])->withErrors($validator)->withInput();
            }else{
                return redirect('/admin/events_add')->withErrors($validator)->withInput();
            }
        }
        
        if($event['id']){
            DB::table('events')
                ->where('id', $event['id'])
                ->update(
                    [
                        'name'          => $event['name'],
                        'description'   => $event['description'],
                        'siteId'        => config('app.siteId'),
                        'website'       => $event['website'],
                        'order'         => ($event['order'])?$event['order']:0,
                        'premium'       => $event['premium'],
                        'is_disabled'   => $event['is_disabled'],
                        'updated_by'    => Auth::user()->id,
                        'updated_at'    => date("Y-m-d H:i:s"),
                    ]
                );
            DB::table('address')
                ->where('id', $event['addressId'])
                ->update(
                    [
                        'address1'      => $event['address1'],
                        'address2'      => $event['address2'],
                        'city'          => $event['city'],
                        'state'         => $event['state'],
                        'zip'           => $event['zip'],
                        'county'        => $event['county'],
                        'phone1'        => $event['phone1'],
                        'phone2'        => $event['phone2'],
                        'latitude'      => $event['latitude'],
                        'longitude'     => $event['latitude'],                   
                    ]
            );
            if($event['urlName'] != $event['urlNameChk']){
                DB::table('url')
                ->where('id', $event['urlId'])
                ->update(
                    [
                        'urlName'       => $event['urlName'],
                        'updated_at'    => date("Y-m-d H:i:s")                 
                    ]
                );
            }
            DB::table('seo')
                ->where('seoId', $event['seoId'])
                ->update(
                    [
                        'SEOMetaTitle'                      => $event['SEOMetaTitle'],
                        'SEOMetaDesc'                       => $event['SEOMetaDesc'],
                        'SEOMetaPublishedTime'              => $event['SEOMetaPublishedTime'],
                        'SEOMetaKeywords'                   => $event['SEOMetaKeywords'],
                        'OpenGraphTitle'                    => $event['OpenGraphTitle'],
                        'OpenGraphDesc'                     => $event['OpenGraphDesc'],
                        'OpenGraphUrl'                      => $event['OpenGraphUrl'],
                        'OpenGraphPropertyType'             => $event['OpenGraphPropertyType'],
                        'OpenGraphPropertyLocale'           => $event['OpenGraphPropertyLocale'],
                        'OpenGraphPropertyLocaleAlternate'  => $event['OpenGraphPropertyLocaleAlternate'],
                        'OpenGraph'                         => $event['OpenGraph'],
                        'updated_at'                        => date("Y-m-d H:i:s")
                    ]
                ); 

            if (!file_exists(public_path().'/image/event/'.$event['id'])) {
                mkdir(public_path().'/image/event/'.$event['id'], 0777, true);
            }
            if($request->hasFile('photos')){
                $files                          = $request->file('photos');
                
                DB::table('photo')->where('eventId', $event['id'])->where('is_primary', 0)->delete();
                
            
                foreach($files as $key=> $file){
                    $filename                   = $file->getClientOriginalName();
                    $rand                       = (rand(10,100));
                    $extension                  = $file->getClientOriginalExtension();                
                    $fileName                   = $event['urlName'].'-'.$key.'-'.$rand.'.'.$extension;

                    $resizeImage                = Image::make($file);
                    $resizeImage->resize(466,350);
                    $path                       = public_path('image/event/'.$event['id'].'/'.$event['urlName'].'-'.$key.'-'.$rand.'.'.$extension);
                    $resizeImage->save($path);   
                    //$file->move(public_path().'/image/event/'.$event['id'], $fileName); 

                    DB::table('photo')->insertGetId(
                        [
                            'photoName'         => $fileName,
                            'order'             => $key,
                            'eventId'           => $event['id'],
                            'updated_at'        => date("Y-m-d H:i:s")
                        ]
                    );
                }
            }
            if($request->hasFile('thumbnail')){
                $files                          = $request->file('thumbnail');

                DB::table('photo')->where('eventId', $event['id'])->where('is_primary', 1)->delete();
            
                foreach($files as $key=> $file){
                    $filename                   = $file->getClientOriginalName();
                    $rand                       = (rand(10,1000));
                    $extension                  = $file->getClientOriginalExtension();                
                    $fileName                   = $event['urlName'].'-'.$key.'-'.$rand.'.'.$extension;
                    //$file->move(public_path().'/image/event/'.$event['id'], $fileName); 
                    $resizeImage                = Image::make($file);
                    $resizeImage->resize(128,95);
                    $path                       = public_path('image/event/'.$event['id'].'/'.$event['urlName'].'-'.$key.'-'.$rand.'.'.$extension);
                    $resizeImage->save($path);                 
                
                    DB::table('photo')->insertGetId(
                        [
                            'photoName'         => $fileName,
                            'order'             => $key,
                            'eventId'         => $event['id'],
                            'is_primary'        => 1,
                            'updated_at'  => date("Y-m-d H:i:s")
                        ]
                    );
                }
            }        
            return redirect('/admin/events')->with('status', 'Event updated!');                    
        }else{

            $eventId                      =   DB::table('events')->insertGetId(
                                                    [
                                                        'name'          => $event['name'],
                                                        'description'   => $event['description'],
                                                        'siteId'        => config('app.siteId'),
                                                        'website'       => $event['website'],
                                                        'order'         => ($event['order'])?$event['order']:0,
                                                        'premium'       => $event['premium'],
                                                        'is_disabled'   => $event['is_disabled'],
                                                        'urlId'         => 0,
                                                        'addressId'     => 0,
                                                        'updated_by'    => Auth::user()->id,
                                                        'updated_at'    => date("Y-m-d H:i:s"),
                                                        'created_at'    => date("Y-m-d H:i:s")
                                                    ]
                                                );

            $addressId                      =   DB::table('address')->insertGetId(
                                                    [
                                                        'address1'      => $event['address1'],
                                                        'address2'      => $event['address2'],
                                                        'city'          => $event['city'],
                                                        'state'         => $event['state'],
                                                        'zip'           => $event['zip'],
                                                        'county'        => $event['county'],
                                                        'phone1'        => $event['phone1'],
                                                        'phone2'        => $event['phone2'],
                                                        'latitude'      => $event['latitude'],
                                                        'longitude'     => $event['latitude'],
                                                    ]
                                                );
            $urlId                          =   DB::table('url')->insertGetId(
                                                    [
                                                        'urlName'       => $event['urlName'],
                                                        'eventId'       => $eventId,
                                                        'created_at'    => date("Y-m-d H:i:s"),
                                                        'updated_at'    => date("Y-m-d H:i:s")
                                                    ]
                                                ); 
            DB::table('events')
                ->where('id', $eventId)
                ->update(
                    [
                        'urlId'             => $urlId,
                        'addressId'         => $addressId
                    ]
                );

            $seoId                          =   DB::table('seo')->insertGetId(
                                                    [
                                                        'urlId'                             => $urlId,
                                                        'SEOMetaTitle'                      => $event['SEOMetaTitle'],
                                                        'SEOMetaDesc'                       => $event['SEOMetaDesc'],
                                                        'SEOMetaPublishedTime'              => $event['SEOMetaPublishedTime'],
                                                        'SEOMetaKeywords'                   => $event['SEOMetaKeywords'],
                                                        'OpenGraphTitle'                    => $event['OpenGraphTitle'],
                                                        'OpenGraphDesc'                     => $event['OpenGraphDesc'],
                                                        'OpenGraphUrl'                      => $event['OpenGraphUrl'],
                                                        'OpenGraphPropertyType'             => $event['OpenGraphPropertyType'],
                                                        'OpenGraphPropertyLocale'           => $event['OpenGraphPropertyLocale'],
                                                        'OpenGraphPropertyLocaleAlternate'  => $event['OpenGraphPropertyLocaleAlternate'],
                                                        'OpenGraph'                         => $event['OpenGraph'],
                                                        'created_at'                        => date("Y-m-d H:i:s"),
                                                        'updated_at'                        => date("Y-m-d H:i:s")
                                                    ]
                                                );   

            if (!file_exists(public_path().'/image/event/'.$eventId)) {
                mkdir(public_path().'/image/event/'.$eventId, 0777, true);
            }                                                
            if($request->hasFile('photos')){
                $files                          = $request->file('photos');
            
                foreach($files as $key=> $file){
                    $filename                   = $file->getClientOriginalName();
                    $rand                       = (rand(10,100));
                    $extension                  = $file->getClientOriginalExtension();                
                    $fileName                   = $event['urlName'].'-'.$key.'-'.$rand.'.'.$extension;
                    //$file->move(public_path().'/image/event/'.$event['id'], $fileName); 
                    $resizeImage                = Image::make($file);
                    $resizeImage->resize(466,350);
                    $path                       = public_path('image/event/'.$eventId.'/'.$event['urlName'].'-'.$key.'-'.$rand.'.'.$extension);
                    $resizeImage->save($path);                      
    
                    DB::table('photo')->insertGetId(
                        [
                            'photoName'         => $fileName,
                            'order'             => $key,
                            'eventId'           => $eventId,
                            'created_at'        => date("Y-m-d H:i:s"),
                            'updated_at'        => date("Y-m-d H:i:s")
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
                    $fileName                   = $event['urlName'].'-'.$key.'-'.$rand.'.'.$extension;
                    //$file->move(public_path().'/image/event/'.$event['id'], $fileName); 
                    $resizeImage                = Image::make($file);
                    $resizeImage->resize(128,95);
                    $path                       = public_path('image/event/'.$eventId.'/'.$event['urlName'].'-'.$key.'-'.$rand.'.'.$extension);
                    $resizeImage->save($path);                     
    
                    DB::table('photo')->insertGetId(
                        [
                            'photoName'         => $fileName,
                            'order'             => $key,
                            'eventId'           => $eventId,
                            'is_primary'        => 1,
                            'created_at'        => date("Y-m-d H:i:s"),
                            'updated_at'        => date("Y-m-d H:i:s")
                        ]
                    );
                }
            }                                                
            return redirect('/admin/events')->with('status', 'Event added!');                                                
        }
    }
    
    public function deleteEvent($id){
        if($id){
            DB::table('events')
            ->where('id', $id)
            ->update(
                [
                    'is_deleted'        => 1
                ]
            ); 
        }
        return redirect('/admin/events')->with('status', 'Category deleted!');
    } 

    public function eventsCategory(){
        $siteId                         =   config('app.siteId');
        $eventsCategoryRs               =   EventsCategory::select('events_category.id', 'events_category.name', 
                                                    'events_category.is_disabled')
                                                ->where('events_category.is_deleted', '=', '0');

        $eventsCategoryRs               =   $eventsCategoryRs->get();
        $eventsCategorys                =   $eventsCategoryRs->toArray();        

        return view('admin.events_category_listing',['category' => $eventsCategorys]); 
    }

    public function eventsCategoryView($id=null){
        
        if($id){
            $categoryRs                     =   EventsCategory::select('events_category.id', 'events_category.name', 
                                                        'events_category.description', 'events_category.is_disabled'
                                                       )
                                                    ->where('events_category.id', '=', $id)
                                                    ->get()->first();

            $category                       =   $categoryRs->toArray();             
        }else{
            $category['id']                 =   "";
        
            $category['name']               =   "";
            $category['description']        =   "";
            $category['is_disabled']        =   "";
        }

        return view('admin.events_category_add',['category' => $category]); 
    }    

    public function addEventsCategory(Request $request){

        $categoryVal                        =   $request->post();

        $validator = Validator::make($request->all(), [
            'name' => 'required',                     
        ]);

        if ($validator->fails()) {
            if($categoryVal['id']){
                return redirect('/admin/events_category_add/'.$categoryVal['id'])->withErrors($validator)->withInput();
            }else{
                return redirect('/admin/events_category_add')->withErrors($validator)->withInput();
            }
        }
        
        if($categoryVal['id']){
            DB::table('events_category')
                ->where('id', $categoryVal['id'])
                ->update(
                    [
                        'name'          => $categoryVal['name'],
                        'description'   => $categoryVal['description'],                       
                        'is_disabled'   => $categoryVal['is_disabled'],
                        'updated_by'    => Auth::user()->id,
                        'updated_at'    => date("Y-m-d H:i:s")                    
                    ]
                );
            return redirect('/admin/events_category')->with('status', 'Category updated!');                    
        }else{

            $eventsCategoryId               =   DB::table('events_category')->insertGetId(
                                                    [
                                                        'name'          => $categoryVal['name'],
                                                        'description'   => $categoryVal['description'],                                                      
                                                        'is_disabled'   => $categoryVal['is_disabled'],                                                       
                                                        'updated_by'    => Auth::user()->id,
                                                        'created_at'    => date("Y-m-d H:i:s"),
                                                        'updated_at'    => date("Y-m-d H:i:s")
                                                    ]
                                                );

                                               
            return redirect('/admin/events_category')->with('status', 'Category added!');                                                
        }
    }

    public function deleteCategory($id){
        if($id){
            DB::table('events_category')
            ->where('id', $id)
            ->update(
                [
                    'is_deleted'        => 1
                ]
            ); 
        }
        return redirect('/admin/events_category')->with('status', 'Category deleted!');
    } 

}
