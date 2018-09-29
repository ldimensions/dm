@extends('layouts.app')
@section('content')
<div class="mcontainer">
    <div class="maincontainer">
        <div class="leftcontainer space1">
            <!-- <div class="col-md-12 searchbar hiddepadding">
                <a href="#" class="selocation"></a>
                <form>
                    <input name="Location" type="text" class="text locationimage" id="Location" placeholder="Location" readonly="readonly" >
                    <select name="Categories" class="select" id="Categories">
                        <option>All</option>
                        <option>Groceries</option>
                    </select>
                    <input type="text" id="Keywords" name="Keywords" placeholder="Keywords">
                    <a href="#" class="search">Search</a>
                </form>
            </div> -->
            <div class="col-md-4 block3">
                <div class="gro_block3">Top 3 Groceries</div>
                    @foreach ($grocery as $key => $rel)
                        <div class="main_block">
                            <a href="../{{config('app.defaultBaseURL.grocery-store-details')}}/{{ $rel['urlName'] }}"class="block_txtblock"><h1 class="text2">{{ $rel['name'] }}</h2></a>                                                          
                            <a href="https://www.google.com/maps/dir//{{ $rel['urlName'] }}/%40{{$rel['latitude']}},{{$rel['longitude']}},12z" target="_blank" class="mapicon1"><img alt="{{$loop->index}}{{ $rel['name'] }}" src="image/map.svg" /></a>
                            <div class="block_kmblock">
                            <!-- @if (isset($rel['distance']) && $rel['distance'])
                                    <div class="gro_kmblock">{{$rel['distance']}}</div>
                                @endif -->  
                                <div class="txtblock">{{$rel['city']}}, {{$rel['zip']}}</div>
                                <a href="tel:{{ $rel['phone1'] }}" class="txtblock1 h21">{{ $rel['phone1'] }}</a>
                            </div>
                            <div class="bottomborder"></div>
                        </div>
                    @endforeach
                    <div class="gro_block3bottom"></div>
                </div>

                <div class="col-md-4 block3">
                    <div class="re_block3">Top 3 Restaurants</div>
                    @foreach ($restaurants as $key => $rel)
                        <div class="main_block">
                            <a href="../{{config('app.defaultBaseURL.dallas-indian-restaurant')}}/{{ $rel['urlName'] }}" class="block_txtblock"><h1 class="text2">{{ $rel['name'] }}</h1></a>                                                          
                            <a href="https://www.google.com/maps/dir//{{ $rel['urlName'] }}/%40{{$rel['latitude']}},{{$rel['longitude']}},12z" target="_blank" class="mapicon1"><img alt="{{$loop->index}}{{ $rel['name'] }}" src="image/map.svg" /></a>
                            <div class="block_kmblock">
                            <!-- @if (isset($rel['distance']) && $rel['distance'])
                                    <div class="re_kmblock">{{$rel['distance']}}</div>
                                @endif   -->
                                <div class="txtblock">{{$rel['city']}}, {{$rel['zip']}}</div>
                                <a href="tel:{{ $rel['phone1'] }}" class="txtblock1 h21">{{ $rel['phone1'] }}</a>
                            </div>
                            <div class="bottomborder"></div>
                        </div>
                    @endforeach                
                    <div class="re_block3bottom"></div>
                </div>

                <div class="col-md-4 block3">
                    <div class="reli_block3">Top 3 Religions</div>
                    @foreach ($religion as $key => $rel)
                        <div class="main_block">
                            @if ($rel['religionName'] == 'Christianity')
                                <a href="../{{config('app.defaultBaseURL.dallas-christian-church')}}/{{ $rel['urlName'] }}" class="block_txtblock"><h1 class="text2">{{ $rel['name'] }}</h1></a>
                            @elseif($rel['religionName'] == 'Hinduism')
                                <a href="../{{config('app.defaultBaseURL.dallas-hindu-temple')}}/{{ $rel['urlName'] }}" class="block_txtblock"><h1 class="text2">{{ $rel['name'] }}</h1></a>
                            @elseif($rel['religionName'] == 'Judaism')
                                <a href="../{{config('app.defaultBaseURL.dallas-hindu-temple')}}/{{ $rel['urlName'] }}" class="block_txtblock"><h1 class="text2">{{ $rel['name'] }}</h1></a>
                            @elseif($rel['religionName'] == 'Buddhism')
                                <a href="../{{config('app.defaultBaseURL.dallas-hindu-temple')}}/{{ $rel['urlName'] }}" class="block_txtblock"><h1 class="text2">{{ $rel['name'] }}</h1></a>
                            @elseif($rel['religionName'] == 'Islam')
                                <a href="../{{config('app.defaultBaseURL.dallas-islan-mosque')}}/{{ $rel['urlName'] }}" class="block_txtblock"><h1 class="text2">{{ $rel['name'] }}</h1></a>                                                          
                            @endif
                            <a href="https://www.google.com/maps/dir//{{ $rel['urlName'] }}/%40{{$rel['latitude']}},{{$rel['longitude']}},12z" target="_blank" class="mapicon1"><img alt="{{$loop->index}}{{ $rel['name'] }}" src="image/map.svg" /></a>
                            <div class="block_kmblock">
                                <!-- @if (isset($rel['distance']) && $rel['distance'])
                                    <div class="reli_kmblock">{{$rel['distance']}}</div>
                                @endif    -->
                                <div class="txtblock">{{$rel['city']}}, {{$rel['zip']}}</div>
                                <a href="tel:{{ $rel['phone1'] }}" class="txtblock1 h21">{{ $rel['phone1'] }}</a>
                            </div>
                            <div class="bottomborder"></div>
                        </div>
                    @endforeach
                    <div class="reli_block3bottom"></div>
                </div>


            </div>
            <div class="col-md-3 rightcontainer nopadding">
            <div class="ad250x250">ADVERTISE HERE</div>
        </div>
    </div>
</div>    
@endsection
