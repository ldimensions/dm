@extends('layouts.app')
@section('content')
<div class="mcontainer">
    <div class="maincontainer">
        <div class="leftcontainer">
            <div class="paggination"><a href="{{ URL::to('/') }}/{{config('app.defaultBaseURL.dallas-indian-restaurant')}}" class="subcontent2 h21">Restaurant</a>&nbsp;&nbsp;>&nbsp;&nbsp;<span class="title">{{ $restaurant['name'] }}</span></div>
            <div class="block2">
                <div class="res_title toparea space">
                <table class="fullWidth">
                        <tr>
                        <td><h1 class="titleblock">{{ $restaurant['name'] }}</h1></td>
                        </tr>
                        <tr>
                        <td><div class="titleblock white smaextra_res">{{ $restaurant['address1'] }} {{ $restaurant['address2'] }}, {{ $restaurant['city'] }}, {{ $restaurant['state'] }}, {{ $restaurant['zip'] }}</div></td>
                        </tr>
                        <tr>
                        <td><a href="tel:{{ $restaurant['phone1'] }}" class="titleblock white extra_res">{{ $restaurant['phone1'] }}</a></td>
                        </tr>
                        @if($foodTypeStr)
                            <tr>
                                <td class="smaextra_res">{{$foodTypeStr}}</td>
                            </tr> 
                        @endif                            
                        @if($todaysWorkingTime)
                            <tr>
                                <td class="smaextra_res">Working Time : {{$todaysWorkingTime}} </td>
                            </tr> 
                        @else
                        <td class="smaextra_res">Working Time : Closed </td>
                        @endif                       
                </table>        
                </div>

                <div class="content">
                    <table class="fullWidth">
                    @if($restaurant['description']) 
                        <tr>
                            <td>&nbsp;</td>
                        </tr>
                        <tr>
                            <td>
                                <div id="description" style="overflow: hidden; height: {{$descriptionHeight}}px;">{!! nl2br($restaurant['description']) !!}</div>
                                @if(strlen($restaurant['description']) >= '220') 
                                <a id="readMore" class="read h21">Read more...</a>
                                @else
                                    <span id="readMore"></span>
                                @endif 
                            </td>
                        </tr> 
                    @endif                                  
                    @if($restaurant['ethnicName']) 
                        <tr>
                            <td class="smallfont tdtoppadd1 topspace">Ethnicity</td>
                        </tr> 
                        <tr>
                            <td><h3>{{ $restaurant['ethnicName'] }}</h3></td>
                        </tr>  
                    @endif              
                        <tr>
                            <td class="smallfont tdtoppadd1">Located In</td>
                        </tr>
                        <tr>
                            <td><h3>{{ $restaurant['city'] }}</h3></td>
                        </tr>
                        @if (isset($distance) && $distance)
                        <tr>
                            <td class="smallfont tdtoppadd1">Distance</td>
                        </tr>
                        <tr>
                            <td>{{ $distance }}</td>
                        </tr>
                        @endif
                    </table>
                    @if($workingTimes)
                        @foreach ($workingTimes as $wtKey => $wtArr)
                            @if($wtKey == "default")
                                <table>
                                    <tr>
                                        <td colspan="3" class="smallfont tdtoppadd1">Working Time</td>
                                    </tr>
                                    @foreach ($wtArr[0] as $wtArrKey => $wtRs)
                                        @if ( !empty ( $wtRs ) )
                                        <tr>
                                            @if ( $wtArrKey == $today )
                                                <td class="activeweekdays_res daysWith">{{$wtArrKey}}</td>
                                            @else
                                                <td class="inactiveweekdays daysWith">{{$wtArrKey}}</td>
                                            @endif    
                                            @foreach ($wtRs as $key => $wt)
                                                @foreach ($wt as $wtTimeKey => $wtTimes)
                                                    @foreach ($wtTimes as $wtTimeKeys => $wtTime)
                                                        @if ( $wtArrKey == $today )
                                                            <td class="activeweekdays_res">
                                                                {{$wtTime}}@if ($loop->parent->index+1 != $loop->parent->count)&nbsp;-&nbsp;@endif @if  ($loop->parent->index == $loop->count)&nbsp;@endif
                                                            </td>
                                                        @else
                                                            <td class="inactiveweekdays">
                                                                {{$wtTime}}@if ($loop->parent->index+1 != $loop->parent->count)&nbsp;-&nbsp;@endif @if($loop->parent->count == $loop->parent->index+1 and $loop->parent->last == 1)&nbsp;@endif
                                                            </td>
                                                        @endif  
                                                    @endforeach                                         
                                                @endforeach
                                            @endforeach
                                        </tr>
                                        @endif                           
                                    @endforeach   
                                </table>   
                            @endif                   
                        @endforeach   
                    @endif  

                    <div class="suggestionblock">
                        <a href="#" data-toggle="modal" data-target="#exampleModal" data-whatever="@mdo" class="subcontent22">Suggest an edit</a>   
                    </div>             
                </div>
            </div>
            <div class="block22">
            <div class="white_t1 space">
                <h2 class="titleh2 graycolor1">{{$restaurant['name']}} Location</h2>
                <a href="https://www.google.com/maps/dir//{{ $restaurant['urlName'] }}/%40{{$restaurant['latitude']}},{{$restaurant['longitude']}},12z" title="{{ $restaurant['name'] }}" target="_blank" class="mapicon12"><img src="{{ URL::to('/') }}/image/map1.svg" alt="{{ $restaurant['name'] }}"/></a>
            </div>
                <div id="map" class="map"></div>
            </div>
            @if($photos)
                <div class="blockk1">
                <div class="block23">
                    <div class="white_Photo space"><h2 class="titleh2 graycolor">{{$restaurant['name']}} Photos</h2></div>
                </div>
                <div class="block231">
                    <div class="topdetail slideshow-container">
                        <ul id="lightSlider">
                        @foreach ($photos as $key => $photo)
                                <li data-thumb="{{ URL::to('/') }}/image/shadow_bottom.gif">
                                    <img src="{{ URL::to('/') }}/image/restaurant/{{$restaurant['id']}}/{{$photo['photoName']}}" alt="{{$loop->index}}{{ $restaurant['name'] }}" style="width:100%;height:100%" class="bottomarea">
                                </li>
                                @endforeach
                        </ul>            
                    </div>        
                </div>
                </div> 
            @endif
            
            <div class="row" id="related"></div>
        </div>

        <div class="rightcontainer">
        <div class="ad250x250"><img alt="ad"  width="100%" height="100%" src="{{ URL::to('/') }}/image/sideBanner.svg"/></div>
        </div>
    </div>
