<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\CommonController;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Http\Models\SuggessionForEdit;
use DateTime;

class SuggessionForEditController extends Controller
{
    public function __construct(){
        $this->middleware('role:Admin');
    }

    public static function index(){

        $commonCtrl                         =   new CommonController;

        $suggessionForEditRs                =   SuggessionForEdit::select('id', 'name', 'suggession', 'created_at', 'is_read', 'type')
                                                    ->where('is_deleted', 0)
                                                    //->orderBy('is_read', 'DESC')
                                                    ->orderBy('created_at', 'DESC')
                                                    ->get(); 
        $suggessionForEdit                  =   $suggessionForEditRs->toArray(); 
        $max_length                         =  100;

        foreach($suggessionForEdit as $key => $ret){

            $suggession                     =   $ret['suggession'];
            if (strlen($suggession) > $max_length)
            {
                $offset                     = ($max_length - 3) - strlen($suggession);
                $suggession                 = substr($suggession, 0, strrpos($suggession, ' ', $offset)) . '...';
            }    
            $date                           =   $commonCtrl->time_elapsed_string($ret['created_at']);
            $suggessionForEdit[$key]['created_at']  = $date;  
            $suggessionForEdit[$key]['suggession']  = $suggession;  
        }
        return view('admin.suggession_for_edit',['suggessionForEdit' => $suggessionForEdit]);      
    }

    public function suggessionView($id) {
        $suggessionForEditRs                =   SuggessionForEdit::select('id', 'name', 'email', 'phone', 'suggession', 'created_at', 'is_read', 'type', 'url')
                                                    ->where('is_deleted', 0)
                                                    ->get()->first();

        $suggessionForEdit                  =   $suggessionForEditRs->toArray(); 

        DB::table('suggession_for_edit')
                ->where('id', $id)
                ->update(
                    [
                        'is_read'        => 0
                    ]
                );         

        return view('admin.suggession_for_edit_view',['suggessionForEdit' => $suggessionForEdit]);  
    }

    public function deleteSuggession($id){
        if($id){
            DB::table('suggession_for_edit')
            ->where('id', $id)
            ->update(
                [
                    'is_deleted'        => 1
                ]
            ); 
        }
        return redirect('/admin/suggession_for_edit')->with('status', 'Suggession deleted!');
    }
}
