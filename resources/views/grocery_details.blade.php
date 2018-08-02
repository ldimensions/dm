@extends('layouts.app')

@section('content')

<div class="col-md-9 leftcontainer">
    <div class="col-md-12 paggination"><a href="{{ URL::to('/') }}/{{config('app.defaultBaseURL.dallas-indian-grocery-store')}}" class="subcontent2">Grocery</a>&nbsp;&nbsp;>&nbsp;&nbsp;<span class="title">Details</span></div>
    <div class="col-md-6 block2">
        <div class="topdetail slideshow-container">
            <ul id="lightSlider">
                @foreach ($photos as $key => $photo)
                    <li data-thumb="{{ URL::to('/') }}/image/shadow_bottom.gif">
                        <img src="{{ URL::to('/') }}/image/grocery/{{$grocery['id']}}/{{$photo['photoName']}}" alt="{{$loop->index}}{{ $grocery['name'] }}" style="width:100%;height:100%" class="toparea">
                    </li>
                @endforeach
            </ul>            
        </div>
        <div class="gro_title">{{ $grocery['name'] }}</div>
        <div class="content">
            <table class="fullWidth">
                <tr>
                    <td colspan="2" class="tdtoppadd">{{ $grocery['description'] }}</td>
                </tr>
                <tr>
                    <td colspan="2" class="smallfont tdtoppadd1">Ethnicity:</td>
                </tr> 
                <tr>
                    <td colspan="2">{{ $grocery['ethnicName'] }}</td>
                </tr>                
                @if ( !empty ( $distance ) )                    
                    <tr>
                        <td colspan="2" class="smallfont tdtoppadd1">Distance:</td>
                    </tr> 
                    <tr>
                        <td colspan="2">{{ $distance }}</td>
                    </tr>
                @endif                 
                <tr>
                    <td colspan="2" class="smallfont tdtoppadd1">Address:</td>
                </tr>
                <tr>
                    <td colspan="2">{{ $grocery['address1'] }} {{ $grocery['address2'] }}, {{ $grocery['city'] }}, {{ $grocery['state'] }}, {{ $grocery['zip'] }}</td>
                </tr>
                <tr>
                    <td colspan="2" class="smallfont tdtoppadd1">Phone:</td>
                </tr>
                <tr>
                    <td colspan="2"><a href="tel:{{ $grocery['phone1'] }}">{{ $grocery['phone1'] }}</a></td>
                </tr>
                @if (isset($grocery['website']) && $grocery['website'])
                  <tr>
                      <td colspan="2" class="smallfont tdtoppadd1">Website:</td>
                  </tr>
                  <tr>
                      <td colspan="2"><a href="{{ $grocery['website'] }}" target="_blank">{{ $grocery['website'] }}</a></td>
                  </tr> 
                @endif               
                <tr>
                    <td colspan="2" class="smallfont tdtoppadd1">Located In:</td>
                </tr>
                <tr>
                    <td colspan="2">{{ $grocery['city'] }}</td>
                </tr>
                @if (isset($distance) && $distance)
                  <tr>
                      <td colspan="2" class="smallfont tdtoppadd1">Distance:</td>
                  </tr>
                  <tr>
                      <td colspan="2">{{ $distance }}</td>
                  </tr>
                @endif
            </table>
            @foreach ($workingTimes as $wtKey => $wtArr)
                @if($wtKey == "default")
                    <table>
                        <tr>
                            <td colspan="2" class="smallfont tdtoppadd1">Mass:</td>
                        </tr>
                        @foreach ($wtArr[0] as $wtArrKey => $wtRs)
                            @if ( !empty ( $wtRs ) )
                            <tr>
                                @if ( $wtArrKey == $today )
                                    <td class="activeweekdays daysWith">{{$wtArrKey}}</td>
                                @else
                                    <td class="inactiveweekdays daysWith">{{$wtArrKey}}</td>
                                @endif    
                                @foreach ($wtRs as $key => $wt)
                                    @foreach ($wt as $wtTimeKey => $wtTime)
                                        @if ( $wtArrKey == $today )
                                            <td class="activeweekdays">{{$wtTime}}@if ($loop->parent->index+1 != $loop->parent->count)&nbsp;-&nbsp;@endif</td>
                                        @else
                                            <td class="inactiveweekdays">{{$wtTime}}@if ($loop->parent->index+1 != $loop->parent->count)&nbsp;-&nbsp;@endif</td>
                                        @endif                                              
                                    @endforeach
                                @endforeach
                            </tr>
                            @endif                           
                        @endforeach   
                    </table>   
                  @endif                   
            @endforeach                                               
        </div>
    </div>
    <div class="col-md-6 block2">
        <div class="white_title toparea">{{ $grocery['name'] }}</div>
        <div id="map" class="map"></div>
    </div>
</div>
<div class="col-md-3 rightcontainer"></div>
  
<div class="row">
    <div class="col-md-12 footerh nopadding"></div>
</div>
<div class="col-md-9 leftcontainer relatedContent">   
    <div class="row" id="related"></div>
</div>
<script>
    /*---------- Google Map ----------*/
    
    function initMap() {
        var lat = parseFloat("{{ $grocery['latitude'] }}");
        var long = parseFloat("{{ $grocery['longitude'] }}");
        console.log(lat+'#'+long);
        var label = "{{ $grocery['name'] }}";
        var myLatLng = {lat: lat, lng: long};

        var map = new google.maps.Map(document.getElementById('map'), {
            zoom: 11,
            center: myLatLng
        });

        var marker = new google.maps.Marker({
            position: myLatLng,
            map: map,
            title: label
        });
    }

    /*---------- Google Map End----------*/

    /*---------- Image Slider ----------*/

    $('#lightSlider').lightSlider({
        gallery: true,
        item: 1,
        loop: true,
        slideMargin: 0,
        thumbItem: 9
    });
    
    /*---------- Image Slider End----------*/

    $( document ).ready(function() {
        alert(<?php echo config('app.defaultBaseURL.dallas-indian-restaurant')?>);
        $.get("<?php echo URL::to('/');?>/grocery-related/<?php echo $grocery['ethnicId'];?>/<?php echo $grocery['id'];?>", function(data, status){
            if(status=="success"){
                document.getElementById("related").innerHTML = data;
            }
        });
    });
    
</script>
<script async defer
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCQJp0CkLijcKXd44Pyn6QWX0Da0PwPKtc&callback=initMap">
</script>
@endsection
