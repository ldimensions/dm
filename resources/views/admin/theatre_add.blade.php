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
                    @if($theatre['id']) {{ 'Edit Theatre' }} @else {{ 'Add Theatre' }} @endif
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="panel-body">                                          
                            <form name="theatre" action="{{ url('/admin/theatre_add') }}" method="POST" role="form" enctype="multipart/form-data">
                                {{ csrf_field() }}
                                <input type="hidden" name="id" value="{{ $theatre['id'] }}" id="id">
                                <input type="hidden" name="addressId" value="{{ $theatre['addressId'] }}" id="addressId">
                                <input type="hidden" name="urlId" value="{{ $theatre['urlId'] }}" id="urlId">
                                <input type="hidden" name="seoId" value="{{ $theatre['seoId'] }}" id="urlId">  
                                
                                <ul class="nav nav-tabs">
                                    <li class="active"><a href="#theatre" data-toggle="tab">Theatre</a>
                                    </li>
                                    <li><a href="#address" data-toggle="tab">Address</a>
                                    </li>
                                    <li><a href="#meta" data-toggle="tab">Meta</a>
                                    </li>
                                </ul>  
                                <div class="tab-content">
                                    <div class="tab-pane fade in active" id="theatre" style="position: relative;min-height: 400px;">
                                        <br/><br/>
                                        <div class="col-lg-6">
                                            <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                                                <label>Name</label>
                                                <input name="name" value="{{ old('name', $theatre['name']) }}" id="name" maxlength="100" class="form-control">
                                                <small class="text-danger">{{ $errors->first('name') }}</small>
                                            </div>
                                            <div class="form-group{{ $errors->has('urlName') ? ' has-error' : '' }}">
                                                <label>URL</label>
                                                <input name="urlName" value="{{ old('urlName', $theatre['urlName']) }}" id="urlName" maxlength="180" class="form-control" >
                                                <input type="hidden" name="urlNameChk" value="{{ $theatre['urlName'] }}" id="urlNameChk" class="form-control" >
                                                <small class="text-danger">{{ $errors->first('urlName') }}</small>                                            
                                            </div> 
                                            <div class="form-group">
                                                <label>Website</label>
                                                <input name="website" value="{{ old('website', $theatre['website']) }}" id="website" maxlength="50" class="form-control">
                                            </div>                                                 
                                            <div class="form-group">
                                                <label>Premium</label>
                                                <select name="premium" id="premium" class="form-control">
                                                    <option value="0" @if(old('premium', $theatre['premium']) == 0) {{ 'selected' }} @endif >No</option>
                                                    <option value="1" @if(old('premium', $theatre['premium']) == 1) {{ 'selected' }} @endif >Yes</option>
                                                </select>
                                            </div>                                                                                                                             
                                        </div>
                                        <div class="col-lg-6">  
                                            <div class="form-group">
                                                <label>Description</label>
                                                <textarea name="description" class="form-control" rows="5">{{ old('description', $theatre['description']) }}</textarea>
                                            </div>                                            
                                            <div class="form-group">
                                                <label>Order</label>
                                                <input name="order" value="{{ old('order', $theatre['order']) }}" id="order" maxlength="15" class="form-control">
                                            </div>                                 
                                            <div class="form-group">
                                                <label>Is Disabled</label>
                                                <select name="is_disabled" id="is_disabled" class="form-control">
                                                    <option value="0" @if(old('is_disabled', $theatre['is_disabled']) == 0) {{ 'selected' }} @endif >No</option>
                                                    <option value="1" @if(old('is_disabled', $theatre['is_disabled']) == 1) {{ 'selected' }} @endif >Yes</option>
                                                </select>
                                            </div> 
                                        </div>                                     
                                    </div>
                                    <div class="tab-pane fade" id="address" style="position: relative;min-height: 455px;" >
                                        <br/><br/>
                                        <div class="col-lg-6">
                                            <div class="form-group{{ $errors->has('address1') ? ' has-error' : '' }}">
                                                <label>Address1</label>
                                                <input name="address1" value="{{ old('address1', $theatre['address1']) }}" id="address1" maxlength="150" class="form-control" >
                                                <small class="text-danger">{{ $errors->first('address1') }}</small>                                            
                                            </div>                                          
                                            <div class="form-group">
                                                <label>Address2</label>
                                                <input name="address2" value="{{ old('address2', $theatre['address2']) }}" id="address2" maxlength="80" class="form-control">
                                            </div>                                          
                                            <div class="form-group">
                                                <label>City</label>
                                                <select name="city" value="{{ old('city', $theatre['city']) }}" id="city" class="form-control">
                                                    @foreach ($cities as $key => $city)
                                                        <option 
                                                            value="{{$city['cityId']}}"
                                                            @if(old('city', $theatre['city']) == $city['cityId']) {{ 'selected' }} @endif
                                                            >
                                                            {{$city['city']}}
                                                        </option>
                                                    @endforeach                                                
                                                </select>
                                            </div>                                         
                                            <div class="form-group{{ $errors->has('state') ? ' has-error' : '' }}">
                                                <label>State</label>
                                                <input name="state" value="{{ old('state', $theatre['state']) }}" id="state" maxlength="40" class="form-control" >
                                                <small class="text-danger">{{ $errors->first('state') }}</small>                                            
                                            </div>                                          
                                            <div class="form-group">
                                                <label>Zip</label>
                                                <input name="zip" value="{{ old('zip', $theatre['zip']) }}" id="zip" maxlength="10" class="form-control" >
                                            </div>   
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label>County</label>
                                                <input name="county" value="{{ old('county', $theatre['county']) }}" id="county" maxlength="10" class="form-control" >
                                            </div>                                                                                  
                                            <div class="form-group">
                                                <label>Phone1</label>
                                                <input name="phone1" value="{{ old('phone1', $theatre['phone1']) }}" id="phone1" maxlength="15" class="form-control">
                                            </div>                                          
                                            <div class="form-group">
                                                <label>phone2</label>
                                                <input name="phone2" value="{{ old('phone2', $theatre['phone2']) }}" id="phone2" maxlength="15" class="form-control">
                                            </div> 
                                            <div class="form-group">
                                                <label>Latitude</label>
                                                <input name="latitude" value="{{ old('latitude', $theatre['latitude']) }}" maxlength="20" id="latitude" class="form-control">
                                            </div>                                          
                                            <div class="form-group">
                                                <label>Longitude</label>
                                                <input name="longitude" value="{{ old('longitude', $theatre['longitude']) }}" maxlength="20" id="longitude" class="form-control">
                                            </div>                                            
                                        </div>                                        
                                    </div>    
                                    <div class="tab-pane fade" id="meta" style="position: relative;min-height: 520px;">
                                        <br/><br/>
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label>SEOMetaTitle</label>
                                                <input name="SEOMetaTitle" value="{{ old('SEOMetaTitle', $theatre['SEOMetaTitle']) }}" id="SEOMetaTitle" maxlength="20" class="form-control">
                                            </div> 
                                            <div class="form-group">
                                                <label>SEOMetaDesc</label>
                                                <textarea name="SEOMetaDesc" class="form-control" rows="5">{{ old('SEOMetaDesc', $theatre['SEOMetaDesc']) }}</textarea>                                                
                                            </div> 
                                            <div class="form-group">
                                                <label>SEOMetaPublishedTime</label>
                                                <input name="SEOMetaPublishedTime" value="{{ old('SEOMetaPublishedTime', $theatre['SEOMetaPublishedTime']) }}" id="SEOMetaPublishedTime" maxlength="15" class="form-control">
                                            </div> 
                                            <div class="form-group">
                                                <label>SEOMetaKeywords</label>
                                                <input name="SEOMetaKeywords" value="{{ old('SEOMetaKeywords', $theatre['SEOMetaKeywords']) }}" id="SEOMetaKeywords" maxlength="150" class="form-control">
                                            </div> 
                                            <div class="form-group">
                                                <label>OpenGraphTitle</label>
                                                <input name="OpenGraphTitle" value="{{ old('OpenGraphTitle', $theatre['OpenGraphTitle']) }}" id="OpenGraphTitle" maxlength="20" class="form-control">
                                            </div>                                         
                                        </div>
                                        <div class="col-lg-6">  
                                            <div class="form-group">
                                                <label>OpenGraphDesc</label>
                                                <input name="OpenGraphDesc" value="{{ old('OpenGraphDesc', $theatre['OpenGraphDesc']) }}" id="OpenGraphDesc" maxlength="150" class="form-control">
                                            </div> 
                                            <div class="form-group">
                                                <label>OpenGraphUrl</label>
                                                <input name="OpenGraphUrl" value="{{ old('OpenGraphUrl', $theatre['OpenGraphUrl']) }}" id="OpenGraphUrl" maxlength="150" class="form-control">
                                            </div> 
                                            <div class="form-group">
                                                <label>OpenGraphPropertyType</label>
                                                <input name="OpenGraphPropertyType" value="{{ old('OpenGraphPropertyType', $theatre['OpenGraphPropertyType']) }}" id="OpenGraphPropertyType" maxlength="25" class="form-control">
                                            </div> 
                                            <div class="form-group">
                                                <label>OpenGraphPropertyLocale</label>
                                                <input name="OpenGraphPropertyLocale" value="{{ old('OpenGraphPropertyLocale', $theatre['OpenGraphPropertyLocale']) }}" id="OpenGraphPropertyLocale" maxlength="150" class="form-control">
                                            </div> 
                                            <div class="form-group">
                                                <label>OpenGraphPropertyLocaleAlternate</label>
                                                <input name="OpenGraphPropertyLocaleAlternate" value="{{ old('OpenGraphPropertyLocaleAlternate', $theatre['OpenGraphPropertyLocaleAlternate']) }}" id="OpenGraphPropertyLocaleAlternate" maxlength="150" class="form-control">
                                            </div>   
                                            <div class="form-group">
                                                <label>OpenGraph</label>
                                                <input name="OpenGraph" value="{{ old('OpenGraph', $theatre['OpenGraph']) }}" id="OpenGraph" maxlength="150" class="form-control">
                                            </div>                                                                                                                                                                                                                                                                                                                                                                                                     
                                        </div>                                         
                                    </div>                                     
                                </div>
                                <div style="position:relative;widht:100%">                                  
                                    <button type="submit" class="btn btn-default">Submit</button>
                                    <a href="{{ url('/admin/theatre') }}"><button type="button" class="btn btn-default">Cancel</button></a>                                                              
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
  

