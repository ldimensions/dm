@extends('layouts.app')

@section('content')

    <div class="col-md-9 leftcontainer">
        <div class="col-md-12 searchbar hiddepadding"> <a href="#" class="selocation"></a>
            <form>
                <input name="Location" type="text" class="text locationimage" id="Location" placeholder="Location" readonly="readonly" >
                <select name="type" class="select" id="type">
                    <option 
                        value="{{config('app.defaultBaseURL.dallas-indian-grocery-store')}}-2" 
                        {{ config('app.defaultBaseURL.dallas-indian-grocery-store').'-2' == $type ? 'selected="selected"' : '' }}>
                            All
                    </option>
                    <option 
                        value="{{config('app.defaultBaseURL.dallas-indian-grocery-store')}}-2"
                        {{ config('app.defaultBaseURL.dallas-indian-grocery-store').'-2' == $type ? 'selected="selected"' : '' }}>
                            Indian
                    </option>
                    <option 
                        value="{{config('app.defaultBaseURL.dallas-kerala-grocery-store')}}-1"
                        {{ config('app.defaultBaseURL.dallas-kerala-grocery-store').'-1' == $type ? 'selected="selected"' : '' }}>
                            Kerala
                    </option>
                    <option 
                        value="{{config('app.defaultBaseURL.dallas-tamil-grocery-store')}}-3"
                        {{ config('app.defaultBaseURL.dallas-tamil-grocery-store').'-3' == $type ? 'selected="selected"' : '' }}>
                            Tamil
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
                <input type="text" id="keyword" value="{{$keyword}}" name="Keyword" placeholder="Keywords" class="text1" maxlength="50" pattern="(1[0-2]|0[1-9])\/(1[5-9]|2\d)">
                <a href="JavaScript:void(0)" class="search" onclick="grocerySearch()">Search</a>
            </form>
        </div>
        <div class="col-md-12 paggination">
            <!-- <div class="count">1 to xx of xx Groceries</div> -->
            <!-- <div class="pagecount">Page: 1 of 1</div> -->
            <div class="pagecount">&nbsp;</div>
        </div>
        @if (count($grocery) == 0)
            <div class="col-md-12 block1">
            Suggestions for improving the results:<br/>
            Try a different location.<br/>
            Check the spelling or try alternate spellings.<br/>
            </div>
        @endif   
        @foreach ($grocery as $key => $rel)
            <div class="col-md-12 block1">
                    <div class="smallImage">
                    @if (isset($rel['photoName']) && $rel['photoName'])
                        <img src="{{ URL::to('/') }}/image/grocery/{{$rel['id']}}/{{$rel['photoName']}}" alt="{{$loop->index}}{{ $rel['name'] }}" style="width:100%;height:100%"></div>
                    @else   
                        <img src="{{ URL::to('/') }}/image/noimage.svg" alt="{{$loop->index}}{{ $rel['name'] }}" style="width:100%;height:100%"></div>
                    @endif                 
                    <div class="content1"> 
                        <a href="{{ URL::to('/') }}/{{config('app.defaultBaseURL.grocery-store-details')}}/{{ $rel['urlName'] }}" class="title">{{ $rel['name'] }}</a><br/>                                                           
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
            var type        =   document.getElementById("type").value;
            var city        =   document.getElementById("city").value;
            var keyword     =   document.getElementById("keyword").value;
            var urlParm     =   '';
            if(city && city != 'all'){
                city        =   'grocery-store-'+city;
            }else{
                city        =   'all';
            }
            urlParm = "{{ URL::to('/') }}/{{config('app.defaultBaseURL.grocery-search')}}/"+type+"/"+city+"/"+keyword;
            window.location.href = urlParm;

        } 

        $(function(){
            $('#keyword').keyup(function()
            {
                var yourInput = $(this).val();
                re = /[`~!@#$%^&*()_|+\-=?;:'",.<>\{\}\[\]\\\/]/gi;
                var isSplChar = re.test(yourInput);
                if(isSplChar)
                {
                    var no_spl_char = yourInput.replace(/[`~!@#$%^&*()_|+\-=?;:'",.<>\{\}\[\]\\\/]/gi, '');
                    $(this).val(no_spl_char);
                }
            });
        
        });
    </script>

@endsection
