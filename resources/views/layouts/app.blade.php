<?php use App\Http\Controllers\CommonController;?>
<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">


        <!-- {!! SEOMeta::generate() !!}
        {!! OpenGraph::generate() !!}
        {!! Twitter::generate() !!} -->
            <!-- OR -->
        <!-- {!! SEO::generate() !!} -->
        
        <!-- MINIFIED -->
        {!! SEO::generate(true) !!}

        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <!-- <title>{{ config('app.name', 'Laravel') }}</title> -->

        <!-- Scripts -->
        <script src="{{ asset('js/app.js') }}" defer></script>

        <!-- Fonts -->
        <link rel="dns-prefetch" href="https://fonts.gstatic.com">
        <link href="https://fonts.googleapis.com/css?family=Raleway:300,400,600" rel="stylesheet" type="text/css">

        <!-- Styles -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
        <!-- <link href="{{ asset('css/app.css') }}" rel="stylesheet"> -->
        <link href="{{ asset('css/style.css') }}" rel="stylesheet">
        <link href="{{ asset('css/lightslider.css') }}" rel="stylesheet">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.0/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
        <script src="{{ asset('js/lightslider.js') }}" ></script>
        <script src="{{ asset('js/common.js') }}" ></script>                
    </head>
    <body>
        <div class="container">
            <div class="row">
                <div class="headerstrip"></div>
                <div class="col-md-12 headerh nopadding">
                    <div class="header"> 
                        @if ( Auth::guest() )
                            <a href="{{ url('/login') }}"  class="signinbutton">Login</a> 
                            <a href="{{ url('/register') }}"  class="signinbutton">Sign Up</a>                            
                        @else
                            <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"  class="signinbutton">Logout</a> 
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">{{ csrf_field() }}</form>                            
                        @endif                        
                        <a href="#"  class="location"><img alt="{{config('app.siteId')}}" src="{{ URL::to('/') }}/image/location.svg" />Dallas</a> 
                    </div>
                    <div class="menumain">
                    <div class="menu"> 
                        <a href="{{ url('/') }}" class="{{ (CommonController::activeMenu('home')) ? 'activemenu' : 'inactivemenu' }}">Home</a> 
                        <a href="{{ route('grocery') }}" class="{{ (CommonController::activeMenu('grocery')) ? 'activemenu' : 'inactivemenu' }}">Groceries</a>                         
                        <a href="{{ route('restaurant') }}" class="{{ (CommonController::activeMenu('restaurant')) ? 'activemenu' : 'inactivemenu' }}">Restaurants </a> 
                        <a href="{{ route('religion') }}" class="{{ (CommonController::activeMenu('religion')) ? 'activemenu' : 'inactivemenu' }}">Religions </a> 
                        <!-- <a href="#" class="{{ (CommonController::activeMenu('travels')) ? 'activemenu' : 'inactivemenu' }}">Travels </a> 
                        <a href="#" class="{{ (CommonController::activeMenu('')) ? 'activemenu' : 'inactivemenu' }}">Auto </a>     
                        <a href="#" class="{{ (CommonController::activeMenu('')) ? 'activemenu' : 'inactivemenu' }}">Events </a> 
                        <a href="#" class="{{ (CommonController::activeMenu('')) ? 'activemenu' : 'inactivemenu' }}">Movies </a>                      -->
                    </div>
                    </div
                </div>
            </div>
            <div class="row">
                @yield('content')
                <div class="row">
                    <div class="col-md-12 footerh nopadding">Copyright &copy; 2018 Ldimensions. All rights reserved.</div>
                </div>
            </div>
        </div>
    </body>
</html>




