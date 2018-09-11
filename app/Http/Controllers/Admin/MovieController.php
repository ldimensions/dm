<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Models\Movie;
use App\Http\Models\Theatre;
use App\Http\Models\MovieTheatre;
use App\Http\Models\MovieBooking;
use App\Http\Models\Photo;
use App\Http\Models\Url;
use App\Http\Models\City;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Image;

class MovieController extends Controller
{
    public function __construct(){
        $this->middleware('role:Admin');
    }

    public function movieListing(){
        $siteId                         =   config('app.siteId');
        
        $movieRs                      =   Movie::select('movie.id as movieId', 'movie.name', 
                                                'movie.is_disabled', 'url.urlName', 'movie.language')
                                                ->leftjoin('url','url.movieId', '=', 'movie.id')
                                                ->leftjoin('site','site.siteId', '=', 'movie.siteId')                                            
                                                ->where('movie.is_deleted', '=', '0')
                                                ->where('site.siteId', '=', $siteId)
                                                ->orderBy('movie.premium', 'DESC')
                                                ->orderBy('movie.order', 'ASC');                                                  
            
        $movieRs                       =   $movieRs->get();
        $movies                        =   $movieRs->toArray();

        return view('admin.movie_listing',['movies' => $movies]);          
    } 

    public function addMovieView($id=null){
        
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

            // $movieTimeRs1                           =   array();
            // $movieTimeRs1['1']['dateTime'][0]       =   date('Y-m-d\TH:i',  strtotime('2018-01-01 01:20:10'));
            // $movieTimeRs1['1']['dateTime'][1]       =   date('Y-m-d\TH:i',  strtotime('2018-01-02 02:20:20'));
            // $movieTimeRs1['1']['dateTime'][3]       =   date('Y-m-d\TH:i',  strtotime('2018-01-02 03:20:20'));
            // $movieTimeRs1['1']['dateTime'][4]       =   date('Y-m-d\TH:i',  strtotime('2018-01-02 04:20:20'));
            // $movieTimeRs1['1']['dateTime'][5]       =   date('Y-m-d\TH:i',  strtotime('2018-01-02 05:20:20'));
            // $movieTimeRs1['1']['dateTime'][6]       =   date('Y-m-d\TH:i',  strtotime('2018-01-02 06:20:20'));            
            // $movieTimeRs1['2']['dateTime'][0]       =   date('Y-m-d\TH:i',  strtotime('2018-01-01 03:22:30'));
            // $movieTimeRs1['2']['dateTime'][1]       =   date('Y-m-d\TH:i',  strtotime('2018-02-01 03:22:30'));
            // $movieTimeRs1['3']['dateTime'][0]       =   date('Y-m-d\TH:i',  strtotime('2018-01-02 04:23:40'));            
            
            //$movieTimeRs['theatreId']['1']  =   
            // echo "<pre>";
            // print_r($movieBookingLinkArr);
            // exit();
        }else{
            $movie['id']                    =   "";
            $movie['addressId']             =   "";
            $movie['urlId']                 =   "";
            $movie['urlName']               =   "";            
            $movie['cast']                  =   "";
            $movie['music']                 =   "";
            $movie['director']              =   "";
            $movie['producer']              =   "";
            $movie['trailer']               =   "";
            $movie['name']                  =   "";
            $movie['description']           =   "";
            $movie['language']              =   "";
            $movie['theatre']               =   "";
            $movie['premium']               =   "";
            $movie['is_disabled']           =   "";
            $movie['order']                 =   ""; 
            $movie['seoId']                               =   ""; 
            $movie['SEOMetaTitle']                        =   ""; 
            $movie['SEOMetaDesc']                         =   ""; 
            $movie['SEOMetaPublishedTime']                =   ""; 
            $movie['SEOMetaKeywords']                     =   ""; 
            $movie['OpenGraphTitle']                      =   ""; 
            $movie['OpenGraphDesc']                       =   ""; 
            $movie['OpenGraphUrl']                        =   ""; 
            $movie['OpenGraphPropertyType']               =   ""; 
            $movie['OpenGraphPropertyLocale']             =   ""; 
            $movie['OpenGraphPropertyLocaleAlternate']    =   ""; 
            $movie['OpenGraph']                           =   "";  
            
            $photoRs                        =   array();
            $movieTheatreAggr               =   array();
            $movieBookingLink               =   array();
        }

