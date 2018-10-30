 
@foreach ($related as $key => $rel)
    @if (isset($type) && $type == 'grocery' && $key == 0)
        <div class="related title">Related Groceries</div>
    @elseif(isset($type) && $type == 'restaurant' && $key == 0)
        <div class="related title">Related Restaurants</div>
    @elseif(isset($type) && $type == 'religion' && $key == 0)
        <div class="related title">Related Religions</div>
    @endif 
    <div class="col-md-12 block1">
        @if (isset($type) && $type == 'grocery')
            <div class="smallImage">
                @if (isset($rel['photoName']) && $rel['photoName'])
                    <img src="{{ URL::to('/') }}/image/grocery/{{$rel['id']}}/{{$rel['photoName']}}" alt="{{$loop->index}}{{ $rel['name'] }}" style="width:100%;height:100%">
                @else
                    <img src="{{ URL::to('/') }}/image/noimage.svg" alt="{{$loop->index}}{{ $rel['name'] }}" style="width:100%;height:100%">
                @endif  
            </div> 
            <a href="../{{config('app.defaultBaseURL.grocery-store-details')}}/{{ $rel['urlName'] }}" title="{{$rel['name']}}" ><h3 class="content1 colorh1">{{ $rel['name'] }}</h3></a>
            @if ($rel['latitude'] && $rel['longitude'])
                <a href="https://www.google.com/maps/dir/{{$rel['latitude']}},{{$rel['longitude']}}" target="_blank" class="mapicon"><img src="{{ URL::to('/') }}/image/map1.svg" alt="{{$loop->index}}{{ $rel['name'] }}"/></a>
            @endif  
            <div class="content2">{{ $rel['address1'] }} {{ $rel['address2'] }}, {{ $rel['city'] }}, {{ $rel['state'] }} {{ $rel['zip'] }}</div>
            <a href="tel:{{ $rel['phone1'] }}" class="content3 h21">{{ $rel['phone1'] }}</a>
            <!-- <div class="content2">Closed - Open 7 AM</div> -->
        @endif 

        @if(isset($type) && $type == 'restaurant')
            <div class="smallImage">
                @if (isset($rel['photoName']) && $rel['photoName'])
                    <img src="{{ URL::to('/') }}/image/restaurant/{{$rel['id']}}/{{$rel['photoName']}}" alt="{{$loop->index}}{{ $rel['name'] }}" style="width:100%;height:100%">
                @else
                    <img src="{{ URL::to('/') }}/image/noimage.svg" alt="{{$loop->index}}{{ $rel['name'] }}" style="width:100%;height:100%">
                @endif   
            </div>              
            <a href="../{{config('app.defaultBaseURL.dallas-indian-restaurant')}}/{{ $rel['urlName'] }}" title="{{$rel['name']}}" ><h3 class="content1 colorh1">{{ $rel['name'] }}</h3></a>
            @if ($rel['latitude'] && $rel['longitude'])
                <a href="https://www.google.com/maps/dir/{{$rel['latitude']}},{{$rel['longitude']}}" title="{{$rel['name']}}" target="_blank" class="mapicon"><img src="{{ URL::to('/') }}/image/map1.svg" alt="{{$loop->index}}{{ $rel['name'] }}"/></a>
            @endif 
            <div class="content2">{{ $rel['address1'] }} {{ $rel['address2'] }}, {{ $rel['city'] }}, {{ $rel['state'] }} {{ $rel['zip'] }}</div>
            <a href="tel:{{ $rel['phone1'] }}" class="content3 h21">{{ $rel['phone1'] }}</a>           
        @endif 
        
        @if(isset($type) && $type == 'religion')
            <div class="smallImage">
                @if (isset($rel['photoName']) && $rel['photoName'])
                    <img src="{{ URL::to('/') }}/image/religion/{{$rel['id']}}/{{$rel['photoName']}}" alt="{{$loop->index}}{{ $rel['name'] }}" style="width:100%;height:100%">
                @else
                    <img src="{{ URL::to('/') }}/image/noimage.svg" alt="{{$loop->index}}{{ $rel['name'] }}" style="width:100%;height:100%"></div>
                @endif 
            </div>                
                <div  class="content1">
                    @if ($rel['religionName'] == 'Christianity')
                        <a href="{{ URL::to('/') }}/{{config('app.defaultBaseURL.dallas-christian-church')}}/{{ $rel['urlName'] }}" title="{{$rel['name']}}" ><h3 class="religionTrim colorh1">{{ $rel['name'] }}<h3></a>
                    @elseif($rel['religionName'] == 'Hinduism')
                    <a href="{{ URL::to('/') }}/{{config('app.defaultBaseURL.dallas-hindu-temple')}}/{{ $rel['urlName'] }}" title="{{$rel['name']}}"><h3 class="religionTrim colorh1">{{ $rel['name'] }}<h3></a>
                    @elseif($rel['religionName'] == 'Judaism')
                        <!-- <a href="{{ URL::to('/') }}/{{config('app.defaultBaseURL.dallas-malayali-temple')}}/{{ $rel['urlName'] }}" ><h3 class="religionTrim colorh1">{{ $rel['name'] }}<h3></a>-->
                    @elseif($rel['religionName'] == 'Buddhism')
                        <!-- <a href="{{ URL::to('/') }}/{{config('app.defaultBaseURL.dallas-malayali-temple')}}/{{ $rel['urlName'] }}" ><h3 class="religionTrim colorh1">{{ $rel['name'] }}<h3></a>-->
                    @elseif($rel['religionName'] == 'Islam')
                    <a href="{{ URL::to('/') }}/{{config('app.defaultBaseURL.dallas-islan-mosque')}}/{{ $rel['urlName'] }}" title="{{$rel['name']}}" ><h3 class="religionTrim colorh1">{{ $rel['name'] }}<h3> </a>                                                       
                    @endif
                </div>    
                @if ($rel['latitude'] && $rel['longitude'])
                    <a href="https://www.google.com/maps/dir/{{$rel['latitude']}},{{$rel['longitude']}}" title="{{$rel['name']}}" target="_blank" class="mapicon"><img src="{{ URL::to('/') }}/image/map1.svg" alt="{{$loop->index}}{{ $rel['name'] }}"/></a>
                @endif 
                <div class="content2">{{ $rel['address1'] }}, {{ $rel['city'] }}, {{ $rel['state'] }} {{ $rel['zip'] }}</span> </div>
                <a href="tel:{{ $rel['phone1'] }}" class="content3 h21">{{ $rel['phone1'] }}</a>              
        @endif  
        @if (isset($type) && $type == 'movie')
            <div class="smallImage">
                @if (isset($rel['photoName']) && $rel['photoName'])
                    <img src="{{ URL::to('/') }}/image/movie/{{$rel['movieId']}}/{{$rel['photoName']}}" alt="{{$loop->index}}{{ $rel['name'] }}" style="width:100%;height:100%">
                @else
                    <img src="{{ URL::to('/') }}/image/noimage.svg" alt="{{$loop->index}}{{ $rel['name'] }}" style="width:100%;height:100%">
                @endif  
            </div> 
            <a href="../{{config('app.defaultBaseURL.indian-movie')}}/{{ $rel['urlName'] }}" title="{{$rel['name']}}" ><h3 class="content1 colorh1">{{ $rel['name'] }}</h3></a>
        @endif 
        @if (isset($type) && $type == 'theatre')
            <div class="smallImage">
                @if (isset($rel['photoName']) && $rel['photoName'])
                    <img src="{{ URL::to('/') }}/image/theatre/{{$rel['id']}}/{{$rel['photoName']}}" alt="{{$loop->index}}{{ $rel['name'] }}" style="width:100%;height:100%">
                @else
                    <img src="{{ URL::to('/') }}/image/noimage.svg" alt="{{$loop->index}}{{ $rel['name'] }}" style="width:100%;height:100%">
                @endif  
            </div> 
            <a href="../{{config('app.defaultBaseURL.indian-theatre')}}/{{ $rel['urlName'] }}" title="{{$rel['name']}}" ><h3 class="content1 colorh1">{{ $rel['name'] }}</h3></a>
            @if ($rel['latitude'] && $rel['longitude'])
                <a href="https://www.google.com/maps/dir/{{$rel['latitude']}},{{$rel['longitude']}}" target="_blank" class="mapicon"><img src="{{ URL::to('/') }}/image/map1.svg" alt="{{$loop->index}}{{ $rel['name'] }}"/></a>
            @endif  
            <div class="content2">{{ $rel['address1'] }}, {{ $rel['city'] }}, {{ $rel['state'] }} {{ $rel['zip'] }}</div>
            <a href="tel:{{ $rel['phone1'] }}" class="content3 h21">{{ $rel['phone1'] }}</a>
            <!-- <div class="content2">Closed - Open 7 AM</div> -->
        @endif                   
    </div>
    </div>
@endforeach


