<?php use App\Http\Controllers\CommonController;?>
<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        
        <meta name="copyright" content="dallasindianportal.com" />
        <meta name="author" content="dallasindianportal.com" />
        <meta name="allow-search" content="yes" />
        <meta name="revisit-after" content="daily" />
        <meta name="robots" content="index,follow" />
        {!! SEO::generate(true) !!}

        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <!-- Scripts -->
        <script src="{{ asset('js/app.js') }}?version=6.1" defer></script>

        <!-- Fonts -->
        <link rel="dns-prefetch" href="https://fonts.gstatic.com">
        <link href="https://fonts.googleapis.com/css?family=Raleway:300,400,600" rel="stylesheet" type="text/css">

        <!-- Styles -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
        <!-- <link href="{{ asset('css/app.css') }}" rel="stylesheet"> -->
        <link href="{{ asset('css/style.css') }}?version=6.1" rel="stylesheet">
        <link href="{{ asset('css/lightslider.css') }}?version=6.1" rel="stylesheet">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.0/jquery.min.js"></script>
    </head>
    <body>
        <div class="container">
            <div class="row">
                <div class="headerstrip"></div>
                <div class="headerh nopadding">
                    <div class="headercontainer">
                        <div class="header"> 
                            <!-- @if ( Auth::guest() )
                                <a href="{{ url('/login') }}"  class="signinbutton">Login</a> 
                                <a href="{{ url('/register') }}"  class="signinbutton">Sign Up</a>                            
                            @else
                                <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"  class="signinbutton">Logout</a> 
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">{{ csrf_field() }}</form>                            
                            @endif                         
                            <a href="#"  class="location"><img alt="{{config('app.siteId')}}" src="{{ URL::to('/') }}/image/location.svg" />Dallas</a>  -->
                            <img alt="DallasIndianPortal" class="hDlOGO" src="{{ URL::to('/') }}/image/dallasLogo.svg"/>
                            <div class="headerad"><img alt="ad"  width="100%" height="100%" src="{{ URL::to('/') }}/image/topBanner.svg"/></div>
                        </div>
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
                    </div>
                </div>
            </div>
            @yield('content')
        </div>
         <div class="footerh nopadding"> Copyright 2018 LDimensions. All rights reserved.</div>

         <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
         <script src="{{ asset('js/common.js') }}" defer="defer"></script> 

        <!-- Global site tag (gtag.js) - Google Analytics -->
        <script async src="https://www.googletagmanager.com/gtag/js?id=UA-126021205-1"></script>
        <script>
            window.dataLayer = window.dataLayer || [];
            function gtag(){dataLayer.push(arguments);}
            gtag('js', new Date());
            gtag('config', 'UA-126021205-1');
        </script>

    </body>
</html>