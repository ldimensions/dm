<?php

namespace App\Http\Controllers\Editor;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Models\Url;
use App\Http\Models\City;
use App\Http\Models\Photo;
use App\Http\Models\PhotoTmp;
use App\Http\Models\Events;
use App\Http\Models\EventsTmp;
use App\Http\Models\EventsCategory;
use App\Http\Models\EventsScheduleTmp;
use App\Http\Models\EventsSchedule;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class EventsController extends Controller
{
    public function __construct(){
        $this->middleware('role:Editor');
    }

    public function index(){
        $siteId                         =   config('app.siteId');
        $eventsRs                       =   Events::select('events.id as eventsId', 'events.name', 
                                                    'city.city', 'events.updated_at',
                                                    'events_tmp.referenceId', 'users.name as updatedBy',
                                                    'events.is_disabled', 'url.urlName')
                                                ->leftjoin('url','url.eventId', '=', 'events.id')
                                                ->leftjoin('events_tmp','events.id', '=', 'events_tmp.referenceId')                                                         
                                                ->leftjoin('address','address.id', '=', 'events.addressId')
                                                ->leftjoin('site','site.siteId', '=', 'events.siteId')                                            
                                                ->leftjoin('city','city.cityId', '=', 'address.city')    
                                                ->leftjoin('users','users.id', '=', 'events.updated_by')                                        
                                                ->where('events.is_deleted', '=', '0')
                                                ->where('site.siteId', '=', $siteId)
                                                ->orderBy('events.created_at', 'DESC');                                                  

        $eventsRs                       =   $eventsRs->get();
        $events                         =   $eventsRs->toArray();

        $eventsTmpRs                    =   EventsTmp::select('events_tmp.id as eventsId', 'events_tmp.name', 
                                                    'events_tmp.updated_at',
                                                    'city.city', 'events_tmp.status', 'users.name as updatedBy',
                                                    'events_tmp.is_disabled', 'url.urlName')
                                                ->leftjoin('url','url.eventId', '=', 'events_tmp.id')
                                                ->leftjoin('address_tmp','address_tmp.id', '=', 'events_tmp.addressId')
                                                ->leftjoin('site','site.siteId', '=', 'events_tmp.siteId')                                            
                                                ->leftjoin('city','city.cityId', '=', 'address_tmp.city')     
                                                ->leftjoin('users','users.id', '=', 'events_tmp.updated_by')                                                 
                                                ->where('events_tmp.is_deleted', '=', '0')
                                                ->where('site.siteId', '=', $siteId)
                                                ->orderBy('events_tmp.created_at', 'DESC');                                                  

        $eventsTmpRs                    =   $eventsTmpRs->get();
        $eventsTmp                      =   $eventsTmpRs->toArray();        
                

        return view('editor.events_listing',['events' => $events, 'events_pending' => $eventsTmp]);          
    } 

    public function addEventsView($id=null){
        
        if($id){
            $eventsRs                       =   EventsTmp::select('events_tmp.id', 'events_tmp.name', 
                                                            'events_tmp.statusMsg', 'events_tmp.status',
                                                            'events_tmp.img',
                                                            'events_tmp.organizerName',
                                                            'events_tmp.organizerEmail', 'events_tmp.organizerPhone',
                                                            'events_tmp.description', 'events_tmp.categoryId',
                                                            'events_tmp.premium', 'events_tmp.order','events_tmp.is_disabled', 
                                                            'address_tmp.address1', 'address_tmp.address2', 'address_tmp.id as addressId',
                                                            'events_tmp.website',  'url.urlName', 'url.id as urlId',                                              
                                                            'address_tmp.state', 'address_tmp.city as city',
                                                            'address_tmp.zip', 'address_tmp.county',
                                                            'address_tmp.phone1', 'address_tmp.phone2', 'address_tmp.latitude',
                                                            'address_tmp.longitude', 
                                                            'seo_tmp.seoId', 'seo_tmp.SEOMetaTitle',
                                                            'seo_tmp.SEOMetaDesc', 'seo_tmp.SEOMetaPublishedTime',
                                                            'seo_tmp.SEOMetaKeywords', 'seo_tmp.OpenGraphTitle',
                                                            'seo_tmp.OpenGraphDesc', 'seo_tmp.OpenGraphUrl',
                                                            'seo_tmp.OpenGraphPropertyType', 'seo_tmp.OpenGraphPropertyLocale',
                                                            'seo_tmp.OpenGraphPropertyLocaleAlternate', 'seo_tmp.OpenGraph')
                                                        ->leftjoin('url','url.eventTempId', '=', 'events_tmp.id')
                                                        ->leftjoin('address_tmp','address_tmp.id', '=', 'events_tmp.addressId')
                                                        ->leftjoin('site','site.siteId', '=', 'events_tmp.siteId')
                                                        ->leftjoin('city','city.cityId', '=', 'address_tmp.city')   
                                                        ->leftjoin('seo_tmp','seo_tmp.urlId', '=', 'url.id')                                                    
                                                        ->where('events_tmp.id', '=', $id)
                                                        ->get()->first();

            $event                          =   $eventsRs->toArray(); 

            $eventScheduleArr               =   EventsScheduleTmp::select('dateTime')
                                                    ->orderBy('dateTime', 'asc')
                                                    ->where('eventId', '=', $id)
                                                    ->get();  
            $eventScheduleRs                =   $eventScheduleArr->toArray();  

            $eventScheduleTime              =   array();
            foreach ($eventScheduleRs as $eventScheduleKey => $eventSchedule) {
                $eventScheduleTime[$eventScheduleKey]['dateTime']   =   date('Y-m-d\TH:i',  strtotime($eventSchedule['dateTime']));
            }
            
            $photoArr                       =   PhotoTmp::select('photoName','is_primary')
                        ->orderBy('order', 'desc')
                        ->where('eventId', '=', $id)
                        ->get();  
            $photoRs                        =   $photoArr->toArray();  
            $event['ref_id']              =   "";
            $event['temp_id']              =   "";
        }else{
            $event['id']                  =   "";
            $event['ref_id']              =   "";
            $event['status']              =   "";
            $event['temp_id']             =   "";
            $event['referenceId']         =   "";
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
        return view('editor.events_add',['event' => $event, 'cities' => $cities, 'photos' => $photoRs, 'eventSchedules' => $eventScheduleTime, 'eventCategorys' => $eventCategoryRs]); 
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
                return redirect('/editor/events_add/'.$event['id'])->withErrors($validator)->withInput();
            }else{
                return redirect('/editor/events_add')->withErrors($validator)->withInput();
            }
        }
        
        if($event['id']  && $event['temp_id'] == ""){
            DB::table('events_tmp')
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
                        'status'        => 2,
                        'updated_at'    => date("Y-m-d H:i:s"),
                    ]
                );
            DB::table('address_tmp')
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
            DB::table('seo_tmp')
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

            DB::table('event_schedule_tmp')->where('eventId', $event['id'])->delete();

            for($i =0; $i<$event['scheduleCount']; $i++){
                if(isset($event['dateTime'][$i])){
                    DB::table('event_schedule_tmp')->insert([
                        [
                            'eventId' => $event['id'], 
                            'dateTime' => $event['dateTime'][$i], 
                            'created_at' => date("Y-m-d H:i:s"), 
                            'updated_at' => date("Y-m-d H:i:s") 
                        ],
                    ]);                                        
                }                
            } 

            if (!file_exists(public_path().'/image/event/'.$event['id'].'_tmp')) {
                mkdir(public_path().'/image/event/'.$event['id'].'_tmp', 0777, true);
            }
            if($request->hasFile('photos')){
                $files                          = $request->file('photos');
                
                DB::table('photo_tmp')->where('eventId', $event['id'])->where('is_primary', 0)->delete();
            
                foreach($files as $key=> $file){
                    $filename                   = $file->getClientOriginalName();
                    $rand                       = (rand(10,100));
                    $extension                  = $file->getClientOriginalExtension();                
                    $fileName                   = $event['urlName'].'-'.$key.'-'.$rand.'.'.$extension;
                    $file->move(public_path().'/image/event/'.$event['id'].'_tmp', $event['urlName'].'-'.$key.'-'.$rand.'.'.$extension);                     

                    DB::table('photo_tmp')->insertGetId(
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

                DB::table('photo_tmp')->where('eventId', $event['id'])->where('is_primary', 1)->delete();
            
                foreach($files as $key=> $file){
                    $filename                   = $file->getClientOriginalName();
                    $rand                       = (rand(10,1000));
                    $extension                  = $file->getClientOriginalExtension();                
                    $fileName                   = $event['urlName'].'-'.$key.'-'.$rand.'.'.$extension;
                    $file->move(public_path().'/image/event/'.$event['id'].'_tmp', $event['urlName'].'-'.$key.'-'.$rand.'.'.$extension);                                   
                
                    DB::table('photo_tmp')->insertGetId(
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
            if($request->hasFile('detailImg')){
                $files                          = $request->file('detailImg');                    
                foreach($files as $key=> $file){
                    $filename                   = $file->getClientOriginalName();
                    $rand                       = (rand(10,1000));
                    $extension                  = $file->getClientOriginalExtension();                
                    $fileName                   = $event['urlName'].'-layout-'.$key.'-'.$rand.'.'.$extension;
                    $file->move(public_path().'/image/event/'.$event['id'].'_tmp', $fileName);                                     
                    DB::table('events_tmp')
                        ->where('id', $event['id'])
                        ->update(
                            [
                                'img'             => $fileName
                            ]
                        );
                }
            }                     
            return redirect('/editor/events')->with('status', 'Event updated!');                    
        }else{

            $eventId                      =   DB::table('events_tmp')->insertGetId(
                                                    [
                                                        'name'          => $event['name'],
                                                        'referenceId'   => ($event['ref_id'])?$event['ref_id']:0,
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
                                                        'status'        => 2,
                                                        'updated_at'    => date("Y-m-d H:i:s"),
                                                        'created_at'    => date("Y-m-d H:i:s")
                                                    ]
                                                );

            $addressId                      =   DB::table('address_tmp')->insertGetId(
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

            if($event['ref_id'] && $event['urlId']){
                DB::table('url')
                    ->where('id', $event['urlId'])
                    ->update(
                        [
                            'eventTempId'       => $eventId,
                        ]
                    );  
            }else{
                $urlId                          =   DB::table('url')->insertGetId(
                    [
                        'urlName'       => $event['urlName'],
                        'eventTempId'   => $eventId,
                        'created_at'    => date("Y-m-d H:i:s"),
                        'updated_at'    => date("Y-m-d H:i:s")
                    ]
                );  
            }                                                 
            DB::table('events_tmp')
                ->where('id', $eventId)
                ->update(
                    [
                        'urlId'             => ($event['urlId'])?$event['urlId']:$urlId,
                        'addressId'         => $addressId
                    ]
                );

            for($i =0; $i<$event['scheduleCount']; $i++){
                if(isset($event['dateTime'][$i])){
                    DB::table('event_schedule_tmp')->insert([
                        [
                            'eventId' => $eventId, 
                            'dateTime' => $event['dateTime'][$i], 
                            'created_at' => date("Y-m-d H:i:s"), 
                            'updated_at' => date("Y-m-d H:i:s") 
                        ],
                    ]);                                        
                }                
            }  

            $seoId                          =   DB::table('seo_tmp')->insertGetId(
                                                    [
                                                        'urlId'                             => ($event['urlId'])?$event['urlId']:$urlId,
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

            if (!file_exists(public_path().'/image/event/'.$eventId.'_tmp')) {
                mkdir(public_path().'/image/event/'.$eventId.'_tmp', 0777, true);
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
                    $file->move(public_path().'/image/event/'.$eventId.'_tmp', $event['urlName'].'-'.$key.'-'.$rand.'.'.$extension);                                     
    
                    DB::table('photo_tmp')->insertGetId(
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
            if($request->hasFile('detailImg')){
                $files                          = $request->file('detailImg');
                
                foreach($files as $key=> $file){
                    $filename                   = $file->getClientOriginalName();
                    $rand                       = (rand(10,1000));
                    $extension                  = $file->getClientOriginalExtension();                
                    $fileName                   = $event['urlName'].'-layout-'.$key.'-'.$rand.'.'.$extension;
                    $file->move(public_path().'/image/event/'.$eventId.'_tmp', $fileName);                                     
                    DB::table('events_tmp')
                        ->where('id', $eventId)
                        ->update(
                            [
                                'img'             => $fileName
                            ]
                        );
                }
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
                    $file->move(public_path().'/image/event/'.$eventId.'_tmp', $event['urlName'].'-'.$key.'-'.$rand.'.'.$extension);                                     
    
                    DB::table('photo_tmp')->insertGetId(
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
            return redirect('/editor/events')->with('status', 'Event added!');                                                
        }
    }
    
    public function deleteEvent($id){
        
        $eventRs                          =   EventsTmp::select('events_tmp.urlId', 'events_tmp.referenceId')
                                                ->where('events_tmp.id', '=', $id)
                                                ->get()->first();


        $event                            =   $eventRs->toArray(); 

        DB::table('events_tmp')->where('id', $id)->delete();
        DB::table('event_schedule_tmp')->where('eventId', $id)->delete();
        DB::table('seo_tmp')->where('urlId', $event['urlId'])->delete();        
        DB::table('photo_tmp')->where('eventId', $id)->delete();            
        if($event['referenceId']){
            DB::table('url')
                ->where('id', $event['urlId'])
                ->update(
                    [
                        'eventTempId'       => 0,
                    ]
                );
        }else{
            DB::table('url')->where('eventTempId', $id)->delete();                
        }

        $this->deleteDirectory(public_path().'/image/event/'.$id.'_tmp');

        return redirect('/editor/events')->with('status', 'Event deleted!');
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

        return view('editor.events_category_listing',['category' => $eventsCategorys]); 
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

        return view('editor.events_category_add',['category' => $category]); 
    }    

    public function addEventsCategory(Request $request){

        $categoryVal                        =   $request->post();

        $validator = Validator::make($request->all(), [
            'name' => 'required',                     
        ]);

        if ($validator->fails()) {
            if($categoryVal['id']){
                return redirect('/editor/events_category_add/'.$categoryVal['id'])->withErrors($validator)->withInput();
            }else{
                return redirect('/editor/events_category_add')->withErrors($validator)->withInput();
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
            return redirect('/editor/events_category')->with('status', 'Category updated!');                    
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

                                               
            return redirect('/editor/events_category')->with('status', 'Category added!');                                                
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
        return redirect('/editor/events_category')->with('status', 'Category deleted!');
    } 

    public function addEventDuplicatetView($id){

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
            $event['ref_id']                =   $id;
            $event['status']                =   "";
            $event['temp_id']               =   $id;
            $event['seoId']                 =   "";   
        }else{
            $event['id']                  =   "";
            $event['ref_id']              =   "";
            $event['status']              =   "";
            $event['referenceId']         =   "";
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
       
        return view('editor.events_add',['event' => $event, 'cities' => $cities, 'photos' => $photoRs, 'eventSchedules' => $eventScheduleTime, 'eventCategorys' => $eventCategoryRs]); 

    }

}
