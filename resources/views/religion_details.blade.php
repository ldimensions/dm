@extends('layouts.app')
@section('content')
<div class="mcontainer">
<div class="maincontainer">
<div class="leftcontainer">
    <div class="paggination"><a href="{{ URL::to('/') }}/{{config('app.defaultBaseURL.dallas-malayali-church')}}" class="subcontent2 h21">Religions</a>&nbsp;&nbsp;>&nbsp;&nbsp;<span class="title">Details</span></div>
    <div class="block2">
        <div class="relig_title toparea space">
            <table class="fullWidth">
                <tr>
                    <td colspan="2" ><h1>{{ $religion['name'] }}</h1></td>
                </tr> 
                <tr>
                    <td colspan="2"><span class="white smaextra_res">{{ $religion['address1'] }} {{ $religion['address2'] }}, {{ $religion['city'] }}, {{ $religion['state'] }}, {{ $religion['zip'] }}</span></td>
                </tr>
                <tr>
                    <td colspan="2"><span class="white smaextra_res"><a href="tel:{{ $religion['phone1'] }}"  class="extra_res">{{ $religion['phone1'] }}</a></span></td>
                </tr> 
                @if ($todaysMassTime || $todaysConfessionTime || $todaysAdorationTime) 
                    <tr>
                        <td colspan="2">
                            @if ($todaysMassTime)<div class="white smaextra_res">Mass: <span>{{$todaysMassTime}}</span></div>@endif   
                            @if ($todaysConfessionTime)<div class="white smaextra_res">Confession: <span>{{$todaysConfessionTime}}</span></div>@endif     
                            @if ($todaysAdorationTime)<div class="white smaextra_res">Adoration:<span>{{$todaysAdorationTime}}</span></div>@endif     
                        </td>
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
                <td colspan="2" class="tdtoppadd"><div id="description" style="overflow: hidden; height: 120px;">{{ $religion['description'] }}</div><button>Read more</button></td>
            </tr>
            @if (isset($religion['website']) && $religion['website'])
                <tr>
                    <td colspan="2" class="smallfont tdtoppadd1">Website:</td>
                </tr>
                <tr>
                    <td colspan="2"><h2><a href="http://{{ $religion['website'] }}" target="_blank" class="h21" >{{ $religion['website'] }}</a></h2></td>
                </tr>   
            @endif                 
            <tr>
                <td colspan="2" class="smallfont tdtoppadd1">Located In:</td>
            </tr>
            <tr>
                <td colspan="2"><h3>{{ $religion['city'] }}</h3></td>
            </tr>
            @if (isset($distance) && $distance)
                <tr>
                    <td colspan="2" class="smallfont tdtoppadd1">Distance:</td>
                </tr>
                <tr>
                    <td colspan="2">{{ $distance }}</td>
                </tr>
            @endif

            @if($workingTimes)
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
        @endif       

        </table>
        <div class="suggestionblock">
            <a href="#" data-toggle="modal" data-target="#exampleModal" data-whatever="@mdo" class="subcontent22">Suggest an edit</a>   
        </div>   
    </div>
    </div>
    <div class="block22">
        <div class="white_t space"><h2 class="titleh2 graycolor">{{ $religion['name'] }} Location</h2></div>
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

    document.querySelector('button').addEventListener('click', function() {
        document.querySelector('#description').style.height= 'auto';
        this.style.display= 'none';
    });
    
</script>
<script async defer
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCQJp0CkLijcKXd44Pyn6QWX0Da0PwPKtc&callback=initMap">
</script>
@endsection



