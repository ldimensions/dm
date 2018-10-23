@extends('layouts.app')
@section('content')

<div class="mcontainer">
<div class="maincontainer">
    <div class="leftcontainer">
        <div class="col-md-12 searchbar hiddepadding">
            <form>
                <select name="type" class="select" id="type">
                    <option>
                            All
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
                <a href="JavaScript:void(0)" class="search" onclick="grocerySearch()">Search</a>
            </form>
        </div>
       
            <div class="col-md-12 block1">
                    <div class="smallImage">
                        <img src="{{ URL::to('/') }}/image/noimage.svg" alt="" style="width:100%;height:100%"></div>
                        <a href="{{ URL::to('/') }}/{{config('app.defaultBaseURL.dallas-indian-movie')}}/hardcoded" title="" ><h2 class="content11 colorh1">Name</h2></a>                                                      
                    <div class="content2">Language</div>
            </div>
    

    </div>
    <div class="col-md-3 rightcontainer nopadding">
        <div class="ad250x250"><img alt="ad"  width="100%" height="100%" src="{{ URL::to('/') }}/image/sideBanner.svg"/></div>
    </div>  
</div>    
</div> 

    
    
    <script>
        function grocerySearch() {
            var type        =   document.getElementById("type").value;
            var city        =   document.getElementById("city").value;
            var keyword     =   document.getElementById("searchKeyword").value;
            var urlParm     =   '';
            if(city && city != 'all'){
                if(type == "{{config('app.defaultBaseURL.dallas-indian-grocery-store')}}-2"){
                    city        =   "{{config('app.defaultBaseURL.indian-grocery-store-in')}}"+city;
                }else if(type == "{{config('app.defaultBaseURL.dallas-kerala-grocery-store')}}-1"){
                    city        =   "{{config('app.defaultBaseURL.kerala-grocery-store-in')}}"+city;
                }else if(type == "{{config('app.defaultBaseURL.dallas-tamil-grocery-store')}}-3"){
                    city        =   "{{config('app.defaultBaseURL.tamil-grocery-in')}}"+city;
                }else{
                    city        =   'all';
                }
            }else{
                city        =   'all';
            }
            urlParm = "{{ URL::to('/') }}/{{config('app.defaultBaseURL.grocery-search')}}/"+type+"/"+city+"/"+keyword;
            window.location.href = urlParm;
        } 
    </script>

@endsection