        $theatreRs                          =   Theatre::select('theatre.id', 'theatre.name', 'city.city as cityName')
                                                    ->leftjoin('address','address.id', '=', 'theatre.addressId')
                                                    ->leftjoin('city','city.cityId', '=', 'address.city')
                                                    ->where('theatre.is_deleted', '=', '0')
                                                    ->orderBy('name', 'asc')
                                                    ->get();  
        $theatres                           =   $theatreRs->toArray();  
        return view('admin.movie_add',['movie' => $movie, 'theatres' => $theatres, 'photos' => $photoRs, 'movieTimes' => $movieTheatreAggr, 'movieBookingLink' => $movieBookingLinkArr]); 
    }

    public function addMovie(Request $request)
    {

        $movieVal                           =   $request->post();
        
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'urlName' => [
                        'required',
                        Rule::unique('url')->ignore($movieVal['urlId'], 'id'),
            ],
            'cast' => 'required',
            'director' => 'required',
            'photos.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'thumbnail.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048'            
        ]);

        if ($validator->fails()) {
            if($movieVal['id']){
                return redirect('/admin/movie_add/'.$movieVal['id'])->withErrors($validator)->withInput();
            }else{
                return redirect('/admin/movie_add')->withErrors($validator)->withInput();
            }
        }
        // echo "<pre>";
        // print_r($movieVal);
          
        // exit();

        
        if($movieVal['id']){
            DB::table('movie')
                ->where('id', $movieVal['id'])
                ->update(
                    [
                        'name'          => $movieVal['name'],
                        'description'   => $movieVal['description'],
                        'language'      => $movieVal['language'],
                        'cast'          => $movieVal['cast'],
                        'music'         => $movieVal['music'],
                        'director'      => $movieVal['director'],
                        'producer'      => $movieVal['producer'],
                        'trailer'       => $movieVal['trailer'],
                        'siteId'        => config('app.siteId'),
                        'order'         => ($movieVal['order'])?$movieVal['order']:0,
                        'premium'       => $movieVal['premium'],
                        'is_disabled'   => $movieVal['is_disabled'],
                        'updated_by'    => Auth::user()->id,
                        'updated_at'    => date("Y-m-d H:i:s")                   
                    ]
                ); 
                
            DB::table('movie_theatre')->where('movieId', $movieVal['id'])->delete();
            $movieTheatre                       =   array();
            for($i =1; $i<=$movieVal['theatreCount']; $i++){
                if(isset($movieVal['theatre_'.$i]) && isset($movieVal['dateTime_'.$i]) && count($movieVal['dateTime_'.$i]) >0){
                    for($j =0; $j< count($movieVal['dateTime_'.$i]); $j++){
                        DB::table('movie_theatre')->insert([
                            ['movieId' => $movieVal['id'], 
                            'theatreId' => $movieVal['theatre_'.$i], 
                            'dateTime' => $movieVal['dateTime_'.$i][$j],
                            'updated_at' => date("Y-m-d H:i:s") ],
                        ]);     
                        // $arr = 
                        //     ['movieId' => $movieVal['id'], 
                        //     'theatreId' => $movieVal['theatre_'.$i], 
                        //     'dateTime' => $movieVal['dateTime_'.$i][$j]];
                        // print_r($arr) ;            
                    } 
                    DB::table('movie_booking')->where('movieId', $movieVal['id'])->where('theatreId', $movieVal['theatre_'.$i])->delete();
                    if(isset($movieVal['bookingLink_'.$i])) {
                        DB::table('movie_booking')->insert([
                            ['movieId' => $movieVal['id'], 
                            'theatreId' => $movieVal['theatre_'.$i], 
                            'bookingLink' => $movieVal['bookingLink_'.$i]]
                        ]);     
                    }                                   
                }                  
            }                    
            if($movieVal['urlName'] != $movieVal['urlNameChk']){
                DB::table('url')
                ->where('id', $movieVal['urlId'])
                ->update(
                    [
                        'urlName'       => $movieVal['urlName'],
                        'updated_at'    => date("Y-m-d H:i:s")                 
                    ]
                );
            }
            DB::table('seo')
                ->where('seoId', $movieVal['seoId'])
                ->update(
                    [
                        'SEOMetaTitle'                      => $movieVal['SEOMetaTitle'],
                        'SEOMetaDesc'                       => $movieVal['SEOMetaDesc'],
                        'SEOMetaPublishedTime'              => $movieVal['SEOMetaPublishedTime'],
                        'SEOMetaKeywords'                   => $movieVal['SEOMetaKeywords'],
                        'OpenGraphTitle'                    => $movieVal['OpenGraphTitle'],
                        'OpenGraphDesc'                     => $movieVal['OpenGraphDesc'],
                        'OpenGraphUrl'                      => $movieVal['OpenGraphUrl'],
                        'OpenGraphPropertyType'             => $movieVal['OpenGraphPropertyType'],
                        'OpenGraphPropertyLocale'           => $movieVal['OpenGraphPropertyLocale'],
                        'OpenGraphPropertyLocaleAlternate'  => $movieVal['OpenGraphPropertyLocaleAlternate'],
                        'OpenGraph'                         => $movieVal['OpenGraph'],
                        'updated_at'                        => date("Y-m-d H:i:s")
                    ]
                ); 

            if (!file_exists(public_path().'/image/movie/'.$movieVal['id'])) {
                mkdir(public_path().'/image/movie/'.$movieVal['id'], 0777, true);
            }
            if($request->hasFile('photos')){
                $files                          = $request->file('photos');
                
                DB::table('photo')->where('movieId', $movieVal['id'])->where('is_primary', 0)->delete();
                
            
                foreach($files as $key=> $file){
                    $filename                   = $file->getClientOriginalName();
                    $rand                       = (rand(10,100));
                    $extension                  = $file->getClientOriginalExtension();                
                    $fileName                   = $movieVal['urlName'].'-'.$key.'-'.$rand.'.'.$extension;

                    $resizeImage                = Image::make($file);
                    $resizeImage->resize(466,350);
                    $path                       = public_path('image/movie/'.$movieVal['id'].'/'.$movieVal['urlName'].'-'.$key.'-'.$rand.'.'.$extension);
                    $resizeImage->save($path);   

                    DB::table('photo')->insertGetId(
                        [
                            'photoName'         => $fileName,
                            'order'             => $key,
                            'movieId'         => $movieVal['id'],
                            'updated_at'  => date("Y-m-d H:i:s")
                        ]
                    );
                }
            }
            if($request->hasFile('thumbnail')){
                $files                          = $request->file('thumbnail');

                DB::table('photo')->where('movieId', $movieVal['id'])->where('is_primary', 1)->delete();
            
                foreach($files as $key=> $file){
                    $filename                   = $file->getClientOriginalName();
                    $rand                       = (rand(10,1000));
                    $extension                  = $file->getClientOriginalExtension();                
                    $fileName                   = $movieVal['urlName'].'-'.$key.'-'.$rand.'.'.$extension;
                    $resizeImage                = Image::make($file);
                    $resizeImage->resize(128,95);
                    $path                       = public_path('image/movie/'.$movieVal['id'].'/'.$movieVal['urlName'].'-'.$key.'-'.$rand.'.'.$extension);
                    $resizeImage->save($path);                 
                
                    DB::table('photo')->insertGetId(
                        [
                            'photoName'         => $fileName,
                            'order'             => $key,
                            'movieId'         => $movieVal['id'],
                            'is_primary'        => 1,
                            'updated_at'  => date("Y-m-d H:i:s")
                        ]
                    );
                }
            }        
        return redirect('/admin/movies')->with('status', 'Movie updated!');             
        }else{

            $movieId                      =   DB::table('movie')->insertGetId(
                                                    [
                                                        'name'          => $movieVal['name'],
                                                        'description'   => $movieVal['description'],
                                                        'language'      => $movieVal['language'],
                                                        'cast'          => $movieVal['cast'],
                                                        'music'         => $movieVal['music'],
                                                        'director'      => $movieVal['director'],
                                                        'producer'      => $movieVal['producer'],
                                                        'trailer'       => $movieVal['trailer'],
                                                        'siteId'        => config('app.siteId'),
                                                        'order'         => ($movieVal['order'])?$movieVal['order']:0,
                                                        'premium'       => $movieVal['premium'],
                                                        'is_disabled'   => $movieVal['is_disabled'],
                                                        'urlId'         => 0,
                                                        'updated_by'    => Auth::user()->id,
                                                        'created_at'  => date("Y-m-d H:i:s"),
                                                        'updated_at'  => date("Y-m-d H:i:s")
                                                    ]
                                                );

            $urlId                          =   DB::table('url')->insertGetId(
                                                    [
                                                        'urlName'       => $movieVal['urlName'],
                                                        'movieId'     => $movieId,
                                                        'created_at'  => date("Y-m-d H:i:s"),
                                                        'updated_at'  => date("Y-m-d H:i:s")
                                                    ]
                                                ); 
            DB::table('movie')
                ->where('id', $movieId)
                ->update(
                    [
                        'urlId'             => $urlId
                    ]
                );

            $movieTheatre                       =   array();
            for($i =1; $i<=$movieVal['theatreCount']; $i++){
                if(isset($movieVal['theatre_'.$i]) && isset($movieVal['dateTime_'.$i]) && count($movieVal['dateTime_'.$i]) >0){
                    for($j =0; $j< count($movieVal['dateTime_'.$i]); $j++){
                        DB::table('movie_theatre')->insert([
                            [
                                'movieId' => $movieId, 
                                'theatreId' => $movieVal['theatre_'.$i][0], 
                                'dateTime' => $movieVal['dateTime_'.$i][$j], 
                                'created_at' => date("Y-m-d H:i:s"), 
                                'updated_at' => date("Y-m-d H:i:s") 
                            ],
                        ]);                                        
                    }                
                }
                if(isset($movieVal['bookingLink_'.$i])) {
                    DB::table('movie_booking')->insert([
                        ['movieId' => $movieVal['id'], 
                        'theatreId' => $movieVal['theatre_'.$i], 
                        'bookingLink' => $movieVal['bookingLink_'.$i]]
                    ]);     
                }                                    
            }             

            $seoId                          =   DB::table('seo')->insertGetId(
                                                    [
                                                        'urlId'                             => $urlId,
                                                        'SEOMetaTitle'                      => $movieVal['SEOMetaTitle'],
                                                        'SEOMetaDesc'                       => $movieVal['SEOMetaDesc'],
                                                        'SEOMetaPublishedTime'              => $movieVal['SEOMetaPublishedTime'],
                                                        'SEOMetaKeywords'                   => $movieVal['SEOMetaKeywords'],
                                                        'OpenGraphTitle'                    => $movieVal['OpenGraphTitle'],
                                                        'OpenGraphDesc'                     => $movieVal['OpenGraphDesc'],
                                                        'OpenGraphUrl'                      => $movieVal['OpenGraphUrl'],
                                                        'OpenGraphPropertyType'             => $movieVal['OpenGraphPropertyType'],
                                                        'OpenGraphPropertyLocale'           => $movieVal['OpenGraphPropertyLocale'],
                                                        'OpenGraphPropertyLocaleAlternate'  => $movieVal['OpenGraphPropertyLocaleAlternate'],
                                                        'OpenGraph'                         => $movieVal['OpenGraph'],
                                                        'created_at'                        => date("Y-m-d H:i:s"),
                                                        'updated_at'                        => date("Y-m-d H:i:s")
                                                    ]
                                                );   

            if (!file_exists(public_path().'/image/movie/'.$movieId)) {
                mkdir(public_path().'/image/movie/'.$movieId, 0777, true);
            }                                                
            if($request->hasFile('photos')){
                $files                          = $request->file('photos');
            
                foreach($files as $key=> $file){
                    $filename                   = $file->getClientOriginalName();
                    $rand                       = (rand(10,100));
                    $extension                  = $file->getClientOriginalExtension();                
                    $fileName                   = $movieVal['urlName'].'-'.$key.'-'.$rand.'.'.$extension;
                    //$file->move(public_path().'/image/movie/'.$movieVal['id'], $fileName); 
                    $resizeImage                = Image::make($file);
                    $resizeImage->resize(466,350);
                    $path                       = public_path('image/movie/'.$movieId.'/'.$movieVal['urlName'].'-'.$key.'-'.$rand.'.'.$extension);
                    $resizeImage->save($path);                      
    
                    DB::table('photo')->insertGetId(
                        [
                            'photoName'         => $fileName,
                            'order'             => $key,
                            'movieId'           => $movieId,
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
                    $fileName                   = $movieVal['urlName'].'-'.$key.'-'.$rand.'.'.$extension;
                    //$file->move(public_path().'/image/movie/'.$movieVal['id'], $fileName); 
                    $resizeImage                = Image::make($file);
                    $resizeImage->resize(128,95);
                    $path                       = public_path('image/movie/'.$movieId.'/'.$movieVal['urlName'].'-'.$key.'-'.$rand.'.'.$extension);
                    $resizeImage->save($path);                     
    
                    DB::table('photo')->insertGetId(
                        [
                            'photoName'         => $fileName,
                            'order'             => $key,
                            'movieId'           => $movieId,
                            'is_primary'        => 1,
                            'created_at'  => date("Y-m-d H:i:s"),
                            'updated_at'  => date("Y-m-d H:i:s")
                        ]
                    );
                }
            }                                                
            return redirect('/admin/movies')->with('status', 'movie Added!');                                                
        }
    }    

    public function theatreListing(){
        $siteId                         =   config('app.siteId');
        
        $theatreRs                      =   Theatre::select('theatre.id as theatreId', 'theatre.name', 
                                                        'city.city', 'theatre.is_disabled', 'url.urlName')
                                                            ->leftjoin('url','url.theatreId', '=', 'theatre.id')
                                                            ->leftjoin('address','address.id', '=', 'theatre.addressId')
                                                            ->leftjoin('site','site.siteId', '=', 'theatre.siteId')                                            
                                                            ->leftjoin('city','city.cityId', '=', 'address.city')                                           
                                                            ->where('theatre.is_deleted', '=', '0')
                                                            ->where('site.siteId', '=', $siteId)
                                                            ->orderBy('theatre.premium', 'DESC')
                                                            ->orderBy('theatre.order', 'ASC');                                                  
            
        $theatreRs                       =   $theatreRs->get();
        $theatres                        =   $theatreRs->toArray();

        return view('admin.theatre_listing',['theatres' => $theatres]);          
    } 

    public function addtheatreView($id=null){
        
        if($id){
            $theatreRs                      =   theatre::select('theatre.id', 'theatre.name', 
                                                        'theatre.description', 
                                                        'theatre.premium', 'theatre.order','theatre.is_disabled', 
                                                        'address.address1', 'address.address2', 'address.id as addressId',
                                                        'theatre.website',  'url.urlName', 'url.id as urlId',                                              
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
                                                    ->leftjoin('url','url.theatreId', '=', 'theatre.id')
                                                    ->leftjoin('address','address.id', '=', 'theatre.addressId')
                                                    ->leftjoin('site','site.siteId', '=', 'theatre.siteId')
                                                    ->leftjoin('city','city.cityId', '=', 'address.city')   
                                                    ->leftjoin('seo','seo.urlId', '=', 'url.id')                                                    
                                                    ->where('theatre.id', '=', $id)
                                                    ->get()->first();

            $theatre                        =   $theatreRs->toArray(); 
           
        }else{
            $theatre['id']                  =   "";
            $theatre['addressId']           =   "";
            $theatre['urlId']               =   "";
            $theatre['name']                =   "";
            $theatre['description']         =   "";
            $theatre['address1']            =   "";
            $theatre['address2']            =   "";
            $theatre['website']             =   "";
            $theatre['urlName']             =   "";
            $theatre['city']                =   "";
            $theatre['state']               =   "";
            $theatre['zip']                 =   "";
            $theatre['county']              =   "";
            $theatre['phone1']              =   "";
            $theatre['phone2']              =   "";
            $theatre['latitude']            =   "";
            $theatre['longitude']           =   "";
            $theatre['premium']             =   "";
            $theatre['is_disabled']         =   "";
            $theatre['order']               =   ""; 
            $theatre['seoId']                               =   ""; 
            $theatre['SEOMetaTitle']                        =   ""; 
            $theatre['SEOMetaDesc']                         =   ""; 
            $theatre['SEOMetaPublishedTime']                =   ""; 
            $theatre['SEOMetaKeywords']                     =   ""; 
            $theatre['OpenGraphTitle']                      =   ""; 
            $theatre['OpenGraphDesc']                       =   ""; 
            $theatre['OpenGraphUrl']                        =   ""; 
            $theatre['OpenGraphPropertyType']               =   ""; 
            $theatre['OpenGraphPropertyLocale']             =   ""; 
            $theatre['OpenGraphPropertyLocaleAlternate']    =   ""; 
            $theatre['OpenGraph']                           =   "";  
            
        }

        $cityRs                             =   City::select('cityId','city', 'value')
                                                    ->orderBy('city', 'asc')
                                                    ->get();  
        $cities                             =   $cityRs->toArray();  
        return view('admin.theatre_add',['theatre' => $theatre, 'cities' => $cities]); 
    }

    public function addTheatre(Request $request)
    {

        $theatreVal                         =   $request->post();

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'urlName' => [
                        'required',
                        Rule::unique('url')->ignore($theatreVal['urlId'], 'id'),
            ],
            'address1' => 'required',
            'city' => 'required',
            'state' => 'required'            
        ]);

        if ($validator->fails()) {
            if($theatreVal['id']){
                return redirect('/admin/theatre_add/'.$theatreVal['id'])->withErrors($validator)->withInput();
            }else{
                return redirect('/admin/theatre_add')->withErrors($validator)->withInput();
            }
        }
        
        if($theatreVal['id']){
            DB::table('theatre')
                ->where('id', $theatreVal['id'])
                ->update(
                    [
                        'name'          => $theatreVal['name'],
                        'description'   => $theatreVal['description'],
                        'siteId'        => config('app.siteId'),
                        'website'       => $theatreVal['website'],
                        'order'         => ($theatreVal['order'])?$theatreVal['order']:0,
                        'premium'       => $theatreVal['premium'],
                        'is_disabled'   => $theatreVal['is_disabled'],
                        'updated_by'    => Auth::user()->id,
                        'updated_at'    => date("Y-m-d H:i:s")                    
                    ]
                );
            DB::table('address')
                ->where('id', $theatreVal['addressId'])
                ->update(
                    [
                        'address1'      => $theatreVal['address1'],
                        'address2'      => $theatreVal['address2'],
                        'city'          => $theatreVal['city'],
                        'state'         => $theatreVal['state'],
                        'zip'           => $theatreVal['zip'],
                        'county'        => $theatreVal['county'],
                        'phone1'        => $theatreVal['phone1'],
                        'phone2'        => $theatreVal['phone2'],
                        'latitude'      => $theatreVal['latitude'],
                        'longitude'     => $theatreVal['latitude'],                   
                    ]
            );
            if($theatreVal['urlName'] != $theatreVal['urlNameChk']){
                DB::table('url')
                ->where('id', $theatreVal['urlId'])
                ->update(
                    [
                        'urlName'       => $theatreVal['urlName'],
                        'updated_at'    => date("Y-m-d H:i:s")                 
                    ]
                );
            }
            DB::table('seo')
                ->where('seoId', $theatreVal['seoId'])
                ->update(
                    [
                        'SEOMetaTitle'                      => $theatreVal['SEOMetaTitle'],
                        'SEOMetaDesc'                       => $theatreVal['SEOMetaDesc'],
                        'SEOMetaPublishedTime'              => $theatreVal['SEOMetaPublishedTime'],
                        'SEOMetaKeywords'                   => $theatreVal['SEOMetaKeywords'],
                        'OpenGraphTitle'                    => $theatreVal['OpenGraphTitle'],
                        'OpenGraphDesc'                     => $theatreVal['OpenGraphDesc'],
                        'OpenGraphUrl'                      => $theatreVal['OpenGraphUrl'],
                        'OpenGraphPropertyType'             => $theatreVal['OpenGraphPropertyType'],
                        'OpenGraphPropertyLocale'           => $theatreVal['OpenGraphPropertyLocale'],
                        'OpenGraphPropertyLocaleAlternate'  => $theatreVal['OpenGraphPropertyLocaleAlternate'],
                        'OpenGraph'                         => $theatreVal['OpenGraph'],
                        'updated_at'                        => date("Y-m-d H:i:s")
                    ]
                ); 
            return redirect('/admin/theatre')->with('status', 'Theatre updated!');                    
        }else{

            $theatreId                      =   DB::table('theatre')->insertGetId(
                                                    [
                                                        'name'          => $theatreVal['name'],
                                                        'description'   => $theatreVal['description'],
                                                        'siteId'        => config('app.siteId'),
                                                        'website'       => $theatreVal['website'],
                                                        'order'         => ($theatreVal['order'])?$theatreVal['order']:0,
                                                        'premium'       => $theatreVal['premium'],
                                                        'is_disabled'   => $theatreVal['is_disabled'],
                                                        'urlId'         => 0,
                                                        'addressId'     => 0,
                                                        'updated_by'    => Auth::user()->id,
                                                        'created_at'  => date("Y-m-d H:i:s"),
                                                        'updated_at'  => date("Y-m-d H:i:s")
                                                    ]
                                                );

            $addressId                      =   DB::table('address')->insertGetId(
                                                    [
                                                        'address1'      => $theatreVal['address1'],
                                                        'address2'      => $theatreVal['address2'],
                                                        'city'          => $theatreVal['city'],
                                                        'state'         => $theatreVal['state'],
                                                        'zip'           => $theatreVal['zip'],
                                                        'county'        => $theatreVal['county'],
                                                        'phone1'        => $theatreVal['phone1'],
                                                        'phone2'        => $theatreVal['phone2'],
                                                        'latitude'      => $theatreVal['latitude'],
                                                        'longitude'     => $theatreVal['latitude'],
                                                    ]
                                                );
            $urlId                          =   DB::table('url')->insertGetId(
                                                    [
                                                        'urlName'       => $theatreVal['urlName'],
                                                        'theatreId'     => $theatreId,
                                                        'created_at'  => date("Y-m-d H:i:s"),
                                                        'updated_at'  => date("Y-m-d H:i:s")
                                                    ]
                                                ); 
            DB::table('theatre')
                ->where('id', $theatreId)
                ->update(
                    [
                        'urlId'             => $urlId,
                        'addressId'         => $addressId
                    ]
                );

            $seoId                          =   DB::table('seo')->insertGetId(
                                                    [
                                                        'urlId'                             => $urlId,
                                                        'SEOMetaTitle'                      => $theatreVal['SEOMetaTitle'],
                                                        'SEOMetaDesc'                       => $theatreVal['SEOMetaDesc'],
                                                        'SEOMetaPublishedTime'              => $theatreVal['SEOMetaPublishedTime'],
                                                        'SEOMetaKeywords'                   => $theatreVal['SEOMetaKeywords'],
                                                        'OpenGraphTitle'                    => $theatreVal['OpenGraphTitle'],
                                                        'OpenGraphDesc'                     => $theatreVal['OpenGraphDesc'],
                                                        'OpenGraphUrl'                      => $theatreVal['OpenGraphUrl'],
                                                        'OpenGraphPropertyType'             => $theatreVal['OpenGraphPropertyType'],
                                                        'OpenGraphPropertyLocale'           => $theatreVal['OpenGraphPropertyLocale'],
                                                        'OpenGraphPropertyLocaleAlternate'  => $theatreVal['OpenGraphPropertyLocaleAlternate'],
                                                        'OpenGraph'                         => $theatreVal['OpenGraph'],
                                                        'created_at'                        => date("Y-m-d H:i:s"),
                                                        'updated_at'                        => date("Y-m-d H:i:s")
                                                    ]
                                                );   
                                              
            return redirect('/admin/theatre')->with('status', 'Theatre added!');                                                
        }
    }
    
    public function deleteTheatre($id){
        if($id){
            DB::table('theatre')
            ->where('id', $id)
            ->update(
                [
                    'is_deleted'        => 1
                ]
            ); 
        }
        return redirect('/admin/theatre')->with('status', 'Theatre deleted!');
    }    

}
