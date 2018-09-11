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

        $typeVal                            =   "";
        $cityVal                            =   "";
        $keywordVal                         =   "";

        if($type){
            $typeArr                        =   explode("-",$type);
            $typeVal                        =   $typeArr[count($typeArr)-1];
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
            
        $movieRs                            =   $movieRs->get();
        $movies                             =   $movieRs->toArray();
        echo "<pre>";
        print_r($movies);
    }

    public function getDetails(Request $request,$url){
        
        $distance                           =   "";
        $commonCtrl                         =   new CommonController;

        $seoUrl                             =   $commonCtrl->seoUrl($request->path(),2);        

        $siteId                             =   config('app.siteId');
        $movieRs                            =   Movie::select()
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

            $todaysDate =   date("l");     
            
            return view('movie_details',['movie' => $movie, 'photos' => $photo, 'today' => $todaysDate]);
        }else{
            return redirect()->back();
        }
    }


}
