<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Models\Movie;
use App\Http\Models\Theatre;
use App\Http\Models\MovieTheatre;
use App\Http\Models\Photo;
use App\Http\Models\Url;
use App\Http\Models\City;

class MovieController extends Controller
{

    public function __construct(){}

    public function index(Request $request,$type,$city=null,$keyword=null){

        $typeVal                        =   "";
        $cityVal                        =   "";
        $keywordVal                     =   ""; 
        $setSeo                         =   false;       
        $siteId                         =   config('app.siteId');
        $commonCtrl                     =   new CommonController;

        if($type && $type!="all"){
            //$typeArr                        =   explode("-",$type);
            //$typeVal                        =   $typeArr[count($typeArr)-1];
            $typeVal                        =   $type;
        }
        if($city && $city !='all'){
            $cityArr                        =   explode("-",$city);
            $cityVal                        =   $cityArr[count($cityArr)-1];
        } 
        if($keyword){
            $keywordVal                     =   $keyword;
        }      
                
        $siteId                             =   config('app.siteId');        

        $movieRs                            =   Movie::select('movie.id as movieId', 'movie.name', 
                                                        'movie.cast', 'movie.language', 'movie.music', 
                                                        'movie.director', 'movie.producer', 'url.urlName', 'photo.photoName')
                                                        ->leftjoin('url','url.movieId', '=', 'movie.id')
                                                        ->leftjoin('site','site.siteId', '=', 'movie.siteId')
                                                        ->join('movie_theatre','movie_theatre.movieId', '=', 'movie.id')  
                                                        ->leftJoin('photo', function($join){
                                                            $join->on('photo.movieId', '=', 'movie.id')
                                                                ->where('photo.is_primary','=',1);
                                                        })                                                                                                      
                                                        ->where('movie.is_deleted', '=', '0')
                                                        ->where('movie_theatre.dateTime', '>=', date("Y-m-d H:i:s") )                                                        
                                                        ->where('site.siteId', '=', $siteId)
                                                        ->groupBy('movie.id','movie.name','movie.cast', 'movie.language', 'movie.music','movie.director', 'movie.producer', 'url.urlName','photo.photoName')
                                                        ->orderBy('movie.premium', 'DESC')
                                                        ->orderBy('movie.created_at', 'DESC');   

        if($cityVal){
            $movieRs->where('city.cityId', '=', $cityVal);
        }
        if($type){
            $movieRs->where('movie.language', '=', $typeVal);
        }      
        if($keywordVal){
            $movieRs->where('movie.name', 'like', '%'.$keywordVal.'%');
        }       
        
        $movies                             =   $movieRs->paginate(16);        
            
        $movieRs                            =   $movieRs->get();
        $movies                             =   $movieRs->toArray();

        $cityRs                             =   City::select('cityId','city', 'value')
                                                        ->orderBy('city', 'asc')
                                                        ->get();  
        $cities                             =   $cityRs->toArray();          

        return view('movies',['movies' => $movies, 'cities' => $cities, 'type' => $type, 'cityVal' => $cityVal, 'keyword' => $keyword]);
    }

