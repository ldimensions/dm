@extends('layouts.app')

@section('content')

    <div class="col-md-9 leftcontainer">
        <div class="col-md-12 searchbar hiddepadding"> <a href="#" class="selocation"></a>
            <form>
                <input name="Location" type="text" class="text locationimage" id="Location" placeholder="Location" readonly="readonly" >
                <select name="type" class="select" id="type">
                    <option value="all">All</option>
                    <option value="Indian">Indian</option>
                    <option value="Kerala">Kerala</option>
                    <option value="Tamil">Tamil</option>
                </select>
                <select name="city" class="select" id="city">
                    <option value="all">All</option>
                    <option value="Addison">Addison</option>
                    <option value="Balch Springs">Balch Springs</option>
                    <option value="Carrollton">Carrollton</option>
                    <option value="Cedar Hill">Cedar Hill</option>
                    <option value="Cockrell Hill">Cockrell Hill</option>
                    <option value="Combine">Combine </option>
                    <option value="Dallas">Dallas</option>
                    <option value="DeSoto">DeSoto</option>
                    <option value="Duncanville">Duncanville</option>
                    <option value="Farmers Branch">Farmers Branch</option>
                    <option value="Ferris">Ferris</option>
                    <option value="Garland">Garland</option>
                    <option value="Glenn Heights">Glenn Heights</option>
                    <option value="Grand Prairie">Grand Prairie</option>
                    <option value="Grapevine">Grapevine</option>
                    <option value="Highland Park">Highland Park</option>
                    <option value="Hutchins">Hutchins</option>
                    <option value="Irving">Irving</option>
                    <option value="Lancaster">Lancaster</option>
                    <option value="Mesquite">Mesquite</option>
                    <option value="Ovilla">Ovilla</option>
                    <option value="Richardson">Richardson</option>
                    <option value="Rowlett">Rowlett</option>
                    <option value="Sachse">Sachse</option>
                    <option value="Seagoville">Seagoville</option>
                    <option value="Sunnyvale">Sunnyvale</option>
                    <option value="University Park">University Park</option>
                    <option value="Wilmer">Wilmer</option>
                    <option value="Wylie">Wylie</option>
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
        @foreach ($grocery as $key => $rel)
        <div class="col-md-12 block1">
                <div class="smallImage">
                @if (isset($rel['photoName']) && $rel['photoName'])
                    <img src="{{ URL::to('/') }}/image/grocery/{{$rel['id']}}/{{$rel['photoName']}}" alt="{{$loop->index}}{{ $rel['name'] }}" style="width:100%;height:100%"></div>
                @else if
                    <img src="{{ URL::to('/') }}/image/noimage.svg" alt="{{$loop->index}}{{ $rel['name'] }}" style="width:100%;height:100%"></div>
                @endif                 
                <div class="content1"> 
                    <a href="../{{config('app.defaultBaseURL.dallas-malayali-mosque')}}/{{ $rel['urlName'] }}" class="title">{{ $rel['name'] }}</a><br/>                                                           
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
            urlParm = "{{ URL::to('/') }}/dallas-indian-grocery-store/"+type+"/"+city;
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
