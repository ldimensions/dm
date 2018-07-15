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
                <div class="reli_block3">Top 3 Religions</div>
                <div class="main_block">
                    <a href="#" class="block_txtblock">University Presbyterian Church</a>
                    <a href="#" class="mapicon1"><img src="image/map.svg" /></a>
                    <div class="block_kmblock">
                        <div class="reli_kmblock">11.0 KM</div>
                        <div class="txtblock">Closed - Opens 7 AM</br>+1 901-896-3245</div>
                    </div>
                    <div class="bottomborder"></div>
                </div>
                <div class="main_block">
                    <a href="#" class="block_txtblock">Bel Air Presbyterian Church</a>
                    <a href="#" class="mapicon1"><img src="image/map.svg" /></a>
                    <div class="block_kmblock">
                        <div class="reli_kmblock">11.0 KM</div>
                        <div class="txtblock">Closed - Opens 7 AM</br>+1 901-896-3245</div>
                    </div>
                    <div class="bottomborder"></div>
                </div>
                <div class="main_block">
                    <a href="#" class="block_txtblock">Fellowship Church Grapevine Campus</a>
                    <a href="#" class="mapicon1"><img src="image/map.svg" /></a>
                    <div class="block_kmblock">
                        <div class="reli_kmblock">11.0 KM</div>
                        <div class="txtblock">Closed - Opens 7 AM</br>+1 901-896-3245</div>
                    </div>
                </div>
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
