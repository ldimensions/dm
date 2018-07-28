@extends('layouts.app')

@section('content')

    <div class="col-md-9 leftcontainer">
        <div class="col-md-12 searchbar hiddepadding"> <a href="#" class="selocation"></a>
            <form>
                <input name="Location" type="text" class="text locationimage" id="Location" placeholder="Location" readonly="readonly" >
                <select name="type" class="select" id="type">
                    <option value="{{config('app.defaultBaseURL.dallas-indian-restaurant')}}">All</option>
                    <option value="{{config('app.defaultBaseURL.dallas-indian-restaurant')}}">Indian</option>
                    <option value="{{config('app.defaultBaseURL.dallas-kerala-restaurant')}}">Kerala</option>
                    <option value="{{config('app.defaultBaseURL.dallas-tamil-restaurant')}}">Tamil</option>
                </select>
                <select name="city" class="select" id="city">
                    <option value="">All</option>
                        @foreach ($cities as $key => $city)
                            <option value="{{$city['value']}}-{{config('app.defaultBaseURL.indian-grocery-store')}}">{{$city['city']}}</option>
                        @endforeach
                    </select>
                <input type="text" id="Keywords" name="Keywords" placeholder="Keywords" class="text1">
                <a href="JavaScript:void(0)" class="search" onclick="grocerySearch()">Search</a>
            </form>
        </div>
        <div class="col-md-12 paggination">
            <!-- <div class="count">1 to xx of xx Groceries</div> -->
            <!-- <div class="pagecount">Page: 1 of 1</div> -->
            <div class="pagecount">&nbsp;</div>
        </div>
        @foreach ($restaurant as $key => $rel)
        <div class="col-md-12 block1">
                <div class="smallImage">
                @if (isset($rel['photoName']) && $rel['photoName'])
                    <img src="{{ URL::to('/') }}/image/restaurant/{{$rel['id']}}/{{$rel['photoName']}}" alt="{{$loop->index}}{{ $rel['name'] }}" style="width:100%;height:100%"></div>
                @else
                    <img src="{{ URL::to('/') }}/image/noimage.svg" alt="{{$loop->index}}{{ $rel['name'] }}" style="width:100%;height:100%"></div>
                @endif                 
                <div class="content1"> 
                    <a href="../{{config('app.defaultBaseURL.dallas-indian-restaurant')}}/{{ $rel['urlName'] }}" class="title">{{ $rel['name'] }}</a><br/>                                                           
                </div>
                <div class="content3"> <span class="subcontent1">Ethnicity:</span> <span class="subcontent2">{{ $rel['ethnicName'] }}</span> </div>
                <a href="https://www.google.com/maps/dir/{{$rel['latitude']}},{{$rel['longitude']}}" target="_blank" class="mapicon"><img src="{{ URL::to('/') }}/image/map1.svg" alt="{{$loop->index}}{{ $rel['name'] }}"/></a>
                <div class="content2"> <span class="subcontent1">Address:</span> <span class="subcontent2">{{ $rel['address1'] }} {{ $rel['address2'] }}, {{ $rel['city'] }}, {{ $rel['state'] }} {{ $rel['zip'] }}</span> </div>
                <div class="content3"> <span class="subcontent1">Phone:</span> <span class="subcontent2"><a href="tel:{{ $rel['phone1'] }}">{{ $rel['phone1'] }}</a></span> </div>
                <div class="content3"> <span class="subcontent1">Located In:</span> <span class="subcontent2">{{ $rel['city'] }}</span> </div>
                @if (isset($rel['distance']) && $rel['distance'])
                    <div class="gro_kmblock_list">Distance : {{ $rel['distance'] }}</div>
                @endif                 
                <div class="open_close">Closed - Open 7 AM----</div>
        </div>
    @endforeach
    </div>
    <div class="col-md-3 rightcontainer"></div>
    
    <script>
        function grocerySearch() {
            var type = document.getElementById("type").value;
            var city = document.getElementById("city").value;
            var urlParm = '';
            //if(type != 'all'){
            urlParm = "{{ URL::to('/') }}/"+type+"/"+city;
            //}
            if(city != 'all'){
                if(urlParm){
                    //urlParm = urlParm+"/"+city;
                }else{
                    //urlParm = "{{ URL::to('/') }}/"+city;
                }
            }
            window.location.href = urlParm;
        }    
    </script>


@endsection
