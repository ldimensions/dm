<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Models\Url;
use App\Http\Models\City;
use App\Http\Models\Photo;
use App\Http\Models\PhotoTmp;
use App\Http\Models\Events;
use App\Http\Models\EventsTmp;
use App\Http\Models\EventsCategory;
use App\Http\Models\EventsSchedule;
use App\Http\Models\EventsScheduleTmp;
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
                                                    'events.updated_at','users.name as updatedBy',
                                                    'events.is_disabled', 'url.urlName')
                                                ->leftjoin('url','url.eventId', '=', 'events.id')
                                                ->leftjoin('address','address.id', '=', 'events.addressId')
                                                ->leftjoin('site','site.siteId', '=', 'events.siteId')                                            
                                                ->leftjoin('city','city.cityId', '=', 'address.city')       
                                                ->leftjoin('users','users.id', '=', 'events.updated_by')                                                                            
                                                ->where('events.is_deleted', '=', '0')
                                                ->where('site.siteId', '=', $siteId)
                                                ->orderBy('events.premium', 'DESC')
                                                ->orderBy('events.order', 'ASC');                                                  

        $eventsRs                       =   $eventsRs->get();
        $events                         =   $eventsRs->toArray();
        // echo "<pre>";
        // print_r($events);exit();

        $eventsTmpRs                    =   EventsTmp::select('events_tmp.id as eventsId', 'events_tmp.name', 
                                                    'city.city', 'events_tmp.status', 
                                                    'events_tmp.updated_at', 'users.name as updatedBy',
                                                    'events_tmp.is_disabled', 'url.urlName')
                                                ->leftjoin('url','url.eventTempId', '=', 'events_tmp.id')
                                                ->leftjoin('address_tmp','address_tmp.id', '=', 'events_tmp.addressId')
                                                ->leftjoin('site','site.siteId', '=', 'events_tmp.siteId')                                            
                                                ->leftjoin('city','city.cityId', '=', 'address_tmp.city') 
                                                ->leftjoin('users','users.id', '=', 'events_tmp.updated_by')                                                                                           
                                                ->where('events_tmp.is_deleted', '=', '0')
                                                ->where('site.siteId', '=', $siteId)
                                                ->orderBy('events_tmp.premium', 'DESC')
                                                ->orderBy('events_tmp.order', 'ASC');                                                  

        $eventsTmpRs                    =   $eventsTmpRs->get();
        $eventsTmp                      =   $eventsTmpRs->toArray();        
                

        return view('admin.events_listing',['events' => $events, 'eventsTmp' => $eventsTmp]);          
    } 

    public function addEventsView($id=null){
        
        if($id){
            $eventsRs                       =   Events::select('events.id', 'events.name', 
                                                            'events.img',
                                                            'events.organizerName',
                                                            'events.organizerEmail', 'events.organizerPhone',
                                                            'events.description', 'events.categoryId',
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

            $eventScheduleArr               =   EventsSchedule::select('dateTime')
                                                    ->orderBy('dateTime', 'asc')
                                                    ->where('eventId', '=', $id)
                                                    ->get();  
            $eventScheduleRs                =   $eventScheduleArr->toArray();  

            $eventScheduleTime              =   array();
            foreach ($eventScheduleRs as $eventScheduleKey => $eventSchedule) {
                $eventScheduleTime[$eventScheduleKey]['dateTime']   =   date('Y-m-d\TH:i',  strtotime($eventSchedule['dateTime']));
            }
            
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
            $event['img']                  =   "";
            $event['description']         =   "";
            $event['categoryId']          =   "";
            $event['organizerName']    =   "";
            $event['organizerEmail']   =   "";
            $event['organizerPhone']   =   "";
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
            $eventScheduleTime              =   array();
        }

        $cityRs                             =   City::select('cityId','city', 'value')
                                                    ->orderBy('city', 'asc')
                                                    ->get();  
        $cities                             =   $cityRs->toArray(); 
        
        $eventCategoryArr                   =   EventsCategory::select('id', 'name')
                                                    ->orderBy('name', 'asc')
                                                    ->get();  
        $eventCategoryRs                    =   $eventCategoryArr->toArray(); 
        return view('admin.events_add',['event' => $event, 'cities' => $cities, 'photos' => $photoRs, 'eventSchedules' => $eventScheduleTime, 'eventCategorys' => $eventCategoryRs]); 
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
                        'categoryId'             => $event['categoryId'],
                        'organizerName'          => $event['organizerName'],
                        'organizerEmail'         => $event['organizerEmail'],
                        'organizerPhone'         => $event['organizerPhone'],
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

                DB::table('event_schedule')->where('eventId', $event['id'])->delete();

                for($i =0; $i<$event['scheduleCount']; $i++){
                    if(isset($event['dateTime'][$i])){
                        DB::table('event_schedule')->insert([
                            [
                                'eventId' => $event['id'], 
                                'dateTime' => $event['dateTime'][$i], 
                                'created_at' => date("Y-m-d H:i:s"), 
                                'updated_at' => date("Y-m-d H:i:s") 
                            ],
                        ]);                                        
                    }                
                }                 

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

                    // $resizeImage                = Image::make($file);
                    // $resizeImage->resize(466,350);
                    // $path                       = public_path('image/event/'.$event['id'].'/'.$event['urlName'].'-'.$key.'-'.$rand.'.'.$extension);
                    // $resizeImage->save($path);   
                    $file->move(public_path().'/image/event/'.$event['id'], $event['urlName'].'-'.$key.'-'.$rand.'.'.$extension);                     

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
                    // $resizeImage                = Image::make($file);
                    // $resizeImage->resize(128,95);
                    // $path                       = public_path('image/event/'.$event['id'].'/'.$event['urlName'].'-'.$key.'-'.$rand.'.'.$extension);
                    // $resizeImage->save($path); 
                    $file->move(public_path().'/image/event/'.$event['id'], $event['urlName'].'-'.$key.'-'.$rand.'.'.$extension);                                                   
                
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
                if($request->hasFile('detailImg')){
                    $files                          = $request->file('detailImg');                    
                    foreach($files as $key=> $file){
                        $filename                   = $file->getClientOriginalName();
                        $rand                       = (rand(10,1000));
                        $extension                  = $file->getClientOriginalExtension();                
                        $fileName                   = $event['urlName'].'-layout-'.$key.'-'.$rand.'.'.$extension;
                        $file->move(public_path().'/image/event/'.$event['id'], $fileName);                                     
                        DB::table('events')
                            ->where('id', $event['id'])
                            ->update(
                                [
                                    'img'             => $fileName
                                ]
                            );
                    }
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
                                                        'categoryId'             => $event['categoryId'],
                                                        'organizerName'          => $event['organizerName'],
                                                        'organizerEmail'         => $event['organizerEmail'],
                                                        'organizerPhone'         => $event['organizerPhone'],
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

            for($i =0; $i<$event['scheduleCount']; $i++){
                if(isset($event['dateTime'][$i])){
                    DB::table('event_schedule')->insert([
                        [
                            'eventId' => $eventId, 
                            'dateTime' => $event['dateTime'][$i], 
                            'created_at' => date("Y-m-d H:i:s"), 
                            'updated_at' => date("Y-m-d H:i:s") 
                        ],
                    ]);                                        
                }                
            }                                                  

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
                    // $resizeImage                = Image::make($file);
                    // $resizeImage->resize(466,350);
                    // $path                       = public_path('image/event/'.$eventId.'/'.$event['urlName'].'-'.$key.'-'.$rand.'.'.$extension);
                    // $resizeImage->save($path);                      
                    $file->move(public_path().'/image/event/'.$eventId, $event['urlName'].'-'.$key.'-'.$rand.'.'.$extension);                                     

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
                    // $resizeImage                = Image::make($file);
                    // $resizeImage->resize(128,95);
                    // $path                       = public_path('image/event/'.$eventId.'/'.$event['urlName'].'-'.$key.'-'.$rand.'.'.$extension);
                    // $resizeImage->save($path);         
                    $file->move(public_path().'/image/event/'.$eventId, $event['urlName'].'-'.$key.'-'.$rand.'.'.$extension);             
    
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
            if($request->hasFile('detailImg')){
                $files                          = $request->file('detailImg');
                
                foreach($files as $key=> $file){
                    $filename                   = $file->getClientOriginalName();
                    $rand                       = (rand(10,1000));
                    $extension                  = $file->getClientOriginalExtension();                
                    $fileName                   = $event['urlName'].'-layout-'.$key.'-'.$rand.'.'.$extension;
                    $file->move(public_path().'/image/event/'.$eventId, $fileName);                                     
                    DB::table('events')
                        ->where('id', $eventId)
                        ->update(
                            [
                                'img'             => $fileName
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

    public function deleteEventTmp($id){
        if($id){

            $eventsTmpRs                        =   EventsTmp::select('events_tmp.urlId', 'events_tmp.addressId', 'events_tmp.referenceId')
                                                        ->where('events_tmp.id', '=', $id)
                                                        ->get()->first();

            $eventsTmp                          =   $eventsTmpRs->toArray(); 

            DB::table('events_tmp')->where('id', $id)->delete();
            DB::table('address_tmp')->where('id', $eventsTmp['addressId'])->delete();
            DB::table('seo_tmp')->where('urlId', $eventsTmp['urlId'])->delete();
            DB::table('photo_tmp')->where('eventId', $id)->delete();    
            DB::table('event_schedule_tmp')->where('eventId', $id)->delete();        
            if($eventsTmp['referenceId']){
                DB::table('url')
                    ->where('id', $eventsTmp['urlId'])
                    ->update(
                        [
                            'eventTempId'       => 0,
                        ]
                    );
            }else{
                DB::table('url')->where('eventTempId', $id)->delete();                
            }

            $this->deleteDirectory(public_path().'/image/event/'.$id.'_tmp');
                
            return redirect('/admin/events')->with('status', 'Event deleted!');
        }else{
            return redirect('/admin/events')->with('status', 'Event deleted!');
        }
        return redirect('/admin/events')->with('status', 'Event deleted!');
    } 

    public function deleteDirectory($dir) {
        if (!file_exists($dir)) {
            return true;
        }
    
        if (!is_dir($dir)) {
            return unlink($dir);
        }
    
        foreach (scandir($dir) as $item) {
            if ($item == '.' || $item == '..') {
                continue;
            }
    
            if (!$this->deleteDirectory($dir . DIRECTORY_SEPARATOR . $item)) {
                return false;
            }
    
        }
    
        return rmdir($dir);
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

    public function rejectEvent(Request $request){
        $eventVal                      =   $request->post();
        if($eventVal['id']){
            DB::table('events_tmp')
                ->where('id', $eventVal['id'])
                ->update(
                    [
                        'status'            => '4',
                        'statusMsg'         =>   $eventVal['reason']                                     
                    ]
                );        

            return redirect('/admin/events')->with('status', 'Event rejected!');
        }      
    }   
    
    public function approveEvent($id){
        $eventTmpRs                         =   EventsTmp::select('events_tmp.id', 'events_tmp.name', 'events_tmp.referenceId',
                                                            'events_tmp.description', 'events_tmp.website', 'events_tmp.categoryId',
                                                            'events_tmp.organizerName', 'events_tmp.organizerEmail',
                                                            'events_tmp.organizerPhone', 'events_tmp.img', 
                                                            'events_tmp.premium', 'events_tmp.order','events_tmp.is_disabled', 
                                                            'events_tmp.updated_by', 'events_tmp.created_at', 
                                                            'url.urlName', 'url.id as urlId',  
                                                            'address_tmp.id as addressTempId',
                                                            'address_tmp.address1', 'address_tmp.address2','address_tmp.id',
                                                            'address_tmp.city', 'address_tmp.state',
                                                            'address_tmp.zip', 'address_tmp.county',
                                                            'address_tmp.phone1', 'address_tmp.phone2', 'address_tmp.fax',
                                                            'address_tmp.latitude', 'address_tmp.longitude',
                                                            'seo_tmp.keyValue', 'seo_tmp.index',                                         
                                                            'seo_tmp.seoId', 'seo_tmp.SEOMetaTitle',
                                                            'seo_tmp.SEOMetaDesc', 'seo_tmp.SEOMetaPublishedTime',
                                                            'seo_tmp.SEOMetaKeywords', 'seo_tmp.OpenGraphTitle',
                                                            'seo_tmp.OpenGraphDesc', 'seo_tmp.OpenGraphUrl',
                                                            'seo_tmp.OpenGraphPropertyType', 'seo_tmp.OpenGraphPropertyLocale',
                                                            'seo_tmp.OpenGraphPropertyLocaleAlternate', 'seo_tmp.OpenGraph')
                                                        ->leftjoin('url','url.eventTempId', '=', 'events_tmp.id')
                                                        ->leftjoin('address_tmp','address_tmp.id', '=', 'events_tmp.addressId')   
                                                        ->leftjoin('site','site.siteId', '=', 'events_tmp.siteId')
                                                        ->leftjoin('seo_tmp','seo_tmp.urlId', '=', 'url.id')                                                    
                                                        ->where('events_tmp.id', '=', $id)
                                                        ->get()->first();
                                                            
        $eventTmpArr                        =   $eventTmpRs->toArray(); 
        if($eventTmpArr['referenceId']){

            $eventsRs                       =   Events::select('events.urlId')
                                                            ->where('events.id', '=', $eventTmpArr['referenceId'])->get()->first();                                                   

            $event                         =   $eventsRs->toArray();            

            DB::table('events')
                ->where('id', $eventTmpArr['referenceId'])
                ->update(
                    [
                        'name'          => $eventTmpArr['name'],
                        'description'   => $eventTmpArr['description'],
                        'siteId'        => config('app.siteId'),
                        'website'       => $eventTmpArr['website'],
                        'categoryId'             => $eventTmpArr['categoryId'],
                        'organizerName'          => $eventTmpArr['organizerName'],
                        'organizerEmail'         => $eventTmpArr['organizerEmail'],
                        'organizerPhone'         => $eventTmpArr['organizerPhone'],
                        'order'         => ($eventTmpArr['order'])?$eventTmpArr['order']:0,
                        'premium'       => $eventTmpArr['premium'],
                        'is_disabled'   => $eventTmpArr['is_disabled'],
                        'is_deleted'    => 0,  
                        'updated_at'    => date("Y-m-d H:i:s")
                    ]                    
                );             
        
            DB::table('url')
                ->where('id', $event['urlId'])
                ->update(
                    [
                        'eventTempId'    => 0                
                    ]
                );
            DB::table('seo')
                ->where('urlId', $event['urlId'])
                ->update(
                    [
                        'SEOMetaTitle'                      => $eventTmpArr['SEOMetaTitle'],
                        'SEOMetaDesc'                       => $eventTmpArr['SEOMetaDesc'],
                        'SEOMetaPublishedTime'              => $eventTmpArr['SEOMetaPublishedTime'],
                        'SEOMetaKeywords'                   => $eventTmpArr['SEOMetaKeywords'],
                        'OpenGraphTitle'                    => $eventTmpArr['OpenGraphTitle'],
                        'OpenGraphDesc'                     => $eventTmpArr['OpenGraphDesc'],
                        'OpenGraphUrl'                      => $eventTmpArr['OpenGraphUrl'],
                        'OpenGraphPropertyType'             => $eventTmpArr['OpenGraphPropertyType'],
                        'OpenGraphPropertyLocale'           => $eventTmpArr['OpenGraphPropertyLocale'],
                        'OpenGraphPropertyLocaleAlternate'  => $eventTmpArr['OpenGraphPropertyLocaleAlternate'],
                        'OpenGraph'                         => $eventTmpArr['OpenGraph'],
                        'updated_at'                        => date("Y-m-d H:i:s")
                    ]
            ); 

            $photoArr                       =   PhotoTmp::select('photoName','is_primary', 'order', 'is_deleted', 'is_disabled')
                                                        ->orderBy('order', 'asc')
                                                        ->where('eventId', '=', $id)
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
                        'eventId' => $eventTmpArr['referenceId']]
                    ]);  
                }
            } 
            
            $eventScheduleTmpTs                  =   EventsScheduleTmp::select('event_schedule_tmp.dateTime',
                                                                    'event_schedule_tmp.updated_at')                                              
                                                        ->where('event_schedule_tmp.eventId', '=', $id)
                                                        ->get();

            $eventScheduleTmpArr                 =   $eventScheduleTmpTs->toArray();     

            for($i =0; $i < count($eventScheduleTmpArr); $i++){
                DB::table('event_schedule')->insert([
                    [
                    'eventId' => $eventTmpArr['referenceId'], 
                    'dateTime' => $eventScheduleTmpArr[$i]['dateTime'],
                    'updated_at' => $eventScheduleTmpArr[$i]['updated_at']
                    ],
                ]);    
            }

            DB::table('events_tmp')->where('id', $id)->delete();
            DB::table('seo_tmp')->where('urlId', $event['urlId'])->delete();        
            DB::table('photo_tmp')->where('eventId', $id)->delete();    
            DB::table('event_schedule_tmp')->where('eventId', $eventTmpArr['id'])->delete();
                                
        }else{

            $addressId                      =   DB::table('address')->insertGetId(
                                                                [
                                                                    'address1'      => $eventTmpArr['address1'],
                                                                    'address2'      => $eventTmpArr['address2'],
                                                                    'city'          => $eventTmpArr['city'],
                                                                    'state'         => $eventTmpArr['state'],
                                                                    'zip'           => $eventTmpArr['zip'],
                                                                    'county'        => $eventTmpArr['county'],
                                                                    'phone1'        => $eventTmpArr['phone1'],
                                                                    'phone2'        => $eventTmpArr['phone2'],
                                                                    'latitude'      => $eventTmpArr['latitude'],
                                                                    'longitude'     => $eventTmpArr['longitude'],
                                                                ]
                                                );  

            $eventId                        =   DB::table('events')->insertGetId(
                                                    [
                                                        'name'          => $eventTmpArr['name'],
                                                        'description'   => $eventTmpArr['description'],
                                                        'siteId'        => config('app.siteId'),
                                                        'website'       => $eventTmpArr['website'],
                                                        'categoryId'             => $eventTmpArr['categoryId'],
                                                        'organizerName'          => $eventTmpArr['organizerName'],
                                                        'organizerEmail'         => $eventTmpArr['organizerEmail'],
                                                        'organizerPhone'         => $eventTmpArr['organizerPhone'],
                                                        'order'         => ($eventTmpArr['order'])?$eventTmpArr['order']:0,
                                                        'premium'       => $eventTmpArr['premium'],
                                                        'is_disabled'   => $eventTmpArr['is_disabled'],
                                                        'img'           => $eventTmpArr['img'],
                                                        'is_deleted'    => 0,
                                                        'addressId'     => $addressId,
                                                        'urlId'         => $eventTmpArr['urlId'],
                                                        'updated_by'    => $eventTmpArr['updated_by'],
                                                        'created_at'  => $eventTmpArr['created_at'],
                                                        'updated_at'  => $eventTmpArr['created_at']  
                                                    ]
                                                );

            DB::table('url')
                ->where('id', $eventTmpArr['urlId'])
                ->update(
                [
                'eventId'          => $eventId,
                'eventTempId'      => 0
                ]
            );              

            $seoId                          =   DB::table('seo')->insertGetId(
                                                        [
                                                        'urlId'                             => $eventTmpArr['urlId'],
                                                        'SEOMetaTitle'                      => $eventTmpArr['SEOMetaTitle'],
                                                        'SEOMetaDesc'                       => $eventTmpArr['SEOMetaDesc'],
                                                        'SEOMetaPublishedTime'              => $eventTmpArr['SEOMetaPublishedTime'],
                                                        'SEOMetaKeywords'                   => $eventTmpArr['SEOMetaKeywords'],
                                                        'OpenGraphTitle'                    => $eventTmpArr['OpenGraphTitle'],
                                                        'OpenGraphDesc'                     => $eventTmpArr['OpenGraphDesc'],
                                                        'OpenGraphUrl'                      => $eventTmpArr['OpenGraphUrl'],
                                                        'OpenGraphPropertyType'             => $eventTmpArr['OpenGraphPropertyType'],
                                                        'OpenGraphPropertyLocale'           => $eventTmpArr['OpenGraphPropertyLocale'],
                                                        'OpenGraphPropertyLocaleAlternate'  => $eventTmpArr['OpenGraphPropertyLocaleAlternate'],
                                                        'OpenGraph'                         => $eventTmpArr['OpenGraph'],
                                                        'created_at'                        => $eventTmpArr['OpenGraph'],
                                                        'updated_at'                        => $eventTmpArr['OpenGraph']
                                                        ]
                                                );     
                                                
            $eventScheduleTmpTs                  =   EventsScheduleTmp::select('event_schedule_tmp.dateTime',
                                                                        'event_schedule_tmp.updated_at')                                              
                                                            ->where('event_schedule_tmp.eventId', '=', $id)
                                                            ->get();
            
            $eventScheduleTmpArr                 =   $eventScheduleTmpTs->toArray();     

            for($i =0; $i < count($eventScheduleTmpArr); $i++){
                DB::table('event_schedule')->insert([
                        [
                            'eventId' => $eventId, 
                            'dateTime' => $eventScheduleTmpArr[$i]['dateTime'],
                            'updated_at' => $eventScheduleTmpArr[$i]['updated_at']
                        ],
                    ]);    
            }

            $photoTypeRs                     =   PhotoTmp::select('photo_tmp.photoName', 'photo_tmp.is_primary', 'photo_tmp.order')
                                                        ->where('photo_tmp.eventId', '=', $id)
                                                        ->get();    

            $photoTypeArr                    =   $photoTypeRs->toArray();      
            foreach($photoTypeArr as $photoType){
                DB::table('photo')->insert([
                    ['photoName' => $photoType['photoName'], 
                    'is_primary' => $photoType['is_primary'], 
                    'order' => $photoType['order'], 
                    'eventId' => $eventId]
                ]); 
            }   
           
            if (!file_exists(public_path().'/image/event/'.$eventId)) {
                mkdir(public_path().'/image/event/'.$eventId, 0777, true);
            }

            $this->copyFiles(public_path().'/image/event/'.$id.'_tmp', public_path().'/image/event/'.$eventId);
            $this->deleteDirectory(public_path().'/image/event/'.$id.'_tmp');

            DB::table('events_tmp')->where('id', $id)->delete();
            DB::table('seo_tmp')->where('urlId', $eventTmpArr['urlId'])->delete();        
            DB::table('photo_tmp')->where('eventId', $id)->delete();
            DB::table('address_tmp')->where('id', $eventTmpArr['id'])->delete();
            DB::table('event_schedule')->where('id', $id)->delete();
        }

        return redirect('/admin/events')->with('status', 'Movie Approved!');        
    }  
    
    function copyFiles($src, $dst) {

        /* Returns false if src doesn't exist */
        $dir = @opendir($src);
      
        /* Make destination directory. False on failure */
        if (!file_exists($dst)) @mkdir($dst);
      
        /* Recursively copy */
        while (false !== ($file = readdir($dir))) {
      
            if (( $file != '.' ) && ( $file != '..' )) {
               if ( is_dir($src . '/' . $file) ) copyFiles($src . '/' . $file, $dst . '/' . $file); 
               else copy($src . '/' . $file, $dst . '/' . $file);
            }
      
        }
       closedir($dir); 
      }


}
