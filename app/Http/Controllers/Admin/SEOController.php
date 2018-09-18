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
            $seoRs                          =   Seo::select('seo.seoId', 'seo.SEOMetaTitle',
                                                            'seo.keyValue', 'seo.indexValue',
                                                            'seo.SEOMetaDesc', 'seo.SEOMetaPublishedTime',
                                                            'seo.SEOMetaKeywords', 'seo.OpenGraphTitle',
                                                            'seo.OpenGraphDesc', 'seo.OpenGraphUrl',
                                                            'seo.OpenGraphPropertyType', 'seo.OpenGraphPropertyLocale',
                                                            'seo.OpenGraphPropertyLocaleAlternate', 'seo.OpenGraph')
                                                        ->where('seo.seoId', '=', $id)
                                                        ->get()->first();

            $seo                            =   $seoRs->toArray(); 

        }else{
            $seo['seoId']                               =   ""; 
            $seo['keyValue']                            =   ""; 
            $seo['indexValue']                          =   "";             
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

    public function addSeo(Request $request)
    {

        $seoVal                           =   $request->post();
        
        $validator = Validator::make($request->all(), [
            'SEOMetaTitle' => 'required',           
        ]);

        if ($validator->fails()) {
            if($seoVal['seoId']){
                return redirect('/admin/seo_add/'.$seoVal['id'])->withErrors($validator)->withInput();
            }else{
                return redirect('/admin/seo_add')->withErrors($validator)->withInput();
            }
        }
       
        if($seoVal['seoId']){
            DB::table('seo')
                ->where('seoId', $seoVal['seoId'])
                ->update(
                    [
                        'SEOMetaTitle'                      => $seoVal['SEOMetaTitle'],
                        'keyValue'                          => $seoVal['keyValue'],
                        'indexValue'                        => $seoVal['indexValue'],
                        'SEOMetaDesc'                       => $seoVal['SEOMetaDesc'],
                        'SEOMetaPublishedTime'              => $seoVal['SEOMetaPublishedTime'],
                        'SEOMetaKeywords'                   => $seoVal['SEOMetaKeywords'],
                        'OpenGraphTitle'                    => $seoVal['OpenGraphTitle'],
                        'OpenGraphDesc'                     => $seoVal['OpenGraphDesc'],
                        'OpenGraphUrl'                      => $seoVal['OpenGraphUrl'],
                        'OpenGraphPropertyType'             => $seoVal['OpenGraphPropertyType'],
                        'OpenGraphPropertyLocale'           => $seoVal['OpenGraphPropertyLocale'],
                        'OpenGraphPropertyLocaleAlternate'  => $seoVal['OpenGraphPropertyLocaleAlternate'],
                        'OpenGraph'                         => $seoVal['OpenGraph'],
                        'updated_at'                        => date("Y-m-d H:i:s")
                    ]
                ); 
            return redirect('/admin/seo')->with('status', 'SEO updated!');             
        }else{
            $seoId                          =   DB::table('seo')->insertGetId(
                                                    [
                                                        'keyValue'                          => $seoVal['keyValue'],
                                                        'indexValue'                        => $seoVal['indexValue'],                                                        
                                                        'SEOMetaTitle'                      => $seoVal['SEOMetaTitle'],
                                                        'SEOMetaDesc'                       => $seoVal['SEOMetaDesc'],
                                                        'SEOMetaPublishedTime'              => $seoVal['SEOMetaPublishedTime'],
                                                        'SEOMetaKeywords'                   => $seoVal['SEOMetaKeywords'],
                                                        'OpenGraphTitle'                    => $seoVal['OpenGraphTitle'],
                                                        'OpenGraphDesc'                     => $seoVal['OpenGraphDesc'],
                                                        'OpenGraphUrl'                      => $seoVal['OpenGraphUrl'],
                                                        'OpenGraphPropertyType'             => $seoVal['OpenGraphPropertyType'],
                                                        'OpenGraphPropertyLocale'           => $seoVal['OpenGraphPropertyLocale'],
                                                        'OpenGraphPropertyLocaleAlternate'  => $seoVal['OpenGraphPropertyLocaleAlternate'],
                                                        'OpenGraph'                         => $seoVal['OpenGraph'],
                                                        'created_at'                        => date("Y-m-d H:i:s"),
                                                        'updated_at'                        => date("Y-m-d H:i:s")
                                                    ]
                                                );   
                                                
            return redirect('/admin/seo')->with('status', 'SEO Added!');                                                
        }
    }     

}
