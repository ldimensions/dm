<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Models\Url;
use App\Http\Models\Seo;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class SEOController extends Controller
{
    public function __construct(){
        $this->middleware('role:Admin');
    }

    public function index(){
        
        $seoRs                              =   Seo::select('seo.seoId', 'seo.urlId', 
                                                            'seo.keyValue', 'seo.indexValue',
                                                            'seo.SEOMetaTitle', 'url.urlName')
                                                    ->leftjoin('url','url.id', '=', 'seo.urlId')
                                                    ->orderBy('seo.updated_at', 'DESC');    

        $seoRs                              =   $seoRs->get();
        $seo                                =   $seoRs->toArray();

        return view('admin.seo_listing',['seo' => $seoRs]);          
    } 

    public function addSeoView($id=null){
        
        if($id){
            $movieRs                      =   Movie::select('movie.id', 'movie.name', 
                                                        'movie.description', 'movie.cast', 'movie.language',
                                                        'movie.music', 'movie.director', 'movie.producer',
                                                        'movie.premium', 'movie.order','movie.is_disabled', 
                                                        'url.urlName', 'url.id as urlId', 'movie.trailer',                                            
                                                        'seo.seoId', 'seo.SEOMetaTitle',
                                                        'seo.SEOMetaDesc', 'seo.SEOMetaPublishedTime',
                                                        'seo.SEOMetaKeywords', 'seo.OpenGraphTitle',
                                                        'seo.OpenGraphDesc', 'seo.OpenGraphUrl',
                                                        'seo.OpenGraphPropertyType', 'seo.OpenGraphPropertyLocale',
                                                        'seo.OpenGraphPropertyLocaleAlternate', 'seo.OpenGraph')
                                                    ->leftjoin('url','url.movieId', '=', 'movie.id')
                                                    ->leftjoin('site','site.siteId', '=', 'movie.siteId')
                                                    ->leftjoin('seo','seo.urlId', '=', 'url.id')                                                    
                                                    ->where('movie.id', '=', $id)
                                                    ->get()->first();

            $movie                          =   $movieRs->toArray(); 

            $photoArr                       =   Photo::select('photoName','is_primary')
                                                        ->orderBy('order', 'desc')
                                                        ->where('movieId', '=', $id)
                                                        ->get();  
            $photoRs                        =   $photoArr->toArray();   

            $movieTimeArr                   =   MovieTheatre::select('theatreId','dateTime')
                                                        ->orderBy('theatreId', 'ASC')
                                                        ->where('movieId', '=', $id)
                                                        ->get();  
            $movieTimeRs                    =   $movieTimeArr->toArray();   
            
            $movieBookingLinkArr            =   MovieBooking::select('theatreId','bookingLink')
                                                        ->where('movieId', '=', $id)
                                                        ->get();  
            $movieBookingLinkRs             =   $movieBookingLinkArr->toArray();              
            
            $theatreIdArr                   =   array();
            $movieTheatreAggr               =   array();

            foreach ($movieTimeRs as $key => $movieTime) {
                $theatreIdArr[$movieTime['theatreId']]          =   $movieTime['theatreId'];               
            } 
            foreach ($theatreIdArr as $theatrekey => $theatreId) {
                $movieTimeAgg                                   =   array();
                $key                                            =   0;
                foreach ($movieTimeRs as $movieKey => $movieTime) {
                    if($theatrekey == $movieTime['theatreId']){
                        $movieTimeAgg['dateTime'][$key]         =   date('Y-m-d\TH:i',  strtotime($movieTime['dateTime']));
                        $movieTheatreAggr[$theatrekey]          =   $movieTimeAgg;
                        $key++;
                    }                 
                }
            } 
            $movieBookingLinkArr                                =   array();
            foreach ($movieBookingLinkRs as $key => $movieBookingLink) {
                $movieBookingLinkArr[$movieBookingLink['theatreId']]          =   $movieBookingLink['bookingLink'];  
            }   
        }else{
            $seo['seoId']                               =   ""; 
            $seo['SEOMetaTitle']                        =   ""; 
            $seo['SEOMetaDesc']                         =   ""; 
            $seo['SEOMetaPublishedTime']                =   ""; 
            $seo['SEOMetaKeywords']                     =   ""; 
            $seo['OpenGraphTitle']                      =   ""; 
            $seo['OpenGraphDesc']                       =   ""; 
            $seo['OpenGraphUrl']                        =   ""; 
            $seo['OpenGraphPropertyType']               =   ""; 
            $seo['OpenGraphPropertyLocale']             =   ""; 
            $seo['OpenGraphPropertyLocaleAlternate']    =   ""; 
            $seo['OpenGraph']                           =   "";  
            
        }

        return view('admin.seo_add',['seo' => $seo]); 
    }    

}
