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
                    @if($seo['seoId']) {{ 'Edit SEO' }} @else {{ 'Add SEO' }} @endif
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="panel-body">                                          
                            <form name="seo" action="{{ url('/admin/seo_add') }}" method="POST" role="form" enctype="multipart/form-data">
                                {{ csrf_field() }}
                                <input type="hidden" name="seoId" value="{{ $seo['seoId'] }}" id="urlId">  
                                
                                <ul class="nav nav-tabs">
                                    <li class="active"><a href="#seo" data-toggle="tab">SEO</a>
                                    </li>                                    
                                </ul>  
                                <div class="tab-content">                                        
                                    <div class="tab-pane fade in active" id="seo" style="position: relative;min-height: 600px;">
                                        <br/><br/>
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label>SEOKeyValue</label>
                                                <input name="keyValue" value="{{ old('keyValue', $seo['keyValue']) }}" id="keyValue" maxlength="100" class="form-control">
                                            </div>
                                            <div class="form-group">
                                                <label>SEOIndexValue</label>
                                                <select name="indexValue" id="indexValue" class="form-control">
                                                    <option value="">-----Select SEO Index Value-----</option>
                                                    <option value="1" @if(old('indexValue', $seo['indexValue']) == 1) {{ 'selected' }} @endif>1</option>
                                                    <option value="2" @if(old('indexValue', $seo['indexValue']) == 2) {{ 'selected' }} @endif>2</option>
                                                    <option value="3" @if(old('indexValue', $seo['indexValue']) == 3) {{ 'selected' }} @endif>3</option>
                                                    <option value="4" @if(old('indexValue', $seo['indexValue']) == 4) {{ 'selected' }} @endif>4</option>
                                                    <option value="5" @if(old('indexValue', $seo['indexValue']) == 5) {{ 'selected' }} @endif>5</option>
                                                    <option value="6" @if(old('indexValue', $seo['indexValue']) == 6) {{ 'selected' }} @endif>6</option>
                                                    <option value="7" @if(old('indexValue', $seo['indexValue']) == 7) {{ 'selected' }} @endif>7</option>
                                                    <option value="8" @if(old('indexValue', $seo['indexValue']) == 8) {{ 'selected' }} @endif>8</option>
                                                    <option value="9" @if(old('indexValue', $seo['indexValue']) == 9) {{ 'selected' }} @endif>9</option>
                                                    <option value="10" @if(old('indexValue', $seo['indexValue']) == 10) {{ 'selected' }} @endif>10</option>
                                                </select>                                                
                                            </div>                                                                                    
                                            <div class="form-group">
                                                <label>SEOMetaTitle</label>
                                                <input name="SEOMetaTitle" value="{{ old('SEOMetaTitle', $seo['SEOMetaTitle']) }}" id="SEOMetaTitle" maxlength="100" class="form-control">
                                                <small class="text-danger">{{ $errors->first('SEOMetaTitle') }}</small>
                                            </div> 
                                            <div class="form-group">
                                                <label>SEOMetaDesc</label>
                                                <textarea name="SEOMetaDesc" class="form-control" rows="5">{{ old('SEOMetaDesc', $seo['SEOMetaDesc']) }}</textarea>                                                
                                            </div>  
                                            <div class="form-group">
                                                <label>SEOMetaKeywords</label>
                                                <input name="SEOMetaKeywords" value="{{ old('SEOMetaKeywords', $seo['SEOMetaKeywords']) }}" id="SEOMetaKeywords" maxlength="150" class="form-control">
                                            </div> 
                                            <div class="form-group">
                                                <label>OpenGraphTitle</label>
                                                <input name="OpenGraphTitle" value="{{ old('OpenGraphTitle', $seo['OpenGraphTitle']) }}" id="OpenGraphTitle" maxlength="20" class="form-control">
                                            </div>                                         
                                        </div>
                                        <div class="col-lg-6">  
                                            <div class="form-group">
                                                <label>OpenGraphDesc</label>
                                                <input name="OpenGraphDesc" value="{{ old('OpenGraphDesc', $seo['OpenGraphDesc']) }}" id="OpenGraphDesc" maxlength="150" class="form-control">
                                            </div> 
                                            <div class="form-group">
                                                <label>OpenGraphUrl</label>
                                                <input name="OpenGraphUrl" value="{{ old('OpenGraphUrl', $seo['OpenGraphUrl']) }}" id="OpenGraphUrl" maxlength="150" class="form-control">
                                            </div> 
                                            <div class="form-group">
                                                <label>OpenGraphPropertyType</label>
                                                <input name="OpenGraphPropertyType" value="{{ old('OpenGraphPropertyType', $seo['OpenGraphPropertyType']) }}" id="OpenGraphPropertyType" maxlength="25" class="form-control">
                                            </div> 
                                            <div class="form-group">
                                                <label>OpenGraphPropertyLocale</label>
                                                <input name="OpenGraphPropertyLocale" value="{{ old('OpenGraphPropertyLocale', $seo['OpenGraphPropertyLocale']) }}" id="OpenGraphPropertyLocale" maxlength="150" class="form-control">
                                            </div> 
                                            <div class="form-group">
                                                <label>OpenGraphPropertyLocaleAlternate</label>
                                                <input name="OpenGraphPropertyLocaleAlternate" value="{{ old('OpenGraphPropertyLocaleAlternate', $seo['OpenGraphPropertyLocaleAlternate']) }}" id="OpenGraphPropertyLocaleAlternate" maxlength="150" class="form-control">
                                            </div>   
                                            <div class="form-group">
                                                <label>OpenGraph</label>
                                                <input name="OpenGraph" value="{{ old('OpenGraph', $seo['OpenGraph']) }}" id="OpenGraph" maxlength="150" class="form-control">
                                            </div>    
                                            <div class="form-group">
                                                <label>SEOMetaPublishedTime</label>
                                                <input name="SEOMetaPublishedTime" value="{{ old('SEOMetaPublishedTime', $seo['SEOMetaPublishedTime']) }}" id="SEOMetaPublishedTime" maxlength="15" class="form-control">
                                            </div>                                                                                                                                                                                                                                                                                                                                                                                                                                             
                                        </div>                                         
                                    </div>                                     
                                </div>
                                <div style="position:relative;widht:100%">                                  
                                    <button type="submit" class="btn btn-default">Submit</button>
                                    <a href="{{ url('/admin/seo') }}"><button type="button" class="btn btn-default">Cancel</button></a>                                                              
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
@endsection
  

