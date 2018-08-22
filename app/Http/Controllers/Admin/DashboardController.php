<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function __construct(){
        $this->middleware('role:Admin');
    }

    public function dashboard(){
        return view('admin.dashboard');
    }    

}