    public function getDetails(Request $request,$url){
        
        $distance                           =   "";
        $commonCtrl                         =   new CommonController;

        $seoUrl                             =   $commonCtrl->seoUrl($request->path(),2);        

        $siteId                             =   config('app.siteId');
        $movieRs                            =   Movie::select('movie.id','movie.name','movie.description','movie.language','movie.cast',
                                                                'movie.music','movie.director','movie.producer','movie.trailer')
                                                        ->leftjoin('url','url.movieId', '=', 'movie.id')
                                                        ->leftjoin('site','site.siteId', '=', 'movie.siteId')
                                                        ->where('site.siteId', '=', $siteId)
                                                        ->where('url.urlName', '=', $url)
                                                        ->where('movie.is_deleted', '=', '0')
                                                        ->where('movie.is_disabled', '=', '0')
                                                        ->get()->first();

        $movie                              =   $movieRs->toArray(); 

        if($movie){
            $movieId                        =   $movie['id'];


            $movieTheatreRs                 =   MovieTheatre::select('theatre.id','theatre.name','theatre.website',
                                                                        'theatre.phone','url.urlName',
                                                                        'movie_theatre.dateTime',
                                                                        'address.address1','address.address2','address.city',
                                                                        'address.state','address.zip','address.phone1',
                                                                        'address.latitude','address.longitude',
                                                                        'movie_booking.bookingLink',
                                                                        'url.urlName'
                                                                        )
                                                                ->leftjoin('theatre','theatre.id', '=', 'movie_theatre.theatreId')
                                                                ->leftjoin('address','address.id', '=', 'theatre.addressId')
                                                                ->leftjoin('url','url.theatreId', '=', 'theatre.id')
                                                                ->leftjoin('movie_booking','movie_booking.theatreId', '=', 'theatre.id')
                                                                ->where('movie_theatre.movieId', '=', $movieId)
                                                                ->where('movie_theatre.dateTime', '>=', date("Y-m-d") )     
                                                                ->orderBy('theatre.id', 'asc')
                                                                ->orderBy('movie_theatre.dateTime', 'asc')
                                                                ->get();

            $movieTheatre                   =   $movieTheatreRs->toArray();
            $movieTheatreTimeArr            =   array();  
            if($movieTheatre){
                $movieTheatreArr                =   array();  
                foreach($movieTheatre as $key => $movieTheatreVal) {   
                    $movieTheatreArr[$movieTheatreVal['id']][]    =   $movieTheatreVal;
                }   
    
                
                foreach($movieTheatreArr as $key => $movieTheatreVal1) {   
                    foreach($movieTheatreVal1 as $key1 => $movieTheatreVal2) {
                        $movieTheatreVal2['date']       =  date("M d D", strtotime($movieTheatreVal2['dateTime']));   
                        $movieTheatreVal2['dateTime']   =  date('G:ia', strtotime($movieTheatreVal2['dateTime']));   
                        $movieTheatreTimeArr[$key]['dateTimeDetails'][$movieTheatreVal2['date']][]   =   $movieTheatreVal2; 
                        $movieTheatreTimeArr[$key]['details']   =   $movieTheatreVal2;                           
                        
                    }
                } 
            }     
            
            $photoRs                        =   Photo::select('photo.photoId', 'photo.photoName', 
                                                    'photo.is_primary', 'photo.order')
                                                        ->where('photo.is_deleted', '=', '0')
                                                        ->where('photo.is_primary', '=', '0')
                                                        ->where('photo.is_disabled', '=', '0')
                                                        ->where('photo.movieId', '=', $movieId)
                                                        ->orderBy('photo.order', 'asc') 
                                                        ->get();        
            
            $photo                          =   $photoRs->toArray();  

            $commonCtrl->setMeta($request->path(),2);

            $today =   date("M d D"); 

            $descriptionHeight              =   $commonCtrl->descriptionLength(strlen($movie['description']));
            
            // echo '<pre>';
            // print_r($movieTheatreTimeArr);
            // exit();
            
            return view('movie_details',['movie' => $movie, 'movieTheatres' => $movieTheatreTimeArr, 'photos' => $photo, 'descriptionHeight' => $descriptionHeight, 'today' => $today]);
        }else{
            return redirect()->back();
        }
    }

