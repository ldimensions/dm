@extends('layouts.app')
@section('content')
<?php use App\Http\Controllers\CommonController;?>
<div class="mcontainer">
    <div class="maincontainer">
        <div class="leftcontainer">
            <div class="paggination"><a href="{{ route('movies') }}" class="subcontent2 h21">Movies</a>&nbsp;&nbsp;>&nbsp;&nbsp;<span class="title">{{$theatre['name']}}</span></div>
            <div class="block2">
                <div class="move_title toparea space">
                    <table class="fullWidth">
                        {!!CommonController::share($theatre['name'])!!}
                        <tr>
                        <td><h1 class="titleblock">{{$theatre['name']}}</h1></td>
                        </tr>
                        <tr>
                            <td>
                                <div class="titleblock white smaextra">
                                    {{$theatre['address1'].', '.$theatre['city'].', '.$theatre['state'].', '.$theatre['zip']}}
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td><a href="tel:{{ $theatre['phone1'] }}" class="titleblock white smaextra extra">{{$theatre['phone1']}}</a></td>
                        </tr>  
                    </table> 
                </div>
                <div class="content">
                    <table class="fullWidth">
                        <tr>
                            <td colspan="2">&nbsp;</td>
                        </tr>
                            @if($theatre['description']) 
                            <tr>
                                <td colspan="2">
                                    <div id="description" style="overflow: hidden; height: {{$descriptionHeight}}px;">{!! nl2br($theatre['description']) !!}</div>
                                    @if(strlen($theatre['description']) >= '220') 
                                    <a id="readMore" class="read h21">Read more...</a>
                                    @else
                                        <span id="readMore"></span>
                                    @endif 
                                </td>
                            </tr>
                        @endif 

                        <tr>
                            <td colspan="2" class="smallfont tdtoppadd1">URL</td>
                        </tr>
                        <tr>
                            <td colspan="2"><h2><a href="http://{{ $theatre['website'] }}" target="_blank" class="h21" >{{$theatre['website']}}</a></h2></td>
                        </tr>
                    </table>  
                </div>
                <div class="movie">
                    <table class="fullWidth">
                    <tr>
                            <td style="padding-bottom:15px;">
                            <div class="theatreBlock">
                                <table class="fullWidth">
                                    <tr>
                                        <td>
                                            <div class="smallImage1">
                                                <img src="{{ URL::to('/') }}/image/noimage.svg" alt="" style="width:100%;height:100%">
                                            </div>
                                            <h2 class="content11 titletopmargin"> <a href="#" title="" class="colorh11">Movie Name</a></h2>                                                      
                                            <div class="content2 Subtitle">Language</div>
                                        </td>
                                    </tr> 
                                    <tr>
                                    <td style="padding-top:8px; border-top:1px solid #f1f1f1;">
                                    <table>
                                    <tr>
                                            <td  colspan="5" class="space2 smallfont tdtoppadd2">Oct 20 Sun</td>
                                        </tr>
                                        <tr>
                                            <td colspan="5" class="space2"><a href="#"target="_blank" class=" inactiveweekdays_moive h21">09:00 am,&nbsp;12:00 am,&nbsp;03:00 pm,&nbsp;
                                            06:00 pm,&nbsp;09:00 pm,&nbsp;12:00 pm&nbsp;</a></td>
                                        </tr>
                                        <tr>
                                            <td  colspan="5" class="space2 smallfontMovie tdtoppadd2">Oct 20 Mon</td>
                                        </tr>
                                        <tr>
                                            <td colspan="5" class="space2 space4"><a href="#"target="_blank" class="activeweekdays_movie">09:00 am,&nbsp;12:00 am,&nbsp;03:00 pm,&nbsp;
                                            06:00 pm,&nbsp;09:00 pm,&nbsp;12:00 pm&nbsp;</a></td>
                                        </tr>

                                    </table>  
                                    </td>
                                    </tr>    
                                </table> 
                            </div>
                            <div class="theatreBlock">
                                <table class="fullWidth">
                                    <tr>
                                        <td>
                                            <div class="smallImage1">
                                                <img src="{{ URL::to('/') }}/image/noimage.svg" alt="" style="width:100%;height:100%">
                                            </div>
                                           <h2 class="content11 titletopmargin"> <a href="#" title="" class="colorh11">Movie Name</a></h2>                                                      
                                            <div class="content2 Subtitle">Language</div>
                                        </td>
                                    </tr> 
                                    <tr>
                                    <td style="padding-top:8px; border-top:1px solid #f1f1f1;">
                                    <table>
                                    <tr>
                                            <td  colspan="5" class="space2 smallfont tdtoppadd2">Oct 20 Sun</td>
                                        </tr>
                                        <tr>
                                            <td colspan="5" class="space2"><a href="#"target="_blank" class=" inactiveweekdays_moive h21">09:00 am,&nbsp;12:00 am,&nbsp;03:00 pm,&nbsp;
                                            06:00 pm,&nbsp;09:00 pm,&nbsp;12:00 pm&nbsp;</a></td>
                                        </tr>
                                        <tr>
                                            <td  colspan="5" class="space2 smallfontMovie tdtoppadd2">Oct 20 Mon</td>
                                        </tr>
                                        <tr>
                                            <td colspan="5" class="space2 space4"><a href="#"target="_blank" class="activeweekdays_movie">09:00 am,&nbsp;12:00 am,&nbsp;03:00 pm,&nbsp;
                                            06:00 pm,&nbsp;09:00 pm,&nbsp;12:00 pm&nbsp;</a></td>
                                        </tr>

                                    </table>  
                                    </td>
                                    </tr>    
                                </table> 
                            </div>
                            </td>
                        </tr>
                    </table>    
                </div>
                <div class="content">
                    <table class="fullWidth">
                    <tr>
                        <td colspan="2">
                            <div class="suggestionblock">
                                <a href="#" data-toggle="modal" data-target="#exampleModal" data-whatever="@mdo" class="subcontent22">Suggest an edit</a>   
                            </div> 
                        </td>
                    </tr> 
                    </table>
                </div>

            </div>
            <div class="block22">
                <div class="white_t1 space">
                    <h2 class="titleh2 graycolor1">{{ $theatre['name'] }} Location</h2>
                    <a href="https://www.google.com/maps/dir//{{ $theatre['name'] }} {{ $theatre['address1'] }} {{ $theatre['city'] }}, {{ $theatre['state'] }}, {{$theatre['zip'] }}/%40{{$theatre['latitude']}},{{$theatre['longitude']}},12z" title="{{$theatre['name']}}" target="_blank" class="mapicon12"><img src="{{ URL::to('/') }}/image/map1.svg" alt="{{$theatre['name']}}"/></a>
                </div>
                <div id="map" class="map"></div>
            </div>             
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
                <input type="hidden" class="form-control nup" id="type" name="type" value="5">
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

     $( document ).ready(function() {
        $.get("<?php echo URL::to('/');?>/theatre-related/<?php echo $theatre['id'];?>", function(data, status){
            if(status=="success"){
                document.getElementById("related").innerHTML = data;
            }
        });
    });     
    
     function initMap() {
        var lat = parseFloat("{{ $theatre['latitude'] }}");
        var long = parseFloat("{{ $theatre['longitude'] }}");
        console.log(lat+'#'+long);
        var label = "{{ $theatre['name'] }}";
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
    $('#lightSlider').lightSlider({
        gallery: true,
        item: 1,
        loop: true,
        slideMargin: 0,
        thumbItem: 9
    });
    
      
    
</script>
<script async defer
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCQJp0CkLijcKXd44Pyn6QWX0Da0PwPKtc&callback=initMap">
</script>
@endsection