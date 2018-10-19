@extends('layouts.app')
@section('content')
<?php use App\Http\Controllers\CommonController;?>

<div class="mcontainer">
    <div class="maincontainer">
        <div class="leftcontainer">
            <div class="paggination">
                <a href="{{ URL::to('/') }}/{{config('app.defaultBaseURL.dallas-indian-religion')}}" class="subcontent2 h21">Religions</a>&nbsp;&nbsp;>&nbsp;&nbsp;
                <span class="title">Details</span>
            </div>
            <div class="block2">
                <div class="relig_title toparea space">
                    <table class="fullWidth">
                        <tr>
                            <td><h1 class="religin_txt titleblock">{{ $religion['name'] }}</h1></td>
                        </tr> 
                        <tr>
                            <td><div class="titleblock smaextra_reli">{{ $religion['address1'] }} {{ $religion['address2'] }}, {{ $religion['city'] }}, {{ $religion['state'] }}, {{ $religion['zip'] }}</div></td>
                        </tr>
                        <tr>
                            <td><a href="tel:{{ $religion['phone1'] }}"  class="titleblock smaextra_reli extra_reli">{{ $religion['phone1'] }}</a></td>
                        </tr>  
                        @if ($todaysMassTime || $todaysConfessionTime || $todaysAdorationTime) 
                            <tr>
                                <td>
                                    @if ($todaysMassTime)<div class="titleblock smaextra_reli  smaextra_reli">Mass: {{$todaysMassTime}}</div>@endif   
                                    @if ($todaysConfessionTime)<div class="titleblock smaextra_reli  smaextra_reli">Confession: {{$todaysConfessionTime}}</div>@endif     
                                    @if ($todaysAdorationTime)<div class="titleblock smaextra_reli  smaextra_reli">Adoration:{{$todaysAdorationTime}}</div>@endif     
                                </td>
                            </tr>   
                        @endif  
                        <div class="share">
                            <a href="#" class="dropdown" data-toggle="dropdown"><img src="{{ URL::to('/') }}/image/share_icon1.svg"/></a>
                            <ul class="dropdown-menu">
                                <li><a href="#">Facebook</a></li>
                                <li><a href="#">Google +</a></li>
                                <li><a href="#">Twitter</a></li>
                            </ul>
                        </div>
                    </table>    
                </div>          
                <div class="content">
                    <table class="fullWidth">
                        @if ($religion['description'])
                            <tr>
                                <td>&nbsp;</td>
                            </tr>
                            <tr>
                                <td>
                                    <div id="description" style="overflow: hidden; height: {{$descriptionHeight}}px;">{!! nl2br($religion['description']) !!}</div>
                                    @if(strlen($religion['description']) >= '220') 
                                    <a id="readMore" class="read h21">Read more...</a>
                                    @else
                                        <span id="readMore"></span>
                                    @endif 
                                </td>
                            </tr>
                        @endif  
                            @if (isset($religion['denominationName']) && $religion['denominationName'])
                                <tr>
                                    <td class="smallfont tdtoppadd1">Denomination</td>
                                </tr>
                                <tr>
                                    <td>{{ $religion['denominationName'] }}</td>
                                </tr>   
                            @endif                         
                            @if (isset($religion['website']) && $religion['website'])
                                <tr>
                                    <td class="smallfont tdtoppadd1">Website</td>
                                </tr>
                                <tr>
                                    <td><a href="http://{{ $religion['website'] }}" target="_blank"><h2 class="h21">{{ $religion['website'] }}</h2></a></td>
                                </tr>   
                            @endif                          
                            <tr>
                                <td class="smallfont tdtoppadd1">Located In</td>
                            </tr>
                            <tr>
                                <td><h3>{{ $religion['city'] }}</h3></td>
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
                        @if ($religion['religionName'] == 'Christianity')
                            @foreach ($workingTimes as $wtKey => $wtArr)
                                @if($wtKey == "Mass" && count($wtArr) >0)
                                    <table>
                                        <tr>
                                            <td colspan="3" class="smallfont tdtoppadd1">Mass</td>
                                        </tr>
                                        @foreach ($wtArr[0] as $wtMassArrKey => $wtMass)
                                            @if ( !empty ( $wtMass ) )
                                            <tr>
                                                @if ( $wtMassArrKey == $today )
                                                    <td class="activeweekdays_reli daysWith">{{CommonController::getDaysShort($wtMassArrKey)}}</td>
                                                @else
                                                    <td class="inactiveweekdays daysWith">{{CommonController::getDaysShort($wtMassArrKey)}}</td>
                                                @endif    
                                                @foreach ($wtMass as $massKey => $mass)
                                                    @foreach ($mass as $massTimeKey => $massTime)
                                                        @if ( $wtMassArrKey == $today )
                                                            <td class="activeweekdays_reli">{{$massTime}}@if ($loop->parent->index+1 != $loop->parent->count),&nbsp;@endif</td>
                                                        @else
                                                            <td class="inactiveweekdays">{{$massTime}}@if ($loop->parent->index+1 != $loop->parent->count),&nbsp;@endif</td>
                                                        @endif                                              
                                                    @endforeach
                                                @endforeach
                                            </tr>
                                            @endif                           
                                        @endforeach   
                                    </table>                     
                                @elseif($wtKey == "Confession"  && count($wtArr) >0)
                                    <table>
                                        <tr>
                                            <td colspan="3" class="smallfont tdtoppadd1">Confession</td>
                                        </tr>
                                        @foreach ($wtArr[0] as $wtConfArrKey => $wtConf)
                                            @if ( !empty ( $wtConf ) )
                                                <tr>
                                                    @if ( $wtConfArrKey == $today )
                                                        <td class="activeweekdays_reli daysWith">{{CommonController::getDaysShort($wtConfArrKey)}}</td>
                                                    @else
                                                        <td class="inactiveweekdays daysWith">{{CommonController::getDaysShort($wtConfArrKey)}}</td>
                                                    @endif                                             
                                                    @foreach ($wtConf as $confession)
                                                        @foreach ($confession as $confessionTimeKey => $confessionTime)
                                                            @if ( $wtConfArrKey == $today )
                                                                <td class="activeweekdays_reli">{{$confessionTime}}@if ($loop->parent->index+1 != $loop->parent->count),&nbsp;@endif</td>
                                                            @else
                                                                <td class="inactiveweekdays">{{$confessionTime}}@if ($loop->parent->index+1 != $loop->parent->count),&nbsp;@endif</td>
                                                            @endif  
                                                        @endforeach
                                                    @endforeach
                                                </tr>
                                            @endif                           
                                        @endforeach
                                    </table>                                 
                                @elseif($wtKey == "Adoration"  && count($wtArr) >0)
                                    <table>
                                        <tr>
                                            <td colspan="3" class="smallfont tdtoppadd1">Adoration</td>
                                        </tr>
                                        @foreach ($wtArr[0] as $wtAdoArrKey => $wtAdo)
                                            @if ( !empty ( $wtAdo ) )
                                                <tr>
                                                    @if ( $wtAdoArrKey == $today )
                                                        <td class="activeweekdays_reli daysWith">{{CommonController::getDaysShort($wtAdoArrKey)}}</td>
                                                    @else
                                                        <td class="inactiveweekdays daysWith">{{CommonController::getDaysShort($wtAdoArrKey)}}</td>
                                                    @endif                                          
                                                    @foreach ($wtAdo as $adoration)
                                                        @foreach ($adoration as $adorationTimeKey => $adorationTime)
                                                            @if ( $wtAdoArrKey == $today )
                                                                <td class="activeweekdays_reli">{{$adorationTime}}@if ($loop->parent->index+1 != $loop->parent->count),&nbsp;@endif</td>
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
                    @endif       
                    <div class="suggestionblock">
                        <a href="#" data-toggle="modal" data-target="#exampleModal" data-whatever="@mdo" class="subcontent22">Suggest an edit</a>   
                    </div>   
                </div>
            </div>
            <div class="block22">
                <div class="white_t1 space">
                    <h2 class="titleh2 graycolor1">{{ $religion['name'] }} Location</h2>
                    <a href="https://www.google.com/maps/dir//{{ $religion['urlName'] }}/%40{{$religion['latitude']}},{{$religion['longitude']}},12z" title="{{ $religion['name'] }}" target="_blank" class="mapicon12"><img src="{{ URL::to('/') }}/image/map1.svg" alt="{{ $religion['name'] }}"/></a>
                </div>
                <div id="map" class="map"></div>
            </div>
            @if($photos)
                <div class="blockk1">
                <div class="block23">
                    <div class="white_Photo space"><h2 class="titleh2 graycolor">{{ $religion['name'] }} Photos</h2></div>
                </div>
                <div class="block231">
                    <div class="topdetail slideshow-container">
                        <ul id="lightSlider">
                        @foreach ($photos as $key => $photo)
                                <li data-thumb="{{ URL::to('/') }}/image/shadow_bottom.gif">
                                    <img src="{{ URL::to('/') }}/image/religion/{{$religion['id']}}/{{$photo['photoName']}}" alt="{{$loop->index}}{{ $religion['name'] }}" style="width:100%;height:100%" class="bottomarea">
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
                    <label id="suggession1" class="col-form-label labelfont">Suggestion:</label>
                    <textarea class="form-control nup" id="suggession" name="suggession"></textarea>
                    <div id="sugessionError"></div>                        
                </div>
            </div>
            <div class="modal-footer">
                <input type="hidden" class="form-control nup" id="type" name="type" value="3">
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
        $.get("<?php echo URL::to('/');?>/religion-related/<?php echo $religion['denominationId'];?>/<?php echo $religion['id'];?>", function(data, status){
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
