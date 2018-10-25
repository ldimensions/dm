@extends('layouts.app')
@section('content')

<div class="mcontainer">
<div class="maincontainer">
    <div class="leftcontainer">
        <div class="col-md-12 searchbar hiddepadding"> <!-- <a href="#" class="selocation"></a> -->
            <form>
                <select name="type" class="select" id="type">
                    <option 
                        value="{{config('app.defaultBaseURL.dallas-indian-grocery-store')}}-2" 
                        {{ config('app.defaultBaseURL.dallas-indian-grocery-store').'-2' == $type ? 'selected="selected"' : '' }}>
                            All
                    </option>
                    <option 
                        value="{{config('app.defaultBaseURL.dallas-indian-grocery-store')}}-2"
                        {{ config('app.defaultBaseURL.dallas-indian-grocery-store').'-2' == $type ? 'selected="selected"' : '' }}>
                            Indian
                    </option>
                    <option 
                        value="{{config('app.defaultBaseURL.dallas-kerala-grocery-store')}}-1"
                        {{ config('app.defaultBaseURL.dallas-kerala-grocery-store').'-1' == $type ? 'selected="selected"' : '' }}>
                            Kerala
                    </option>
                    <option 
                        value="{{config('app.defaultBaseURL.dallas-tamil-grocery-store')}}-3"
                        {{ config('app.defaultBaseURL.dallas-tamil-grocery-store').'-3' == $type ? 'selected="selected"' : '' }}>
                            Tamil
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
        <!-- <div class="col-md-12 paggination">
             <div class="count">1 to xx of xx Groceries</div> 
            <div class="pagecount">Page: 1 of 1</div> 
            <div class="pagecount">&nbsp;</div>
        </div> -->
        @if (count($grocery) == 0)
            <div class="col-md-12 block1">
            Suggestions for improving the results:<br/>
            Try a different location.<br/>
            Check the spelling or try alternate spellings.<br/>
            </div>
        @endif   
        @foreach ($grocery as $key => $rel)
            <div class="col-md-12 block1">
                    <div class="smallImage">
                    @if (isset($rel['photoName']) && $rel['photoName'])
                        <img src="{{ URL::to('/') }}/image/grocery/{{$rel['id']}}/{{$rel['photoName']}}" alt="{{$loop->index}}{{ $rel['name'] }}" style="width:100%;height:100%"></div>
                    @else   
                        <img src="{{ URL::to('/') }}/image/noimage.svg" alt="{{$loop->index}}{{ $rel['name'] }}" style="width:100%;height:100%"></div>
                    @endif                  
                    <h2 class="content1"><a href="{{ URL::to('/') }}/{{config('app.defaultBaseURL.grocery-store-details')}}/{{ $rel['urlName'] }}" title="{{ $rel['name'] }}" class="colorh1">{{ $rel['name'] }}</a></h2>                                                      
                    @if ($rel['latitude'] && $rel['longitude'])
                        <a href="https://www.google.com/maps/dir//{{ $rel['name'] }} {{ $rel['address1'] }} {{ $rel['city'] }}, {{ $rel['state'] }} {{ $rel['zip'] }}/%40{{$rel['latitude']}},{{$rel['longitude']}},12z" title="{{ $rel['name'] }}" target="_blank" class="mapicon"><img src="{{ URL::to('/') }}/image/map1.svg" alt="{{$loop->index}}{{ $rel['name'] }}"/></a>
                    @endif    
                    <div class="content2">{{ $rel['address1'] }}@if (!$rel['address2']),@endif @if ($rel['address2']){{ $rel['address2'] }},@endif {{ $rel['city'] }}, {{ $rel['state'] }} {{ $rel['zip'] }}</div>
                    <span class="content3 "><a href="tel:{{ $rel['phone1'] }}" class="h21">{{ $rel['phone1'] }}</a></span>
                    @if (isset($rel['distance']) && $rel['distance'])
                        <div class="gro_kmblock_list">{{ $rel['distance'] }}</div>
                    @endif                 
                    <!-- <div class="open_close">Closed - Open 7 AM</div> -->
            </div>
        @endforeach

        {{ $grocery->links() }}

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
