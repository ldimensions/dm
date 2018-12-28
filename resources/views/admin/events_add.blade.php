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
                    @if($event['id']) {{ 'Edit Event' }} @else {{ 'Add Event' }} @endif
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="panel-body">                                                                                        
                            <form name="event" action="{{ url('/admin/event_add') }}" method="POST" role="form" enctype="multipart/form-data">
                                {{ csrf_field() }}
                                <input type="hidden" name="id" value="{{ $event['id'] }}" id="id">
                                <input type="hidden" name="addressId" value="{{ $event['addressId'] }}" id="addressId">
                                <input type="hidden" name="urlId" value="{{ $event['urlId'] }}" id="urlId">
                                <input type="hidden" name="seoId" value="{{ $event['seoId'] }}" id="urlId"> 
                                
                                <ul class="nav nav-tabs">
                                    <li class="active"><a href="#event" data-toggle="tab">Event</a>
                                    </li>
                                    <li><a href="#schedule" data-toggle="tab">Schedule</a>
                                    </li>                                    
                                    <li><a href="#address" data-toggle="tab">Venue</a>
                                    </li>
                                    <li><a href="#meta" data-toggle="tab">Meta</a>
                                    </li>
                                    <li><a href="#images" data-toggle="tab">Images</a>
                                    </li> 
                                </ul>  
                                <div class="tab-content">
                                    <div class="tab-pane fade in active" id="event" style="position: relative;min-height: 600px;">
                                        <br/><br/>
                                        <div class="col-lg-6">
                                            <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                                                <label>Name</label>
                                                <input name="name" value="{{ old('name', $event['name']) }}" id="name" maxlength="100" class="form-control">
                                                <small class="text-danger">{{ $errors->first('name') }}</small>
                                            </div>
                                            <div class="form-group{{ $errors->has('urlName') ? ' has-error' : '' }}">
                                                <label>URL</label>
                                                <input name="urlName" value="{{ old('urlName', $event['urlName']) }}" id="urlName" maxlength="180" class="form-control" >
                                                <input type="hidden" name="urlNameChk" value="{{ $event['urlName'] }}" id="urlNameChk" class="form-control" >
                                                <small class="text-danger">{{ $errors->first('urlName') }}</small>                                            
                                            </div> 
                                            <div class="form-group">
                                                <label>Website</label>
                                                <input name="website" value="{{ old('website', $event['website']) }}" id="website" maxlength="50" class="form-control">
                                            </div>                                               
                                            <div class="form-group">
                                                <label>Premium</label>
                                                <select name="premium" id="premium" class="form-control">
                                                    <option value="0" @if(old('premium', $event['premium']) == 0) {{ 'selected' }} @endif >No</option>
                                                    <option value="1" @if(old('premium', $event['premium']) == 1) {{ 'selected' }} @endif >Yes</option>
                                                </select>
                                            </div>  
                                            <div class="form-group">
                                                <label>Order</label>
                                                <input type="number" min="0" step="1" name="order" value="{{ old('order', $event['order']) }}" id="order" maxlength="15" class="form-control">
                                            </div>                                 
                                            <div class="form-group">
                                                <label>Is Disabled</label>
                                                <select name="is_disabled" id="is_disabled" class="form-control">
                                                    <option value="0" @if(old('is_disabled', $event['is_disabled']) == 0) {{ 'selected' }} @endif >No</option>
                                                    <option value="1" @if(old('is_disabled', $event['is_disabled']) == 1) {{ 'selected' }} @endif >Yes</option>
                                                </select>
                                            </div>                                                                                                                                                                                                                                                                                                   
                                        </div>
                                        <div class="col-lg-6"> 
                                            <div class="form-group">
                                                <label>Category</label>
                                                <select name="categoryId" id="categoryId" value="{{ old('categoryId', $event['categoryId']) }}"  class="form-control">
                                                    <option value="">Please select</option>
                                                    @foreach ($eventCategorys as $key => $eventCategory)
                                                        <option 
                                                            value="{{$eventCategory['id']}}"
                                                            @if(old('categoryId', $event['categoryId']) == $eventCategory['id']) {{ 'selected' }} @endif>
                                                            {{$eventCategory['name']}}
                                                        </option>
                                                    @endforeach                                                
                                                </select>    
                                            </div>  
                                            <div class="form-group">
                                                <label>Organizer Name</label>
                                                <input name="organizerName" value="{{ old('organizerName', $event['organizerName']) }}" id="organizerName" maxlength="190" class="form-control">
                                            </div>                                             
                                            <div class="form-group">
                                                <label>Organizer Email</label>
                                                <input name="organizerEmail" value="{{ old('organizerEmail', $event['organizerEmail']) }}" id="organizationEmail" maxlength="50" class="form-control">
                                            </div> 
                                            <div class="form-group">
                                                <label>Organizer Phone</label>
                                                <input name="organizerPhone" value="{{ old('organizerPhone', $event['organizerPhone']) }}" id="organizationPhone" maxlength="50" class="form-control">
                                            </div>                                                                                         
                                        </div>  
                                        <div class="col-lg-10">
                                            <div class="form-group">
                                                <label>Description</label>
                                                <textarea name="description" class="form-control" rows="5">{{ old('description', $event['description']) }}</textarea>
                                            </div> 
                                        </div>                                   
                                    </div>
                                    <div class="tab-pane fade" id="schedule" style="position: relative;min-height: 255px;height:255px;height:auto;overflow: auto;" >
                                        @if($event['id'] && count($eventSchedules) >0) 
                                            <input type="hidden" name="scheduleCount" id="scheduleCount" value="{{count($eventSchedules)}}"/><br/>
                                            @foreach ($eventSchedules as $eventScheduleKey => $eventSchedule)  
                                                @if($eventScheduleKey == 0)
                                                    <div id="dateDiv">
                                                        <div class="form-group">
                                                            <input type="datetime-local" id="" name="dateTime[]" value="{{$eventSchedule['dateTime']}}" /> 
                                                            <button type="button" class="btn btn-default btn-sm" id="addDate"><i class="fa glyphicon-plus"></i></button>
                                                        </div>
                                                    </div>
                                                @else 
                                                    <div id="dateDiv_{{$eventScheduleKey}}">
                                                        <div class="form-group">
                                                            <input type="datetime-local" id="" name="dateTime[]" value="{{$eventSchedule['dateTime']}}" /> 
                                                            <button type="button" class="btn btn-default btn-sm" onClick="removeDate('dateDiv_{{$eventScheduleKey}}')"><i class="glyphicon glyphicon-remove"></i></button>
                                                        </div>
                                                    </div>
                                                @endif                                                 
                                            @endforeach 
                                        @else 
                                            <input type="text" name="scheduleCount" id="scheduleCount" value="1"/>
                                            <div id="dateDiv">
                                                <div class="form-group">
                                                    <input type="datetime-local" id="" name="dateTime[]" value="" /> 
                                                    <button type="button" class="btn btn-default btn-sm" id="addDate"><i class="fa glyphicon-plus"></i></button>
                                                </div>
                                            </div>
                                        @endif 
                                        
                                    </div>
                                    <div class="tab-pane fade" id="address" style="position: relative;min-height: 455px;" >
                                        <br/><br/>
                                        <div class="col-lg-6">
                                            <div class="form-group{{ $errors->has('address1') ? ' has-error' : '' }}">
                                                <label>Address1</label>
                                                <input name="address1" value="{{ old('address1', $event['address1']) }}" id="address1" maxlength="150" class="form-control" >
                                                <small class="text-danger">{{ $errors->first('address1') }}</small>                                            
                                            </div>                                          
                                            <div class="form-group">
                                                <label>Address2</label>
                                                <input name="address2" value="{{ old('address2', $event['address2']) }}" id="address2" maxlength="80" class="form-control">
                                            </div>                                          
                                            <div class="form-group">
                                                <label>City</label>
                                                <select name="city" value="{{ old('city', $event['city']) }}" id="city" class="form-control">
                                                    @foreach ($cities as $key => $city)
                                                        <option 
                                                            value="{{$city['cityId']}}"
                                                            @if(old('city', $event['city']) == $city['cityId']) {{ 'selected' }} @endif
                                                            >
                                                            {{$city['city']}}
                                                        </option>
                                                    @endforeach                                                
                                                </select>
                                            </div>                                         
                                            <div class="form-group{{ $errors->has('state') ? ' has-error' : '' }}">
                                                <label>State</label>
                                                <input name="state" value="{{ old('state', $event['state']) }}" id="state" maxlength="40" class="form-control" >
                                                <small class="text-danger">{{ $errors->first('state') }}</small>                                            
                                            </div>                                          
                                            <div class="form-group">
                                                <label>Zip</label>
                                                <input name="zip" value="{{ old('zip', $event['zip']) }}" id="zip" maxlength="10" class="form-control" >
                                            </div>   
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label>County</label>
                                                <input name="county" value="{{ old('county', $event['county']) }}" id="county" maxlength="10" class="form-control" >
                                            </div>                                                                                  
                                            <div class="form-group">
                                                <label>Phone1</label>
                                                <input name="phone1" value="{{ old('phone1', $event['phone1']) }}" id="phone1" maxlength="15" class="form-control">
                                            </div>                                          
                                            <div class="form-group">
                                                <label>phone2</label>
                                                <input name="phone2" value="{{ old('phone2', $event['phone2']) }}" id="phone2" maxlength="15" class="form-control">
                                            </div> 
                                            <div class="form-group">
                                                <label>Latitude</label>
                                                <input name="latitude" value="{{ old('latitude', $event['latitude']) }}" maxlength="20" id="latitude" class="form-control">
                                            </div>                                          
                                            <div class="form-group">
                                                <label>Longitude</label>
                                                <input name="longitude" value="{{ old('longitude', $event['longitude']) }}" maxlength="20" id="longitude" class="form-control">
                                            </div>                                            
                                        </div>                                        
                                    </div>    
                                    <div class="tab-pane fade" id="meta" style="position: relative;min-height: 520px;">
                                        <br/><br/>
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label>SEOMetaTitle</label>
                                                <input name="SEOMetaTitle" value="{{ old('SEOMetaTitle', $event['SEOMetaTitle']) }}" id="SEOMetaTitle" maxlength="70" class="form-control">
                                            </div> 
                                            <div class="form-group">
                                                <label>SEOMetaDesc</label>
                                                <textarea name="SEOMetaDesc" class="form-control" rows="5">{{ old('SEOMetaDesc', $event['SEOMetaDesc']) }}</textarea>                                                
                                            </div> 
                                            <div class="form-group">
                                                <label>SEOMetaPublishedTime</label>
                                                <input name="SEOMetaPublishedTime" value="{{ old('SEOMetaPublishedTime', $event['SEOMetaPublishedTime']) }}" id="SEOMetaPublishedTime" maxlength="15" class="form-control">
                                            </div> 
                                            <div class="form-group">
                                                <label>SEOMetaKeywords</label>
                                                <input name="SEOMetaKeywords" value="{{ old('SEOMetaKeywords', $event['SEOMetaKeywords']) }}" id="SEOMetaKeywords" maxlength="150" class="form-control">
                                            </div> 
                                            <div class="form-group">
                                                <label>OpenGraphTitle</label>
                                                <input name="OpenGraphTitle" value="{{ old('OpenGraphTitle', $event['OpenGraphTitle']) }}" id="OpenGraphTitle" maxlength="20" class="form-control">
                                            </div>                                         
                                        </div>
                                        <div class="col-lg-6">  
                                            <div class="form-group">
                                                <label>OpenGraphDesc</label>
                                                <input name="OpenGraphDesc" value="{{ old('OpenGraphDesc', $event['OpenGraphDesc']) }}" id="OpenGraphDesc" maxlength="150" class="form-control">
                                            </div> 
                                            <div class="form-group">
                                                <label>OpenGraphUrl</label>
                                                <input name="OpenGraphUrl" value="{{ old('OpenGraphUrl', $event['OpenGraphUrl']) }}" id="OpenGraphUrl" maxlength="150" class="form-control">
                                            </div> 
                                            <div class="form-group">
                                                <label>OpenGraphPropertyType</label>
                                                <input name="OpenGraphPropertyType" value="{{ old('OpenGraphPropertyType', $event['OpenGraphPropertyType']) }}" id="OpenGraphPropertyType" maxlength="25" class="form-control">
                                            </div> 
                                            <div class="form-group">
                                                <label>OpenGraphPropertyLocale</label>
                                                <input name="OpenGraphPropertyLocale" value="{{ old('OpenGraphPropertyLocale', $event['OpenGraphPropertyLocale']) }}" id="OpenGraphPropertyLocale" maxlength="150" class="form-control">
                                            </div> 
                                            <div class="form-group">
                                                <label>OpenGraphPropertyLocaleAlternate</label>
                                                <input name="OpenGraphPropertyLocaleAlternate" value="{{ old('OpenGraphPropertyLocaleAlternate', $event['OpenGraphPropertyLocaleAlternate']) }}" id="OpenGraphPropertyLocaleAlternate" maxlength="150" class="form-control">
                                            </div>   
                                            <div class="form-group">
                                                <label>OpenGraph</label>
                                                <input name="OpenGraph" value="{{ old('OpenGraph', $event['OpenGraph']) }}" id="OpenGraph" maxlength="150" class="form-control">
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
                                            <div class="form-group{{ $errors->has('photos.*') ? ' has-error' : '' }}">
                                                <label>Image Layout</label>
                                                <input type="file" class="form-control" name="detailImg[]" />
                                                <small class="text-danger">{{ $errors->first('detailImg') }}</small>                                                
                                            </div>                                                                                                                       
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label>Thumbnail</label>
                                                @foreach ($photos as $key => $photo)
                                                    @if($photo['is_primary'] == 1)
                                                        <div class="smallImage">
                                                            <img src="{{ URL::to('/') }}/image/event/{{$event['id']}}/{{$photo['photoName']}}"  style="width:100px;height:100px">
                                                        </div>
                                                    @endif
                                                @endforeach
                                            </div> 
                                            
                                            <div class="form-group">
                                                <div>Featured</div>
                                                @foreach ($photos as $key => $photo)
                                                    @if($photo['is_primary'] == 0)
                                                        <div class="smallImage" style="float:left;padding:10px;">
                                                            <img src="{{ URL::to('/') }}/image/event/{{$event['id']}}/{{$photo['photoName']}}"  style="width:100px;height:100px">                                                        </div>                                                    
                                                    @endif
                                                @endforeach
                                            </div> 
                                            @if($event['img'])      
                                                <div class="form-group">
                                                    <div>Layout</div>
                                                        <div class="smallImage" style="float:left;padding:10px;">
                                                            <img src="{{ URL::to('/') }}/image/event/{{$event['id']}}/{{$event['img']}}"  style="width:100px;height:100px">
                                                        </div>                                                    
                                                </div>  
                                            @endif                                                                                                                                       
                                        </div>
                                    </div>                             
                                </div>
                                <div class="col-lg-10" style="position:relative;widht:100%">                                  
                                    <button type="submit" class="btn btn-default">Submit</button>
                                    <a href="{{ url('/admin/events') }}"><button type="button" class="btn btn-default">Cancel</button></a>                                                              
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
  <script src="{{ asset('admin/ckeditor/ckeditor.js')}}"></script>

<script>
   
    $("#addDate").click(function(){
        var scheduleCount;
        scheduleCount                                =   document.getElementById("scheduleCount").value;
        scheduleCount                                =   parseInt(scheduleCount)+1;
        var now = Date.now();
        document.getElementById("scheduleCount").value     =   scheduleCount;
        
        $('#dateDiv').append(`
            <div class="form-group" id="`+now+`">
                <input type="datetime-local" id="" name="dateTime[]" value="" /> 
                <button type="button" class="btn btn-default btn-sm" onClick="removeDate('`+now+`')"><i class="glyphicon glyphicon-remove"></i></button>
            </div>
        `); 

    });

    function removeDate(id){
        $('#'+id).remove();
        var scheduleCount                            =   document.getElementById("scheduleCount").value;
        scheduleCount                                =   parseInt(scheduleCount)-1;
        document.getElementById("scheduleCount").value     =   scheduleCount;        
    }      

    CKEDITOR.replace( 'description' );
</script>
@endsection
  

