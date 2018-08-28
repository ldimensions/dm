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
                                <input type="hidden" name="addressId" value="{{ $movie['addressId'] }}" id="addressId">
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
                                    <div class="tab-pane fade in active" id="movie" style="position: relative;min-height: 550px;height:550px;">
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
                                    <div class="tab-pane fade" id="theatre" style="position: relative;min-height: 455px;" >
                                        <br/><br/>
                                        <div id="theatreDiv">
                                            <input type="text" name="theatreId" id="theatreId" value="1"/>
                                            <div class="col-lg-8 col-xs-10 col-sm-10">  
                                                <div class="panel panel-default">
                                                    <div class="panel-body">
                                                        <div class="form-group">
                                                            <label>Theatre</label>
                                                            <select name="theatre_1[]" value="{{ old('theatre', $movie['theatre']) }}" id="theatre" class="form-control">
                                                                @foreach ($theatres as $key => $theatre)
                                                                    <option 
                                                                        value="{{$theatre['id']}}"
                                                                        @if(old('theatre', $movie['theatre']) == $theatre['id']) {{ 'selected' }} @endif
                                                                        >
                                                                        {{$theatre['name']}} ({{$theatre['cityName']}})
                                                                    </option>
                                                                @endforeach                                                
                                                            </select>
                                                        </div> 
                                                        <div id="dateDiv">
                                                            <input type="text" name="dateId" id="dateId" value="1_1"/>
                                                            <div class="col-lg-10 col-xs-10 col-sm-10"> 
                                                                <div class="panel panel-default">
                                                                    <div class="panel-body">                                                       
                                                                        <div class="form-group">
                                                                            <label>Date</label>
                                                                            <div class="input-group date" id="datePicker_1_1">
                                                                                <input type="text" name="date_1_1[]" class="form-control" />
                                                                                <span class="input-group-addon">
                                                                                    <span class="glyphicon glyphicon-calendar"></span>
                                                                                </span>
                                                                            </div>
                                                                        </div> 
                                                                        <div id="timeDiv">
                                                                            <input type="text" name="timeId" id="timeId" value="1_1_1"/>
                                                                            <div class="form-group">
                                                                                <div class="col-lg-10 col-xs-10 col-sm-10"> 
                                                                                    <label>Time</label>
                                                                                    <div class="input-group date" id="timePicker_1_1">
                                                                                        <input type="text" name="time[]"  class="form-control" />
                                                                                        <span class="input-group-addon">
                                                                                            <span class="glyphicon glyphicon-calendar"></span>
                                                                                        </span>
                                                                                    </div>
                                                                                </div>                                                                                                                                               
                                                                                <div class="col-lg-2 col-xs-2 col-sm-2" style="top:25px;"> 
                                                                                    <button type="button" class="btn btn-default btn-sm" id="addTime"><i class="fa glyphicon-plus"></i></button>
                                                                                </div>  
                                                                            </div>                                                                               
                                                                        </div>                                                                                                                               
                                                                    </div>
                                                                </div>  
                                                            </div>
                                                            <div class="col-lg-2 col-xs-2 col-sm-2"> 
                                                                <button type="button" class="btn btn-default btn-sm" id="addDate"><i class="fa glyphicon-plus"></i></button>
                                                            </div> 
                                                        </div> 
                                                    </div>                                                 
                                                </div>                                                                             
                                            </div>
                                            <div class="col-lg-4 col-xs-2 col-sm-2"> 
                                                <button type="button" class="btn btn-default btn-sm" id="addTheatre" ><i class="fa glyphicon-plus"></i></button>
                                            </div>                                             
                                        </div>
                                    </div>    
                                    <div class="tab-pane fade" id="meta" style="position: relative;min-height: 520px;">
                                        <br/><br/>
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label>SEOMetaTitle</label>
                                                <input name="SEOMetaTitle" value="{{ old('SEOMetaTitle', $movie['SEOMetaTitle']) }}" id="SEOMetaTitle" maxlength="20" class="form-control">
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
                                    <a href="{{ url('/admin/movie') }}"><button type="button" class="btn btn-default">Cancel</button></a>                                                              
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

    //$( document ).ready(function() {

        $("#addTheatre").click(function(){
            var theatreIdVal                    =   parseInt(document.getElementById("theatreId").value)+1;
            document.getElementById("theatreId").value = theatreIdVal;
            $('#theatreDiv').append(`<div class="col-lg-8 col-xs-10 col-sm-10" id=`+theatreIdVal+`>  
                                        <div class="panel panel-default">
                                            <div class="panel-body">
                                                <div class="form-group">
                                                    <label>Theatre</label>
                                                    <select name="theatre_`+theatreIdVal+`[]" value="{{ old('theatre', $movie['theatre']) }}" id="theatre" class="form-control">
                                                        @foreach ($theatres as $key => $theatre)
                                                            <option 
                                                                value="{{$theatre['id']}}"
                                                                @if(old('theatre', $movie['theatre']) == $theatre['id']) {{ 'selected' }} @endif
                                                                >
                                                                {{$theatre['name']}} ({{$theatre['cityName']}})
                                                            </option>
                                                        @endforeach                                                
                                                    </select>
                                                </div> 
                                                <div id="dateDiv">
                                                    <input type="text" name="dateId" id="dateId" value="`+theatreIdVal+`_1"/>
                                                    <div class="col-lg-10 col-xs-10 col-sm-10"> 
                                                        <div class="panel panel-default">
                                                            <div class="panel-body">                                                       
                                                                <div class="form-group">
                                                                    <label>Date</label>
                                                                    <div class="input-group date" id="datePicker_`+theatreIdVal+`_1">
                                                                        <input type="text" name="date_`+theatreIdVal+`_1" class="form-control" />
                                                                        <span class="input-group-addon">
                                                                            <span class="glyphicon glyphicon-calendar"></span>
                                                                        </span>
                                                                    </div>
                                                                </div> 
                                                                <div id="timeDiv">
                                                                    <input type="text" name="timeId" id="timeId" value="`+theatreIdVal+`_1_1"/>
                                                                    <div class="form-group">
                                                                        <div class="col-lg-10 col-xs-10 col-sm-10"> 
                                                                            <label>Time</label>
                                                                            <div class="input-group date" id="timePicker_`+theatreIdVal+`_1_1">
                                                                                <input type="text" name="time[]" class="form-control" />
                                                                                <span class="input-group-addon">
                                                                                    <span class="glyphicon glyphicon-calendar"></span>
                                                                                </span>
                                                                            </div>
                                                                        </div>                                                                                                                                               
                                                                        <div class="col-lg-2 col-xs-2 col-sm-2" style="top:25px;"> 
                                                                            <button type="button" class="btn btn-default btn-sm" id="addTime"><i class="fa glyphicon-plus"></i></button>
                                                                        </div>  
                                                                    </div>                                                                               
                                                                </div>                                                                                                                               
                                                            </div>
                                                        </div>  
                                                    </div>
                                                    <div class="col-lg-2 col-xs-2 col-sm-2"> 
                                                        <button type="button" class="btn btn-default btn-sm" id="addDate"><i class="fa glyphicon-plus"></i></button>
                                                    </div>                                                 
                                                </div> 
                                            </div>                                                 
                                        </div>                                                                             
                                    </div>
                                    <div class="col-lg-4 col-xs-2 col-sm-2" id=`+theatreIdVal+`_btn> 
                                        <button type="button" class="btn btn-default btn-sm" onClick="removeTheatre(`+theatreIdVal+`)"><i class="glyphicon glyphicon-remove"></i></button>
                                    </div>    
                                `); 
            //$('#theatreDiv').find(".theatreId").attr("id", theatreIdVal);
            //$('#theatreDiv').find(".theatreIdBtn").attr("id", theatreIdVal+'_btn');
            var datePickerId                =   "#datePicker_"+theatreIdVal+"_1";
            $(datePickerId).datetimepicker({
                format: "yyyy-mm-dd",
                autoclose: true,
                todayBtn: true,
                //startDate: "2013-02-14 10:00",
                minuteStep: 10,
                autoclose: 1,
                todayHighlight: 1,
                startView: 2,
                minView: 2,            
            }); 
            
            var timePickerId                =   "#timePicker_"+theatreIdVal+"_1_1";
            $(timePickerId).datetimepicker({
                format: "hh:ii",
                weekStart: 1,
                todayBtn:  1,
                autoclose: 1,
                todayHighlight: 1,
                startView: 1,
                minView: 0,
                maxView: 1,
                forceParse: 0          
            }); 

        });

        $("#addDate").click(function(){
            console.log('k');
            var dateIdVal;
            dateIdVal                           =   document.getElementById("dateId").value;
            dateIdValSplit                      =   dateIdVal.split("_");
            dateId                              =   parseInt(dateIdValSplit[1])+1;
            dateNewVal                          =   dateIdValSplit[0]+"_"+dateId;        
            document.getElementById("dateId").value     =   dateNewVal;

            var timeIdVal;
            timeIdVal                           =   document.getElementById("timeId").value;
            timeIdValSplit                      =   timeIdVal.split("_");
            timeId                              =   parseInt(timeIdValSplit[2])+1;
            timeIdNewVal                        =   timeIdValSplit[0]+"_"+timeIdValSplit[1]+"_"+timeId;        
            document.getElementById("timeId").value     =   timeIdNewVal; 

            $('#dateDiv').append(`  
                                    <div class="col-lg-10 col-xs-10 col-sm-10" id=`+dateNewVal+`> 
                                        <div class="panel panel-default">
                                            <div class="panel-body">                                                       
                                                <div class="form-group">
                                                    <label>Date</label>
                                                    <div class="input-group date" id="datePicker_`+dateNewVal+`">
                                                        <input type="text" name="date_`+dateNewVal+`" id="`+dateNewVal+`[]" class="form-control" />
                                                        <span class="input-group-addon">
                                                            <span class="glyphicon glyphicon-calendar"></span>
                                                        </span>
                                                    </div>
                                                </div> 
                                                <div id="timeDiv">
                                                    <div class="form-group">
                                                        <div class="col-lg-10 col-xs-10 col-sm-10"> 
                                                            <label>Time</label>
                                                            <div class="input-group date" id="timePicker_`+timeIdNewVal+`">
                                                                <input type="text" class="form-control" />
                                                                <span class="input-group-addon">
                                                                    <span class="glyphicon glyphicon-calendar"></span>
                                                                </span>
                                                            </div>
                                                        </div>                                                                                                                                               
                                                        <div class="col-lg-2 col-xs-2 col-sm-2" style="top:25px;"> 
                                                            <button type="button" class="btn btn-default btn-sm" id="addTime()"><i class="glyphicon glyphicon-remove"></i></button>
                                                        </div>  
                                                    </div>                                                                               
                                                </div>                                                                                                                               
                                            </div>
                                        </div>  
                                    </div> 
                                    <div class="col-lg-2 col-xs-2 col-sm-2" id=`+dateNewVal+`_btn> 
                                        <button type="button" class="btn btn-default btn-sm" onClick="removeDate('`+dateNewVal+`')"><i class="glyphicon glyphicon-remove"></i></button>
                                    </div>                                          
                                `); 

            var datePickerId                =   "#datePicker_"+dateNewVal;
            $(datePickerId).datetimepicker({
                format: "yyyy-mm-dd",
                autoclose: true,
                todayBtn: true,
                //startDate: "2013-02-14 10:00",
                minuteStep: 10,
                autoclose: 1,
                todayHighlight: 1,
                startView: 2,
                minView: 2,            
            });  

        });

        $("#addTime").click(function(){
            var timeIdVal;
            timeIdVal                           =   document.getElementById("timeId").value;
            timeIdValSplit                      =   timeIdVal.split("_");
            timeId                              =   parseInt(timeIdValSplit[2])+1;
            timeIdNewVal                        =   timeIdValSplit[0]+"_"+timeIdValSplit[1]+"_"+timeId;        
            document.getElementById("timeId").value     =   timeIdNewVal;        
            $('#timeDiv').append(`  
                                    <div class="form-group">
                                        <div class="col-lg-10 col-xs-10 col-sm-10" style="position:relative;padding-top:10px;"> 
                                            <div class="input-group date" id="timePicker">
                                                <input type="text" name="time[]" class="form-control" />
                                                <span class="input-group-addon">
                                                    <span class="glyphicon glyphicon-calendar"></span>
                                                </span>
                                            </div>
                                        </div>                                                                                                                                               
                                        <div class="col-lg-2 col-xs-2 col-sm-2" style="position:relative;padding-top:10px;"> 
                                            <button type="button" class="btn btn-default btn-sm" id="addTime()"><i class="glyphicon glyphicon-remove"></i></button>
                                        </div>  
                                    </div>  
                                    `); 

            var timePickerId                =   "#timePicker_"+timeIdNewVal;
            $(timePickerId).datetimepicker({
                format: "hh:ii",
                weekStart: 1,
                todayBtn:  1,
                autoclose: 1,
                todayHighlight: 1,
                startView: 1,
                minView: 0,
                maxView: 1,
                forceParse: 0          
            });                                 

        });        


    //});

    function addDate(){
        console.log('ssdaf');
    };
    
    function removeTheatre(id){
        $('#'+id).remove();
        $('#'+id+'_btn').remove();
    }  
    


    function removeDate(id){
        console.log(id);
        $('#'+id).remove();
        $('#'+id+'_btn').remove();        
    }     



    function removeTime(){
        $('#a').remove();
        $('#abtn').remove();
    }

    
    
    $(document).ready(function() {
        $('#datePicker_1_1').datetimepicker({
            format: "yyyy-mm-dd",
            autoclose: true,
            todayBtn: true,
            //startDate: "2013-02-14 10:00",
            minuteStep: 10,
            autoclose: 1,
            todayHighlight: 1,
            startView: 2,
            minView: 2,            
        });     
        $('#timePicker_1_1').datetimepicker({
            format: "hh:ii",
            weekStart: 1,
            todayBtn:  1,
            autoclose: 1,
            todayHighlight: 1,
            startView: 1,
            minView: 0,
            maxView: 1,
            forceParse: 0          
        });                
    });  
    </script>
@endsection
  

