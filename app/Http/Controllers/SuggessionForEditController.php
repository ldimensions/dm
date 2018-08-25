<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\CommonController;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Http\Models\SuggessionForEdit;
use Mail;

class SuggessionForEditController extends Controller
{
    public function __construct(){
        
    }

    public static function suggessionForEdit(Request $request){

        //$suggessionVal                      =   $request->post();

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'suggession' => 'required',
        ]);

        if (!$validator->fails()) {
            $id = DB::table('suggession_for_edit')->insertGetId(
                [
                    'name'          => $request->post('name'),
                    'email'         => $request->post('email'),
                    'phone'         => $request->post('phone'),
                    'suggession'    => $request->post('suggession'),
                    'url'           => $request->post('url'),
                    'type'          => $request->post('type'),
                    'is_read'       => 1,
                    'created_at'    => date("Y-m-d H:i:s"),
                    'updated_at'    => date("Y-m-d H:i:s")
                ]
            );

            if($request->post('type') == 1){
                $suggessiontype             =   "Grocery";
            }else if($request->post('type') == 2){
                $suggessiontype             =   "Restarunt";
            }else if($request->post('type') == 3){
                $suggessiontype             =   "Religion";
            }else{
                $suggessiontype             =   "Unknown";
            }

            $data                           =   array(
                                                    'name' =>$request->post('name'), 
                                                    "email" => $request->post('email'), 
                                                    "phone" => $request->post('phone'),
                                                    "url" => $request->post('url'),
                                                    "type" => $suggessiontype,
                                                    "suggession" => $request->post('suggession')
                                                );
            
            $fromEmail                  =   config('app.fromEmail');
            $toEmail                    =   config('app.toEmail');
            
            Mail::send('email.suggession', $data, function($message) use ($fromEmail, $toEmail, $suggessiontype){
                $message->to($toEmail, 'Suggession from '.$suggessiontype)
                        ->subject('Suggession from '.$suggessiontype);
                $message->from($fromEmail,'');
            });
        }
    }
    
    public static function getSuggessionNotification(){

        $commonCtrl                         =   new CommonController;
        $suggessionForEditRs                =   SuggessionForEdit::select('name', 'suggession', 'created_at')
                                                    ->where('is_read', 1)
                                                    ->where('is_deleted', 0)
                                                    ->orderBy('created_at', 'DESC')
                                                    ->take(4)->get(); 
        $suggessionForEdit                  =   $suggessionForEditRs->toArray(); 

        $suggessionForEditCount             =   DB::table('suggession_for_edit')->where('is_read', 1)->where('is_deleted', 0)->count();    
        $html                               =   '';
        $max_length                         =  80;
        
        foreach($suggessionForEdit as $ret){
            $name                           =   $ret['name'];
            $suggession                     =   $ret['suggession'];

            $date                           =   $commonCtrl->time_elapsed_string($ret['created_at']);
            
            if (strlen($suggession) > $max_length)
            {
                $offset                     = ($max_length - 3) - strlen($suggession);
                $suggession                 = substr($suggession, 0, strrpos($suggession, ' ', $offset)) . '...';
            }            
            $html                           .= '<li>
                                                    <a href="#">
                                                        <div>
                                                            <strong>'.$name.'</strong>
                                                            <span class="pull-right text-muted">
                                                                <em>'.$date.'</em>
                                                            </span>
                                                        </div>
                                                        <div>'.$suggession.'</div>
                                                    </a>
                                                </li>
                                                <li class="divider"></li>';
        }
        if($html){
            $html                           .= '<li>
                                                    <a class="text-center" href="/admin/suggession_for_edit"">
                                                        <strong>Read All Messages</strong>
                                                        <i class="fa fa-angle-right"></i>
                                                    </a>
                                                </li>';
        }else{
            $html                           .= '<li class="text-center">
                                                    <strong>No New Messages</strong>
                                                </li>';
        }
       
        
        return ['message' =>$html, 'count' => $suggessionForEditCount];

    }
}
