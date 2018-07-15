<?php

namespace App\Http\Controllers;

use App\Http\Controllers\CommonController;
use Illuminate\Http\Request;
use App\Http\Models\Religion;
use App\Http\Models\Photo;
use App\Http\Models\Url;

use SEOMeta;
use OpenGraph;
use Twitter;
use Cookie;


class HomeController extends Controller
{

    public function __construct(){
        //$this->middleware('auth');
    }


    public function index(Request $request){


        $commonCtrl                     =   new CommonController;

        $siteId                         =   config('app.siteId');
        $religionRs                     =   Religion::select('religion.id', 'religion.name', 
                                                'religion.shortDescription', 'religion.workingTime',
                                                'address.address1', 'address.address2',
                                                'address.city', 'address.state',
                                                'address.zip', 'religion.shortDescription',
                                                'address.city', 'address.state',
                                                'address.phone1', 'address.latitude',
                                                'address.longitude','religion_type.religionName',
                                                'url.urlName', 'photo.photoName',
                                                'denomination.denominationName')
                                            ->leftjoin('religion_type','religion_type.id', '=', 'religion.religionTypeId')                                                
                                            ->leftjoin('denomination','denomination.id', '=', 'religion.denominationId')                                                
                                            ->leftjoin('url','url.religionId', '=', 'religion.id')
                                            ->leftjoin('address','address.id', '=', 'religion.addressId')
                                            ->leftjoin('site','site.siteId', '=', 'religion.siteId')
                                            ->leftjoin('photo','photo.religionId', '=', 'religion.id')                                            
                                            ->where('religion.is_deleted', '=', '0')
                                            ->where('religion.is_disabled', '=', '0')
                                            ->where('site.siteId', '=', $siteId)
                                            ->where('photo.is_primary', '=', '1')
                                            ->orderBy('religion.premium', 'desc')
                                            ->orderBy('religion.order', 'asc') 
                                            ->take(5)                                                                                                      
                                            ->get(); 
        
        $religion                       =   $religionRs->toArray();  
        //print_r($religion);

        $commonCtrl->setMeta($request->path(),1);
        
        return view('home',['religion' => $religion]);
    }
}