    public function theatreDetails(Request $request,$url){

        $commonCtrl                         =   new CommonController;
        
        $theatreRs                          =   Theatre::select('theatre.id','theatre.name','theatre.website',
                                                                'theatre.phone','url.urlName','theatre.description',
                                                                'address.address1','address.address2','address.city',
                                                                'address.state','address.zip','address.phone1',
                                                                'address.latitude','address.longitude'
                                                                )
                                                        ->leftjoin('address','address.id', '=', 'theatre.addressId')
                                                        ->leftjoin('url','url.theatreId', '=', 'theatre.id')
                                                        ->where('url.urlName', '=', $url)
                                                        ->orderBy('theatre.id', 'asc')
                                                        ->get()->first();

        $theatre                            =   $theatreRs->toArray();
        $movieRs                            =   Movie::select('movie.id as movieId', 'movie.name', 'movie.language', 
                                                                'url.urlName', 'photo.photoName')
                                                                ->leftjoin('url','url.movieId', '=', 'movie.id')
                                                                ->rightjoin('movie_theatre','movie_theatre.movieId', '=', 'movie.id')  
                                                                ->leftJoin('photo', function($join){
                                                                    $join->on('photo.movieId', '=', 'movie.id')
                                                                        ->where('photo.is_primary','=',1);
                                                                })                                                                                                      
                                                                ->where('movie.is_deleted', '=', '0')
                                                                ->where('movie_theatre.dateTime', '>=', date("Y-m-d H:i:s") )       
                                                                ->where('movie_theatre.theatreId', '=', $theatre['id'])           
                                                                ->groupBy('movie.id','movie.name','url.urlName','photo.photoName','movie.language')                                                                
                                                                ->orderBy('movie.premium', 'DESC')
                                                                ->orderBy('movie_theatre.dateTime', 'ASC')                                                 
                                                                ->get();
        $movies                             =   $movieRs->toArray();      

        $descriptionHeight                  =   $commonCtrl->descriptionLength(strlen($theatre['description']));
        $commonCtrl->setMeta($request->path(),2);
        // echo '<pre>';
        // print_r($movies);
        // exit();

        return view('theatre_details',['theatre' => $theatre, 'descriptionHeight' => $descriptionHeight, 'movies' => $movies]);
    }

    public function getRelated(Request $request,$language,$id){
        
        $distance                       =   "";
        $commonCtrl                     =   new CommonController;        

        $siteId                         =   config('app.siteId');

        $relatedRs                      =   Movie::select('movie.id as movieId', 'movie.name', 
                                                            'movie.cast', 'movie.language', 'movie.music', 
                                                            'movie.director', 'movie.producer', 'url.urlName', 'photo.photoName')
                                                            ->leftjoin('url','url.movieId', '=', 'movie.id')
                                                            ->leftjoin('site','site.siteId', '=', 'movie.siteId')
                                                            ->join('movie_theatre','movie_theatre.movieId', '=', 'movie.id')  
                                                            ->leftJoin('photo', function($join){
                                                                $join->on('photo.movieId', '=', 'movie.id')
                                                                    ->where('photo.is_primary','=',1);
                                                            })                                                                                                      
                                                            ->where('movie.is_deleted', '=', '0')
                                                            ->where('movie_theatre.dateTime', '>=', date("Y-m-d H:i:s") )                                                        
                                                            ->where('site.siteId', '=', $siteId)
                                                            ->where('movie.id', '!=', $id)
                                                            ->where('movie.language', '=', $language)    
                                                            ->groupBy('movie.id','movie.name','movie.cast', 'movie.language', 'movie.music','movie.director', 'movie.producer', 'url.urlName','photo.photoName')
                                                            ->orderBy('movie.premium', 'DESC')
                                                            ->orderBy('movie.created_at', 'DESC')
                                                            ->take(10)->get();                                                
            
        $related                     =   $relatedRs->toArray();  

        return view('related',['related' => $related, 'type' => 'movie']);
    }  
    
    function getTheatreRelated(Request $request,$id){

        $distance                       =   "";
        $commonCtrl                     =   new CommonController;        

        $siteId                         =   config('app.siteId');

        $relatedRs                      =   Theatre::select('theatre.id','theatre.name',
                                                                'theatre.phone','url.urlName',
                                                                'address.address1','address.address2','city.city',
                                                                'address.state','address.zip','address.phone1',
                                                                'address.latitude','address.longitude'
                                                                )
                                                        ->leftjoin('address','address.id', '=', 'theatre.addressId')
                                                        ->leftjoin('city','city.cityId', '=', 'address.city')  
                                                        ->leftjoin('url','url.theatreId', '=', 'theatre.id')
                                                        ->where('theatre.id', '!=', $id)
                                                        ->orderBy('theatre.id', 'asc')
                                                        ->take(10)->get();    
        $related                        =   $relatedRs->toArray();  

        return view('related',['related' => $related, 'type' => 'theatre']);                                                         

    }


}
