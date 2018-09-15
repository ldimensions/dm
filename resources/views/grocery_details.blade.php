@extends('layouts.app')
@section('content')
<div class="mcontainer">
<div class="maincontainer">
<div class="leftcontainer">
    <div class="paggination"><a href="{{ URL::to('/') }}/{{config('app.defaultBaseURL.dallas-indian-grocery-store')}}" class="subcontent2 h21">Grocery</a>&nbsp;&nbsp;>&nbsp;&nbsp;<span class="title">{{ $grocery['name'] }}</span></div>
    <div class="block2">
        <div class="gro_title toparea space">
            <table class="fullWidth">
                <tr>
                <td colspan="2" ><h1 class="titleblock">{{ $grocery['name'] }}</h1></td>
                </tr>
                <tr>
                    <td colspan="2"><div class="titleblock"><span class="white smaextra">{{ $grocery['address1'] }} {{ $grocery['address2'] }}, {{ $grocery['city'] }}, {{ $grocery['state'] }}, {{ $grocery['zip'] }}</span></div></td>
                </tr>
                <tr>
                    <td colspan="2"><div class="titleblock"><span class="white smaextra"><a href="tel:{{ $grocery['phone1'] }}" class="extra">{{ $grocery['phone1'] }}</a></span></div></td>
                </tr>
                @if($todaysWorkingTime)
                    <tr>
                        <td colspan="2" class="smaextra">Working Time : {{$todaysWorkingTime}}</td>
                    </tr>                         
                @endif
            </table> 
        </div>
        <div class="content">
            <table class="fullWidth">
            <tr>
                <td colspan="2">&nbsp;</td>
            </tr>
            <tr>
                <td colspan="2">
                    <div id="description" style="overflow: hidden; height: {{$descriptionHeight}}px;">{{ $grocery['description'] }}</div>
                    @if(strlen($grocery['description']) >= '220') 
                        <a id="readMore">Read more</a>
                    @else
                        <span id="readMore"></span>
                    @endif 
                </td>
            </tr>
            @if (isset($grocery['website']) && $grocery['website'])
                <tr>
                    <td colspan="2" class="smallfont tdtoppadd1">Website</td>
                </tr>
                <tr>
                    <td colspan="2"><h2><a href="http://{{ $grocery['website'] }}" class="h21" target="_blank">{{ $grocery['website'] }}</a></h2></td>
                </tr> 
            @endif 
            @if($grocery['ethnicName']) 
                <tr>
                    <td colspan="2" class="smallfont tdtoppadd1 topspace">Ethnicity</td>
                </tr> 
                <tr>
                    <td colspan="2"><h3>{{ $grocery['ethnicName'] }}</h3></td>
                </tr>                
            @endif            
            <tr>
                <td colspan="2" class="smallfont tdtoppadd1">Located In</td>
            </tr>
            <tr>
                <td colspan="2"><h3>{{ $grocery['city'] }}</h3></td>
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
            @if($workingTimes)
                @foreach ($workingTimes as $wtKey => $wtArr)
                    @if($wtKey == "default")
                        <table>
                            <tr>
                                <td colspan="2" class="smallfont tdtoppadd1">Working Time</td>
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
            @endif
            <div class="suggestionblock">
                <a href="#" data-toggle="modal" data-target="#exampleModal" data-whatever="@mdo" class="subcontent22">Suggest an edit</a>   
            </div>                                         
        </div>
    </div>
    <div class="block22">
    <div class="white_t space"><h2 class="titleh2 graycolor">{{$grocery['name']}} Location</h2></div>
        <div id="map" class="map"></div>
    </div>
    @if($photos)
    <div class="blockk1">
        <div class="block23">
            <div class="white_Photo space"><h2 class="titleh2 graycolor">{{$grocery['name']}} Photos</h2></div>
        </div>
        <div class="block231">
            <div class="topdetail slideshow-container">
                <ul id="lightSlider">
                    @foreach ($photos as $key => $photo)
                        <li data-thumb="{{ URL::to('/') }}/image/shadow_bottom.gif">
                            <img src="{{ URL::to('/') }}/image/grocery/{{$grocery['id']}}/{{$photo['photoName']}}" alt="{{$loop->index}}{{ $grocery['name'] }}" style="width:100%;height:100%" class="bottomarea">
                        </li>
                    @endforeach
                </ul>            
            </div>        
        </div>
    </div>    
    @endif
    <div class="row" id="related"></div>
</div>
<div class="rightcontainer"></div>
</div>
</div>
  
<div class="row">
    <div class="col-md-12 footerh nopadding"></div>
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
                    <label for="recipient-name" class="col-form-label labelfont">Name:</label>
                    <input type="text" class="form-control nup" id="name" name="name" maxLength="40">
                    <div id="nameError"></div>
                </div>
                <div class="form-group">
                    <label for="recipient-name" class="col-form-label labelfont">Email:</label>
                    <input type="text" class="form-control nup" id="email" name="email" maxLength="50">
                    <div id="emailError"></div>
                </div>   
                <div class="form-group">
                    <label for="recipient-name" class="col-form-label labelfont">Phone:</label>
                    <input type="text" class="form-control nup" id="phone" name="phone" maxLength="20">
                </div>                       
                <div class="form-group" id="formGrpErrSuggession">
                    <label for="message-text" class="col-form-label labelfont">Suggestion:</label>
                    <textarea class="form-control nup" id="suggession" name="suggession"></textarea>
                    <div id="sugessionError"></div>                        
                </div>
            </div>
            <div class="modal-footer">
                <input type="hidden" class="form-control nup" id="type" name="type" value="1">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="suggessionBtn" />Submit</button>
            </div>            
        </div>
    </div>
</div>
<div class="loading-overlay">
    <div class="spin-loader"></div>
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
        $.get("<?php echo URL::to('/');?>/grocery-related/<?php echo $grocery['ethnicId'];?>/<?php echo $grocery['id'];?>", function(data, status){
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