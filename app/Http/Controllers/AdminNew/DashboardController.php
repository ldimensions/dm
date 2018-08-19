<?php

namespace App\Http\Controllers\AdminNew;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function __construct(){
        //$this->middleware('auth');
    }

    public function dashboard(){
        return view('adminNew.dashboard');
    }    

}
