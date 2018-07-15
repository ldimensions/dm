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

    </head>
    <body>
        <div class="container">
            <div class="row">
                <div class="headerstrip"></div>
                <div class="col-md-12 headerh nopadding">
                    <div class="header"> 
                        <a href="#"  class="signinbutton">Sign In</a> 
                        <a href="#"  class="location"><img alt="{{config('app.siteId')}}" src="{{ URL::to('/') }}/image/location.svg" />Dallas</a> 
                    </div>
                    <div class="menu"> 
                        <a href="{{ url('/') }}" class="{{ (request()->is('/')) ? 'activemenu' : 'inactivemenu' }}">Home</a> 
                        <a href="{{ route('grocery') }}" class="{{ (request()->is('dallas-malayali-grocery-store')) ? 'activemenu' : 'inactivemenu' }}">Groceries</a> 
                        <a href="#" class="inactivemenu">Restaurants </a> 
                        <a href="{{ route('religion') }}" class="{{ (request()->is('dallas-malayali-church')) ? 'activemenu' : 'inactivemenu' }}">Religions </a> 
                        <a href="#" class="inactivemenu">Automotive </a> 
                    </div>
                </div>
            </div>
            <div class="row">
                @yield('content')
                <div class="row">
                    <div class="col-md-12 footerh nopadding">Copyright &copy; 2018 Ldimensions. All rights reserved.</div>
                </div>
            </div>
        </div>
        <script>
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(showPosition);
            } else {
                console.log("Geolocation is not supported by this browser.");
            }
            function showPosition(position) {
                console.log("Latitude: " + position.coords.latitude +"Longitude: " + position.coords.longitude);
                setCookie(position.coords.latitude, position.coords.longitude);
            }
            function setCookie(lat, long, value) {
                var d = new Date;
                d.setTime(d.getTime() + 24*60*60*1000*1);
                document.cookie = "lat=" + lat + ";path=/;expires=" + d.toGMTString();
                document.cookie = "long=" + long + ";path=/;expires=" + d.toGMTString();
            }    
        </script>
    </body>
</html>
