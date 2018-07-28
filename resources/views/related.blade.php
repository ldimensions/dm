@foreach ($related as $key => $rel)
    <div class="col-md-12 block1">
        @if (isset($type) && $type == 'grocery')
            <div class="smallImage">
                @if (isset($rel['photoName']) && $rel['photoName'])
                    <img src="{{ URL::to('/') }}/image/grocery/{{$rel['id']}}/{{$rel['photoName']}}" alt="{{$loop->index}}{{ $rel['name'] }}" style="width:100%;height:100%">
                @else
                    <img src="{{ URL::to('/') }}/image/noimage.svg" alt="{{$loop->index}}{{ $rel['name'] }}" style="width:100%;height:100%">
                @endif  
            </div> 
            <div class="content1"> 
                <a href="../{{config('app.defaultBaseURL.grocery-store-details')}}/{{ $rel['urlName'] }}" class="title">{{ $rel['name'] }}</a><br/>
            </div>
            <a href="Details.html" class="mapicon"><img src="{{ URL::to('/') }}/image/map1.svg" alt="{{$loop->index}}{{ $rel['name'] }}"/></a>
            <div class="content2"> <span class="subcontent1">Address:</span> <span class="subcontent2">{{ $rel['address1'] }} {{ $rel['address2'] }}, {{ $rel['city'] }}, {{ $rel['state'] }} {{ $rel['zip'] }}</span> </div>
            <div class="content3"> <span class="subcontent1">Phone:</span> <span class="subcontent2"><a href="tel:{{ $rel['phone1'] }}">{{ $rel['phone1'] }}</a></span> </div>
            <div class="content3"> <span class="subcontent1">Located In:</span> <span class="subcontent2">{{ $rel['city'] }}</span> </div>
            @if (isset($rel['distance']) && $rel['distance'])
                <div class="gro_kmblock_list">Distance : {{ $rel['distance'] }}</div>
            @endif                 
            <div class="open_close">Closed - Open 7 AM----</div>
        @endif 

        @if(isset($type) && $type == 'restaurant')
            <div class="smallImage">
                @if (isset($rel['photoName']) && $rel['photoName'])
                    <img src="{{ URL::to('/') }}/image/restaurant/{{$rel['id']}}/{{$rel['photoName']}}" alt="{{$loop->index}}{{ $rel['name'] }}" style="width:100%;height:100%">
                @else
                    <img src="{{ URL::to('/') }}/image/noimage.svg" alt="{{$loop->index}}{{ $rel['name'] }}" style="width:100%;height:100%">
                @endif   
            </div>              
            <div class="content1"> 
                <a href="../{{config('app.defaultBaseURL.dallas-indian-restaurant')}}/{{ $rel['urlName'] }}" class="title">{{ $rel['name'] }}</a><br/>
            </div>
            <a href="Details.html" class="mapicon"><img src="{{ URL::to('/') }}/image/map1.svg" alt="{{$loop->index}}{{ $rel['name'] }}"/></a>
            <div class="content2"> <span class="subcontent1">Address:</span> <span class="subcontent2">{{ $rel['address1'] }} {{ $rel['address2'] }}, {{ $rel['city'] }}, {{ $rel['state'] }} {{ $rel['zip'] }}</span> </div>
            <div class="content3"> <span class="subcontent1">Phone:</span> <span class="subcontent2"><a href="tel:{{ $rel['phone1'] }}">{{ $rel['phone1'] }}</a></span> </div>
            <div class="content3"> <span class="subcontent1">Located In:</span> <span class="subcontent2">{{ $rel['city'] }}</span> </div>
            @if (isset($rel['distance']) && $rel['distance'])
                <div class="gro_kmblock_list">Distance : {{ $rel['distance'] }}</div>
            @endif                 
            <div class="open_close">Closed - Open 7 AM----</div>
        @endif 
        
        @if(isset($type) && $type == 'religion')
            <div class="smallImage">
                @if (isset($rel['photoName']) && $rel['photoName'])
                    <img src="{{ URL::to('/') }}/image/religion/{{$rel['id']}}/{{$rel['photoName']}}" alt="{{$loop->index}}{{ $rel['name'] }}" style="width:100%;height:100%">
                @else
                    <img src="{{ URL::to('/') }}/image/noimage.svg" alt="{{$loop->index}}{{ $rel['name'] }}" style="width:100%;height:100%"></div>
                @endif 
            </div>                
                <div class="content1"> 
                    @if ($rel['religionName'] == 'Christianity')
                        <a href="../{{config('app.defaultBaseURL.dallas-malayali-church')}}/{{ $rel['urlName'] }}" class="title">{{ $rel['name'] }}</a><br/>
                    @elseif($rel['religionName'] == 'Hinduism')
                        <a href="{{config('app.defaultBaseURL.dallas-malayali-temple')}}/{{ $rel['urlName'] }}" class="title">{{ $rel['name'] }}</a><br/>
                    @elseif($rel['religionName'] == 'Judaism')
                        <a href="{{config('app.defaultBaseURL.dallas-malayali-temple')}}/{{ $rel['urlName'] }}" class="title">{{ $rel['name'] }}</a><br/>
                    @elseif($rel['religionName'] == 'Buddhism')
                        <a href="{{config('app.defaultBaseURL.dallas-malayali-temple')}}/{{ $rel['urlName'] }}" class="title">{{ $rel['name'] }}</a><br/>
                    @elseif($rel['religionName'] == 'Islam')
                        <a href="{{config('app.defaultBaseURL.dallas-malayali-mosque')}}/{{ $rel['urlName'] }}" class="title">{{ $rel['name'] }}</a><br/>                                                           
                    @endif
                </div>
                <a href="Details.html" class="mapicon"><img src="{{ URL::to('/') }}/image/map1.svg" alt="{{$loop->index}}{{ $rel['name'] }}"/></a>
                <div class="content2"> <span class="subcontent1">Address:</span> <span class="subcontent2">{{ $rel['address1'] }} {{ $rel['address2'] }}, {{ $rel['city'] }}, {{ $rel['state'] }} {{ $rel['zip'] }}</span> </div>
                <div class="content3"> <span class="subcontent1">Phone:</span> <span class="subcontent2"><a href="tel:{{ $rel['phone1'] }}">{{ $rel['phone1'] }}</a></span> </div>
                <div class="content3"> <span class="subcontent1">Located In:</span> <span class="subcontent2">{{ $rel['city'] }}</span> </div>
                @if (isset($rel['distance']) && $rel['distance'])
                    <div class="gro_kmblock_list">Distance : {{ $rel['distance'] }}</div>
                @endif                 
                <div class="open_close">Closed - Open 7 AM----</div>              
        @endif    
    </div>
@endforeach


