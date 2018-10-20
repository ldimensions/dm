@extends('layouts.app')

@section('content')


<div class="mcontainer">
<div class="maincontainer">
    <div class="leftcontainer">
    <div class="col-md-12 searchbar hiddepadding">
        <form>
            <select name="type" class="select" id="type">
                <option 
                    value="{{config('app.defaultBaseURL.dallas-indian-religion')}}" 
                    {{ config('app.defaultBaseURL.dallas-indian-religion') == $type ? 'selected="selected"' : '' }}>
                        All
                </option>
                <option 
                    value="{{config('app.defaultBaseURL.dallas-christian-church')}}-1"
                    {{ config('app.defaultBaseURL.dallas-christian-church').'-1' == $type ? 'selected="selected"' : '' }}>
                        Christianity
                </option>
                <option 
                    value="{{config('app.defaultBaseURL.dallas-hindu-temple')}}-2"
                    {{ config('app.defaultBaseURL.dallas-hindu-temple').'-2' == $type ? 'selected="selected"' : '' }}>
                        Hinduism
                </option>
                <option 
                    value="{{config('app.defaultBaseURL.dallas-islan-mosque')}}-5"
                    {{ config('app.defaultBaseURL.dallas-islan-mosque').'-5' == $type ? 'selected="selected"' : '' }}>
                        Islam
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
            <a href="JavaScript:void(0)" class="search" onclick="religionSearch()">Search</a>
        </form>
    </div>
    <!-- <div class="col-md-12 paggination">
        <div class="count">1 to xx of xx Groceries</div>
        <div class="pagecount">Page: 1 of 1</div>
        <div class="pagecount">&nbsp;</div>
    </div> -->
    @if (count($religion) == 0)
        <div class="col-md-12 block1">
            Suggestions for improving the results:<br/>
            Try a different location.<br/>
            Check the spelling or try alternate spellings.<br/>
        </div>
    @endif    
    @foreach ($religion as $key => $rel)
        <div class="col-md-12 block1">
                <div class="smallImage">
                @if (isset($rel['photoName']) && $rel['photoName'])
                    <img src="{{ URL::to('/') }}/image/religion/{{$rel['id']}}/{{$rel['photoName']}}" alt="{{$loop->index}}{{ $rel['name'] }}" style="width:100%;height:100%"></div>
                @else
                    <img src="{{ URL::to('/') }}/image/noimage.svg" alt="{{$loop->index}}{{ $rel['name'] }}" style="width:100%;height:100%"></div>
                @endif         
                @if ($rel['religionName'] == 'Christianity')
                    <a href="{{ URL::to('/') }}/{{config('app.defaultBaseURL.dallas-christian-church')}}/{{ $rel['urlName'] }}"><h2 class="content4 colorh1"> {{ $rel['name'] }}</h2></a>
                @elseif($rel['religionName'] == 'Hinduism')
                    <a href="{{ URL::to('/') }}/{{config('app.defaultBaseURL.dallas-hindu-temple')}}/{{ $rel['urlName'] }}"><h2 class="content4 colorh1"> {{ $rel['name'] }}</h2></a>
                @elseif($rel['religionName'] == 'Judaism')
                    <!-- <a href="{{ URL::to('/') }}/{{config('app.defaultBaseURL.dallas-malayali-temple')}}/{{ $rel['urlName'] }}" ><h2 class="content4 colorh1"> {{ $rel['name'] }}</h2></a> -->
                @elseif($rel['religionName'] == 'Buddhism')
                    <!-- <a href="{{ URL::to('/') }}/{{config('app.defaultBaseURL.dallas-malayali-temple')}}/{{ $rel['urlName'] }}"><h2 class="content4 colorh1"> {{ $rel['name'] }}</h2></a> -->
                @elseif($rel['religionName'] == 'Islam')
                    <a href="{{ URL::to('/') }}/{{config('app.defaultBaseURL.dallas-islan-mosque')}}/{{ $rel['urlName'] }}"><h2 class="content4 colorh1"> {{ $rel['name'] }}</h2></a>                                                          
                @endif
                @if ($rel['latitude'] && $rel['longitude'])
                    <a href="https://www.google.com/maps/dir//{{ $rel['name'] }} {{ $rel['address1'] }} {{ $rel['city'] }}, {{ $rel['state'] }} {{ $rel['zip'] }}/%40{{$rel['latitude']}},{{$rel['longitude']}},12z" target="_blank" class="mapicon"><img src="{{ URL::to('/') }}/image/map1.svg" alt="{{$loop->index}}{{ $rel['name'] }}"/></a>
                @endif  
                <div class="content2">{{ $rel['address1'] }} {{ $rel['address2'] }}, {{ $rel['city'] }}, {{ $rel['state'] }} {{ $rel['zip'] }}</div>
                <a href="tel:{{ $rel['phone1'] }}" class="content3 h21">{{ $rel['phone1'] }}</a>
                @if (isset($rel['distance']) && $rel['distance'])
                    <div class="reli_kmblock_list">{{ $rel['distance'] }}</div>
                @endif                 
        </div>
    @endforeach
    
    {{ $religion->links() }}

    </div>
    <div class="rightcontainer">
    <div class="ad250x250"><img alt="ad"  width="100%" height="100%" src="{{ URL::to('/') }}/image/sideBanner.svg"/></div>
    </div>
</div>
</div>


<script>
        function religionSearch() {
            var type        =   document.getElementById("type").value;
            var city        =   document.getElementById("city").value;
            var keyword     =   document.getElementById("searchKeyword").value;
            var urlParm     =   '';
            if(city && city != 'all'){
                if(type == "{{config('app.defaultBaseURL.dallas-christian-church')}}-1"){
                    city        =   "{{config('app.defaultBaseURL.christian-church-in')}}"+city;
                }else if(type == "{{config('app.defaultBaseURL.dallas-hindu-temple')}}-2"){
                    city        =   "{{config('app.defaultBaseURL.hindu-temple-in')}}"+city;
                }else if(type == "{{config('app.defaultBaseURL.dallas-islan-mosque')}}-5"){
                    city        =   "{{config('app.defaultBaseURL.islam-mosque-in')}}"+city;                    
                }else{
                    city        =   city;
                }
            }else{
                city        =   'all';
            }
            urlParm = "{{ URL::to('/') }}/{{config('app.defaultBaseURL.religion-search')}}/"+type+"/"+city+"/"+keyword;
            window.location.href = urlParm;            
        } 
    </script>
@endsection
