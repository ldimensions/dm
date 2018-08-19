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
                    @if($religion['id']) {{ 'Edit religion' }} @else {{ 'Add Religion' }} @endif
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="panel-body">                                          
                            <form name="religion" action="{{ url('/admin/religion_add') }}" method="POST" role="form" enctype="multipart/form-data">
                                {{ csrf_field() }}
                                <input type="hidden" name="id" value="{{ $religion['id'] }}" id="id">
                                <input type="hidden" name="addressId" value="{{ $religion['addressId'] }}" id="addressId">
                                <input type="hidden" name="urlId" value="{{ $religion['urlId'] }}" id="urlId">
                                <input type="hidden" name="seoId" value="{{ $religion['seoId'] }}" id="urlId">  
                                
                                <ul class="nav nav-tabs">
                                    <li class="active"><a href="#religion" data-toggle="tab">Religion</a>
                                    </li>
                                    <li><a href="#address" data-toggle="tab">Address</a>
                                    </li>
                                    <li><a href="#meta" data-toggle="tab">Meta</a>
                                    </li>
                                    <li><a href="#images" data-toggle="tab">Images</a>
                                    </li> 
                                </ul>  
                                <div class="tab-content">
                                    <div class="tab-pane fade in active" id="religion" style="position: relative;min-height: 680px;">
                                        <br/><br/>
                                        <div class="col-lg-6">
                                            <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                                                <label>Name</label>
                                                <input name="name" value="{{ old('name', $religion['name']) }}" id="name" maxlength="100" class="form-control">
                                                <small class="text-danger">{{ $errors->first('name') }}</small>
                                            </div>
                                            <div class="form-group{{ $errors->has('urlName') ? ' has-error' : '' }}">
                                                <label>URL</label>
                                                <input name="urlName" value="{{ old('urlName', $religion['urlName']) }}" id="urlName" maxlength="180" class="form-control" >
                                                <input type="hidden" name="urlNameChk" value="{{ $religion['urlName'] }}" id="urlNameChk" class="form-control" >
                                                <small class="text-danger">{{ $errors->first('urlName') }}</small>                                            
                                            </div> 
                                            <div class="form-group">
                                                <label>Website</label>
                                                <input name="website" value="{{ old('website', $religion['website']) }}" id="website" maxlength="50" class="form-control">
                                            </div>    
                                            <div class="form-group">
                                                <label>Religion Type</label>

                                                <select name="religionTypeId" value="{{ old('religionTypeId', $religion['religionTypeId']) }}" id="religionTypeId" class="form-control">
                                                    @foreach ($religionTypes as $key => $religionType)
                                                        <option 
                                                            value="{{$religionType['id']}}"
                                                            @if(old('religionTypeId', $religion['religionTypeId']) == $religionType['id']) {{ 'selected' }} @endif
                                                            >
                                                            {{$religionType['religionName']}}
                                                        </option>
                                                    @endforeach                                                
                                                </select>                                                
                                            </div>  
                                            <div class="form-group">
                                                <label>Denomination Type</label>
                                                <select name="denominationId" value="{{ old('denominationId', $religion['denominationId']) }}" id="denominationId"  class="form-control">
                                                    @foreach ($denominations as $key => $denomination)
                                                        <option 
                                                            value="{{$denomination['id']}}"
                                                            @if(old('denominationId', $religion['denominationId']) == $denomination['id']) {{ 'selected' }} @endif
                                                            >
                                                            {{$denomination['denominationName']}}
                                                        </option>
                                                    @endforeach 
                                                </select>
                                            </div>
                                                                                        
                                            <div class="form-group">
                                                <label>Premium</label>
                                                <select name="premium" id="premium" class="form-control">
                                                    <option value="0" @if(old('premium', $religion['premium']) == 0) {{ 'selected' }} @endif >No</option>
                                                    <option value="1" @if(old('premium', $religion['premium']) == 1) {{ 'selected' }} @endif >Yes</option>
                                                </select>
                                            </div>                                          
                                            <div class="form-group">
                                                <label>Order</label>
                                                <input name="order" value="{{ old('order', $religion['order']) }}" id="order" maxlength="15" class="form-control">
                                            </div>                                 
                                            <div class="form-group">
                                                <label>Is Disabled</label>
                                                <select name="is_disabled" id="is_disabled" class="form-control">
                                                    <option value="0" @if(old('is_disabled', $religion['is_disabled']) == 0) {{ 'selected' }} @endif >No</option>
                                                    <option value="1" @if(old('is_disabled', $religion['is_disabled']) == 1) {{ 'selected' }} @endif >Yes</option>
                                                </select>
                                            </div>                                                                                    
                                        </div>
                                        <div class="col-lg-6">  
                                            <div class="form-group">
                                                <label>Description</label>
                                                <textarea name="description" class="form-control" rows="5">{{ old('description', $religion['description']) }}</textarea>
                                            </div>                                            
                                            <div class="form-group">
                                                <label>Working Time</label>
                                                <textarea name="workingTime" class="form-control" rows="8">{{ old('workingTime', $religion['workingTime']) }}</textarea>
                                            </div>   
                                        </div>                                     
                                    </div>
                                    <div class="tab-pane fade" id="address" style="position: relative;min-height: 455px;" >
                                        <br/><br/>
                                        <div class="col-lg-6">
                                            <div class="form-group{{ $errors->has('address1') ? ' has-error' : '' }}">
                                                <label>Address1</label>
                                                <input name="address1" value="{{ old('address1', $religion['address1']) }}" id="address1" maxlength="150" class="form-control" >
                                                <small class="text-danger">{{ $errors->first('address1') }}</small>                                            
                                            </div>                                          
                                            <div class="form-group">
                                                <label>Address2</label>
                                                <input name="address2" value="{{ old('address2', $religion['address2']) }}" id="address2" maxlength="80" class="form-control">
                                            </div>                                          
                                            <div class="form-group">
                                                <label>City</label>
                                                <select name="city" value="{{ old('city', $religion['city']) }}" id="city" class="form-control">
                                                    @foreach ($cities as $key => $city)
                                                        <option 
                                                            value="{{$city['cityId']}}"
                                                            @if(old('city', $religion['city']) == $city['cityId']) {{ 'selected' }} @endif
                                                            >
                                                            {{$city['city']}}
                                                        </option>
                                                    @endforeach                                                
                                                </select>
                                            </div>                                         
                                            <div class="form-group{{ $errors->has('state') ? ' has-error' : '' }}">
                                                <label>State</label>
                                                <input name="state" value="{{ old('state', $religion['state']) }}" id="state" maxlength="40" class="form-control" >
                                                <small class="text-danger">{{ $errors->first('state') }}</small>                                            
                                            </div>                                          
                                            <div class="form-group">
                                                <label>Zip</label>
                                                <input name="zip" value="{{ old('zip', $religion['zip']) }}" id="zip" maxlength="10" class="form-control" >
                                            </div>   
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label>County</label>
                                                <input name="county" value="{{ old('county', $religion['county']) }}" id="county" maxlength="10" class="form-control" >
                                            </div>                                                                                  
                                            <div class="form-group">
                                                <label>Phone1</label>
                                                <input name="phone1" value="{{ old('phone1', $religion['phone1']) }}" id="phone1" maxlength="15" class="form-control">
                                            </div>                                          
                                            <div class="form-group">
                                                <label>phone2</label>
                                                <input name="phone2" value="{{ old('phone2', $religion['phone2']) }}" id="phone2" maxlength="15" class="form-control">
                                            </div> 
                                            <div class="form-group">
                                                <label>Latitude</label>
                                                <input name="latitude" value="{{ old('latitude', $religion['latitude']) }}" maxlength="20" id="latitude" class="form-control">
                                            </div>                                          
                                            <div class="form-group">
                                                <label>Longitude</label>
                                                <input name="longitude" value="{{ old('longitude', $religion['longitude']) }}" maxlength="20" id="longitude" class="form-control">
                                            </div>                                            
                                        </div>                                        
                                    </div>    
                                    <div class="tab-pane fade" id="meta" style="position: relative;min-height: 520px;">
                                        <br/><br/>
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label>SEOMetaTitle</label>
                                                <input name="SEOMetaTitle" value="{{ old('SEOMetaTitle', $religion['SEOMetaTitle']) }}" id="SEOMetaTitle" maxlength="20" class="form-control">
                                            </div> 
                                            <div class="form-group">
                                                <label>SEOMetaDesc</label>
                                                <textarea name="SEOMetaDesc" class="form-control" rows="5">{{ old('SEOMetaDesc', $religion['SEOMetaDesc']) }}</textarea>                                                
                                            </div> 
                                            <div class="form-group">
                                                <label>SEOMetaPublishedTime</label>
                                                <input name="SEOMetaPublishedTime" value="{{ old('SEOMetaPublishedTime', $religion['SEOMetaPublishedTime']) }}" id="SEOMetaPublishedTime" maxlength="15" class="form-control">
                                            </div> 
                                            <div class="form-group">
                                                <label>SEOMetaKeywords</label>
                                                <input name="SEOMetaKeywords" value="{{ old('SEOMetaKeywords', $religion['SEOMetaKeywords']) }}" id="SEOMetaKeywords" maxlength="150" class="form-control">
                                            </div> 
                                            <div class="form-group">
                                                <label>OpenGraphTitle</label>
                                                <input name="OpenGraphTitle" value="{{ old('OpenGraphTitle', $religion['OpenGraphTitle']) }}" id="OpenGraphTitle" maxlength="20" class="form-control">
                                            </div>                                         
                                        </div>
                                        <div class="col-lg-6">  
                                            <div class="form-group">
                                                <label>OpenGraphDesc</label>
                                                <input name="OpenGraphDesc" value="{{ old('OpenGraphDesc', $religion['OpenGraphDesc']) }}" id="OpenGraphDesc" maxlength="150" class="form-control">
                                            </div> 
                                            <div class="form-group">
                                                <label>OpenGraphUrl</label>
                                                <input name="OpenGraphUrl" value="{{ old('OpenGraphUrl', $religion['OpenGraphUrl']) }}" id="OpenGraphUrl" maxlength="150" class="form-control">
                                            </div> 
                                            <div class="form-group">
                                                <label>OpenGraphPropertyType</label>
                                                <input name="OpenGraphPropertyType" value="{{ old('OpenGraphPropertyType', $religion['OpenGraphPropertyType']) }}" id="OpenGraphPropertyType" maxlength="25" class="form-control">
                                            </div> 
                                            <div class="form-group">
                                                <label>OpenGraphPropertyLocale</label>
                                                <input name="OpenGraphPropertyLocale" value="{{ old('OpenGraphPropertyLocale', $religion['OpenGraphPropertyLocale']) }}" id="OpenGraphPropertyLocale" maxlength="150" class="form-control">
                                            </div> 
                                            <div class="form-group">
                                                <label>OpenGraphPropertyLocaleAlternate</label>
                                                <input name="OpenGraphPropertyLocaleAlternate" value="{{ old('OpenGraphPropertyLocaleAlternate', $religion['OpenGraphPropertyLocaleAlternate']) }}" id="OpenGraphPropertyLocaleAlternate" maxlength="150" class="form-control">
                                            </div>   
                                            <div class="form-group">
                                                <label>OpenGraph</label>
                                                <input name="OpenGraph" value="{{ old('OpenGraph', $religion['OpenGraph']) }}" id="OpenGraph" maxlength="150" class="form-control">
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
                                                            <img src="{{ URL::to('/') }}/image/religion/{{$religion['id']}}/{{$photo['photoName']}}"  style="width:100px;height:100px">
                                                        </div>
                                                    @endif
                                                @endforeach
                                            </div> 
                                            
                                            <div class="form-group">
                                                <div>Featured</div>
                                                @foreach ($photos as $key => $photo)
                                                    @if($photo['is_primary'] == 0)
                                                        <div class="smallImage" style="float:left;padding:10px;">
                                                            <img src="{{ URL::to('/') }}/image/religion/{{$religion['id']}}/{{$photo['photoName']}}"  style="width:100px;height:100px">
                                                        </div>                                                    
                                                    @endif
                                                @endforeach
                                            </div>                                                                                                 
                                        </div>
                                    </div>                             
                                </div>
                                <div style="position:relative;widht:100%">                                  
                                    <button type="submit" class="btn btn-default">Submit</button>
                                    <a href="{{ url('/admin/religion') }}"><button type="button" class="btn btn-default">Cancel</button></a>                                                              
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
    $(document).ready(function() {
        $('#dataTables-example').DataTable({
            responsive: true
        });
    });  
    </script>
@endsection
  

