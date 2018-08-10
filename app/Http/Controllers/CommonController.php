<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Models\Url;

use SEOMeta;
use OpenGraph;
use Twitter;

class CommonController extends Controller
{
    public function __construct(){

    }

    function distance($lat2, $lon2, $unit) {

        $currentLat                         =   (isset($_COOKIE['lat']))?$_COOKIE['lat']:'';
        $currentLong                        =   (isset($_COOKIE['long']))?$_COOKIE['long']:'';
        if($currentLat && $currentLong){
            $theta                              =   $currentLong - $lon2;
            $dist                               =   sin(deg2rad($currentLat)) * sin(deg2rad($lat2)) +  cos(deg2rad($currentLat)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
            $dist                               =   acos($dist);
            $dist                               =   rad2deg($dist);
            $miles                              =   $dist * 60 * 1.1515;
            $unit                               =   strtoupper($unit);
          
            if ($unit == "K") {
              return ($miles * 1.609344);
            } else if ($unit == "N") {
              return ($miles * 0.8684);
            } else {
              return $miles;
            }    
        }
        return false;
    }

    function seoUrl($url, $index){
        $url                                =   explode("/",$url);
        return $url[$index-1];
    }

    function setMeta($url, $index){

        $url                                =   explode("/",$url);
        $seoUrl                             =   $url[$index-1];

        $seoRs                              =   Url::select('seo.SEOMetaTitle', 'seo.SEOMetaDesc', 
                                                'seo.SEOMetaPublishedTime','seo.SEOMetaKeywords',
                                                'seo.OpenGraphTitle','seo.OpenGraphDesc',
                                                'seo.OpenGraphUrl','seo.OpenGraphPropertyType',
                                                'seo.OpenGraphPropertyLocale','seo.OpenGraphPropertyLocaleAlternate',
                                                'seo.OpenGraph')
                                                ->join('seo','seo.urlId', '=', 'url.id')
                                                ->where('url.urlName', '=', $seoUrl)
                                                ->get()->first();  
        if($seoRs){
            
            $seo                            =   $seoRs->toArray();
            SEOMeta::setTitle($seo['SEOMetaTitle']);
            SEOMeta::setDescription($seo['SEOMetaDesc']);
            SEOMeta::addKeyword([$seo['SEOMetaKeywords']]); 
            if($seo['SEOMetaPublishedTime']){
                SEOMeta::addMeta('article:published_time', $seo['SEOMetaPublishedTime']->toW3CString(), 'property');
            }
            
        }else{
            $defaultSEOValues               =   config('app.defaultSEO');
            $defaultSEO                     =   $defaultSEOValues[1];
            SEOMeta::setTitle($defaultSEO['SEOMetaTitle']);
            SEOMeta::setDescription($defaultSEO['SEOMetaDesc']);
            SEOMeta::addKeyword($defaultSEO['SEOMetaKeywords']);    
        }

    }

    function setMeta1(){
      SEOMeta::setTitle($post->title);
      SEOMeta::setDescription($post->resume);
      SEOMeta::addMeta('article:published_time', $post->published_date->toW3CString(), 'property');
      SEOMeta::addMeta('article:section', $post->category, 'property');
      SEOMeta::addKeyword(['key1', 'key2', 'key3']);

      OpenGraph::setDescription($post->resume);
      OpenGraph::setTitle($post->title);
      OpenGraph::setUrl('http://current.url.com');
      OpenGraph::addProperty('type', 'article');
      OpenGraph::addProperty('locale', 'pt-br');
      OpenGraph::addProperty('locale:alternate', ['pt-pt', 'en-us']);

      OpenGraph::addImage($post->cover->url);
      OpenGraph::addImage($post->images->list('url'));
      OpenGraph::addImage(['url' => 'http://image.url.com/cover.jpg', 'size' => 300]);
      OpenGraph::addImage('http://image.url.com/cover.jpg', ['height' => 300, 'width' => 300]);

      // Namespace URI: http://ogp.me/ns/article#
      // article
      OpenGraph::setTitle('Article')
          ->setDescription('Some Article')
          ->setType('article')
          ->setArticle([
              'published_time' => 'datetime',
              'modified_time' => 'datetime',
              'expiration_time' => 'datetime',
              'author' => 'profile / array',
              'section' => 'string',
              'tag' => 'string / array'
          ]);

      // Namespace URI: http://ogp.me/ns/book#
      // book
      OpenGraph::setTitle('Book')
          ->setDescription('Some Book')
          ->setType('book')
          ->setBook([
              'author' => 'profile / array',
              'isbn' => 'string',
              'release_date' => 'datetime',
              'tag' => 'string / array'
          ]);

      // Namespace URI: http://ogp.me/ns/profile#
      // profile
      OpenGraph::setTitle('Profile')
           ->setDescription('Some Person')
          ->setType('profile')
          ->setProfile([
              'first_name' => 'string',
              'last_name' => 'string',
              'username' => 'string',
              'gender' => 'enum(male, female)'
          ]);

      // Namespace URI: http://ogp.me/ns/music#
      // music.song
      OpenGraph::setType('music.song')
          ->setMusicSong([
              'duration' => 'integer',
              'album' => 'array',
              'album:disc' => 'integer',
              'album:track' => 'integer',
              'musician' => 'array'
          ]);

      // music.album
      OpenGraph::setType('music.album')
          ->setMusicAlbum([
              'song' => 'music.song',
              'song:disc' => 'integer',
              'song:track' => 'integer',
              'musician' => 'profile',
              'release_date' => 'datetime'
          ]);

       //music.playlist
      OpenGraph::setType('music.playlist')
          ->setMusicPlaylist([
              'song' => 'music.song',
              'song:disc' => 'integer',
              'song:track' => 'integer',
              'creator' => 'profile'
          ]);

      // music.radio_station
      OpenGraph::setType('music.radio_station')
          ->setMusicRadioStation([
              'creator' => 'profile'
          ]);

      // Namespace URI: http://ogp.me/ns/video#
      // video.movie
      OpenGraph::setType('video.movie')
          ->setVideoMovie([
              'actor' => 'profile / array',
              'actor:role' => 'string',
              'director' => 'profile /array',
              'writer' => 'profile / array',
              'duration' => 'integer',
              'release_date' => 'datetime',
              'tag' => 'string / array'
          ]);

      // video.episode
      OpenGraph::setType('video.episode')
          ->setVideoEpisode([
              'actor' => 'profile / array',
              'actor:role' => 'string',
              'director' => 'profile /array',
              'writer' => 'profile / array',
              'duration' => 'integer',
              'release_date' => 'datetime',
              'tag' => 'string / array',
              'series' => 'video.tv_show'
          ]);

      // video.tv_show
      OpenGraph::setType('video.tv_show')
          ->setVideoTVShow([
              'actor' => 'profile / array',
              'actor:role' => 'string',
              'director' => 'profile /array',
              'writer' => 'profile / array',
              'duration' => 'integer',
              'release_date' => 'datetime',
              'tag' => 'string / array'
          ]);

      // video.other
      OpenGraph::setType('video.other')
          ->setVideoOther([
              'actor' => 'profile / array',
              'actor:role' => 'string',
              'director' => 'profile /array',
              'writer' => 'profile / array',
              'duration' => 'integer',
              'release_date' => 'datetime',
              'tag' => 'string / array'
          ]);

      // og:video
      OpenGraph::addVideo('http://example.com/movie.swf', [
              'secure_url' => 'https://example.com/movie.swf',
              'type' => 'application/x-shockwave-flash',
              'width' => 400,
              'height' => 300
          ]);

      // og:audio
      OpenGraph::addAudio('http://example.com/sound.mp3', [
              'secure_url' => 'https://secure.example.com/sound.mp3',
              'type' => 'audio/mpeg'
          ]);

    }

    public static function activeMenu($type){
        $isActive           =   false;
        $hostName           =   $_SERVER['PHP_SELF'];
        $hostNameArr        =   explode("/",$hostName);

        $groceryArr         =   array('dallas-indian-grocery-store','dallas-grocery-store','grocery-search');
        $restaurantArr      =   array('dallas-indian-restaurant','restaurant-search');
        $religionArr        =   array('dallas-indian-religion','religion-search','dallas-malayali-church','dallas-christian-church','dallas-hindu-temple','dallas-islan-mosque');
        $travelsArr         =   array('dallas-indian-travels','travels-search');        
        switch($type){
            case 'home':
                if(count($hostNameArr)  ==  2){
                    $isActive = true;
                }
                break;            
            case 'grocery':
                foreach($groceryArr as $grocery){
                    if (in_array($grocery, $hostNameArr)){
                        $isActive = true;
                    }
                }
                break;
            case 'restaurant':
                foreach($restaurantArr as $restaurant){
                    if (in_array($restaurant, $hostNameArr)){
                        $isActive = true;
                    }
                }
                break;
            case 'religion':
                foreach($religionArr as $religion){
                    if (in_array($religion, $hostNameArr)){
                        $isActive = true;
                    }
                }
                break;
            case 'travels':
                foreach($travelsArr as $travel){
                    if (in_array($travel, $hostNameArr)){
                        $isActive = true;
                    }
                }
                break;
                
        }
        return $isActive;
    }

    public static function suggessionForEdit(Request $request){
        echo $request->input('name');
    }

}
