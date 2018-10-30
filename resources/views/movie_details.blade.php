@extends('layouts.app')
@section('content')
<?php use App\Http\Controllers\CommonController;?>
<div class="mcontainer">
    <div class="maincontainer">
        <div class="leftcontainer">
            <div class="paggination"><a href="{{ route('movies') }}" class="subcontent2 h21">Movies</a>&nbsp;&nbsp;>&nbsp;&nbsp;<span class="title">{{$movie['name']}}</span></div>
            <div class="block2">
                <div class="move_title toparea space">
                    <table class="fullWidth">
                        {!!CommonController::share($movie['name'])!!}
                        <tr>
                        <td><h1 class="titleblock">{{ $movie['name'] }}</h1></td>
                        </tr>
                        <tr>
                            <td><div class="titleblock white smaextra">{{ CommonController::getLanguage($movie['language']) }}</div></td>
                        </tr>                       
                    </table> 
                </div>
                <div class="content">
                    <table class="fullWidth">
                        <tr>
                            <td colspan="2">&nbsp;</td>
                        </tr>
                        @if($movie['description']) 
                            <tr>
                                <td colspan="2">
                                    <div id="description" style="overflow: hidden; height: {{$descriptionHeight}}px;">{!! nl2br($movie['description']) !!}</div>
                                    @if(strlen($movie['description']) >= '220') 
                                    <a id="readMore" class="read h21">Read more...</a>
                                    @else
                                        <span id="readMore"></span>
                                    @endif 
                                </td>
                            </tr>
                        @endif 
                        <tr>
                            <td colspan="2" class="smallfont tdtoppadd1">Producer</td>
                        </tr>
                        <tr>
                            <td colspan="2"><h3 class="h21" >{{ $movie['producer'] }}</h3></td>
                        </tr> 
                        <tr>
                            <td colspan="2" class="smallfont tdtoppadd1">Director</td>
                        </tr>
                        <tr>
                            <td colspan="2"><h3 class="h21" >{{ $movie['director'] }}</h3></td>
                        </tr> 
                        <tr>
                            <td colspan="2" class="smallfont tdtoppadd1">Cast</td>
                        </tr>
                        <tr>
                            <td colspan="2"><h3 class="h21" >{{ $movie['cast'] }}</h3></td>
                        </tr> 
                        <tr>
                            <td colspan="2" class="smallfont tdtoppadd1">Music</td>
                        </tr>
                        <tr>
                            <td colspan="2"><h3 class="h21" >{{ $movie['music'] }}</h3></td>
                        </tr> 
                        <!-- <tr>
                            <td colspan="2" class="smallfont tdtoppadd1">URL</td>
                        </tr>
                        <tr>
                            <td colspan="2"><h2><a href="#" target="_blank" class="h21" >{{ $movie['name'] }}</a></h2></td>
                        </tr> -->
                    </table>  
                </div>
                @if($movieTheatres)
                    <div class="movie">
                        <table class="fullWidth">
                            <tr>
                                <td style="padding-bottom:15px;">
                                    @foreach ($movieTheatres as $key => $movieTheatre)
                                        <div class="theatreBlock">
                                            <table class="fullWidth">
                                                <a href="https://www.google.com/maps/dir//{{ $movieTheatre['details']['name'] }} {{ $movieTheatre['details']['address1'] }} {{ $movieTheatre['details']['city'] }}, {{ $movieTheatre['details']['state'] }}, {{$movieTheatre['details']['zip'] }}/%40{{$movieTheatre['details']['latitude']}},{{$movieTheatre['details']['longitude']}},12z" title="{{$movieTheatre['details']['name']}}" target="_blank" class="mapicon3"><img src="{{ URL::to('/') }}/image/map1.svg" alt="{{$movieTheatre['details']['name']}}"/></a>
                                                <a href="http://{{ $movieTheatre['details']['bookingLink'] }}" title="{{ $movie['name'] }}" target="_blank" class="bookingIcon"><img src="{{ URL::to('/') }}/image/calendar.svg" alt="{{ $movie['name'] }}"/></a>
                                                <tr>
                                                    <td colspan="2">
                                                        <h1 class="space3 space2 titleblock1">
                                                            <a href="{{ URL::to('/') }}/{{config('app.defaultBaseURL.indian-theatre')}}/{{$movieTheatre['details']['urlName']}}" alt="" class="colorh11">
                                                                {{$movieTheatre['details']['name']}}
                                                            </a>
                                                        </h1>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td colspan="2" class="space2">
                                                        {{$movieTheatre['details']['address1'].', '.$movieTheatre['details']['city'].', '.$movieTheatre['details']['state'].', '.$movieTheatre['details']['zip']}}
                                                    </td>
                                                </tr> 
                                                <tr>
                                                    <td colspan="2"><h2 class="space2"><a href="http://{{ $movieTheatre['details']['website'] }}" target="_blank" class="h21" >{{$movieTheatre['details']['website']}}</a></h2></td>
                                                </tr>
                                                <tr>
                                                    <td colspan="2" style="padding-bottom:10px;"><a href="tel:{{ $movieTheatre['details']['phone1'] }}" target="_blank" class="space2 h21">{{$movieTheatre['details']['phone1']}}</td>
                                                </tr>                                              
                                                @foreach ($movieTheatre['dateTimeDetails'] as $key1 => $theatre)
                                                    <tr>
                                                        <td style="padding-top:8px; border-top:1px solid #f1f1f1;">
                                                            <table>                                                    
                                                                <tr>
                                                                    @if ( $theatre[0]['date'] == $today )
                                                                        <td  colspan="5" class="space2 smallfontMovie tdtoppadd2">{{$theatre[0]['date']}}</td>
                                                                    @else
                                                                        <td  colspan="5" class="space2 smallfont tdtoppadd2">{{$theatre[0]['date']}}</td>
                                                                    @endif    
                                                                </tr>
                                                                <tr>
                                                                    <td colspan="5" class="space2 space4">
                                                                        @if ( $theatre[0]['date'] == $today )
                                                                            <a href="http://{{ $movieTheatre['details']['bookingLink'] }}" target="_blank" class="activeweekdays_movie">
                                                                                @foreach ($theatre as $key1 => $time)
                                                                                    {{$time['dateTime']}}@if(!$loop->last), @endif
                                                                                @endforeach 
                                                                            </a>
                                                                        @else
                                                                            <a href="http://{{ $movieTheatre['details']['bookingLink'] }}" target="_blank" class="inactiveweekdays_moive">
                                                                                @foreach ($theatre as $key1 => $time)
                                                                                    {{$time['dateTime']}}@if(!$loop->last), @endif
                                                                                @endforeach 
                                                                            </a>                                                                    
                                                                        @endif 
                                                                    </td>
                                                                </tr>
                                                            </table>  
                                                        </td>
                                                    </tr>  
                                                @endforeach  
                                            </table> 
                                        </div>
                                    @endforeach
                                </td>
                            </tr>
                        </table>    
                    </div>
                @endif
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
                <h2 class="titleh2 graycolor1">{{ $movie['name'] }} Trailer</h2></div>
                <div id="video" class="video">
                    <iframe width="400" height="245" src="{{ $movie['trailer'] }}" frameborder="0"></iframe>
                </div>
            </div>
            @if($photos)
                <div class="blockk1">
                    <div class="block23">
                        <div class="white_Photo space"><h2 class="titleh2 graycolor">{{ $movie['name'] }} Photos</h2></div>
                    </div>
                    <div class="block231">
                        <div class="topdetail slideshow-container">
                            <ul id="lightSlider">
                                @foreach ($photos as $key => $photo)
                                    <li data-thumb="{{ URL::to('/') }}/image/shadow_bottom.gif">
                                        <img src="{{ URL::to('/') }}/image/movie/{{$movie['id']}}/{{$photo['photoName']}}" alt="{{$loop->index}}{{ $movie['name'] }}" style="width:100%;height:100%" class="bottomarea">
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
                <input type="hidden" class="form-control nup" id="type" name="type" value="4">
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

    /*---------- Image Slider ----------*/
    $('#lightSlider').lightSlider({
        gallery: true,
        item: 1,
        loop: true,
        slideMargin: 0,
        thumbItem: 9
    });
    
    /*---------- Image Slider End----------*/

    document.querySelector('#readMore').addEventListener('click', function() {
        document.querySelector('#description').style.height= 'auto';
        this.style.display= 'none';
    }); 
  
    
</script>

@endsection