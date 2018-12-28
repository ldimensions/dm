@extends('layouts.app')
@section('content')

<div class="mcontainer">
    <div class="maincontainer">
        <div class="leftcontainer">
            <div class="col-md-12 searchbar hiddepadding">
                <form>
                    <select name="schedule" class="select" id="schedule">
                        <option value="1" {{ $schedule == '1' ? 'selected="selected"' : '' }}>Past Events</option>
                        <option value="2" {{ $schedule == '2' ? 'selected="selected"' : '' }}>Events</option>
                        <!-- <option value="3" {{ $schedule == '3' ? 'selected="selected"' : '' }}>Up Comming</option>                     -->
                    </select>
                    <select name="city" class="select" id="city">
                        <option value="all">All</option>
                        @foreach ($cities as $key => $city)
                            <option 
                                value="{{$city['cityId']}}"
                                {{$city['cityId'] == $cityVal ? 'selected="selected"' : '' }}>
                                {{$city['city']}}
                            </option>
                        @endforeach
                    </select>
                    <input type="text" id="searchKeyword" value="{{$keyword}}" name="searchKeyword" placeholder="Keywords" class="text1" maxlength="50" pattern="(1[0-2]|0[1-9])\/(1[5-9]|2\d)">
                    <a href="JavaScript:void(0)" class="search" onclick="eventSearch()">Search</a>
                </form>
            </div>
            @if (count($events) == 0)
                <div class="col-md-12 block1">
                    Suggestions for improving the results:<br/>
                    Try a different location.<br/>
                    Check the spelling or try alternate spellings.<br/>
                </div>
            @endif        
  


            @foreach ($events as $key => $rel)
                <div class="col-md-12 block1">
                    <div class="smallImage">
                        @if (isset($rel['photoName']) && $rel['photoName'])
                        <img src="{{ URL::to('/') }}/image/event/{{$rel['eventId']}}/{{$rel['photoName']}}" alt="{{$loop->index}}{{ $rel['name'] }}" style="width:100%;height:100%">
                        @else
                        <img src="{{ URL::to('/') }}/image/noimage.svg" alt="{{$loop->index}}{{ $rel['name'] }}" style="width:100%;height:100%">
                        @endif    
                    </div>             
                    <a href="{{ URL::to('/') }}/{{config('app.defaultBaseURL.events')}}/{{ $rel['urlName'] }}" title="{{ $rel['name'] }}" ><h2 class="content1 colorh1">{{ $rel['name'] }}</h2></a>                        
                    <span class="content3 ">{{ $rel['categoryName'] }}</span>
                    <div class="content2">{{ $rel['address1'] }} {{ $rel['address2'] }}, {{ $rel['city'] }}, {{ $rel['state'] }} {{ $rel['zip'] }}</div>                          
                </div>
            @endforeach                 

            {{ $events->links() }}

        </div>
        <div class="col-md-3 rightcontainer nopadding">
            <div class="ad250x250"><img alt="ad"  width="100%" height="100%" src="{{ URL::to('/') }}/image/sideBanner.svg"/></div>
        </div>  
    </div>    
</div> 

    
    
    <script>
        function eventSearch() {
            var city        =   document.getElementById("city").value;
            var keyword     =   document.getElementById("searchKeyword").value;
            var schedule    =   document.getElementById("schedule").value;
            var urlParm     =   '';            
            urlParm = "{{ URL::to('/') }}/{{config('app.defaultBaseURL.event-search')}}/"+schedule+"/"+city+"/"+keyword;
            window.location.href = urlParm;
        } 
    </script>

@endsection
