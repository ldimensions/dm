@extends('layouts.app')
@section('content')
<?php use App\Http\Controllers\CommonController;?>
<div class="mcontainer">
<div class="maincontainer">
    <div class="leftcontainer">
        <div class="col-md-12 searchbar hiddepadding">
            <form>
                <select name="type" class="select" id="type">
                    <option 
                        value="all">
                        All
                    </option>
                    <option value="1"
                        {{ $type == '1' ? 'selected="selected"' : '' }}>
                        {{ config('app.movieLanguage.1') }}
                    </option>    
                    <option value="2"
                        {{ $type == '2' ? 'selected="selected"' : '' }}>
                        {{ config('app.movieLanguage.2') }}
                    </option>  
                    <option value="3"
                        {{ $type == '3' ? 'selected="selected"' : '' }}>
                        {{ config('app.movieLanguage.3') }}
                    </option>  
                    <option value="4"
                        {{ $type == '4' ? 'selected="selected"' : '' }}>
                        {{ config('app.movieLanguage.4') }}
                    </option>  
                    <option value="5"
                        {{ $type == '5' ? 'selected="selected"' : '' }}>
                        {{ config('app.movieLanguage.5') }}
                    </option>  
                    <option value="6"
                        {{ $type == '6' ? 'selected="selected"' : '' }}>
                        {{ config('app.movieLanguage.6') }}
                    </option>  
                    <option value="7"
                        {{ $type == '7' ? 'selected="selected"' : '' }}>
                        {{ config('app.movieLanguage.7') }}
                    </option>  
                    <option value="8"
                        {{ $type == '8' ? 'selected="selected"' : '' }}>
                        {{ config('app.movieLanguage.8') }}
                    </option>  
                    <option value="9"
                        {{ $type == '9' ? 'selected="selected"' : '' }}>
                        {{ config('app.movieLanguage.9') }}
                    </option>  
                    <option value="10"
                        {{ $type == '10' ? 'selected="selected"' : '' }}>
                        {{ config('app.movieLanguage.10') }}
                    </option>                                                                                                                                                                                                                
                </select>
                <select name="city" class="select" id="city">
                    <option value="all">All</option>
                    @foreach ($cities as $key => $city)
                        <option 
                            value="{{$city['value']}}-{{$city['cityId']}}"
                            {{$city['cityId'] == $cityVal ? 'selected="selected"' : '' }}>
                            {{$city['city']}}
                        </option>
                    @endforeach
                </select>
                <input type="text" id="searchKeyword" value="{{$keyword}}" name="searchKeyword" placeholder="Keywords" class="text1" maxlength="50" pattern="(1[0-2]|0[1-9])\/(1[5-9]|2\d)">
                <a href="JavaScript:void(0)" class="search" onclick="movieSearch()">Search</a>
            </form>
        </div>
        @if (count($movies) == 0)
            <div class="col-md-12 block1">
                    <div class="smallImage">
                        <img src="{{ URL::to('/') }}/image/noimage.svg" alt="" style="width:100%;height:100%"></div>
                        <h2 class="content11"><a href="{{ URL::to('/') }}/{{config('app.defaultBaseURL.indian-movie')}}/hardcoded" title="" class="colorh1">Name</a> </h2>                                                     
                    <div class="content2">Language</div>

            </div>
        @endif         
        @foreach ($movies as $key => $rel)
            <div class="col-md-12 block1">
                <div class="smallImage">
                    @if (isset($rel['photoName']) && $rel['photoName'])
                        <img src="{{ URL::to('/') }}/image/movie/{{$rel['movieId']}}/{{$rel['photoName']}}" alt="{{$loop->index}}{{ $rel['name'] }}" style="width:100%;height:100%"></div>
                    @else   
                        <img src="{{ URL::to('/') }}/image/noimage.svg" alt="{{$loop->index}}{{ $rel['name'] }}" style="width:100%;height:100%"></div>
                    @endif                     
                    <a href="{{ URL::to('/') }}/{{config('app.defaultBaseURL.indian-movie')}}/{{ $rel['urlName'] }}" title="{{ $rel['name'] }}" ><h2 class="content11 colorh1">{{ $rel['name'] }}</h2></a>                                                      
                <div class="content2">{{ CommonController::getLanguage($rel['language']) }}</div>
            </div>
        @endforeach


    </div>
    <div class="col-md-3 rightcontainer nopadding">
        <div class="ad250x250"><img alt="ad"  width="100%" height="100%" src="{{ URL::to('/') }}/image/sideBanner.svg"/></div>
    </div>  
</div>    
</div> 

    
    
    <script>
        function movieSearch() {
            var type        =   document.getElementById("type").value;
            var city        =   document.getElementById("city").value;
            var keyword     =   document.getElementById("searchKeyword").value;
            var urlParm     =   '';            
            urlParm = "{{ URL::to('/') }}/{{config('app.defaultBaseURL.movie-search')}}/"+type+"/"+city+"/"+keyword;
            window.location.href = urlParm;
        } 
    </script>

@endsection
