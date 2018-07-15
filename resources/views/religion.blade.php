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
        @foreach ($religion as $key => $rel)
            <div class="smallImage">
            @if (isset($rel['photoName']) && $rel['photoName'])
                <img src="image/noimage.svg" /></div>
            @else if
                <img src="image/noimage.svg" /></div>
            @endif                 
            <div class="content1"> 
                @if ($rel['religionName'] == 'Christianity')
                    <a href="{{config('app.defaultBaseURL.dallas-malayali-church')}}/{{ $rel['urlName'] }}" class="title">{{ $rel['name'] }}</a></br>
                @elseif($rel['religionName'] == 'Hinduism')
                    <a href="dallas-malayali-temple/{{ $rel['urlName'] }}" class="title">{{ $rel['name'] }}</a></br>
                @elseif($rel['religionName'] == 'Judaism')
                    <a href="dallas-malayali-temple/{{ $rel['urlName'] }}" class="title">{{ $rel['name'] }}</a></br>
                @elseif($rel['religionName'] == 'Buddhism')
                    <a href="dallas-malayali-temple/{{ $rel['urlName'] }}" class="title">{{ $rel['name'] }}</a></br>
                @elseif($rel['religionName'] == 'Islam')
                    <a href="dallas-malayali-mosque/{{ $rel['urlName'] }}" class="title">{{ $rel['name'] }}</a></br>                                                            
                @endif
                <span>{{ str_limit($rel['shortDescription'], 100) }}</span> 
            </div>
            <a href="Details.html" class="mapicon"><img src="image/map1.svg" /></a>
            <div class="content2"> <span class="subcontent1">Address:</span> <span class="subcontent2">{{ $rel['address1'] }} {{ $rel['address2'] }}, {{ $rel['city'] }}, {{ $rel['state'] }} {{ $rel['zip'] }}</span> </div>
            <div class="content3"> <span class="subcontent1">Phone:</span> <span class="subcontent2">{{ $rel['phone1'] }}</span> </div>
            <div class="content3"> <span class="subcontent1">Located In:</span> <span class="subcontent2">{{ $rel['city'] }}</span> </div>
            @if (isset($rel['distance']) && $rel['distance'])
                <div class="gro_kmblock_list">Distance : {{ $rel['distance'] }}</div>
            @endif                 
            <div class="open_close">Closed - Open 7 AM----</div>
        @endforeach
    </div>
</div>
<div class="col-md-3 rightcontainer"></div>
@endsection
