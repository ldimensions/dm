@extends('layouts.app')

@section('content')

    <div class="col-md-9 leftcontainer">
        <div class="col-md-12 searchbar hiddepadding"> <a href="#" class="selocation"></a>
            <form>
                <input name="Location" type="text" class="text locationimage" id="Location" placeholder="Location" readonly="readonly" >
                <select name="Type" class="select" id="Type">
                    <option>All</option>
                    <option>Type 1</option>
                </select>
                <select name="Distance" class="select" id="Distance">
                    <option>All</option>
                    <option>25 KM</option>
                    <option>50 KM</option>
                    <option>75 KM</option>
                    <option>100 KM</option>
                </select>
                <input type="text" id="Keywords" name="Keywords" placeholder="Keywords" class="text1">
                <a href="#" class="search">Search</a>
            </form>
        </div>
        <div class="col-md-12 paggination">
            <div class="count">1 to xx of xx Groceries</div>
            <div class="pagecount">Page: 1 of 1</div>
        </div>
        <div class="col-md-12 block1">
            <div class="smallImage"><img src="image/noimage.svg" /></div>
            <div class="content1"> 
                <a href="Details.html" class="title">Whole Food Market</a></br>
                <span>Eco-minded chain with natural & organic grocery items, housewares & other products (most sell wine).</span> 
            </div>
            <a href="Details.html" class="mapicon"><img src="image/map1.svg" /></a>
            <div class="content2"> <span class="subcontent1">Address:</span> <span class="subcontent2">Westfield Countryside Mall, 27001 US Hwy 19 N, Clearwater, FL 33761, USA</span> </div>
            <div class="content3"> <span class="subcontent1">Phone:</span> <span class="subcontent2">+1 727-724-7100</span> </div>
            <div class="content3"> <span class="subcontent1">Located In:</span> <span class="subcontent2">Westfield Countryside</span> </div>
            <div class="gro_kmblock_list">11.0 KM</div>
            <div class="open_close">Closed - Open 7 AM</div>
        </div>
    </div>
    <div class="col-md-3 rightcontainer"></div>


@endsection
