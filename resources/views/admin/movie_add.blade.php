@extends('layouts.admin')

@section('content')
  <div id="page-wrapper">
    <div style="position: relative;height:30px;"></div>
    @if ($errors->any())
    <div class="alert alert-danger alert-dismissable">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    @if($movie['id']) {{ 'Edit movie' }} @else {{ 'Add movie' }} @endif
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="panel-body">                                          
                            <form name="movie" action="{{ url('/admin/movie_add') }}" method="POST" role="form" enctype="multipart/form-data">
                                {{ csrf_field() }}
                                <input type="hidden" name="id" value="{{ $movie['id'] }}" id="id">
                                <input type="hidden" name="urlId" value="{{ $movie['urlId'] }}" id="urlId">
                                <input type="hidden" name="seoId" value="{{ $movie['seoId'] }}" id="urlId">  
                                
                                <ul class="nav nav-tabs">
                                    <li class="active"><a href="#movie" data-toggle="tab">movie</a>
                                    </li>
                                    <li><a href="#theatre" data-toggle="tab">Theatre</a>
                                    </li>
                                    <li><a href="#meta" data-toggle="tab">Meta</a>
                                    </li>
                                    <li><a href="#images" data-toggle="tab">Images</a>
                                    </li> 
                                </ul>  
                                <div class="tab-content">
                                    <div class="tab-pane fade in active" id="movie" style="position: relative;min-height: 610px;height:610px;">
                                        <br/><br/>
                                        <div class="col-lg-6">
                                            <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                                                <label>Name</label>
                                                <input name="name" value="{{ old('name', $movie['name']) }}" id="name" maxlength="100" class="form-control">
                                                <small class="text-danger">{{ $errors->first('name') }}</small>
                                            </div>
                                            <div class="form-group{{ $errors->has('urlName') ? ' has-error' : '' }}">
                                                <label>URL</label>
                                                <input name="urlName" value="{{ old('urlName', $movie['urlName']) }}" id="urlName" maxlength="180" class="form-control" >
                                                <input type="hidden" name="urlNameChk" value="{{ $movie['urlName'] }}" id="urlNameChk" class="form-control" >
                                                <small class="text-danger">{{ $errors->first('urlName') }}</small>                                            
                                            </div> 
                                            <div class="form-group">
                                                <label>Language</label>
                                                <select name="language" id="language" class="form-control">
                                                    <option value="1" @if(old('language', $movie['language']) == 1) {{ 'selected' }} @endif>Hindi</option>
                                                    <option value="2" @if(old('language', $movie['language']) == 2) {{ 'selected' }} @endif>Malayalam</option>
                                                    <option value="3" @if(old('language', $movie['language']) == 3) {{ 'selected' }} @endif>Tamil</option>
                                                    <option value="4" @if(old('language', $movie['language']) == 4) {{ 'selected' }} @endif>Telugu</option>
                                                    <option value="5" @if(old('language', $movie['language']) == 5) {{ 'selected' }} @endif>Kannada</option>
                                                    <option value="6" @if(old('language', $movie['language']) == 6) {{ 'selected' }} @endif>Punjabi</option>
                                                    <option value="7" @if(old('language', $movie['language']) == 7) {{ 'selected' }} @endif>Urdu</option>
                                                    <option value="8" @if(old('language', $movie['language']) == 8) {{ 'selected' }} @endif>Bengali</option>
                                                    <option value="9" @if(old('language', $movie['language']) == 9) {{ 'selected' }} @endif>Gujarathi</option>
                                                    <option value="10" @if(old('language', $movie['language']) == 10) {{ 'selected' }} @endif>Marathi</option>
                                                </select>
                                            </div>                                             
                                            <div class="form-group">
                                                <label>Premium</label>
                                                <select name="premium" id="premium" class="form-control">
                                                    <option value="0" @if(old('premium', $movie['premium']) == 0) {{ 'selected' }} @endif >No</option>
                                                    <option value="1" @if(old('premium', $movie['premium']) == 1) {{ 'selected' }} @endif >Yes</option>
                                                </select>
                                            </div>                                          
                                            <div class="form-group">
                                                <label>Order</label>
                                                <input name="order" value="{{ old('order', $movie['order']) }}" id="order" maxlength="15" class="form-control">
                                            </div>                                 
                                            <div class="form-group">
                                                <label>Is Disabled</label>
                                                <select name="is_disabled" id="is_disabled" class="form-control">
                                                    <option value="0" @if(old('is_disabled', $movie['is_disabled']) == 0) {{ 'selected' }} @endif >No</option>
                                                    <option value="1" @if(old('is_disabled', $movie['is_disabled']) == 1) {{ 'selected' }} @endif >Yes</option>
                                                </select>
                                            </div>    
                                            <div class="form-group">
                                                <label>Trailer</label>
                                                <input name="trailer" value="{{ old('trailer', $movie['trailer']) }}" id="trailer" maxlength="240" class="form-control">
                                            </div>                                                                                                                            
                                        </div>
                                        <div class="col-lg-6">  
                                            <div class="form-group">
                                                <label>Description</label>
                                                <textarea name="description" class="form-control" rows="5">{{ old('description', $movie['description']) }}</textarea>
                                            </div>                                            
                                            <div class="form-group{{ $errors->has('cast') ? ' has-error' : '' }}">
                                                <label>Cast</label>
                                                <input name="cast" value="{{ old('cast', $movie['cast']) }}" id="cast" maxlength="150" class="form-control">
                                                <small class="text-danger">{{ $errors->first('cast') }}</small>                                            
                                            </div>   
                                            <div class="form-group">
                                                <label>Music</label>
                                                <input name="music" value="{{ old('music', $movie['music']) }}" id="music" maxlength="150" class="form-control">
                                            </div>  
                                            <div class="form-group{{ $errors->has('director') ? ' has-error' : '' }}">
                                                <label>Director</label>
                                                <input name="director" value="{{ old('director', $movie['director']) }}" id="director" maxlength="150" class="form-control">
                                                <small class="text-danger">{{ $errors->first('director') }}</small>                                            
                                            </div>  
                                            <div class="form-group">
                                                <label>Producer</label>
                                                <input name="producer" value="{{ old('producer', $movie['producer']) }}" id="producer" maxlength="150" class="form-control">
                                            </div>
                                        </div>                                     
                                    </div>
                                    <div class="tab-pane fade" id="theatre" style="position: relative;min-height: 255px;height:255px;height:auto;overflow: auto;" >
                                        <br/><br/>
                                        <div id="theatreDiv">
                                            @if($movie['id'] && count($movieTimes) >0) 
                                                <input type="text" name="theatreCount" id="theatreCount" value="{{count($movieTimes)}}"/>
                                                <span style="display:none">{{$index = 1}}</span>   
                                                @foreach ($movieTimes as $theatreKey => $movieTime)
                                                
                                                    @if($theatreKey == 1)
                                                        <div class="col-lg-12 col-xs-12 col-sm-12">  
                                                    @else 
                                                        <div class="col-lg-12 col-xs-12 col-sm-12" id="theatre_div_{{$theatreKey}}">  
                                                    @endif 
                                                            <div class="col-lg-6 col-xs-10 col-sm-10"> 
                                                                <div class="panel panel-default">
                                                                    <div class="panel-body">
                                                                        <div class="form-group">
                                                                            <label>Theatre</label>
                                                                            <select name="theatre_{{$index}}" value="" id="theatre" class="form-control">
                                                                                @foreach ($theatres as $timeKey => $theatre)
                                                                                    <option 
                                                                                        value="{{$theatre['id']}}"    
                                                                                        @if($theatreKey == $theatre['id']) {{ 'selected' }} @endif                                                                                
                                                                                        >
                                                                                        {{$theatre['name']}} ({{$theatre['cityName']}})
                                                                                    </option>
                                                                                @endforeach                                                
                                                                            </select>
                                                                        </div>
                                                                        <div class="form-group">
                                                                            <label>Booking Link</label>
                                                                            <input type="text" name="bookingLink_{{$index}}" id="boolingLink" value="{{(isset($movieBookingLink[$theatreKey]))?$movieBookingLink[$theatreKey]:''}}" class="form-control"/>
                                                                        </div>
                                                                        @foreach ($movieTime as $key => $timeRs) 
                                                                            <input type="text" name="dateCount_{{$index}}" id="dateCount_{{$index}}" value="{{count($timeRs)}}"/>
                                                                            <div id="dateDiv_{{$theatreKey}}" >
                                                                                @foreach ($timeRs as $timeKey => $time) 
                                                                                    <div class="form-group" id="{{$theatreKey}}_{{$timeKey+1}}">
                                                                                        <input type="datetime-local" id="" name="dateTime_{{$index}}[]" value="{{$time}}" /> 
                                                                                        @if($timeKey == 0)
                                                                                            <button type="button" class="btn btn-default btn-sm" id="addDate_btn_{{$theatreKey}}"><i class="fa glyphicon-plus"></i></button>   
                                                                                            <script>
                                                                                                $("#addDate_btn_"+{{$theatreKey}}).click(function(){
                                                                                                    console.log("#addDate_btn_"+{{$theatreKey}});
                                                                                                    var dateCountVal;
                                                                                                    //var theatreIdVal                            =   document.getElementById("theatreCount").value;
                                                                                                    dateCountVal                                =   document.getElementById("dateCount_"+{{$index}}).value;
                                                                                                    dateCountId                                 =   parseInt(dateCountVal)+1;
                                                                                                    document.getElementById("dateCount_"+{{$index}}).value     =   dateCountId;
                                                                                                    
                                                                                                    $("#dateDiv_"+{{$theatreKey}}).append(`
                                                                                                                            <div class="form-group" id="`+dateCountId+`">
                                                                                                                                <input type="datetime-local" id="" name="dateTime_`+{{$index}}+`[]" value="" /> 
                                                                                                                                <button type="button" class="btn btn-default btn-sm" onClick="removeDate('`+dateCountId+`')"><i class="glyphicon glyphicon-remove"></i></button>
                                                                                                                            </div>
                                                                                                                        `); 

                                                                                                }); 
                                                                                            </script>                                                                                                                                                                                                                                                                                 
                                                                                        @else 
                                                                                            <button type="button" class="btn btn-default btn-sm" onClick="removeDate('{{$theatreKey}}_{{$timeKey+1}}')"><i class="glyphicon glyphicon-remove"></i></button>                                                                                            
                                                                                        @endif 
                                                                                                                                                                            
                                                                                    </div>
                                                                                @endforeach   
                                                                            </div>
                                                                        @endforeach   
                                                                    </div>                                                 
                                                                </div> 
                                                            </div> 
                                                        @if($index == 1)
                                                            <div class="col-lg-6 col-xs-2 col-sm-2"> 
                                                                <button type="button" class="btn btn-default btn-sm" id="addTheatre" ><i class="fa glyphicon-plus"></i></button>
                                                            </div>                                                           
                                                        @else 
                                                            <div class="col-lg-6 col-xs-2 col-sm-2"> 
                                                                --<button type="button" class="btn btn-default btn-sm" id="removeTheatre_{{$theatreKey}}"><i class="glyphicon glyphicon-remove"></i></button>
                                                            </div> 
                                                            <script>
                                                                $("#removeTheatre_"+{{$theatreKey}}).click(function(){
                                                                    console.log({{$theatreKey}});
                                                                    $('#theatre_div_'+{{$theatreKey}}).remove();
                                                                });
                                                            </script>
                                                        @endif                                                                                                                                      
                                                                                                          
                                                    </div> 
                                                    <span style="display:none">{{$index++}}</span>                                            
                                                @endforeach                                            
                                            @else 
                                                <input type="text" name="theatreCount" id="theatreCount" value="1"/>
                                                <div class="col-lg-12 col-xs-12 col-sm-12">  
                                                    <div class="col-lg-6 col-xs-10 col-sm-10"> 
                                                        <div class="panel panel-default">
                                                            <div class="panel-body">
                                                                <div class="form-group">
                                                                    <label>Theatre</label>
                                                                    @if($movie['id'])
                                                                        <select name="theatre_1" value="" id="theatre" class="form-control">
                                                                            @foreach ($theatres as $key => $theatre)
                                                                                <option 
                                                                                    value="{{$theatre['id']}}">
                                                                                    {{$theatre['name']}} ({{$theatre['cityName']}})
                                                                                </option>
                                                                            @endforeach                                                
                                                                        </select>
                                                                    @else 
                                                                        <select name="theatre_1" value="{{ old('theatre', $movie['theatre']) }}" id="theatre" class="form-control">
                                                                            @foreach ($theatres as $key => $theatre)
                                                                                <option 
                                                                                    value="{{$theatre['id']}}"
                                                                                    @if(old('theatre', $movie['theatre']) == $theatre['id']) {{ 'selected' }} @endif
                                                                                    >
                                                                                    {{$theatre['name']}} ({{$theatre['cityName']}})
                                                                                </option>
                                                                            @endforeach                                                
                                                                        </select>    
                                                                    @endif  
                                                                </div> 
                                                                <div class="form-group">
                                                                    <label>Booking Link</label>
                                                                    <input type="text" name="bookingLink_1" id="boolingLink" value="" class="form-control"/>
                                                                </div>                                                                
                                                                <div id="dateDiv">
                                                                    <input type="text" name="dateCount_1" id="dateCount_1" value="1"/>
                                                                    <div class="form-group">
                                                                        <input type="datetime-local" id="" name="dateTime_1[]" value="" /> 
                                                                        <button type="button" class="btn btn-default btn-sm" id="addDate"><i class="fa glyphicon-plus"></i></button>
                                                                    </div>
                                                                </div> 
                                                            </div>                                                 
                                                        </div> 
                                                    </div>                                                                          
                                                    <div class="col-lg-6 col-xs-2 col-sm-2"> 
                                                        <button type="button" class="btn btn-default btn-sm" id="addTheatre" ><i class="fa glyphicon-plus"></i></button>
                                                    </div>                                                 
                                                </div>                                            
                                            @endif                                                                                                                                  
                                        </div>
                                    </div>    
                                    <div class="tab-pane fade" id="meta" style="position: relative;min-height: 520px;">
                                        <br/><br/>
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label>SEOMetaTitle</label>
                                                <input name="SEOMetaTitle" value="{{ old('SEOMetaTitle', $movie['SEOMetaTitle']) }}" id="SEOMetaTitle" maxlength="70" class="form-control">
                                            </div> 
                                            <div class="form-group">
                                                <label>SEOMetaDesc</label>
                                                <textarea name="SEOMetaDesc" class="form-control" rows="5">{{ old('SEOMetaDesc', $movie['SEOMetaDesc']) }}</textarea>                                                
                                            </div> 
                                            <div class="form-group">
                                                <label>SEOMetaPublishedTime</label>
                                                <input name="SEOMetaPublishedTime" value="{{ old('SEOMetaPublishedTime', $movie['SEOMetaPublishedTime']) }}" id="SEOMetaPublishedTime" maxlength="15" class="form-control">
                                            </div> 
                                            <div class="form-group">
                                                <label>SEOMetaKeywords</label>
                                                <input name="SEOMetaKeywords" value="{{ old('SEOMetaKeywords', $movie['SEOMetaKeywords']) }}" id="SEOMetaKeywords" maxlength="150" class="form-control">
                                            </div> 
                                            <div class="form-group">
                                                <label>OpenGraphTitle</label>
                                                <input name="OpenGraphTitle" value="{{ old('OpenGraphTitle', $movie['OpenGraphTitle']) }}" id="OpenGraphTitle" maxlength="20" class="form-control">
                                            </div>                                         
                                        </div>
                                        <div class="col-lg-6">  
                                            <div class="form-group">
                                                <label>OpenGraphDesc</label>
                                                <input name="OpenGraphDesc" value="{{ old('OpenGraphDesc', $movie['OpenGraphDesc']) }}" id="OpenGraphDesc" maxlength="150" class="form-control">
                                            </div> 
                                            <div class="form-group">
                                                <label>OpenGraphUrl</label>
                                                <input name="OpenGraphUrl" value="{{ old('OpenGraphUrl', $movie['OpenGraphUrl']) }}" id="OpenGraphUrl" maxlength="150" class="form-control">
                                            </div> 
                                            <div class="form-group">
                                                <label>OpenGraphPropertyType</label>
                                                <input name="OpenGraphPropertyType" value="{{ old('OpenGraphPropertyType', $movie['OpenGraphPropertyType']) }}" id="OpenGraphPropertyType" maxlength="25" class="form-control">
                                            </div> 
                                            <div class="form-group">
                                                <label>OpenGraphPropertyLocale</label>
                                                <input name="OpenGraphPropertyLocale" value="{{ old('OpenGraphPropertyLocale', $movie['OpenGraphPropertyLocale']) }}" id="OpenGraphPropertyLocale" maxlength="150" class="form-control">
                                            </div> 
                                            <div class="form-group">
                                                <label>OpenGraphPropertyLocaleAlternate</label>
                                                <input name="OpenGraphPropertyLocaleAlternate" value="{{ old('OpenGraphPropertyLocaleAlternate', $movie['OpenGraphPropertyLocaleAlternate']) }}" id="OpenGraphPropertyLocaleAlternate" maxlength="150" class="form-control">
                                            </div>   
                                            <div class="form-group">
                                                <label>OpenGraph</label>
                                                <input name="OpenGraph" value="{{ old('OpenGraph', $movie['OpenGraph']) }}" id="OpenGraph" maxlength="150" class="form-control">
                                            </div>                                                                                                                                                                                                                                                                                                                                                                                                     
                                        </div>                                         
                                    </div> 
                                    <div class="tab-pane fade" id="images" style="position: relative;min-height: 210px;">
                                        <br/><br/>
                                        <div class="col-lg-6">
                                            <div class="form-group{{ $errors->has('thumbnail') ? ' has-error' : '' }}">
                                                <label>Thumbnail</label>
                                                <input type="file" class="form-control" name="thumbnail[]" />
                                                <small class="text-danger">{{ $errors->first('thumbnail') }}</small>
                                            </div> 
                                            <div class="form-group{{ $errors->has('photos.*') ? ' has-error' : '' }}">
                                                <label>Main Images</label>
                                                <input type="file" class="form-control" name="photos[]" multiple />
                                                <small class="text-danger">{{ $errors->first('photos.*') }}</small>                                                
                                            </div>                                                                                   
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label>Thumbnail</label>
                                                @foreach ($photos as $key => $photo)
                                                    @if($photo['is_primary'] == 1)
                                                        <div class="smallImage">
                                                            <img src="{{ URL::to('/') }}/image/movie/{{$movie['id']}}/{{$photo['photoName']}}"  style="width:100px;height:100px">
                                                        </div>
                                                    @endif
                                                @endforeach
                                            </div> 
                                            
                                            <div class="form-group">
                                                <div>Featured</div>
                                                @foreach ($photos as $key => $photo)
                                                    @if($photo['is_primary'] == 0)
                                                        <div class="smallImage" style="float:left;padding:10px;">
                                                            <img src="{{ URL::to('/') }}/image/movie/{{$movie['id']}}/{{$photo['photoName']}}"  style="width:100px;height:100px">
                                                        </div>                                                    
                                                    @endif
                                                @endforeach
                                            </div>                                                                                                 
                                        </div>
                                    </div>                             
                                </div>
                                <div style="position:relative;widht:100%">                                  
                                    <button type="submit" class="btn btn-default">Submit</button>
                                    <a href="{{ url('/admin/movies') }}"><button type="button" class="btn btn-default">Cancel</button></a>                                                              
                                </div>
                            </form>
                        </div>
                    </div>
                    <!-- /.row (nested) -->
                </div>
                <!-- /.panel-body -->
            </div>
            <!-- /.panel -->
        </div>
            <!-- /.col-lg-12 -->
    </div>
    
