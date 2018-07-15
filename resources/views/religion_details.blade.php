@extends('layouts.app')

@section('content')

<div class="col-md-9 leftcontainer">
    <div class="col-md-12 paggination"><a href="list.html" class="subcontent2">Religions</a>&nbsp;&nbsp;>&nbsp;&nbsp;<a href="#" class="title">Details</a></div>
    <div class="col-md-6 block2">
        <div class="topdetail slideshow-container">
            <ul id="lightSlider">
                @foreach ($photos as $key => $photo)
                    <li data-thumb="{{ URL::to('/') }}/image/shadow_bottom.gif">
                        <img src="{{ URL::to('/') }}/image/religion/{{$photo['photoName']}}" alt="{{$loop->index}}{{ $religion['name'] }}" style="width:100%;height:100%" class="toparea">
                    </li>
                @endforeach
            </ul>            
        </div>
        <div class="gro_title">{{ $religion['name'] }}</div>
        <div class="content">
            <table class="fullWidth">
                <tr>
                    <td colspan="2" class="tdtoppadd">{{ $religion['description'] }}</td>
                </tr>
                <tr>
                    <td colspan="2" class="smallfont tdtoppadd1">Address:</td>
                </tr>
                <tr>
                    <td colspan="2">{{ $religion['address1'] }} {{ $religion['address2'] }}, {{ $religion['city'] }}, {{ $religion['state'] }}, {{ $religion['zip'] }}</td>
                </tr>
                <tr>
                    <td colspan="2" class="smallfont tdtoppadd1">Phone:</td>
                </tr>
                <tr>
                    <td colspan="2"><a href="tel:{{ $religion['phone1'] }}">{{ $religion['phone1'] }}</a></td>
                </tr>
                <tr>
                    <td colspan="2" class="smallfont tdtoppadd1">Website:</td>
                </tr>
                <tr>
                    <td colspan="2"><a href="{{ $religion['website'] }}" target="_blank">{{ $religion['website'] }}</a></td>
                </tr>                
                <tr>
                    <td colspan="2" class="smallfont tdtoppadd1">Located In:</td>
                </tr>
                <tr>
                    <td colspan="2">{{ $religion['city'] }}</td>
                </tr>
                    <tr>
                    <td colspan="2" class="smallfont tdtoppadd1">Distance:</td>
                </tr>
                <tr>
                    <td colspan="2">{{ $distance }}</td>
                </tr>
            </table>
            @if ($religion['religionName'] == 'Christianity')
            @foreach ($workingTimes as $wtKey => $wtArr)
                @if($wtKey == "Mass")
                    <table>
                        <tr>
                            <td colspan="2" class="smallfont tdtoppadd1">Mass:</td>
                        </tr>
                        @foreach ($wtArr[0] as $wtMassArrKey => $wtMass)
                            @if ( !empty ( $wtMass ) )
                            <tr>
                                @if ( $wtMassArrKey == $today )
                                    <td class="activeweekdays daysWith">{{$wtMassArrKey}}</td>
                                @else
                                    <td class="inactiveweekdays daysWith">{{$wtMassArrKey}}</td>
                                @endif    
                                @foreach ($wtMass as $massKey => $mass)
                                    @foreach ($mass as $massTimeKey => $massTime)
                                        @if ( $wtMassArrKey == $today )
                                            <td class="activeweekdays">{{$massTime}}@if ($loop->parent->index+1 != $loop->parent->count),&nbsp;@endif</td>
                                        @else
                                            <td class="inactiveweekdays">{{$massTime}}@if ($loop->parent->index+1 != $loop->parent->count),&nbsp;@endif</td>
                                        @endif                                              
                                    @endforeach
                                @endforeach
                            </tr>
                            @endif                           
                        @endforeach   
                    </table>                     
                @elseif($wtKey == "Confession")
                    <table>
                        <tr>
                            <td colspan="2" class="smallfont tdtoppadd1">Confession:</td>
                        </tr>
                        @foreach ($wtArr[0] as $wtConfArrKey => $wtConf)
                            @if ( !empty ( $wtConf ) )
                                <tr>
                                    @if ( $wtConfArrKey == $today )
                                        <td class="activeweekdays daysWith">{{$wtConfArrKey}}</td>
                                    @else
                                        <td class="inactiveweekdays daysWith">{{$wtConfArrKey}}</td>
                                    @endif                                             
                                    @foreach ($wtConf as $confession)
                                        @foreach ($confession as $confessionTimeKey => $confessionTime)
                                            @if ( $wtConfArrKey == $today )
                                                <td class="activeweekdays">{{$confessionTime}}@if ($loop->parent->index+1 != $loop->parent->count),&nbsp;@endif</td>
                                            @else
                                                <td class="inactiveweekdays">{{$confessionTime}}@if ($loop->parent->index+1 != $loop->parent->count),&nbsp;@endif</td>
                                            @endif  
                                        @endforeach
                                    @endforeach
                                </tr>
                            @endif                           
                        @endforeach
                    </table>                                 
                @elseif($wtKey == "Adoration")
                    <table>
                        <tr>
                            <td colspan="2" class="smallfont tdtoppadd1">Adoration:</td>
                        </tr>
                        @foreach ($wtArr[0] as $wtAdoArrKey => $wtAdo)
                            @if ( !empty ( $wtAdo ) )
                                <tr>
                                    @if ( $wtAdoArrKey == $today )
                                        <td class="activeweekdays daysWith">{{$wtAdoArrKey}}</td>
                                    @else
                                        <td class="inactiveweekdays daysWith">{{$wtAdoArrKey}}</td>
                                    @endif                                          
                                    @foreach ($wtAdo as $adoration)
                                        @foreach ($adoration as $adorationTimeKey => $adorationTime)
                                            @if ( $wtAdoArrKey == $today )
                                                <td class="activeweekdays">{{$adorationTime}}@if ($loop->parent->index+1 != $loop->parent->count),&nbsp;@endif</td>
                                            @else
                                                <td class="inactiveweekdays">{{$adorationTime}}@if ($loop->parent->index+1 != $loop->parent->count),&nbsp;@endif</td>
                                            @endif 
                                        @endforeach
                                    @endforeach
                                </tr>                           
                            @endif                           
                        @endforeach     
                    </table>                                             
                @endif                       
            @endforeach                                               
            @elseif ($religion['religionName'] == 'Hinduism')
                I have multiple records!
            @elseif ($religion['religionName'] == 'Islam')
                I have multiple records!
            @elseif ($religion['religionName'] == 'Judaism')
                I have multiple records!
            @elseif ($religion['religionName'] == 'Buddhism')
                I have multiple records!                
            @else
            
            @endif
        </div>
    </div>
    <div class="col-md-6 block2">
        <div class="white_title toparea">{{ $religion['name'] }}</div>
        <div id="map" class="map"></div>
    </div>
</div>
<div class="col-md-3 rightcontainer"></div>
  
<div class="row">
    <div class="col-md-12 footerh nopadding"></div>
</div>
<div class="col-md-9 leftcontainer" style="min-height:20px;">
    <div class="row" id="related"></div>
</div>
<script>
    /*---------- Google Map ----------*/
    
    function initMap() {
        var lat = parseFloat("{{ $religion['latitude'] }}");
        var long = parseFloat("{{ $religion['longitude'] }}");
        console.log(lat+'#'+long);
        var label = "{{ $religion['name'] }}";
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
        $.get("<?php echo URL::to('/');?>/religion-related/<?php echo $religion['denominationName'];?>/<?php echo $religion['id'];?>", function(data, status){
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



