<?php use App\Http\Controllers\CommonController;?>
@foreach ($related as $key => $rel)
    @if (isset($type) && $type == 'grocery' && $key == 0)
        <div class="related title">Related Groceries</div>
    @elseif(isset($type) && $type == 'restaurant' && $key == 0)
        <div class="related title">Related Restaurants</div>
    @elseif(isset($type) && $type == 'religion' && $key == 0)
        <div class="related title">Related Religions</div>
    @elseif(isset($type) && $type == 'movie' && $key == 0)
        <div class="related title">Related Movies</div>
    @elseif(isset($type) && $type == 'theatre' && $key == 0)
        <div class="related title">Related Theatres</div>
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
            <h3 class="content1"><a href="../{{config('app.defaultBaseURL.grocery-store-details')}}/{{ $rel['urlName'] }}" title="{{$rel['name']}}" class="colorh1">{{ $rel['name'] }}</a></h3>
            @if ($rel['latitude'] && $rel['longitude'])
                <a href="https://www.google.com/maps/dir//{{ $rel['name'] }} {{ $rel['address1'] }} {{ $rel['city'] }}, {{ $rel['state'] }}, {{$rel['zip'] }}/%40{{$rel['latitude']}},{{$rel['longitude']}},12z" target="_blank" class="mapicon"><img src="{{ URL::to('/') }}/image/map1.svg" alt="{{$loop->index}}{{ $rel['name'] }}"/></a>
            @endif  
            <div class="content2">{{ $rel['address1'] }} {{ $rel['address2'] }}, {{ $rel['city'] }}, {{ $rel['state'] }} {{ $rel['zip'] }}</div>
            <span class="content3"><a href="tel:{{ $rel['phone1'] }}" class="h21">{{ $rel['phone1'] }}</a><span>
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
            <h3 class="content1"><a href="../{{config('app.defaultBaseURL.dallas-indian-restaurant')}}/{{ $rel['urlName'] }}" title="{{$rel['name']}}" class="colorh1">{{ $rel['name'] }}</a></h3>
            @if ($rel['latitude'] && $rel['longitude'])
                <a href="https://www.google.com/maps/dir//{{ $rel['name'] }} {{ $rel['address1'] }} {{ $rel['city'] }}, {{ $rel['state'] }}, {{$rel['zip'] }}/%40{{$rel['latitude']}},{{$rel['longitude']}},12z" title="{{$rel['name']}}" target="_blank" class="mapicon"><img src="{{ URL::to('/') }}/image/map1.svg" alt="{{$loop->index}}{{ $rel['name'] }}"/></a>
            @endif 
            <div class="content2">{{ $rel['address1'] }} {{ $rel['address2'] }}, {{ $rel['city'] }}, {{ $rel['state'] }} {{ $rel['zip'] }}</div>
            <span class="content3"><a href="tel:{{ $rel['phone1'] }}" class="h21">{{ $rel['phone1'] }}</a></span>         
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
                    <h3 class="religionTrim"><a href="{{ URL::to('/') }}/{{config('app.defaultBaseURL.dallas-christian-church')}}/{{ $rel['urlName'] }}" title="{{$rel['name']}}" class="colorh1">{{ $rel['name'] }}</a><h3>
                    @elseif($rel['religionName'] == 'Hinduism')
                    <h3 class="religionTrim"><a href="{{ URL::to('/') }}/{{config('app.defaultBaseURL.dallas-hindu-temple')}}/{{ $rel['urlName'] }}" title="{{$rel['name']}}" class="colorh1">{{ $rel['name'] }}</a><h3>
                    @elseif($rel['religionName'] == 'Judaism')
                        <!-- <h3 class="religionTrim"><a href="{{ URL::to('/') }}/{{config('app.defaultBaseURL.dallas-malayali-temple')}}/{{ $rel['urlName'] }}" class="colorh1">{{ $rel['name'] }}</a><h3>-->
                    @elseif($rel['religionName'] == 'Buddhism')
                        <!-- <h3 class="religionTrim"><a href="{{ URL::to('/') }}/{{config('app.defaultBaseURL.dallas-malayali-temple')}}/{{ $rel['urlName'] }}" class="colorh1">{{ $rel['name'] }}</a><h3>-->
                    @elseif($rel['religionName'] == 'Islam')
                    <h3 class="religionTrim"><a href="{{ URL::to('/') }}/{{config('app.defaultBaseURL.dallas-islan-mosque')}}/{{ $rel['urlName'] }}" title="{{$rel['name']}}" class="colorh1">{{ $rel['name'] }}</a><h3>                                                        
                    @endif
                </div>    
                @if ($rel['latitude'] && $rel['longitude'])
                    <a href="https://www.google.com/maps/dir//{{ $rel['name'] }} {{ $rel['address1'] }} {{ $rel['city'] }}, {{ $rel['state'] }}, {{$rel['zip'] }}/%40{{$rel['latitude']}},{{$rel['longitude']}},12z" title="{{$rel['name']}}" target="_blank" class="mapicon"><img src="{{ URL::to('/') }}/image/map1.svg" alt="{{$loop->index}}{{ $rel['name'] }}"/></a>
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

            <a href="../{{config('app.defaultBaseURL.indian-movie')}}/{{ $rel['urlName'] }}" title="{{$rel['name']}}" ><h3 class="content11 colorh1">{{ $rel['name'] }}</h3></a>
            <div class="content2">{{  CommonController::getLanguage($rel['language']) }}</div>
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
                <a href="https://www.google.com/maps/dir//{{ $rel['name'] }} {{ $rel['address1'] }} {{ $rel['city'] }}, {{ $rel['state'] }}, {{$rel['zip'] }}/%40{{$rel['latitude']}},{{$rel['longitude']}},12z" target="_blank" class="mapicon"><img src="{{ URL::to('/') }}/image/map1.svg" alt="{{$loop->index}}{{ $rel['name'] }}"/></a>
            @endif  
            <div class="content2">{{ $rel['address1'] }}, {{ $rel['city'] }}, {{ $rel['state'] }} {{ $rel['zip'] }}</div>
            <a href="tel:{{ $rel['phone1'] }}" class="content3 h21">{{ $rel['phone1'] }}</a>
            <!-- <div class="content2">Closed - Open 7 AM</div> -->
        @endif                   
    </div>
    </div>
@endforeach