</div>
<script>
    $("#addTheatre").click(function(){
        var theatreIdVal                    =   parseInt(document.getElementById("theatreCount").value)+1;
        document.getElementById("theatreCount").value = theatreIdVal;

        // var dateIdVal;
        // dateIdVal                           =   document.getElementById("dateCount").value;
        // dateIdValSplit                      =   dateIdVal.split("_");
        // dateId                              =   parseInt(dateIdValSplit[1])+1;
        // dateNewVal                          =   dateIdValSplit[0]+"_"+dateId;        
        // document.getElementById("dateCount").value     =   dateNewVal;

        $('#theatreDiv').append(`
                                    <div class="col-lg-12 col-xs-12 col-sm-12" id="theatre_div_`+theatreIdVal+`">  
                                        <div class="col-lg-6 col-xs-10 col-sm-10"> 
                                            <div class="panel panel-default">
                                                <div class="panel-body">
                                                    <div class="form-group">
                                                        <label>Theatre</label>
                                                        <select name="theatre_`+theatreIdVal+`" value="" id="theatre" class="form-control">
                                                            @foreach ($theatres as $key => $theatre)
                                                                <option 
                                                                    value="{{$theatre['id']}}"
                                                                    >
                                                                    {{$theatre['name']}} ({{$theatre['cityName']}})
                                                                </option>
                                                            @endforeach                                                
                                                        </select>
                                                    </div> 
                                                    <div class="form-group">
                                                        <label>Booking Link</label>
                                                        <input type="text" name="bookingLink_`+theatreIdVal+`" id="boolingLink" value="" class="form-control"/>
                                                    </div>                                                     
                                                    <div id="dateDiv_`+theatreIdVal+`">
                                                        <input type="text" name="dateCount_`+theatreIdVal+`" id="dateCount_`+theatreIdVal+`" value="1"/>
                                                            <div class="form-group">
                                                                <input type="datetime-local" id="" name="dateTime_`+theatreIdVal+`[]" value="" /> 
                                                                <button type="button" class="btn btn-default btn-sm" id="addDate_btn_`+theatreIdVal+`"><i class="fa glyphicon-plus"></i></button>
                                                            </div>                                                                                                                           
                                                        </div>                                                             
                                                    </div> 
                                                </div>                                                 
                                            </div> 

                                            <div class="col-lg-6 col-xs-2 col-sm-2"> 
                                                -<button type="button" class="btn btn-default btn-sm" id="removeTheatre_`+theatreIdVal+`" ><i class="glyphicon glyphicon-remove"></i></button>
                                            </div>   
                                        </div>
                                    </div>                                                                          
                                </div>                                   
                            `); 
        $("#addDate_btn_"+theatreIdVal).click(function(){
            var dateCountVal;
            //var theatreIdVal                            =   document.getElementById("theatreCount").value;
            dateCountVal                                =   document.getElementById("dateCount_"+theatreIdVal).value;
            dateCountId                                 =   parseInt(dateCountVal)+1;
            document.getElementById("dateCount_"+theatreIdVal).value     =   dateCountId;
            
            $("#dateDiv_"+theatreIdVal).append(`
                                    <div class="form-group" id="`+dateCountId+`">
                                        <input type="datetime-local" id="" name="dateTime_`+theatreIdVal+`[]" value="" /> 
                                        <button type="button" class="btn btn-default btn-sm" onClick="removeDate('`+dateCountId+`')"><i class="glyphicon glyphicon-remove"></i></button>
                                    </div>
                                `); 

        });   
        $("#removeTheatre_"+theatreIdVal).click(function(){
            console.log(theatreIdVal);
            $('#theatre_div_'+theatreIdVal).remove();
        });

    });

    $("#addDate").click(function(){
        var dateCountVal;
        var theatreIdVal                            =   document.getElementById("theatreCount").value;
        dateCountVal                                =   document.getElementById("dateCount_1").value;
        dateCountId                                 =   parseInt(dateCountVal)+1;
        document.getElementById("dateCount_1").value     =   dateCountId;
        
        $('#dateDiv').append(`
                                <div class="form-group" id="1_`+dateCountId+`">
                                    <input type="datetime-local" id="" name="dateTime_1[]" value="" /> 
                                    <button type="button" class="btn btn-default btn-sm" onClick="removeDate('1_`+dateCountId+`')"><i class="glyphicon glyphicon-remove"></i></button>
                                </div>
                            `); 

    });

    function removeDate(id){
        console.log(id);
        $('#'+id).remove();
    }     
    function removeTheatre(id){
        console.log(id);
        $('#theatre_div_'+id).remove();
    } 
    
 
</script>
@endsection
  

