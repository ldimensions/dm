<?php

namespace App\Http\Controllers\Editor;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Models\ReligionTmp;
use App\Http\Models\RestaurantTemp;
use App\Http\Models\GroceryTmp;

class DashboardController extends Controller
{
    public function __construct(){
        $this->middleware('role:Editor');
    }

    public function dashboard(){

        $religionSubmitted                  =   0;
        $religionRejected                   =   0;
        $restaurantSubmitted                =   0;
        $restaurantRejected                 =   0;
        $grocerySubmitted                   =   0;
        $groceryRejected                    =   0;
        
        $religionSubmitted                  =   ReligionTmp::select('religion_tmp.id')                                                                                                                               
                                                    ->where('religion_tmp.status', '=', '2')->get()->count();

        $religionRejected                   =   ReligionTmp::select('religion_tmp.id')                                                                                                                               
                                                    ->where('religion_tmp.status', '=', '4')->get()->count();     
                                                    
        $restaurantSubmitted                =   RestaurantTemp::select('restaurant_tmp.id')                                                                                                                               
                                                    ->where('restaurant_tmp.status', '=', '2')->get()->count();

        $restaurantRejected                 =   RestaurantTemp::select('restaurant_tmp.id')                                                                                                                               
                                                    ->where('restaurant_tmp.status', '=', '4')->get()->count(); 

        $grocerySubmitted                   =   GroceryTmp::select('grocery_tmp.id')                                                                                                                               
                                                    ->where('grocery_tmp.status', '=', '2')->get()->count();

        $groceryRejected                    =   GroceryTmp::select('grocery_tmp.id')                                                                                                                               
                                                    ->where('grocery_tmp.status', '=', '4')->get()->count();                                                      

        return view('editor.dashboard',['religionSubmitted' => $religionSubmitted, 
                                        'religionRejected' => $religionRejected,
                                        'restaurantSubmitted' => $restaurantSubmitted,
                                        'restaurantRejected' => $restaurantRejected,
                                        'grocerySubmitted' => $grocerySubmitted,
                                        'groceryRejected' => $groceryRejected
                                        ]);
    }  

}
