@extends('layouts.app')

@section('content')
<div class="col-md-9 leftcontainer">
    <div class="col-md-12 searchbar hiddepadding"> <a href="#" class="selocation"></a>
        <form>
            <input name="Location" type="text" class="text locationimage" id="Location" placeholder="Location" readonly="readonly" >
            <select name="type" class="select" id="type">
                <option 
                    value="{{config('app.defaultBaseURL.dallas-indian-religion')}}" 
                    {{ config('app.defaultBaseURL.dallas-indian-religion') == $type ? 'selected="selected"' : '' }}>
                        All
                </option>
                <option 
                    value="{{config('app.defaultBaseURL.dallas-malayali-church')}}-1"
                    {{ config('app.defaultBaseURL.dallas-malayali-church').'-1' == $type ? 'selected="selected"' : '' }}>
                        Christianity
                </option>
                <option 
                    value="{{config('app.defaultBaseURL.dallas-malayali-temple')}}-2"
                    {{ config('app.defaultBaseURL.dallas-malayali-temple').'-2' == $type ? 'selected="selected"' : '' }}>
                        Hinduism
                </option>
                <option 
                    value="{{config('app.defaultBaseURL.dallas-malayali-mosque')}}-5"
                    {{ config('app.defaultBaseURL.dallas-malayali-mosque').'-5' == $type ? 'selected="selected"' : '' }}>
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
    <div class="col-md-12 paggination">
        <!-- <div class="count">1 to xx of xx Groceries</div> -->
        <!-- <div class="pagecount">Page: 1 of 1</div> -->
        <div class="pagecount">&nbsp;</div>
    </div>
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
                <div class="content1"> 
                    @if ($rel['religionName'] == 'Christianity')
                        <a href="{{ URL::to('/') }}/{{config('app.defaultBaseURL.dallas-malayali-church')}}/{{ $rel['urlName'] }}" class="title">{{ $rel['name'] }}</a><br/>
                    @elseif($rel['religionName'] == 'Hinduism')
                        <a href="{{ URL::to('/') }}/{{config('app.defaultBaseURL.dallas-malayali-temple')}}/{{ $rel['urlName'] }}" class="title">{{ $rel['name'] }}</a><br/>
                    @elseif($rel['religionName'] == 'Judaism')
                        <a href="{{ URL::to('/') }}/{{config('app.defaultBaseURL.dallas-malayali-temple')}}/{{ $rel['urlName'] }}" class="title">{{ $rel['name'] }}</a><br/>
                    @elseif($rel['religionName'] == 'Buddhism')
                        <a href="{{ URL::to('/') }}/{{config('app.defaultBaseURL.dallas-malayali-temple')}}/{{ $rel['urlName'] }}" class="title">{{ $rel['name'] }}</a><br/>
                    @elseif($rel['religionName'] == 'Islam')
                        <a href="{{ URL::to('/') }}/{{config('app.defaultBaseURL.dallas-malayali-mosque')}}/{{ $rel['urlName'] }}" class="title">{{ $rel['name'] }}</a><br/>                                                           
                    @endif
                    <!-- <span>{{ str_limit($rel['shortDescription'], 100) }}</span>  -->
                </div>
                <a href="https://www.google.com/maps/dir/{{$rel['latitude']}},{{$rel['longitude']}}" target="_blank" class="mapicon"><img src="{{ URL::to('/') }}/image/map1.svg" alt="{{$loop->index}}{{ $rel['name'] }}"/></a>
                <div class="content2"> <span class="subcontent1">Address:</span> <span class="subcontent2">{{ $rel['address1'] }} {{ $rel['address2'] }}, {{ $rel['city'] }}, {{ $rel['state'] }} {{ $rel['zip'] }}</span> </div>
                <div class="content3"> <span class="subcontent1">Phone:</span> <span class="subcontent2"><a href="tel:{{ $rel['phone1'] }}">{{ $rel['phone1'] }}</a></span> </div>
                <div class="content3"> <span class="subcontent1">Located In:</span> <span class="subcontent2">{{ $rel['city'] }}</span> </div>
                @if (isset($rel['distance']) && $rel['distance'])
                    <div class="gro_kmblock_list">Distance : {{ $rel['distance'] }}</div>
                @endif                 
                <!-- <div class="open_close">Closed - Open 7 AM</div> -->
        </div>
    @endforeach

</div>
<div class="col-md-3 rightcontainer"></div>

<script>
        function religionSearch() {
            var type        =   document.getElementById("type").value;
            var city        =   document.getElementById("city").value;
            var keyword     =   document.getElementById("searchKeyword").value;
            var urlParm     =   '';
            if(city && city != 'all'){
                if(type == "{{config('app.defaultBaseURL.dallas-malayali-church')}}-1"){
                    city        =   'malayali-church-in-'+city;
                }else if(type == "{{config('app.defaultBaseURL.dallas-malayali-temple')}}-2"){
                    city        =   'malayali-temple-in-'+city;
                }else if(type == "{{config('app.defaultBaseURL.dallas-malayali-mosque')}}-5"){
                    city        =   'malayali-mosque-in-'+city;
                }else{
                    city        =   'all';
                }
            }else{
                city        =   'all';
            }
            urlParm = "{{ URL::to('/') }}/{{config('app.defaultBaseURL.religion-search')}}/"+type+"/"+city+"/"+keyword;
            window.location.href = urlParm;
        } 
    </script>
@endsection