</div>
  


<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title titleh2 " id="exampleModalLabel">Suggest an edit</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group" id="formGrpErrName">
                    <label id="name1" class="col-form-label labelfont">Name:</label>
                    <input type="text" class="form-control nup" id="name" name="name" maxLength="40">
                    <div id="nameError"></div>
                </div>
                <div class="form-group">
                    <label id="email1" class="col-form-label labelfont">Email:</label>
                    <input type="text" class="form-control nup" id="email" name="email" maxLength="50">
                    <div id="emailError"></div>
                </div>   
                <div class="form-group">
                    <label id="phone1" class="col-form-label labelfont">Phone:</label>
                    <input type="text" class="form-control nup" id="phone" name="phone" maxLength="20">
                </div>                       
                <div class="form-group" id="formGrpErrSuggession">
                    <label id="suggessioin1" class="col-form-label labelfont">Suggestion:</label>
                    <textarea class="form-control nup" id="suggession" name="suggession"></textarea>
                    <div id="sugessionError"></div>                        
                </div>
            </div>
            <div class="modal-footer">
                <input type="hidden" class="form-control nup" id="type" name="type" value="2">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="suggessionBtn">Submit</button>
            </div>            
        </div>
    </div>
</div>
<div class="loading-overlay">
    <div class="spin-loader"></div>
</div>
<script src="{{ asset('js/lightslider.js') }}"></script>

<script>
    /*---------- Google Map ----------*/
    
    function initMap() {
        var lat = parseFloat("{{ $restaurant['latitude'] }}");
        var long = parseFloat("{{ $restaurant['longitude'] }}");
        console.log(lat+'#'+long);
        var label = "{{ $restaurant['name'] }}";
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
        $.get("<?php echo URL::to('/');?>/restaurant-related/<?php echo $restaurant['ethnicId'];?>/<?php echo $restaurant['id'];?>", function(data, status){
            if(status=="success"){
                document.getElementById("related").innerHTML = data;
            }
        });
    });

    document.querySelector('#readMore').addEventListener('click', function() {
        document.querySelector('#description').style.height= 'auto';
        this.style.display= 'none';
    });      
    
</script>
<script async defer
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCQJp0CkLijcKXd44Pyn6QWX0Da0PwPKtc&callback=initMap">
</script>
@endsection

