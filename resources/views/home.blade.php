@extends('layouts.app')
@section('content')
<?php use App\Http\Controllers\CommonController;?>
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
                            <h1 class="block_txtblock"><a href="../{{config('app.defaultBaseURL.grocery-store-details')}}/{{ $rel['urlName'] }}" class="text2">{{ $rel['name'] }}</a></h2>                                                          
                            <a href="https://www.google.com/maps/dir//{{ $rel['name'] }} {{ $rel['address1'] }} {{ $rel['city'] }}, {{ $rel['state'] }} {{ $rel['zip'] }}/%40{{$rel['latitude']}},{{$rel['longitude']}},12z" target="_blank" class="mapicon1"><img alt="{{$loop->index}}{{ $rel['name'] }}" src="image/map.svg" /></a>
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
                            <h1 class="block_txtblock"><a href="../{{config('app.defaultBaseURL.dallas-indian-restaurant')}}/{{ $rel['urlName'] }}" class="text2">{{ $rel['name'] }}</a></h1>                                                        
                            <a href="https://www.google.com/maps/dir//{{ $rel['name'] }} {{ $rel['address1'] }} {{ $rel['city'] }}, {{ $rel['state'] }} {{ $rel['zip'] }}/%40{{$rel['latitude']}},{{$rel['longitude']}},12z" target="_blank" class="mapicon1"><img alt="{{$loop->index}}{{ $rel['name'] }}" src="image/map.svg" /></a>
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
                            <h1 class="block_txtblock"><a href="../{{config('app.defaultBaseURL.dallas-christian-church')}}/{{ $rel['urlName'] }}" class="text2">{{ $rel['name'] }}</a></h1>
                            @elseif($rel['religionName'] == 'Hinduism')
                            <h1 class="block_txtblock"><a href="../{{config('app.defaultBaseURL.dallas-hindu-temple')}}/{{ $rel['urlName'] }}" class="text2">{{ $rel['name'] }}</a></h1>
                            @elseif($rel['religionName'] == 'Judaism')
                            <h1 class="block_txtblock"><a href="../{{config('app.defaultBaseURL.dallas-hindu-temple')}}/{{ $rel['urlName'] }}" class="text2">{{ $rel['name'] }}</a></h1>
                            @elseif($rel['religionName'] == 'Buddhism')
                            <h1 class="block_txtblock"><a href="../{{config('app.defaultBaseURL.dallas-hindu-temple')}}/{{ $rel['urlName'] }}" class="text2">{{ $rel['name'] }}</a></h1>
                            @elseif($rel['religionName'] == 'Islam')
                            <h1 class="block_txtblock"><a href="../{{config('app.defaultBaseURL.dallas-islan-mosque')}}/{{ $rel['urlName'] }}" class="text2">{{ $rel['name'] }}</a></h1>                                                         
                            @endif
                            <a href="https://www.google.com/maps/dir//{{ $rel['name'] }} {{ $rel['address1'] }} {{ $rel['city'] }}, {{ $rel['state'] }} {{ $rel['zip'] }}/%40{{$rel['latitude']}},{{$rel['longitude']}},12z" target="_blank" class="mapicon1"><img alt="{{$loop->index}}{{ $rel['name'] }}" src="image/map.svg" /></a>
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
                @if (count($movies) > 0)
                    <div class="col-md-4 block3">
                        <div class="re_block4">Top 3 Movies</div>
                        @foreach ($movies as $key => $rel)
                            <div class="main_block1">
                                <h1 class="block_txtblock k_txtblock1"><a href="{{ URL::to('/') }}/{{config('app.defaultBaseURL.indian-movie')}}/{{ $rel['urlName'] }}" title="{{ $rel['name'] }}"  class="text2">{{ $rel['name'] }}</a></h1>                                                        
                                <div class="block_kmblock kmblock1">
                                    <div class="txtblock">{{ CommonController::getLanguage($rel['language']) }}</div>
                                </div>
                                <div class="bottomborder"></div>
                            </div>
                        @endforeach                
                        <div class="re_block4bottom"></div>
                    </div>                
                @endif

            </div>
            <div class="col-md-3 rightcontainer nopadding">
            <div class="ad250x250"><img alt="ad"  width="100%" height="100%" src="{{ URL::to('/') }}/image/sideBanner.svg"/></div>
        </div>
    </div>
</div>    

@endsection
