@extends('layouts.app')

@section('content')

    <div class="col-md-9 leftcontainer">
        <div class="col-md-12 searchbar hiddepadding">
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
        </div>
        <div class="col-md-4 block3">
            <div class="gro_block3">Top 3 Groceries</div>
            <div class="main_block">
                <a href="Details.html" class="block_txtblock">Whole Food Market</a>
                <a href="Details.html" class="mapicon1"><img src="image/map.svg" /></a>
                <div class="block_kmblock">
                    <div class="gro_kmblock">11.0 KM</div>
                        <div class="txtblock">Closed - Opens 7 AM</br>+1 901-896-3245</div>
                    </div>
                    <div class="bottomborder"></div>
                </div>
                <div class="main_block">
                    <a href="#" class="block_txtblock">GIANT Food Stores</a>
                    <a href="#" class="mapicon1"><img src="image/map.svg" /></a>
                    <div class="block_kmblock">
                        <div class="gro_kmblock">11.0 KM</div>
                        <div class="txtblock">Closed - Opens 7 AM</br>+1 901-896-3245</div>
                    </div>
                    <div class="bottomborder"></div>
                </div>
                <div class="main_block">
                    <a href="#" class="block_txtblock">Food Country USA</a>
                    <a href="#" class="mapicon1"><img src="image/map.svg" /></a>
                    <div class="block_kmblock">
                        <div class="gro_kmblock">11.0 KM</div>
                        <div class="txtblock">Closed - Opens 7 AM</br>+1 901-896-3245</div>
                    </div>
                </div>
                <div class="gro_block3bottom"></div>
            </div>

            <div class="col-md-4 block3">
                <div class="re_block3">Top 3 Restaurants</div>
                <div class="main_block">
                    <a href="#" class="block_txtblock">Branded Restaurants USA</a>
                    <a href="#" class="mapicon1"><img src="image/map.svg" /></a>
                    <div class="block_kmblock">
                        <div class="re_kmblock">11.0 KM</div>
                        <div class="txtblock">Closed - Opens 7 AM</br>+1 901-896-3245</div>
                    </div>
                    <div class="bottomborder"></div>
                </div>
                <div class="main_block">
                    <a href="#" class="block_txtblock">USA Family Restaurant</a>
                    <a href="#" class="mapicon1"><img src="image/map.svg" /></a>
                    <div class="block_kmblock">
                        <div class="re_kmblock">11.0 KM</div>
                        <div class="txtblock">Closed - Opens 7 AM</br>+1 901-896-3245</div>
                    </div>
                    <div class="bottomborder"></div>
                </div>
                <div class="main_block">
                    <a href="#" class="block_txtblock">Quad Cities USA Family Restaurant</a>
                    <a href="#" class="mapicon1"><img src="image/map.svg" /></a>
                    <div class="block_kmblock">
                        <div class="re_kmblock">11.0 KM</div>
                        <div class="txtblock">Closed - Opens 7 AM</br>+1 901-896-3245</div>
                    </div>
                </div>
                <div class="re_block3bottom"></div>
            </div>

            <div class="col-md-4 block3">
                <div class="reli_block3">Religions</div>
                @foreach ($religion as $key => $rel)
                    <div class="main_block">
                        @if ($rel['religionName'] == 'Christianity')
                            <a href="../{{config('app.defaultBaseURL.dallas-malayali-church')}}/{{ $rel['urlName'] }}" class="block_txtblock">{{ $rel['name'] }}</a>
                        @elseif($rel['religionName'] == 'Hinduism')
                            <a href="../{{config('app.defaultBaseURL.dallas-malayali-temple')}}/{{ $rel['urlName'] }}" class="block_txtblock">{{ $rel['name'] }}</a>
                        @elseif($rel['religionName'] == 'Judaism')
                            <a href="../{{config('app.defaultBaseURL.dallas-malayali-temple')}}/{{ $rel['urlName'] }}" class="block_txtblock">{{ $rel['name'] }}</a>
                        @elseif($rel['religionName'] == 'Buddhism')
                            <a href="../{{config('app.defaultBaseURL.dallas-malayali-temple')}}/{{ $rel['urlName'] }}" class="block_txtblock">{{ $rel['name'] }}</a>
                        @elseif($rel['religionName'] == 'Islam')
                            <a href="../{{config('app.defaultBaseURL.dallas-malayali-mosque')}}/{{ $rel['urlName'] }}" class="block_txtblock">{{ $rel['name'] }}</a>                                                          
                        @endif
                        <a href="https://www.google.com/maps/dir/{{$rel['latitude']}},{{$rel['longitude']}}" target="_blank" class="mapicon1"><img {{$loop->index}}{{ $rel['name'] }} src="image/map.svg" /></a>
                        <div class="block_kmblock">
                            @if (isset($rel['distance']) && $rel['distance'])
                                <div class="reli_kmblock">{{$rel['distance']}}</div>
                            @endif   
                            <div class="txtblock">{{$rel['city']}}, {{$rel['zip']}}</br>{{$rel['phone1']}}</div>
                        </div>
                        <div class="bottomborder"></div>
                    </div>
                @endforeach
                <div class="reli_block3bottom"></div>
            </div>

            <div class="col-md-4 block3">
                <div class="auto_block3">Top 3 Automotive</div>
                <div class="main_block">
                    <a href="#" class="block_txtblock">Automotive USA</a>
                    <a href="#" class="mapicon1"><img src="image/map.svg" /></a>
                    <div class="block_kmblock">
                        <div class="auto_kmblock">11.0 KM</div>
                        <div class="txtblock">Closed - Opens 7 AM</br>+1 901-896-3245</div>
                    </div>
                    <div class="bottomborder"></div>
                </div>
                <div class="main_block">
                    <a href="#" class="block_txtblock">USA Automotive</a>
                    <a href="#" class="mapicon1"><img src="image/map.svg" /></a>
                    <div class="block_kmblock">
                        <div class="auto_kmblock">11.0 KM</div>
                        <div class="txtblock">Closed - Opens 7 AM</br>+1 901-896-3245</div>
                    </div>
                    <div class="bottomborder"></div>
                </div>
                <div class="main_block">
                    <a href="#" class="block_txtblock">Eissmann Automotive NA Inc</a>
                    <a href="#" class="mapicon1"><img src="image/map.svg" /></a>
                    <div class="block_kmblock">
                        <div class="auto_kmblock">11.0 KM</div>
                        <div class="txtblock">Closed - Opens 7 AM</br>+1 901-896-3245</div>
                    </div>
                </div>
                <div class="auto_block3bottom"></div>
            </div>

        </div>
        <div class="col-md-3 rightcontainer"></div>
    </div>
@endsection
