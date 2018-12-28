<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Models\Photo;
use App\Http\Models\Url;
use App\Http\Models\City;
use App\Http\Models\Events;
use App\Http\Models\EventsSchedule;
use OpenGraph;

class EventsController extends Controller
{
    public function __construct(){}
        
    public function index(Request $request,$schedule = 2,$city=null,$keyword=null){

        $typeVal                        =   "";
        $cityVal                        =   "";
        $keywordVal                     =   ""; 
        $setSeo                         =   false;       
        $siteId                         =   config('app.siteId');
        $commonCtrl                     =   new CommonController;

        if($city && $city !='all'){
            //$cityArr                        =   explode("-",$city);
            //$cityVal                        =   $cityArr[count($cityArr)-1];
            $cityVal                        =   $city;
        } 
        if($keyword){
            $keywordVal                     =   $keyword;
        }          

        $eventsRs                       =   Events::select('events.id as eventId', 'events.name', 
                                                 'photo.photoName', 'url.urlName', 'events_category.name as categoryName',
                                                 'address.address1', 'address.address2','address.state',
                                                 'city.city', 'address.state', 'address.zip')
                                                ->leftjoin('url','url.eventId', '=', 'events.id')
                                                ->leftjoin('site','site.siteId', '=', 'events.siteId')
                                                ->leftJoin('photo', function($join){
                                                    $join->on('photo.eventId', '=', 'events.id')
                                                        ->where('photo.is_primary','=',1);
                                                })  
                                                ->leftjoin('address','address.id', '=', 'events.addressId')
                                                ->leftjoin('events_category','events_category.id', '=', 'events.categoryId')
                                                ->leftjoin('city','city.cityId', '=', 'address.city')
                                                ->where('events.is_deleted', '=', '0')
                                                ->where('site.siteId', '=', $siteId)
                                                ->groupBy('events.id','events.name', 'photo.photoName', 'url.urlName', 'city.city', 'address.state', 'address.zip', 'address.address1', 'address.address2','address.state','events_category.name')
                                                ->orderBy('events.premium', 'DESC')
                                                ->orderBy('events.created_at', 'DESC');      
                                                
        if($schedule == '2'){
            $eventsRs->join('event_schedule','event_schedule.eventId', '=', 'events.id'); 
            $eventsRs->where('event_schedule.dateTime', '>=', date("Y-m-d H:i:s") );
        }else if($schedule == '3'){
            $eventsRs->leftjoin('event_schedule','event_schedule.eventId', '=', 'events.id'); 
            $eventsRs->where('event_schedule.dateTime', null );
        }else{
            $eventsRs->leftjoin('event_schedule','event_schedule.eventId', '=', 'events.id'); 
        }
        
        if($cityVal){
            $eventsRs->where('city.cityId', '=', $cityVal);
        }             
        if($keywordVal){
            $eventsRs->where('events.name', 'like', '%'.$keywordVal.'%');
        }                                                  

        $events                             =   $eventsRs->paginate(16);              

        $cityRs                             =   City::select('cityId','city', 'value')
                                                        ->orderBy('city', 'asc')
                                                        ->get();  
        $cities                             =   $cityRs->toArray();  

        return view('events',['events' => $events, 'cities' => $cities, 'cityVal' => $cityVal, 'keyword' => $keyword, 'schedule' => $schedule]);
    }

