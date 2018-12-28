@extends('layouts.app')
@section('content')
<div class="mcontainer">
    <div class="maincontainer">
        <div class="leftcontainer">
            <div class="paggination"><a href="{{ URL::to('/') }}/{{config('app.defaultBaseURL.events')}}" class="subcontent2 h21">Events</a>&nbsp;&nbsp;>&nbsp;&nbsp;<span class="title">{{ $event['name'] }}</span></div>
            <div class="block2">
                <div class="gro_title toparea space">
                    <table class="fullWidth">
                        <tr>
                        <td><h1 class="titleblock">{{ $event['name'] }}</h1></td>
                        </tr>
                        <tr>
                            <td><div class="titleblock white smaextra">{{ $event['address1'] }} {{ $event['address2'] }}, {{ $event['city'] }}, {{ $event['state'] }}, {{ $event['zip'] }}</div></td>
                        </tr>
                        <tr>
                            <td><a href="tel:{{ $event['phone1'] }}" class="titleblock white smaextra extra">{{ $event['phone1'] }}</a></td>
                        </tr>
                        <!-- @if($todaysWorkingTime)
                            <tr>
                                <td class="smaextra">Working Time : {{$todaysWorkingTime}}</td>
                            </tr>                         
                        @endif -->
                    </table> 
                </div>
                <div class="content">
                    <table class="fullWidth">
                        @if($schedule) 
                            <tr>
                                <td colspan="2">Schedule</td>
                            </tr>
                            @foreach ($schedule as $key => $time)      
                                <tr>                                                                
                                    <td colspan="2">
                                        <div id="description" style="overflow: hidden;">{{$time['dateTime']}}@if(!$loop->last), @endif</div>                                    
                                    </td>
                                </tr>
                            @endforeach 
                        @endif                    
                        @if($event['description']) 
                            <tr>
                                <td colspan="2">&nbsp;</td>
                            </tr>
                            <tr>
                                <td colspan="2">
                                    <div id="description" style="overflow: hidden;">{!! nl2br($event['description']) !!}</div>                                    
                                </td>
                            </tr>
                        @endif   
                        @if (isset($event['website']) && $event['website'])
                            <tr>
                                <td colspan="2" class="smallfont tdtoppadd1">Website</td>
                            </tr>
                            <tr>
                                <td colspan="2"><h2><a href="{{ $event['website'] }}" target="_blank" class="h21">{{ $event['website'] }}</a></h2></td>
                            </tr> 
                        @endif                                   
                        <tr>
                            <td colspan="2" class="smallfont tdtoppadd1">Located In</td>
                        </tr>
                        <tr>
                            <td colspan="2"><h3>{{ $event['city'] }}</h3></td>
                        </tr>
                        @if (isset($distance) && $distance)
                            <tr>
                                <td colspan="2" class="smallfont tdtoppadd1">Distance</td>
                            </tr>
                            <tr>
                                <td colspan="2">{{ $distance }}</td>
                            </tr>
                        @endif
                    </table>

                    <div class="suggestionblock">
                        <a href="#" data-toggle="modal" data-target="#exampleModal" data-whatever="@mdo" class="subcontent22">Suggest an edit</a>   
                    </div>                                         
                </div>
            </div>
            @if ($event['organizerName'] || $event['organizerEmail'] || $event['organizerPhone'] )
                <div class="blockk1">
                    <div class="block23">
                        <div class="white_Photo space"><h2 class="titleh2 graycolor">{{$event['name']}} Contact</h2></div>
                    </div>
                    <div class="block231">
                        <table class="fullWidth">                       
                            @if ($event['organizerName'])
                                <tr>
                                    <td colspan="2" class="smallfont tdtoppadd1">Organizer name</td>
                                </tr>
                                <tr>
                                    <td colspan="2">{{$event['organizerName']}}</td>
                                </tr>
                            @endif
                            @if ($event['organizerEmail'])
                                <tr>
                                    <td colspan="2" class="smallfont tdtoppadd1">Organizer Email</td>
                                </tr>
                                <tr>
                                    <td colspan="2">{{$event['organizerEmail']}}</td>
                                </tr>
                            @endif
                            @if ($event['organizerPhone'])
                                <tr>
                                    <td colspan="2" class="smallfont tdtoppadd1">Organizer Phone</td>
                                </tr>
                                <tr>
                                    <td colspan="2">{{$event['organizerPhone']}}</td>
                                </tr>
                            @endif                                                
                        </table>
                    </div>
                </div>            
            @endif    
            <div class="block22">
            @if ($event['latitude'] && $event['longitude'])
                <div class="white_t1 space">
                    <h2 class="titleh2 graycolor1">{{$event['name']}} Location</h2>
                    <a href="https://www.google.com/maps/dir//{{ $event['name'] }} {{ $event['address1'] }} {{ $event['address2'] }}, {{ $event['city'] }}, {{ $event['state'] }}, {{ $event['zip'] }}/%40{{$event['latitude']}},{{$event['longitude']}},12z" title="{{ $event['name'] }}" target="_blank" class="mapicon12"><img src="{{ URL::to('/') }}/image/map1.svg" alt="{{ $event['name'] }}"/></a>
                    </div>
                    <div id="map" class="map"></div>
                </div>
            @endif
            </div>
            @if($photos)
            <div class="blockk1">
                <div class="block23">
                    <div class="white_Photo space"><h2 class="titleh2 graycolor">{{$event['name']}} Photos</h2></div>
                </div>
                <div class="block231">
                    <div class="topdetail slideshow-container">
                        <ul id="lightSlider">
                            @foreach ($photos as $key => $photo)
                                <li data-thumb="{{ URL::to('/') }}/image/shadow_bottom.gif">
                                    <img src="{{ URL::to('/') }}/image/event/{{$event['id']}}/{{$photo['photoName']}}" alt="{{$loop->index}}{{ $event['name'] }}" style="width:100%;height:100%" class="bottomarea">
                                </li>
                            @endforeach
                        </ul>            
                    </div>        
                </div>
            </div>    
            @endif
            <div class="row" id="related"></div>
        </div>
        <div class="col-md-3 rightcontainer nopadding">
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
                    <label id="suggession1" class="col-form-label labelfont">Suggestion:</label>
                    <textarea class="form-control nup" id="suggession" name="suggession"></textarea>
                    <div id="sugessionError"></div>                        
                </div>
            </div>
            <div class="modal-footer">
                <input type="hidden" class="form-control nup" id="type" name="type" value="1">
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
        var lat = parseFloat("{{ $event['latitude'] }}");
        var long = parseFloat("{{ $event['longitude'] }}");
        console.log(lat+'#'+long);
        var label = "{{ $event['name'] }}";
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
        $.get("<?php echo URL::to('/');?>/event-related/<?php echo $event['id'];?>", function(data, status){
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