    public function getDetails(Request $request,$url){

        $distance                       =   "";
        $todaysWorkingTime              =   "";
        $descriptionHeight              =   "20";
        $commonCtrl                     =   new CommonController;

        $seoUrl                         =   $commonCtrl->seoUrl($request->path(),2);        

        $siteId                         =   config('app.siteId');
        $eventRs                        =   Events::select('events.id', 'events.name', 
                                                    'events.description', 
                                                    'events.organizerName', 'events.organizerContact',
                                                    'events.organizerEmail', 'events.organizerPhone',
                                                    'events_category.name as categoryName',
                                                    'address.address1', 'address.address2',
                                                    'events.website',                                                
                                                    'city.city', 'address.state',
                                                    'address.zip', 'address.county',
                                                    'address.phone1', 'address.latitude',
                                                    'address.longitude',
                                                    'url.urlName')
                                                    ->leftjoin('url','url.eventId', '=', 'events.id')
                                                    ->leftjoin('events_category','events_category.id', '=', 'events.categoryId')
                                                    ->leftjoin('address','address.id', '=', 'events.addressId')
                                                    ->leftjoin('site','site.siteId', '=', 'events.siteId')
                                                    ->leftjoin('city','city.cityId', '=', 'address.city')                                                                                       
                                                    ->where('site.siteId', '=', $siteId)
                                                    ->where('url.urlName', '=', $url)
                                                    ->where('events.is_deleted', '=', '0')
                                                    ->where('events.is_disabled', '=', '0')
                                                    ->get()->first();

        $event                          =   $eventRs->toArray(); 

        if($event){
            $eventId                        =   $event['id'];
            
            $lat                            =   ($event['latitude'])?$event['latitude']:'';
            $long                           =   ($event['longitude'])?$event['longitude']:'';

            $todaysDate                     =   date("l");   

            $scheduleRs                     =   EventsSchedule::select('event_schedule.dateTime')
                                                    ->where('event_schedule.eventId', '=', $eventId)                                                  
                                                    ->orderBy('dateTime', 'asc')
                                                    ->get();        
            
            $schedule                          =   $scheduleRs->toArray();  
            // if($workingTimes){
            //     foreach($workingTimes as $rootKey => $workingTime) {
            //         foreach($workingTime as $subkey => $subWorkingTime) {
            //             foreach($subWorkingTime as $dayKey => $dayWorkingTime) {
            //                 foreach($dayWorkingTime as $keys => $times) {
            //                     foreach($times as $key => $time) {
            //                         $oldKey                     =   "";
            //                         $workingTimes[$rootKey][$subkey][$dayKey][$keys][$key]['time'] = date("g:i a", strtotime($workingTimes[$rootKey][$subkey][$dayKey][$keys][$key]['time']));
            //                         if($dayKey == $todaysDate){
            //                             if($oldKey != $key){
            //                                 $todaysWorkingTime      .=   ' - '.$workingTimes[$rootKey][$subkey][$dayKey][$keys][$key]['time'];                            
            //                             }else{
            //                                 $todaysWorkingTime      .=   ($todaysWorkingTime)?', '.$workingTimes[$rootKey][$subkey][$dayKey][$keys][$key]['time']: $workingTimes[$rootKey][$subkey][$dayKey][$keys][$key]['time'];
            //                             }
            //                         }
            //                         $oldKey                         =  $key; 
            //                     }                                
            //                 }
            //             }
            //         }
            //     }
            // }
    
            $photoRs                        =   Photo::select('photo.photoId', 'photo.photoName', 
                                                    'photo.is_primary', 'photo.order')
                                                    ->where('photo.is_deleted', '=', '0')
                                                    ->where('photo.is_primary', '=', '0')
                                                    ->where('photo.is_disabled', '=', '0')
                                                    ->where('photo.eventId', '=', $eventId)
                                                    ->orderBy('photo.order', 'asc') 
                                                    ->get();        
            
            $photo                          =   $photoRs->toArray();  

            $commonCtrl->setMeta($request->path(),2);
            if(!empty($photo)){
                OpenGraph::addImage('http://'.$_SERVER['SERVER_NAME']."/image/event/".$eventId."/".$photo[0]['photoName'], ['height' => 300, 'width' => 300]);    
            }
            $descriptionHeight              =   $commonCtrl->descriptionLength(strlen($event['description']));
            return view('event_details',['event' => $event, 'photos' => $photo, 'schedule' => $schedule, 'today' => $todaysDate, 'todaysWorkingTime' => $todaysWorkingTime, 'descriptionHeight' => $descriptionHeight]);
        }else{
            return redirect()->back();
        }

    }
}
